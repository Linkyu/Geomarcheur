<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// TODO: Document this file (cf. #66)

class Geomarcheur extends CI_Controller
{
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
    public function index()
    {
        if (isset($_SESSION['logged_in'])) {
            $this->redirect_after_login($_SESSION['is_admin']);
        } else {
            $this->login();
        }
    }

    public function player()
    {
        $this->load->view('player/player_index_view');
    }

    public function dashboard()
    {
        $this->load->view('admin/admin_dashboard');
    }

    // Getter functions
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

    private function getUserPosition($user_id, $list)
    {
        foreach ($list as $position => $user) {
            // TODO: Handle ties
            if ($user['id'] === $user_id) {
                return $position + 1;
            }
        }
        return -1;
    }

    // Operational functions
    public function sellPlace()
    {
        // TODO: Switch this to use POST instead
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

    // TODO: Log this
    public function toggleBan()
    {
        $user_id = $this->input->get('idUser');

        $datas['resultat'] = $this->geomarcheur_db->listUser($user_id);
        foreach ($datas['resultat'] as $key => $value) {
            $is_banned = $value['is_banned'];
        }
        $datas['user_id'] = $user_id;
        if ($is_banned == 1) {
            $datas['is_banned'] = 0;
            $this->geomarcheur_db->toggleBan($datas);
            return $is_banned = 0;
        } else {
            $datas['is_banned'] = 1;
            $this->geomarcheur_db->toggleBan($datas);
            return $is_banned = 1;
        }

    }

    public function disablePlace()
    {
        // TODO: Switch this to use POST instead
        // TODO: Log this
        $place_id = $this->input->get('id');
        $this->geomarcheur_db->disable_place($place_id);
    }

    public function editPlace()
    {
        if ($this->input->server('REQUEST_METHOD') != 'POST') {
            $this->logger(LogType::ERROR, __FUNCTION__ . ": Was called with wrong method (POST was expected).");
            http_response_code(400);
            echo "Wrong method. Please use POST.";
            exit();
        }

        $place['id'] = $this->input->post('id');
        $place['name'] = $this->input->post('name');
        $place['address'] = $this->input->post('address');
        $place['value'] = $this->input->post('value');

        if ($place['id'] == '') {
            $this->logger(LogType::ERROR, __FUNCTION__ . ": Was called with no id parameter.");
            http_response_code(401);
            echo "Missing parameter: id.";
            exit();
        }
        if ($place['value'] == '') {
            $this->logger(LogType::ERROR, __FUNCTION__ . ": Was called with an empty value.");
            http_response_code(401);
            echo "Parameter error: value can't be null.";
            exit();
        }
        if ($place['name'] == '') {
            $this->logger(LogType::ERROR, __FUNCTION__ . ": Was called with no name.");
            http_response_code(401);
            echo "Parameter error: name can't be null.";
            exit();
        }

        $result = $this->geomarcheur_db->edit_place($place);
        $this->logger(LogType::DEBUG, __FUNCTION__ . ": Place " . $place['id'] . " was modified.");
        http_response_code(200);
        echo "Place modified.";
    }

    // TODO: Polymorph this with editPlace, or at least DRY it up a little
    public function createPlace()
    {
        if ($this->input->server('REQUEST_METHOD') != 'POST') {
            $this->logger(LogType::ERROR, __FUNCTION__ . ": Was called with wrong method (POST was expected).");
            http_response_code(400);
            echo "Wrong method. Please use POST.";
            exit();
        }

        $place['name'] = $this->input->post('name');
        $place['address'] = $this->input->post('address');
        $place['value'] = $this->input->post('value');
        $place['lat'] = $this->input->post('lat');
        $place['lng'] = $this->input->post('lng');

        if ($place['value'] == '') {
            $this->logger(LogType::ERROR, __FUNCTION__ . ": Was called with an empty value.");
            http_response_code(401);
            echo "Parameter error: value can't be null.";
            exit();
        }
        if ($place['name'] == '') {
            $this->logger(LogType::ERROR, __FUNCTION__ . ": Was called with no name.");
            http_response_code(401);
            echo "Parameter error: name can't be null.";
            exit();
        }

        $result = $this->geomarcheur_db->create_place($place);
        $this->logger(LogType::DEBUG, __FUNCTION__ . ": A new place was created: " . $place['name'] . "."); // TODO: Get better logs from write events
        http_response_code(200);
        echo "Place created.";
    }

    public function editProfile()
    {
        if ($this->input->server('REQUEST_METHOD') != 'POST') {
            $this->logger(LogType::ERROR, __FUNCTION__ . ": Was called with wrong method (POST was expected).");
            http_response_code(400);
            echo "Wrong method. Please use POST.";
            exit();
        }

        $user['id'] = $this->input->post('id');
        $user['bio'] = $this->input->post('bio');
        $user['quote'] = $this->input->post('quote');

        if ($user['id'] == '') {
            $this->logger(LogType::ERROR, __FUNCTION__ . ": Was called with no id parameter.");
            http_response_code(401);
            echo "Missing parameter: id.";
            exit();
        }

        $result = $this->geomarcheur_db->edit_profile($user);
        $this->logger(LogType::DEBUG, __FUNCTION__ . ": User " . $user['id'] . " was modified.");
        http_response_code(200);
        echo "Profile modified.";
    }

    public function givePoint()
    {
        if ($this->input->server('REQUEST_METHOD') != 'POST') {
            $this->logger(LogType::ERROR, __FUNCTION__ . ": Was called with wrong method (POST was expected).");
            http_response_code(400);
            echo "Wrong method. Please use POST.";
            exit();
        }

        $data['placeId'] = $this->input->post('placeId');
        $data['userId'] = $this->input->post('userId');

        if ($data['placeId'] == '') {
            $this->logger(LogType::ERROR, __FUNCTION__ . ": Was called with no placeId parameter.");
            http_response_code(401);
            echo "Missing parameter: placeId.";
            exit();
        }
        if ($data['userId'] == '') {
            $this->logger(LogType::ERROR, __FUNCTION__ . ": Was called with no userId parameter.");
            http_response_code(401);
            echo "Missing parameter: userId.";
            exit();
        }

        // Check when was the last passage in this place; if never, then it is now
        $last_passage = $this->geomarcheur_db->get_last_passage($data);
        $passage_date = $last_passage != null ? DateTime::CreateFromFormat('Y-m-d H:i:s', $last_passage) : new DateTime();
        $passage_date = $passage_date->getTimestamp();

        $now_date = new DateTime();
        $now_date = $now_date->getTimestamp();
        $max_passage_delay = 30 * 60;
        $current_passage_delay = $now_date - $passage_date;

        // TODO: Change the second half of the condition for better sustainability
        if ($current_passage_delay > $max_passage_delay or $current_passage_delay == 0) {
            $result = $this->geomarcheur_db->give_point($data);
            // TODO: Add more infos in these logs
            $this->logger(LogType::DEBUG, __FUNCTION__ . ": User " . $data['userId'] . " gave a point to the owner of place #" . $data["placeId"] . ".");
            http_response_code(200);
            echo "Point given.";
            exit();
        }

        $this->logger(LogType::DEBUG, __FUNCTION__ . ": User " . $data['userId'] . " passed by place #" . $data["placeId"] . " but didn't give a point to the owner because the delay has not yet expired [" . $current_passage_delay . "s restantes]");
        http_response_code(200);
        echo "Point already given.";
        exit();
    }

    // Identification functions
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

        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            // The run function will fail if there's no data to validate
            // (i.e., the form has not been filled out yet, or not correctly)
            if ($this->form_validation->run() == FALSE) {
                http_response_code(400);
                $this->logger(LogType::WARNING, __FUNCTION__ . ": Someone attempted to log in with invalid credentials.");
                echo "L'identifiant ou mot de passe est invalide.";
                exit();
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
                    echo "L'identifiant ou mot de passe est erroné.";
                }
            }
        } else {
            $this->load->view('login_view');
        }
    }

public function check_pseudo() {

    $pseudo = $this->input->post('newUsername');
    $pseudo_exists = $this->geomarcheur_db->check_pseudo($pseudo);

    if (!empty($pseudo_exists)) {

    http_response_code(401);
        echo "Le pseudo existe déjà.";

    } else {
        $password = $this->input->post('pass1');
        $query = $this->geomarcheur_db->inscription($pseudo, $password);
        $this->logger(LogType::DEBUG, "Joueur '$pseudo' vient de s'inscrire.");

    }

}









    private function redirect_after_login($is_admin)
    {
        if ($is_admin) {
            $this->dashboard();
        } else {
            $this->player();
        }
    }

    public function logout()
    {
        $username = $_SESSION['user'];
        // Removing session data
        session_destroy();
        $this->logger(LogType::DEBUG, __FUNCTION__ . ": " . $username . " logged out successfully.");

        $data['message_display'] = 'Bye bye';
        $this->load->view('login_view', $data);   // TODO: Process the exit message
    }

    public function buyPlace() {
        $user_id = $this->input->get('idUser');
        $place_id = $this->input->get('idPlace');

        $this->geomarcheur_db->buy_place($user_id, $place_id);
        $this->logger(LogType::DEBUG, "Joueur '$user_id' à acheté le lieu '$place_id'");
    }


    /*
        public function disablePlace() {

            $place_id = $this->input->get('id');
            $this->geomarcheur_db->disable_place($place_id);
        }
        */


    /**
     * @deprecated See editProfile()
     */
    public function modify_profile()
    {

        $aDatas['id'] = $this->input->post('id');
        $aDatas['pseudo'] = $this->input->post('pseudo');
        $aDatas['bio'] = $this->input->post('bio');
        $aDatas['quote'] = $this->input->post('quote');

        $this->geomarcheur_db->modify_profile($aDatas);

    }

    /**
     * Simple logging method
     *
     * This method will log messages in a readable format in the logs folder. The possible types are DEBUG, WARNING and ERROR.<br />
     * DEBUG messages should be used whenever an action is taken by the server with no error.<br />
     * WARNING messages are when there is a non-blocking error.<br />
     * ERROR messages are ONLY for blocking errors that prevent the server from performing its given task.
     *
     * @param $type : The log type determined in @class LogType
     * @param $message : The message to write in the log
     */
    private function logger($type, $message)
    {
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
                $handler = fopen($filename, 'w') or die('Cannot create file: ' . $filename); //implicitly creates file
            }
            $handler = fopen($filename, 'a') or die('Cannot open file: ' . $filename);
            $data = $this->log_format($type, $message);
            fwrite($handler, $data);
        } elseif ($type === LogType::WARNING) {
            $filename = $log_dir . date("Y-m-d") . "_WARNING.log";
            if (!file_exists($filename)) {
                $handler = fopen($filename, 'w') or die('Cannot create file: ' . $filename); //implicitly creates file
            }
            $handler = fopen($filename, 'a') or die('Cannot open file: ' . $filename);
            $data = $this->log_format($type, $message);
            fwrite($handler, $data);
        } elseif ($type === LogType::ERROR) {
            $filename = $log_dir . date("Y-m-d") . "_ERROR.log";
            if (!file_exists($filename)) {
                $handler = fopen($filename, 'w') or die('Cannot create file: ' . $filename); //implicitly creates file
            }
            $handler = fopen($filename, 'a') or die('Cannot open file: ' . $filename);
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

    private function log_format($type, $message)
    {
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


abstract class LogType
{
    const DEBUG = 0;
    const WARNING = 1;
    const ERROR = 2;
}





