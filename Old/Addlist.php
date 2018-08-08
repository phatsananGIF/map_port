<?php
class Addlist extends CI_Controller {

    function __construct() {      
        parent::__construct();
        $this->load->model('model_server');
    }



    public function index(){
        $query = ("SELECT * FROM xinet_server"); 
        $server_sites = $this->db->query($query);
        $server_sites = $server_sites->result_array();

        $data['server_sites'] = $server_sites;

        $this->load->view('layouts/header_view');
        $this->load->view('add_view',$data);
        $this->load->view('layouts/footer_view');

    }//f.index=-



    public function saveadd(){

        if($this->input->post()){
            
            $ipserver = $this->input->post("ipserver");
            $serverport = $this->input->post("serverport");
            $ipremote = $this->input->post("ipremote");
            $remoteport = $this->input->post("remoteport");


            //--- match IP---//
            if( !preg_match("/^\d{1,3}.\d{1,3}.\d{1,3}.\d{1,3}$/",$ipserver) ){
                echo json_encode("ip server is not format");
                return;
            }else if( !preg_match("/^\d{1,3}.\d{1,3}.\d{1,3}.\d{1,3}$/",$ipremote) ){
                echo json_encode("ip sim is not format");
                return;
            }
    
            $query = ("SELECT COUNT(IF(serverIp = '$ipserver' AND serverPort = '$serverport' and deldt='0000-00-00 00:00:00',1,NULL))  as server,
                        COUNT(IF(remoteIp = '$ipremote' AND remotePort = '$remoteport' and deldt='0000-00-00 00:00:00',1,NULL))  as remote
                        FROM xinet_map_port"); 
            $sites = $this->db->query($query);
            $sites = $sites->row();

            $queryip = $this->db->get_where('xinet_server', array('server' => $ipremote));
            $ipcompare = $queryip->row();


            if($sites->server != 0){
                echo json_encode("ip server and port have already");
            }else if($sites->remote != 0){
                $queryipserver = $this->db->get_where('xinet_map_port', array('remoteIp' => $ipremote, 'remotePort' => $remoteport, 'deldt'=>'0000-00-00 00:00:00' ));
                $ipserver = $queryipserver->row();
                echo json_encode("ip sim and port have already in ip server ".$ipserver->serverIp);
            }else if($queryip->num_rows() != 0){
                echo json_encode("ip sim is same ip server");
            }else{
                
                $queryserver = $this->db->get_where('xinet_server', array('server' => $ipserver));
                $serverid = $queryserver->row();

                if($queryserver->num_rows() == 0){
                    //บันทึกข้อมูล ip server ที่ไม่มี
                    $INSERT = ("INSERT INTO xinet_server (server,adddt) VALUES ('$ipserver', now())");
                    $this->db->query($INSERT);

                    $queryserver = $this->db->get_where('xinet_server', array('server' => $ipserver));
                    $serverid =  $queryserver->row();
                }

                //บันทึกข้อมูลทั้งหมด
                $INSERT = ("INSERT INTO xinet_map_port (serverId, serverIp, serverPort, remoteIp, remotePort, adddt) 
                            VALUES ('$serverid->id', '$ipserver', '$serverport', '$ipremote', '$remoteport', now())");
                $this->db->query($INSERT);

                
                echo json_encode("save");
                
            }

        }//if-post
        redirect("addlist","refresh");
        exit();


    }//f.saveadd


    


}//class