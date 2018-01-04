<?php

class Geomarcheur_db extends CI_Model
{

    public function listAllUsers()
    {
        $this->load->database();
        $query = $this->db->query('SELECT id, pseudo, credits, inscription_date, bio, quote, is_banned, is_admin FROM user ORDER BY credits DESC');
        return $query->result_array();
    }

    public function listUser($id)
    {
        $this->load->database();
        $query = $this->db->query('SELECT id, pseudo, credits, inscription_date, bio, quote, is_banned, is_admin FROM user WHERE id=' . $id);
        return $query->result_array();
    }

    public function listAllPlaces()
    {
        $this->load->database();
        $query = $this->db->query('SELECT * FROM place');
        return $query->result_array();
    }

    // TODO: This could benefit from some polymorphism
    public function listAllPlacesAsc()
    {
        $this->load->database();
        $query = $this->db->query('SELECT * FROM place ORDER BY name');
        return $query->result_array();
    }

    public function listUserPlaces($id)
    {
        $this->load->database();
        $query = $this->db->query('SELECT * FROM place WHERE id_User=' . $id . ' ORDER BY name');
        return $query->result_array();
    }

    public function listPlace($id)
    {
        $this->load->database();
        $query = $this->db->query('SELECT * FROM place WHERE id=' . $id);
        return $query->result_array();
    }

    public function sellPlace($id)
    {
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

        $refund_query = $this->db->query('UPDATE user SET credits = ' . ($user_credit + ceil($place_credit * .75)) . ' WHERE id=' . $place_user);
        $sell_place_query = $this->db->query('UPDATE place SET id_User = NULL WHERE id = ' . $id);

        return array($refund_query, $sell_place_query);
    }

    public function login($data)
    {
        $username = $data['username'];
        $password = $data['password'];

        $this->load->database();
        $get_user_query = $this->db->query("SELECT id, pseudo, is_admin FROM user WHERE pseudo='" . $username . "' AND password='" . $password . "'");

        if (sizeof($get_user_query->result_array()) > 0) {
            return $get_user_query->result();
        } else {
            return false;
        }
    }


    public function disable_place($id_place)
    {

        $this->load->database();

        // recupere l'ID du proprietaire du lieu + la valeur du lieu
        $place_datas = $this->db->query("SELECT id_User, value, status FROM place WHERE id='" . $id_place . "' ");

        $value = 0;
        $id_user = 0;

        // recupération de l'état actif/desactif pour changer le status
        $place_datas = $this->db->query("SELECT * FROM place WHERE id='" . $id_place . "' ")->result_array();
        $place_status = $place_datas[0]['status'];
        $place_value = $place_datas[0]['value'];
        $place_owner = $place_datas[0]['id_User'];

        var_dump($place_status);

        // si il y a un proprio, rajouter la valeur à son compte
        if (!empty($place_owner)) {

            $owner = $this->db->query("SELECT credits, is_banned FROM user WHERE id='" . $place_owner . "' ")->result_array();
            if (!$owner[0]['is_banned']) {
                $user_credits = $owner[0]['credits'];

                $new_value = $user_credits + $place_value;
                $refund_query = $this->db->query('UPDATE user SET credits = ' . $new_value . '  WHERE id = ' . $place_owner);
            }
            $revoke_ownership_query = $this->db->query('UPDATE place SET id_User = NULL WHERE id = ' . $place_datas[0]['id']);
        }

        // This should be in a different function
        // TODO: Create a reactivate function
        /*if ($place_status === '0') {
            $reactivate_place = $this->db->query('UPDATE place SET status = 1  WHERE id = '.$id_place);
        }*/

        if ($place_status === '1') {
            $desactivate_place = $this->db->query('UPDATE place SET status = 0  WHERE id = ' . $id_place);
        }
    }

    public function edit_profile($profile)
    {
        $this->load->database();

        $bio = $this->db->escape($profile['bio']);
        $quote = $this->db->escape($profile['quote']);

        $refund_query = $this->db->query("UPDATE user SET quote=" . $quote . ", bio=" . $bio . " WHERE id=" . $profile['id']);
    }
}
