<?php
require './controller/controller.php';

class Router {
    public static function redirect(){
        if(isset($_GET['p'])){
            $Controller = new Controller($twig);
            if($_GET['p'] == "home"){
                $Controller->home();
            }
            elseif($_GET['p'] == "blog"){
                if(isset($_GET['post'])){
                    $id = intval($_GET['post']);
                    $Controller->post($id);
                }else{
                    $Controller->liste();
                }
            }
            
        }
    }
}
 Router::redirect();