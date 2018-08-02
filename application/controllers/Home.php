<?php
class Home extends CI_Controller {

    function __construct() {      
        parent::__construct();
        $this->load->model('model_server');
    }


    
    public function index(){
        $sites = "";

        $query = ("SELECT * FROM xinet_server"); 
        $server_sites = $this->db->query($query);
        $server_sites = $server_sites->result_array();
    
        foreach($server_sites as $site){
            $query = (" SELECT COUNT(*) as count_server FROM xinet_map_port WHERE serverId='".$site['id']."' and deldt='0000-00-00 00:00:00' "); 
            $count_server = $this->db->query($query);
            $count_server = $count_server->row();
            $site['count'] = $count_server->count_server;
            $sites[] = $site;
        };


        $data['server_sites'] = $sites;

        $this->load->view('layouts/header_view');
        $this->load->view('server_view',$data);
        $this->load->view('layouts/footer_view');

    }//f.index



    public function getlist(){
            $server_ip = $this->input->post("server_ip");
            
            $list=[];
            $arrlist=[];
            $arrdata=[];

            $server_sites = $this->model_server->getlistinserver($server_ip); 

            foreach($server_sites as $site){
                //$url = base_url().'home/del/'.$site['id'];
                $status = $site['adddt'];
                if($site['editdt'] != '0000-00-00 00:00:00'){
                    $status = $site['editdt'];
                }
                
                $list=[ $site['serverId'],
                        $site['serverIp'], 
                        $site['serverPort'], 
                        $site['remoteIp'], 
                        $site['remotePort'], 
                        $status,
                        '<div style="text-align:center;">
                            <a class="btn btn-sm btn-outline-success" href="#" onclick="popup_edit('.$site['id'].');"><i class="fa fa-fw fa-pencil-square-o"></i></a>
                            <a class="btn btn-sm btn-outline-danger" href="#" onclick="dellist('.$site['id'].');"><i class="fa fa-fw fa-trash-o"></i></a>
                        </div>'
                    ];
                    
                $arrlist[] = $list;
            };

        
        if(count($arrlist) == 0){
            redirect("home","refresh");
            exit();
        }else{
            $arrdata['data']=$arrlist;
            $arrdata['serverIp']=$server_ip;
            echo json_encode( $arrdata);
        }
        
        
    }//f.getlist




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
                echo json_encode("ip remote is not format");
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
        redirect("home","refresh");
        exit();


    }//f.saveadd


    
    public function getbyID(){

        if($this->input->post()){
            $id = $this->input->post("id");
            

            $querybyID = (" SELECT * FROM xinet_map_port WHERE id='$id' "); 
            $listbyID = $this->db->query($querybyID);
            $listbyID = $listbyID->row();

            $queryport = (" SELECT remoteIp, remotePort FROM xinet_map_port WHERE remoteIp='".$listbyID->remoteIp."' and deldt='0000-00-00 00:00:00'  "); 
            $listportremote = $this->db->query($queryport);
            $listportremote = $listportremote->result_array();

            $datalist['listbyID'] = $listbyID;
            $datalist['listportremote'] = $listportremote;

            echo json_encode($datalist);

        }//if-post
        redirect("home","refresh");
        exit();

    }//f.getbyID



    public function save_edit(){

        if($this->input->post()){

            $id = $this->input->post("id");
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

            $query = ("SELECT COUNT(IF(id != '$id' and serverIp = '$ipserver' AND serverPort = '$serverport' and deldt='0000-00-00 00:00:00',1,NULL))  as server,
                        COUNT(IF(id != '$id' and remoteIp = '$ipremote' AND remotePort = '$remoteport' and deldt='0000-00-00 00:00:00',1,NULL))  as remote
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
                
                //อัพเดทข้อมูลทั้งหมด
                $queryupdate = (" UPDATE xinet_map_port SET serverIp = '$ipserver', serverPort = '$serverport',
                                remoteIp = '$ipremote', remotePort = '$remoteport', editdt = now() WHERE id='$id' ");
                $this->db->query($queryupdate);

                
                echo json_encode("save");
                
            }        

        }//if-post
        redirect("home","refresh");
        exit();


    }//f.save_edit



    public function del(){
        
        if($this->input->post()){
            $id = $this->input->post("id");
            
            $query = (" UPDATE xinet_map_port SET deldt = now() WHERE id='$id' ");
            $this->db->query($query);

            $query = (" SELECT serverIp FROM xinet_map_port WHERE id='$id' "); 
            $ip_server = $this->db->query($query);
            $ip_server = $ip_server->row();

            echo json_encode($ip_server);

        }//if-post
        redirect("home","refresh");
        exit();


    }//end f.del

}//class