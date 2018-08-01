<?php
class Login extends CI_Controller {

    function __construct() {
        parent::__construct();
        //Load radius database
        $this->radius_db = $this->load->database('radius_db', TRUE);//เรียกใช้งาน radius ดาต้าเบส
        $this->load->library('encrypt');//เข้ารหัส
    }

    public function index(){
        
        if($this->input->post("btlogin")!=null){

            $this->load->helper(array('form', 'url'));
            $this->load->library('form_validation');
    
            $this->form_validation->set_rules(
                'InputUser', 'User name',
                'required|trim|callback_InputUser_check',
                array('required' => 'Please enter %s')

            );

            $this->form_validation->set_rules(
                'InputPassword', 'Password',
                'required|trim|callback_InputPassword_check',
                array(  'required'  => 'Please enter %s')
            );
     
            if ($this->form_validation->run() == FALSE){
                   

            }else{

                $rslogin = $this->db->query("SELECT * FROM user_login WHERE user_name = '".$this->input->post('InputUser')."' AND password = password('".$this->input->post('InputPassword')."') AND disable = '0000-00-00 00:00:00' ");
                
                $rslogin = $rslogin->row_array();

                $newdata = array(
                        'userlogin'     => $rslogin['user_name'],
                        'passwordlogin' => $rslogin['password'],
                        'type'          => $rslogin['user_type'],
                        'web'           => 'my'
                );

                $this->session->set_userdata($newdata);

                $this->db->query(" INSERT INTO user_use_page (user_name, page_use, 	time_use) VALUES ('".$this->input->post('InputUser')."', 'Login', now() ) ");
                

                redirect("","refresh");
                exit();
            }


        }//btlogin

        $this->load->view('login_view');

    }//end f.index


    public function InputUser_check($str){
        
        $rsuserlogin = $this->db->get_where('user_login', array('user_name' => $str,'disable' => '0000-00-00 00:00:00'));
    
        if ( ($str != "") && ($rsuserlogin->num_rows() == 0) ){
            $this->form_validation->set_message('InputUser_check', '%s wrong');
            return FALSE;
        }else{
            return TRUE;
        }
    
    }//end f.InputUser_check

    public function InputPassword_check($str){
        
        $rspasslogin = $this->db->query("SELECT * FROM user_login WHERE user_name = '".$this->input->post('InputUser')."' AND password = password('".$str."') AND disable = '0000-00-00 00:00:00' ");
        //print_r($this->db->last_query());

        if (  ($str != "") && ($rspasslogin->num_rows() == 0) ){
            $this->form_validation->set_message('InputPassword_check', '%s wrong');
            return FALSE;
        }else{
            return TRUE;
        }
    
    }//end f.InputPassword_check


    
    public function indexT(){

        $query = ("SELECT * FROM user_login limit 1"); 
        $user_login_all = $this->radius_db->query($query);
        $user_login_all = $user_login_all->result_array();

        foreach($user_login_all as $user){
            $pass = "123456";
            $user['password'] = $this->encrypt->encode($pass);
            $user['password'] = $this->encrypt->encode($user['password']);
            $user['password'] = $this->encrypt->decode($user['password']);
            $user['password'] = $this->encrypt->decode($user['password']);

        }
        

        $this->session->set_userdata($user);
        

        $this->load->view('login_view');

    }//end f.index




}//class

