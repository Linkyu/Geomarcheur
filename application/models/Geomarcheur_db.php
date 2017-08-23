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

}
