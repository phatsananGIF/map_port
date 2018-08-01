<?php
//class สำหรับ check login
class Onload{
    private $ci;
    function __construct(){
        $this->ci = &get_instance();// &get_instance() = เรียกใช้งานคุณสมบัตของci
    }
    
    public function check_login(){
        $controller = $this->ci->router->class; //จะ return controller หน้าปัจจุบันนะเวลานั้นออกมา
        $function = $this->ci->router->method; //จะ return function ที่ใช้งานนะเวลานั้นออกมา
        if($controller == "auto"){
           
        }elseif($this->ci->session->userdata("userlogin")==null){//check session login ว่ามีเข้ามาหรือยัง
            if($controller != "login"){//ถ้าไม่ได้อยู่หน้า login ให้รีไปหน้า login
                redirect("login","refresh");
                exit();
            }
        }else{
            $page = $controller.'/'.$function;
            $user_name = $this->ci->session->userdata("userlogin");

            $this->ci->db->query(" INSERT INTO user_use_page (user_name, page_use, 	time_use) VALUES ('".$user_name."', '".$page."', now() ) ");

            if($controller == "login"){//ถ้าอยู่หน้า login ให้รีไป
                redirect("","refresh");
                exit();
            }else if($this->ci->session->userdata("type")!=1 && $controller == "manageuser" && $this->ci->session->userdata("web")=='other' ){
                redirect("","refresh");
                exit();
            }
        }

    }//f.check_login
}
?>