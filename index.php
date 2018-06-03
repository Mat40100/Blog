<?php

require "vendor/autoload.php";

/**
 * Class Router
 */
class Router
{

    /**
     *
     */
    public static function redirect()
    {
        $lpController = new \controller\LpAbstractController();
        $adminController = new \controller\AdminAbstractController();
        $blogController = new \controller\BlogAbstractController();

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
                $blogController->post();
                break;

            case 'list':
                $blogController->postList();
                break;

            case 'add_comment':
                $blogController->addComment();
                break;

            default :
                $blogController->postList();
                break;
            }
            break;

        case 'admin':
            switch ($_GET['d']) {
            case 'valid_comment':
                $adminController->validComments();
                break;
            case 'add_post':
                $adminController->addPost();
                break;
            case 'modifier':
                $adminController->modPost();
                break;
            case 'supprimer':
                $adminController->delPost();
                break;
            case 'valid_mod':
                $adminController->validMod();
                break;
            default:
                $adminController->admin();
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
