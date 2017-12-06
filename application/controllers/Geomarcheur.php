<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Geomarcheur extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     *	- or -
     * 		http://example.com/index.php/welcome/index
     *	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    public function index()
    {

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
        $this->load->model('geomarcheur_db');
        $user_id=$this->uri->segment(3);

        if ($user_id != null){
            $data['resultat']=$this->geomarcheur_db->listUser($user_id);
        } else {
            $data['resultat']=$this->geomarcheur_db->listAllUsers();
        }

        header("Content-Type: application/json");
        echo json_encode($data);
    }

    public function getPlace()
    {
        $this->load->model('geomarcheur_db');
        $place_id=$this->uri->segment(3);

        if ($place_id == 'asc') {
            $data['resultat']=$this->geomarcheur_db->listAllPlacesAsc();
        } else if($place_id != null){
            $data['resultat']=$this->geomarcheur_db->listPlace($place_id);
        } else {
            $data['resultat']=$this->geomarcheur_db->listAllPlaces();
        }

        header("Content-Type: application/json");
        echo json_encode($data);
    }

    public function getUserPlaces()
    {
        $this->load->model('geomarcheur_db');
        $place_id=$this->uri->segment(3);

        if ($place_id != null){
            $data['resultat']=$this->geomarcheur_db->listUserPlaces($place_id);
        } else {
            // TODO: figure out how to send an error back
        }

        header("Content-Type: application/json");
        echo json_encode($data);
    }


    public function delete()
    {
        $id_place=$this->uri->segment(3);

        if ($id_place ==null) {

        } else {
            $this->load->model('geomarcheur_db');
            $data['resultat']=$this->geomarcheur_db->delete($id_place);
        }
        $this->load->view('admin/admin_dashboard');
    }



    public function disablePlace() {
        $this->load->model('geomarcheur_db');
        $id_place = $this->input->post('id_place');

        // Assuming the data received is valid (TODO: Assume it is not)
        $result = $this->geomarcheur_db->disablePlace($id_place);
    }


    public function createDatatable()
    {
        $this->load->model('geomarcheur_db');
        $data['resultat']=$this->geomarcheur_db->listAllUsers();

        foreach ($data as $value) {


            echo "<tr>";
            echo "<td><i class='material-icons circle orange accent-4 grey-text text-lighten-5'>account_circle</i></td>";
            echo "<td>" . $value['pseudo'] . "</td>";
            echo "<td>" . $value['credits'] . "</td>";
            echo "<td>" . $value['is_admin'] . "</td>";
            echo "</tr>";
        }

    }
















    public function create()
    {
        if ($this->input->server('REQUEST_METHOD') == 'GET'){
            $this->load->view('create_view');
        }
        else if ($this->input->server('REQUEST_METHOD') == 'POST'){
            $this->load->model('actualite_db');

            $data['title'] = $this->input->post('news_title');
            $data['image'] = $this->input->post('news_image');
            $data['text'] = $this->input->post('news_text');
            $id = $this->actualite_db->create($data);
            $data2['resultat']=$this->actualite_db->display($id);
            $this->load->view('index_view',$data2);
        }
    }






    public function display()
    {
        $this->load->model('produitbdd');

        $data['resultat']=$this->produitbdd->listerTous();

        $this->load->view('vuelistertous',$data);
    }




    /*
    public function operation()
     {
        // 1- récupérer les parametres / données de la requete HTTP, et faire les vérifications nécessaires (session etc.)
        // 2- faire éventuellement appel à la couche Base de données (requetes SQL)
        // 3- transmettre les données à la vue pour génération de la réponse HTTP
     }
     */
 }





