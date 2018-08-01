<?php
class Logout extends CI_Controller {

    function __construct() {      
        parent::__construct();
    }
    
    public function index(){

        $user_name = $this->session->userdata('user_name');
        $this->db->query(" INSERT INTO user_use_page (user_name, page_use, 	time_use) VALUES ('".$user_name."', 'Logout', now() ) ");

        $this->session->unset_userdata('userlogin');
        $this->session->unset_userdata('passwordlogin');
        $this->session->unset_userdata('type');
        $this->session->unset_userdata('web');

        redirect("","refresh");
        exit();

    }//end f.index





}//class

