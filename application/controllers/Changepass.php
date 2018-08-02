<?php
class Changepass extends CI_Controller {

    function __construct() {      
        parent::__construct();
    }
    
    public function index(){

        if($this->input->post("btchangepass")!=null){
            
            $this->load->helper(array('form', 'url'));
            $this->load->library('form_validation');
    
            $this->form_validation->set_rules(
                'changpass[original]', 'old password',
                'required|trim|callback_Opass_check',
                array(  'required'  => 'please enter %s')

            );

            $this->form_validation->set_rules(
                'changpass[new]', 'new password',
                'required|trim|min_length[3]',
                array('required' => 'please enter %s',
                        'min_length'=> '%s ต้องมีอย่างน้อย 3 ตัว')
            );

            $this->form_validation->set_rules(
                'changpass[connew]', 'confirm password',
                'required|trim|matches[changpass[new]]',
                array(  'required' => 'please %s',
                        'matches' => '%s not matched'
                )
            );
        
            if ($this->form_validation->run() == FALSE){
                    

            }else{

                $this->db->query(" UPDATE user_login set password=PASSWORD('".$this->input->post('changpass[new]')."') , dtupdate=now() WHERE user_name ='".$this->session->userdata('userlogin')."' and disable='0000-00-00 00:00:00' ");
                
                $this->session->set_userdata('passwordlogin',$this->input->post("changpass[new]"));

                redirect("","refresh");
                exit();
            }


            
        }
    
        $this->load->view('layouts/header_view');
        $this->load->view('changepassword_view');
        $this->load->view('layouts/footer_view');


    }//end f.index


    public function Opass_check($str){
        
        $rspass = $this->db->query("SELECT * FROM user_login WHERE user_name = '".$this->session->userdata('userlogin')."' AND password = password('".$str."') AND disable = '0000-00-00 00:00:00' ");
        

        if (($str != "") && ($rspass->num_rows() == 0)){
            $this->form_validation->set_message('Opass_check', '%s wrong');
            return FALSE;
        }else{
            return TRUE;
        }
    
    }//end f.Opass_check



}//class 

