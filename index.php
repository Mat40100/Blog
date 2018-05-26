<?php

require "vendor/autoload.php";

class Router
{

    public static function redirect() 
    {
        $lpController = new controller\LpController();
        $AdminController = new \controller\AdminController();
        $BlogController = new \controller\BlogController();

        switch ($_GET['p']) {
        case 'home':
            switch ($_GET['d']) {
            case '':
                $lpController->generic();
                break;

            case 'connect':
                $lpController->log();
                break;
            case 'disconnect':
                $lpController->disconnect();
                break;
            default :
                $lpController->generic();
                break;
            }
            break;

        case 'blog':
            switch ($_GET['d']) {
            case 'post':
                $BlogController->post();
                break;

            case 'list':
                $BlogController->postList();
                break;

            case 'add_comment':
                $BlogController->addComment();
                break;

            default :
                $BlogController->liste();
                break;
            }
            break;

        case 'admin':
            switch ($_GET['d']) {
            case 'valid_comment':
                $AdminController->validComments();
                break;
            case 'add_post':
                $AdminController->addPost();
                break;
            case 'modifier':
                $AdminController->modPost();
                break;
            case 'supprimer':
                $AdminController->delPost();
                break;
            case 'valid_mod':
                $AdminController->validMod();
                break;
            default:
                $AdminController->admin();
                break;
            }

            break;

        case 'alert':
            $lpController->alert();
            break;
        case 'mail':
            $lpController->mail();
            break;
        case 'dl':
            $lpController->dl();
            break;
        default :
            $lpController->generic();
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
