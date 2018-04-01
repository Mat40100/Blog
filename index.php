<?php
require './controller/controller.php';

class Router {
    public static function redirect(){
        if(isset($_GET['p'])){
            $Controller = new Controller($twig);
            if($_GET['p'] == "home"){
                $Controller->acceuil();
            }
            elseif($_GET['p'] == "blog"){
                
                if($_GET['d']=='post' && isset($_GET['id'])){
                    $id = intval($_GET['id']);
                    $Controller->post($id);
                }
                elseif($_GET['d']=='post' && !isset($_GET['id'])){
                    $Controller->ERR();
                }
                elseif($_GET['d']=='list'){
                    $Controller->liste();
                }
            }
            elseif($_GET['p'] == 'add_comment'){
               $Controller->add_comment();
            }
        }
    }
}
 Router::redirect();