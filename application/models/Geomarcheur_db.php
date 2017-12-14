<?php
class Geomarcheur_db extends CI_Model {

    public function listAllUsers() {
        $this->load->database();
        $query = $this->db->query('SELECT * FROM user');
        return $query->result_array();
    }

    public function listUser($id) {
        $this->load->database();
        $query = $this->db->query('SELECT * FROM user WHERE id='.$id);
        return $query->result_array();
    }

    public function listAllPlaces() {
        $this->load->database();
        $query = $this->db->query('SELECT * FROM place');
        return $query->result_array();
    }

    // TODO: This could benefit from some polymorphism
    public function listAllPlacesAsc() {
        $this->load->database();
        $query = $this->db->query('SELECT * FROM place ORDER BY name');
        return $query->result_array();
    }

    public function listUserPlaces($id) {
        $this->load->database();
        $query = $this->db->query('SELECT * FROM place WHERE id_User='.$id.' ORDER BY name');
        return $query->result_array();
    }

    public function listPlace($id) {
        $this->load->database();
        $query = $this->db->query('SELECT * FROM place WHERE id='.$id);
        return $query->result_array();
    }

    public function sellPlace($id) {
        $this->load->database();

        $get_place_user_query = $this->db->select('*')
            ->from('place')
            ->join('user', 'user.id = place.id_User')
            ->where('place.id=' . $id)
            ->get();
            //->query('SELECT * FROM place p JOIN user u ON p.id_User = u.id WHERE p.id='.$id);
            /**/

        $place_credit = 0;
        $place_user = 0;
        $user_credit = 0;
        foreach ($get_place_user_query->result_array() as $row) {
            $place_user = $row['id_User'];
            $place_credit = $row['value'];
            $user_credit = $row['credits'];
        }

        $refund_query = $this->db->query('UPDATE user SET credits = '.($user_credit + ceil($place_credit * .75)).' WHERE id=' . $place_user);
        $sell_place_query = $this->db->query('UPDATE place SET id_User = NULL WHERE id = '.$id);

        return array($refund_query, $sell_place_query);
    }

    public function login($data) {
        $username = $data['username'];
        $password = $data['password'];

        $this->load->database();
        $get_user_query = $this->db->query("SELECT pseudo, is_admin FROM user WHERE pseudo='".$username."' AND password='".$password."'");

        if (sizeof($get_user_query->result_array()) > 0) {
            return $get_user_query->result();
        } else {
            return false;
        }
    }
}
