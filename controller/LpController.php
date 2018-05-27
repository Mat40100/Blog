<?php

namespace controller;

session_start();

class LpController extends ControllerMain {

    public function generic() {
        $home = $this->gen->getInfos();
        try {
            echo $this->twig->render(
                'content_home.twig', [
                    'infos' => $home
                ]
            );
        } catch (\Twig_Error_Loader $e) {
        } catch (\Twig_Error_Runtime $e) {
        } catch (\Twig_Error_Syntax $e) {
        }
        try {
            echo $this->twig->render('navbar_main.twig');
        } catch (\Twig_Error_Loader $e) {
        } catch (\Twig_Error_Runtime $e) {
        } catch (\Twig_Error_Syntax $e) {
        }
    }

    public function mail() {
        if ($this->gen->mailContact($_POST)) {
            header('location: ?p=alert&alert=Email envoyé!');
        } else {
            header('location: ?p=alert&alert=Problème avec l\'envoie, réessayez');
        }
    }

    public function alert() {
        $home = $this->gen->getInfos();
        try {
            echo $this->twig->render('navbar_main.twig');
        } catch (\Twig_Error_Loader $e) {
        } catch (\Twig_Error_Runtime $e) {
        } catch (\Twig_Error_Syntax $e) {
        }
        try {
            echo $this->twig->render(
                'alert.twig', [
                    'infos' => $home,
                    'alert' => $_GET['alert']
                ]
            );
        } catch (\Twig_Error_Loader $e) {
        } catch (\Twig_Error_Runtime $e) {
        } catch (\Twig_Error_Syntax $e) {
        }
    }

    public function dl() {
        if (isset($_GET['pdf'])) {
            $pdf = $_GET['pdf'];
            header("Content-type: application/pdf");
            header("Content-Disposition: attachment; filename=$pdf");
            readfile($pdf);
        }
    }

    public function log() {
        $_SESSION['user'] = new \model\entity\User();
        switch ($_SESSION['user']->connect($_POST['email'], $_POST['pwd'])) {
            case 'ok':
                if ($_SESSION['user']->getUserlvl() > 2) {
                    header('location: ?p=home');
                } elseif ($_SESSION['user']->getUserlvl() <= 2) {
                    header('location: ?p=admin');
                }
                break;
            case 'false_mdp':
                header('location: ?p=alert&alert=Mauvais Nom utilisateur ou mot de passe#login');
                break;
            case 'false_ip':
                header('location: ?p=alert&alert=Trop de tentatives, retentez plus tard.');
                break;
            default:
                break;
        }
    }

    public function disconnect() {
        $_SESSION = array();
        session_destroy;
        header('location: ?p=home#about');
    }

}
