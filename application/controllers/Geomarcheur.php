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

        if ($place_id != null){
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


    public function delete()
    {
        $idproduit=$this->uri->segment(3);

        if ($idproduit ==null) {
            $data['erreur']="Id produit introuvable";
            $data['message']="";
            $data['resultat']="";
        } else {
            $this->load->model('actualite_db');
            $data['resultat']=$this->actualite_db->delete($idproduit);
            $data['erreur']="";
            $data['message']="Produit n° ".$idproduit." supprimé.";
        }
        $this->load->view('delete_view',$data);
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





