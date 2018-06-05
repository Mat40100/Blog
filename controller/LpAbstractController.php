<?php

namespace controller;

use model\Formatcontent;

session_start();

/**
 * Class LpController
 * @package controller
 */
class LpAbstractController extends AbstractController {

    /**
     *
     */
    public function generic() {
        $home = $this->gen->getInfos();

        echo $this->twig->render(
            'content_home.twig', [
                'infos' => $home
            ]
        );

        echo $this->twig->render('navbar_main.twig');
    }

    /**
     *
     */
    public function mail() {

        if(!$this->gen->mailContact($_POST)){
            header('location: ?p=alert&alert=Problème avec l\'envoie, réessayez');
        }

        header('location: ?p=alert&alert=Email envoyé!');

    }

    /**
     *
     */
    public function alert() {
        $home = $this->gen->getInfos();

        echo $this->twig->render('navbar_main.twig');


        echo $this->twig->render(
            'alert.twig', [
                'infos' => $home,
                'alert' => $_GET['alert']
            ]
        );

    }

    /**
     *
     */
    public function dl() {

        if(!isset($_GET['pdf'])){
            header('location: ?p=alert&alert=Problème avec l\'url');
        }

        $pdf = $_GET['pdf'];
        header("Content-type: application/pdf");
        header("Content-Disposition: attachment; filename=$pdf");
        readfile($pdf);
    }

    /**
     *
     */
    public function log() {
        switch ($this->usersManager->connect(Formatcontent::formatBdd($_POST['email']), Formatcontent::formatBdd($_POST['pwd']))) {
            case 'ok':
                if ($_SESSION['user']->getUserLvl() > 2) {
                    header('location: ?p=home');
                } elseif ($_SESSION['user']->getUserLvl() <= 2) {
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
                header('location: ?p=alert&alert=Erreur système');
                break;
        }
    }

    /**
     *
     */
    public function disconnect() {
        $_SESSION = array();
        session_destroy();
        header('location: ?p=home#about');
    }

}
