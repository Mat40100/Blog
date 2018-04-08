<?php

require './controller/controller.php';

class Router {

    public static function redirect() {
        $Controller = new Controller();
        switch ($_GET['p']) {
            case 'home':
                switch ($_GET['d']) {
                    case '':
                        $Controller->acceuil();
                        break;

                    case 'connect':
                        $Controller->log();
                        break;
                    case 'disconnect':
                        $Controller->disconnect();
                        break;
                    default :
                        $Controller->acceuil();
                        break;
                }
                break;

            case 'blog':
                switch ($_GET['d']) {
                    case 'post':
                        if (!isset($_GET['id']) or $_GET['id'] == '0') {
                            $Controller->acceuil();
                        }
                        $id = intval($_GET['id']);
                        $Controller->post($id);
                        break;

                    case 'list':
                        $Controller->liste();
                        break;

                    default :
                        $Controller->liste();
                        break;
                }
                break;

            case 'admin':
                switch ($_GET['d']) {
                    case 'valid_comment':
                        $Controller->valid_comments();
                        break;
                    case 'add_post':
                        $Controller->add_post();
                        break;
                    case 'modifier':
                        $Controller->mod_post();
                        break;
                    case 'supprimer':
                        $Controller->del_post();
                        break;
                    case 'valid_mod':
                        $Controller->valid_mod();
                        break;

                    default:
                        $Controller->admin();
                        break;
                }

                break;

            case 'add_comment':
                $Controller->add_comment();
                break;
            case 'alert':
                $Controller->alert();
                break;
            default :
                $Controller->acceuil();
                break;
        }
    }

}

session_start();

if (isset($_SESSION['user'])) {
    switch ($_SESSION['user']->verif_ticket()) {
        case 'ok':
            break;
        case 'timed_out':
            session_destroy();
            header('location: ?p=alert&alert=Deconnection : Séssion expirée');
            break;
        case 'wrong_ticket':
            session_destroy();
            header('location: ?p=alert&alert=Deconnection : Disparité des jetons');
            break;
    }
}
Router::redirect();
