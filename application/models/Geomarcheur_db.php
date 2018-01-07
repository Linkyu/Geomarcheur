<?php

class Geomarcheur_db extends CI_Model
{
    // Getter functions
    public function listAllUsers()
    {
        $this->load->database();
        //$query = $this->db->query('SELECT id, pseudo, credits, inscription_date, bio, quote, is_banned, is_admin FROM user ORDER BY credits DESC');
        $query = $this->db->query('SELECT inscription_date, bio, quote, is_banned, is_admin,
                                  u.pseudo, u.id, u.credits, count(p.id) AS nbOfPlace
                                  FROM user u
                                  LEFT JOIN place p ON p.id_User = u.id
                                  GROUP BY u.pseudo, u.id, u.credits
                                  ORDER BY u.credits DESC, count(p.id) DESC');

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

    public function listUserWithPlaceCount()
    {
        $this->load->database();
        $query = $this->db->query("SELECT pseudo, COUNT(place.id) AS place_count FROM place, user WHERE place.id_User = user.id GROUP BY pseudo");
        return $query->result_array();
    }

    public function listPlace($id)
    {
        $this->load->database();
        $query = $this->db->query('SELECT * FROM place WHERE id=' . $id);
        return $query->result_array();
    }

    public function get_last_passage($data)
    {
        $this->load->database();

        $query = $this->db->query("SELECT * FROM log" .
            " WHERE id_User=" . $data["userId"] . " AND id_Place=" . $data["placeId"] . " AND event_type=" . EventType::GIVE_POINT .
            " ORDER BY event_date DESC");

        if (sizeof($query->result_array()) > 0) {
            $result_array = $query->result_array();
            $result = $result_array[0]["event_date"];
        } else {
            $result = null;
        }

        return $result;
    }

    public function getAllCredits()
    {
        $this->load->database();

        $query = $this->db->query("SELECT SUM(credits) AS credit_count FROM user");
        $query_array = $query->result_array();

        return $query_array[0];
    }

    // Operational functions
    public function sellPlace($id)
    {
        $this->load->database();

        $get_place_user_query = $this->db->select('*')
            ->from('place')
            ->join('user', 'user.id = place.id_User')
            ->where('place.id=' . $id)
            ->get();
        //->query('SELECT * FROM place p JOIN user u ON p.id_User = u.id WHERE p.id='.$id);

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

        $log_query = $this->db->query('INSERT INTO log (event_type, event_date, id_User, id_Place) VALUES (' . EventType::SELL . ', \'' . date('Y-m-d H:i:s') . '\', ' . $place_user . ', ' . $id . ')');

        return array($refund_query, $sell_place_query);
    }

    public function toggleBan($datas)
    {
        $this->load->database();
        $ban_management = $this->db->query('UPDATE user SET is_banned = ' . ($datas["is_banned"]) . ', credits=0 WHERE id=' . ($datas["user_id"]));

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

        $query = $this->db->query("UPDATE user SET quote=" . $quote . ", bio=" . $bio . " WHERE id=" . $profile['id']);
    }

    public function edit_place($place)
    {
        $this->load->database();

        $name = $this->db->escape($place['name']);
        $address = $this->db->escape($place['address']);
        $value = $this->db->escape($place['value']);

        $query = $this->db->query("UPDATE place SET name=" . $name . ", address=" . $address . ", value=" . $value . " WHERE id=" . $place['id']);
    }


    public function buy_place($user_id, $place_id)
    {
        $this->load->database();

        $query = $this->db->query("UPDATE place SET id_User = " . $user_id . " WHERE id = " . $place_id);

        $query = $this->db->query("SELECT value FROM place WHERE id='" . $place_id . "'");
        $row = $query->row();
        $value = $row->value;

        $query = $this->db->query("UPDATE user SET credits = credits - '" . $value . "'  WHERE id=" . $user_id);
        $log_query = $this->db->query('INSERT INTO log (event_type, event_date, id_User, id_Place) VALUES (' . EventType::BUY . ', \'' . date('Y-m-d H:i:s') . '\', ' . $user_id . ', ' . $place_id . ')');
    }


    public function create_place($place)
    {
        $this->load->database();

        $name = $this->db->escape($place['name']);
        $address = $this->db->escape($place['address']);
        $value = $this->db->escape($place['value']);
        $lat = $this->db->escape($place['lat']);
        $lng = $this->db->escape($place['lng']);

        $query = $this->db->query("INSERT INTO place (address, name, value, lat, lng) VALUES (" . $address . ", " . $name . ", " . $value . ", " . $lat . ", " . $lng . ")");
    }

    /**
     * @deprecated See edit_profile()
     * @param $aDatas
     */
    public function modify_profile($aDatas)
    {
        $this->load->database();

        // si le pseudo n'est pas vide...
        if (!empty($aDatas['pseudo'])) {
            //je le compare avec ceux de la BDD
            $check_pseudo = $this->db->query("SELECT pseudo FROM user WHERE pseudo='" . $aDatas['pseudo'] . "'");
            $row = $check_pseudo->row();
            //var_dump($row);
            // si la ligne est remplie ça veux dire qu'il y a déjà le pseudo dans la BDD
            if (!empty($row)) {
                $pseudo_db = $row->pseudo;
                if ($pseudo_db == $aDatas['pseudo']) {
                    echo "C'est la mer noire ! Y'a déjà le pseudo !";
                }
                // si c'est pas la cas je modifie le pseudo
            } else {
                $modify_profile = $this->db->query("UPDATE user SET  bio = '" . $aDatas['bio'] . "', quote = '" . $aDatas['quote'] . "' WHERE id = '" . $aDatas['id'] . "' ");
            }
        } else {
            echo "C'est la mer noire ! y'a pas de pseudo !";
        }
        // dans tout les cas on insére quand meme le reste

        $modify_profile = $this->db->query("UPDATE user SET bio = '" . $aDatas['bio'] . "', quote = '" . $aDatas['quote'] . "' WHERE id = '" . $aDatas['id'] . "' ");

    }

    public function give_point($data)
    {
        $this->load->database();

        // TODO: Maybe make sure that there *is* an owner
        $owner_query = $this->db->query("SELECT * FROM place WHERE id=" . $data['placeId'])->result_array();

        $query = $this->db->query("UPDATE user SET credits = credits + 1 WHERE id=" . $owner_query[0]['id_User']);
        $log_query = $this->db->query('INSERT INTO log (event_type, event_date, id_User, id_Place) VALUES (' . EventType::GIVE_POINT . ', \'' . date('Y-m-d H:i:s') . '\', ' . $data["userId"] . ', ' . $data["placeId"] . ')');
    }
}


abstract class EventType
{
    const BUY = 0;
    const SELL = 1;
    const GIVE_POINT = 2;
}