<?php
class Manageuser extends CI_Controller {

    function __construct() {      
        parent::__construct();
    }
    
    public function index(){

        $queryuser = (" SELECT user_id, user_name, type_user.type_user_name, IF(register < dtupdate,dtupdate,register) as status
                        FROM user_login LEFT JOIN type_user ON user_login.user_type =type_user.type_user_id
                        WHERE disable='0000-00-00 00:00:00' ");
        $user_login = $this->db->query($queryuser);
        $user_login = $user_login->result_array();

        $querytype_user = (" SELECT * FROM type_user ");
        $type_user = $this->db->query($querytype_user);
        $arrlist['type_user'] = $type_user->result_array();

        foreach($user_login as $user){
            if($user){

            }

            $list=[ $user['user_id'],
                    $user['user_name'],
                    $user['type_user_name'],
                    $user['status'],
                    '<div style="text-align:center;">
                        <a class="btn btn-sm btn-outline-success" href="#" onclick="popup_edituser('.$user['user_id'].');"><i class="fa fa-fw fa-pencil-square-o"></i></a>
                        <a class="btn btn-sm btn-outline-danger" href="#" onclick="deluser('.$user['user_id'].');"><i class="fa fa-fw fa-trash-o"></i></a>
                    </div>'
                ];
                
            $arrlist['datauser'][] = $list;
        };

        $data['server_sites'] = $arrlist;

        $this->load->view('layouts/header_view');
        $this->load->view('manageuser_view',$data);
        $this->load->view('layouts/footer_view');
        
    }//end f.index



    public function getuserbyID(){
        $user_id = $this->input->post("id");

        $queryuser = $this->db->get_where('user_login', array('user_id' => $user_id, 'disable' => '0000-00-00 00:00:00'));
        $get_user = $queryuser->row();

        echo json_encode($get_user);

    }//end f.getuserbyID



    public function saveadd(){

        $username = $this->input->post("username");
        $password = $this->input->post("password");
        $typeuser = $this->input->post("typeuser");
/*
        $username = 'user';
        $password = '111';
        $typeuser = '1';
*/
        $queryuser = $this->db->get_where('user_login', array('user_name' => $username, 'disable' => '0000-00-00 00:00:00'));
        $userlogin = $queryuser->row();

        if($queryuser->num_rows() != 0){
            echo json_encode("user name have already");
        }else{
            //บันทึกข้อมูล
            $INSERT = ("INSERT INTO user_login (user_name, password, user_type, register) 
            VALUES ('$username', password('$password'), '$typeuser', now())");
            $this->db->query($INSERT);

            echo json_encode("save");
        }

    }//end f.saveadd



    public function saveedit(){
        $id = $this->input->post("id");
        $username = $this->input->post("username");
        $password = $this->input->post("password");
        $typeuser = $this->input->post("typeuser");

        //update ข้อมูล
        if($password == ""){
            $query = (" UPDATE user_login SET user_type = '$typeuser', dtupdate = now() WHERE user_id='$id' ");
            $this->db->query($query);
        }else{
            $query = (" UPDATE user_login SET password = password('$password'), user_type = '$typeuser',
                        dtupdate = now() WHERE user_id='$id' ");
            $this->db->query($query);
        }
        
        echo json_encode("save");
        
    }//end f.saveedit



    public function deluser($id){
        $query = (" UPDATE user_login SET disable = now() WHERE user_id='$id' ");
        $this->db->query($query);

        redirect("manageuser","refresh");
        exit();  

    }//end f.del

}//class