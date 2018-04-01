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
                
                if($_GET['d']=='post'){
                    $id = intval($_GET['post']);
                    $Controller->post($id);
                }
                elseif($_GET['d']=='list'){
                    $Controller->liste();
                }
            }
            
        }
    }
}
 Router::redirect();