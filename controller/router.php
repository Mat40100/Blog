<?php
require 'controller.php';

class Router {
    public static function redirect(){
        if(isset($_GET['p'])){
            $Controller = new Controller($twig);
            if($_GET['p'] == "home"){
                
            }
            if($_GET['p'] == "liste"){
                $list = $Controller->liste();
            }
            if($_GET['p'] == "post"){
                if(isset($_GET['post'])){
                    $id = intval($_GET['post']);
                    $post = $Controller->post($id);
                }else{
                    // Gestion erreure
                }
            }
            
        }
    }
}
 Router::redirect();