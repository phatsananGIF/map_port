<?php
class Auto extends CI_Controller {

    function __construct() {
        parent::__construct();
        //Load radius database
        $this->radius_db = $this->load->database('radius_db', TRUE);//เรียกใช้งาน radius ดาต้าเบส
        $this->load->library('encrypt');//เข้ารหัส
    }

    public function index(){
        $this->session->unset_userdata('userlogin');
        $this->session->unset_userdata('passwordlogin');
        $this->session->unset_userdata('type');
        $this->session->unset_userdata('web');

        $code = $_GET['code'];
        $code = str_replace(" ", "+", $code);

        $num = substr($code,0,1);
        $code = substr($code,1);
        $code = substr_replace($code, '', $num ,1);
        $user = $this->encrypt->decode($code);

        $rslogin = $this->radius_db->query("SELECT * FROM user_login WHERE user_name = '$user' AND disable = '' ");
        $rslogin = $rslogin->row_array();

        if(count($rslogin) == 0){
            redirect("login","refresh");
            exit();
        }else{
            
            $newdata = array(
                'userlogin'     => $rslogin['user_name'],
                'passwordlogin' => $rslogin['password'],
                'type'          => $rslogin['user_type'],
                'web'           => 'other'
            );
            $this->session->set_userdata($newdata);
            
            $this->db->query(" INSERT INTO user_use_page (user_name, page_use, time_use) VALUES ('$user', 'LoginAuto', now() ) ");
            
            redirect("","refresh");
            exit();
            
        }


    }//end f.index

}//class