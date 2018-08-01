<?php
 
class Model_server extends CI_Model {
    
    public function getlistinserver($server_ip){
        $query = (" SELECT * FROM xinet_map_port WHERE serverIp='$server_ip' and deldt='0000-00-00 00:00:00' ");
        $server_sites = $this->db->query($query);
        $server_sites = $server_sites->result_array();
        return $server_sites;

    }//f.getlistinserver


}//class