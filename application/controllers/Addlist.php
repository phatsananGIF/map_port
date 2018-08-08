<?php
class Addlist extends CI_Controller {

    function __construct() {      
        parent::__construct();
        $this->load->model('model_server');

        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->helper('file');
    }



    public function index(){
        $showtab = "oneadd";
        $datafile=array();
        $query = ("SELECT * FROM xinet_server"); 
        $server_sites = $this->db->query($query);
        $server_sites = $server_sites->result_array();


        if( $this->input->post("btsubmit") ){

            $this->form_validation->set_rules('file', 'file', 'callback_file_check');

            if ($this->form_validation->run() == FALSE){
                $showtab = "alladd";

            }else{
                $showtab = "alladd";

                if ($fh = fopen($_FILES["file"]["tmp_name"], 'r')){
                    $lines=array();
                    while (!feof($fh)){
                        $line = fgets($fh);
                        if( preg_match("/^\d{1,3}.\d{1,3}.\d{1,3}.\d{1,3} \d{1,5} \d{1,3}.\d{1,3}.\d{1,3}.\d{1,3} \d{1,5}$/",$line) ){
                            $lines[]=$line;
                        }

                    }
                    fclose($fh);
                }//if read file

                foreach($lines as $line){
                    $rowarray = explode(" ",$line);

                    $ipserver = trim($rowarray[0]);
                    $serverport = trim($rowarray[1]);
                    $ipremote = trim($rowarray[2]);
                    $remoteport = trim($rowarray[3]);

                    $query = ("SELECT COUNT(IF(serverIp = '$ipserver' AND serverPort = '$serverport' and deldt='0000-00-00 00:00:00',1,NULL))  as server,
                                COUNT(IF(remoteIp = '$ipremote' AND remotePort = '$remoteport' and deldt='0000-00-00 00:00:00',1,NULL))  as remote
                                FROM xinet_map_port"); 
                    $sites = $this->db->query($query);
                    $sites = $sites->row();


                    $queryip = $this->db->get_where('xinet_server', array('server' => $ipremote));
                    $ipcompare = $queryip->row();

                    array_splice($rowarray,0,0,'row');

                    if($sites->server != 0){
                        $rowarray[]="<font size='3' color='red'>ip server and port have already</font>";
                        $datafile[]=$rowarray;

                    }else if($sites->remote != 0){
                        $queryipserver = $this->db->get_where('xinet_map_port', array('remoteIp' => $ipremote, 'remotePort' => $remoteport, 'deldt'=>'0000-00-00 00:00:00' ));
                        $ipserver = $queryipserver->row();
                        $rowarray[]="<font size='3' color='red'>ip sim and port have already in ip server ".$ipserver->serverIp."</font>";
                        $datafile[]=$rowarray;

                    }else if($queryip->num_rows() != 0){
                        $rowarray[]="<font size='3' color='red'>ip sim is same ip server</font>";
                        $datafile[]=$rowarray;

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

                        
                        $rowarray[]="save";
                        $datafile[]=$rowarray;
                        
                    }


                }//วน save


                
            }//if-else form_validation

        }// bt-btsubmit



        $data['server_sites'] = $server_sites;
        $data['showtab'] = $showtab;
        $data['datafile'] = $datafile;

        $this->load->view('layouts/header_view');
        $this->load->view('add_view',$data);
        $this->load->view('layouts/footer_view');

    }//f.index



    public function file_check($str){
        $allowed_mime_type_arr = array('text/plain');
        $mime = get_mime_by_extension($_FILES['file']['name']);
        if(isset($_FILES['file']['name']) && $_FILES['file']['name']!=""){
            if(in_array($mime, $allowed_mime_type_arr)){
                return true;
            }else{
                $this->form_validation->set_message('file_check', 'Please select only text file.');
                return false;
            }
        }else{
            $this->form_validation->set_message('file_check', 'Please choose a file.');
            return false;
        }
    }//f.saveadd



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