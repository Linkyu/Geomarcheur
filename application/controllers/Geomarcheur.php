<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// TODO: Document this file (cf. #66)

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
        $this->logger(LogType::DEBUG, __FUNCTION__ . ": Retrieved " . sizeof($data['resultat']) . " line" . (sizeof($data['resultat']) > 1 ? "s" : "") . " from User table.");

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
        $this->logger(LogType::DEBUG, __FUNCTION__ . ": Retrieved " . sizeof($data['resultat']) . " line" . (sizeof($data['resultat']) > 1 ? "s" : "") . " from Place table.");

        header("Content-Type: application/json");
        echo json_encode($data);
    }

    public function getUserPlaces()
    {
        $place_id = $this->uri->segment(3);

        if ($place_id != null) {
            $data['resultat'] = $this->geomarcheur_db->listUserPlaces($place_id);
        } else {
            $this->logger(LogType::ERROR, __FUNCTION__ . ": Was called with no parameter.");
            http_response_code(400);
            echo "Missing parameter: place_id";
            exit();
        }
        $this->logger(LogType::DEBUG, __FUNCTION__ . ": Retrieved " . sizeof($data['resultat']) . " line" . (sizeof($data['resultat']) > 1 ? "s" : "") . " from PLace table.");

        header("Content-Type: application/json");
        echo json_encode($data);
    }

    public function getUserRank()
    {
        $user_id = $this->uri->segment(3);

        $data['resultat'] = $this->geomarcheur_db->listAllUsers();
        $this->logger(LogType::DEBUG, __FUNCTION__ . ": Retrieved " . sizeof($data['resultat']) . " line" . (sizeof($data['resultat']) > 1 ? "s" : "") . " from User table.");

        // TODO: process the -1 error code case
        $rank = array('rank' => $this->getUserPosition($user_id, $data['resultat']));

        header("Content-Type: application/json");
        echo json_encode($rank);
    }
    private function getUserPosition($user_id, $list) {
        foreach ($list as $position => $user) {
            // TODO: Handle ties
            if ($user['id'] === $user_id) {
                return $position + 1;
            }
        }
        return -1;
    }

    public function sellPlace()
    {
        $place_id = $this->uri->segment(3);

        if ($place_id != null) {
            $result = $this->geomarcheur_db->sellPlace($place_id);

            // TODO: echo an actual output that can serve for DEBUG mode (cf. #67)
            $this->logger(LogType::DEBUG, __FUNCTION__ . ": Place " . $place_id . " was sold.");
            http_response_code(200);
            echo "Place sold.";
        } else {
            $this->logger(LogType::ERROR, __FUNCTION__ . ": Was called with no parameter.");
            http_response_code(400);
            echo "Missing parameter: place_id.";
            exit();
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
                // TODO: This part of the logic doesn't make sense (cf. #68)
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

                $this->logger(LogType::DEBUG, __FUNCTION__ . ": " . $data['username'] . " logged in successfully.");
                $this->redirect_after_login($_SESSION['is_admin']);
            } else {
                $this->logger(LogType::WARNING, __FUNCTION__ . ": " . $data['username'] . " attempted to log in with erroneous credentials.");
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
        $username = $_SESSION['user'];
        // Removing session data
        session_destroy();
        $this->logger(LogType::DEBUG, __FUNCTION__ . ": " . $username . " logged out successfully.");

        $data['message_display'] = 'Bye bye';
        $this->load->view('login_view', $data);   // TODO: Process the exit message
    }

    /**
     * Simple logging method
     *
     * This method will log messages in a readable format in the logs folder. The possible types are DEBUG, WARNING and ERROR.<br />
     * DEBUG messages should be used whenever an action is taken by the server with no error.<br />
     * WARNING messages are when there is a non-blocking error.<br />
     * ERROR messages are ONLY for blocking errors that prevent the server from performing its given task.
     *
     * @param $type: The log type determined in @class LogType
     * @param $message: The message to write in the log
     */
    private function logger($type, $message){
        $log_dir = "./application/logs/" . date("Y-m-d") . "/";
        $handler = null;

        // Create the logs subdirectory if it doesn't exist yet
        if (!is_dir($log_dir)) {
            mkdir($log_dir);
        }

        // Handle (very slightly) differently depending on the type of the message
        // TODO: DRY this
        if ($type === LogType::DEBUG) {
            $filename = $log_dir . date("Y-m-d") . "_DEBUG.log";
            if (!file_exists($filename)) {
                $handler = fopen($filename, 'w') or die('Cannot create file: '.$filename); //implicitly creates file
            }
            $handler = fopen($filename, 'a') or die('Cannot open file: '.$filename);
            $data = $this->log_format($type, $message);
            fwrite($handler, $data);
        } elseif ($type === LogType::WARNING) {
            $filename = $log_dir . date("Y-m-d") . "_WARNING.log";
            if (!file_exists($filename)) {
                $handler = fopen($filename, 'w') or die('Cannot create file: '.$filename); //implicitly creates file
            }
            $handler = fopen($filename, 'a') or die('Cannot open file: '.$filename);
            $data = $this->log_format($type, $message);
            fwrite($handler, $data);
        } elseif ($type === LogType::ERROR) {
            $filename = $log_dir . date("Y-m-d") . "_ERROR.log";
            if (!file_exists($filename)) {
                $handler = fopen($filename, 'w') or die('Cannot create file: '.$filename); //implicitly creates file
            }
            $handler = fopen($filename, 'a') or die('Cannot open file: '.$filename);
            $data = $this->log_format($type, $message);
            fwrite($handler, $data);
        } else {
            // This should not happen
            $sub_message = "A log was sent with the wrong parameters. Original message: " . $message;
            $this->logger(LogType::WARNING, $sub_message);
        }

        if ($handler != null) {
            fclose($handler);
        }
    }

    private function log_format($type, $message) {
        if ($type === LogType::DEBUG) {
            return date("[h:i:s] ") . "[DEBUG] " . $message . "\r\n";
        } elseif ($type === LogType::WARNING) {
            return date("[h:i:s] ") . "[WARNING] " . $message . "\r\n";
        } elseif ($type === LogType::ERROR) {
            return date("[h:i:s] ") . "[ERROR] " . $message . "\r\n";
        }
        return date("[h:i:s] ") . "[???] " . $message . "\r\n";
    }

    // Debugging function
    /*public function log_tester() {
        $this->logger(LogType::WARNING, "ATTENCIONE");
    }*/

}

abstract class LogType {
    const DEBUG = 0;
    const WARNING = 1;
    const ERROR = 2;
}





