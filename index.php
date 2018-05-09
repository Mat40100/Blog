<?php

require "vendor/autoload.php";

class Router
{

    public static function redirect() 
    {
        $Controller = new controller\Controller();
        switch ($_GET['p']) {
        case 'home':
            switch ($_GET['d']) {
            case '':
                $Controller->generic();
                break;

            case 'connect':
                $Controller->log();
                break;
            case 'disconnect':
                $Controller->disconnect();
                break;
            default :
                $Controller->generic();
                break;
            }
            break;

        case 'blog':
            switch ($_GET['d']) {
            case 'post':
                if (!isset($_GET['id']) || $_GET['id'] == '0') {
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
                $Controller->validComments();
                break;
            case 'add_post':
                $Controller->addPost();
                break;
            case 'modifier':
                $Controller->modPost();
                break;
            case 'supprimer':
                $Controller->delPost();
                break;
            case 'valid_mod':
                $Controller->validMod();
                break;
            default:
                $Controller->admin();
                break;
            }

            break;

        case 'add_comment':
            $Controller->addComment();
            break;
        case 'alert':
            $Controller->alert();
            break;
        case 'mail':
            $Controller->mail();
            break;
        case 'dl':
            $Controller->dl();
            break;
        default :
            $Controller->generic();
            break;
        }
    }

}

session_start();

if (isset($_SESSION['user'])) {
    switch ($_SESSION['user']->verifTicket()) {
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
