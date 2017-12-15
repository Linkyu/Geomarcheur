<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Geomarcheur extends CI_Controller
{

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *        http://example.com/index.php/welcome
     *    - or -
     *        http://example.com/index.php/welcome/index
     *    - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    function __construct()
    {
        parent:: __construct();
        // Load helpers
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('session');

        // Load database
        $this->load->model('geomarcheur_db');
    }

    public function index()
    {
        $this->login();
    }

    public function player()
    {
        $this->load->view('player/player_index_view');
    }

    public function dashboard()
    {
        $this->load->view('admin/admin_dashboard');
    }

    public function getUser()
    {
        $user_id = $this->uri->segment(3);

        if ($user_id != null) {
            $data['resultat'] = $this->geomarcheur_db->listUser($user_id);
        } else {
            $data['resultat'] = $this->geomarcheur_db->listAllUsers();
        }

        header("Content-Type: application/json");
        echo json_encode($data);
    }

    public function getPlace()
    {
        $place_id = $this->uri->segment(3);

        if ($place_id == 'asc') {
            $data['resultat'] = $this->geomarcheur_db->listAllPlacesAsc();
        } else if ($place_id != null) {
            $data['resultat'] = $this->geomarcheur_db->listPlace($place_id);
        } else {
            $data['resultat'] = $this->geomarcheur_db->listAllPlaces();
        }

        header("Content-Type: application/json");
        echo json_encode($data);
    }

    public function getUserPlaces()
    {
        $place_id = $this->uri->segment(3);

        if ($place_id != null) {
            $data['resultat'] = $this->geomarcheur_db->listUserPlaces($place_id);
        } else {
            // TODO: figure out how to send an error back
        }

        header("Content-Type: application/json");
        echo json_encode($data);
    }

    public function sellPlace()
    {
        $place_id = $this->uri->segment(3);

        if ($place_id != null) {
            $result = $this->geomarcheur_db->sellPlace($place_id);

            // TODO: echo an actual output that can serve for DEBUG mode
            /*foreach ($result as $item) {
                foreach ($item as $row) {
                    echo $row;
                }
            }*/
        } else {
            echo "ERROR - Was expecting place, received nothing instead.";
        }
    }


    public function login()
    {
        // Set up the data to be validated
        $this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');

        // No need to log in if the session is still active
        if (isset($_SESSION['logged_in'])) {
            http_response_code(400);
            $this->redirect_after_login($_SESSION['is_admin']);
        }

        // The run function will fail if there's no data to validate
        // (i.e., the form has not been filled out yet, or not correctly)
        if ($this->form_validation->run() == FALSE) {
            if (isset($_SESSION['logged_in'])) {
                // TODO: This part of the logic doesn't make sense
                echo validation_errors();
            } else {
                $this->load->view('login_view');
            }
        } else {
            // Retrieve the data from the POST array
            $data = array(
                'username' => $this->input->post('username'),
                'password' => $this->input->post('password')
            );

            // Call to the login model function
            // Assuming the data received is safe (TODO: Assume it is not)
            $is_login_valid = $this->geomarcheur_db->login($data);

            if ($is_login_valid) {
                http_response_code(200);
                // Add user data in session
                $this->session->set_userdata('logged_in', true);
                $this->session->set_userdata('user', $is_login_valid[0]->pseudo);
                $this->session->set_userdata('user_id', $is_login_valid[0]->id);
                $this->session->set_userdata('is_admin', $is_login_valid[0]->is_admin);

                $this->redirect_after_login($_SESSION['is_admin']);
            } else {
                // TODO: Display error message
                http_response_code(401);
                echo "Bad login";
            }
        }
    }

    private function redirect_after_login($is_admin) {
        if ($is_admin) {
            $this->dashboard();
        } else {
            $this->player();
        }
    }

    public function logout () {
        // Removing session data
        session_destroy();
        $data['message_display'] = 'Bye bye';
        $this->load->view('login_view', $data);
    }

    /**
     * @param $type: The log type determined in @class LogType
     * @param $message! The message to write in the log
     */
    private function logger($type, $message){
        $log_dir = base_url() . "/logs/" . date("Y-m-d");

        if (!is_dir($log_dir)) {
            mkdir($log_dir);
        }

        if ($type == LogType::DEBUG) {
            $filename = date("Y-m-d") . " - DEBUG.log";
            if (!file_exists($filename)) {
                echo $filename . " created. Message: " . $message;
            }

        } elseif ($type == LogType::WARNING) {
            $filename = date("Y-m-d") . " - WARNING.log";
            if (!file_exists($filename)) {
                echo $filename . " created. Message: " . $message;
            }

        } elseif ($type == LogType::ERROR) {
            $filename = date("Y-m-d") . " - ERROR.log";
            if (!file_exists($filename)) {
                echo $filename . " created. Message: " . $message;
            }

        } else {
            // This should not happen
            $sub_message = get_prefix($type) . "A log was sent with the wrong parameters. Original message: " . $message;
            $this->logger(LogType::WARNING, $sub_message);
        }

        function get_prefix($type) {
            return "PREFIX";
        }
    }

    public function log_tester() {
        $this->logger(LogType::DEBUG, "Test");
    }

}

abstract class LogType {
    const DEBUG = 0;
    const WARNING = 1;
    const ERROR = 2;
}





