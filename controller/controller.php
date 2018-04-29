<?php

namespace controller;

session_start();

class Controller {

    protected $Uman;
    protected $Pman;
    protected $Cman;
    protected $Gen;
    private $twig;
    protected $db;

    public function __construct() {
        $this->twig = \model\DBfactory::twig();
        $this->twig->addGlobal("session", $_SESSION['user']);

        $this->Uman = new \model\UsersManager();
        $this->Pman = new \model\PostsManager();
        $this->Cman = new \model\CommentManager();
        $this->Gen = new \model\Generic();
        $this->db = \model\DBfactory::Getinstance();
    }

    public function generic() {
        $home = $this->Gen->getInfos();
        echo $this->twig->render('content_home.twig', [
            'infos' => $home
        ]);
        echo $this->twig->render('navbar_main.twig');
    }

    public function mail() {
        if ($this->Gen->mail_contact($_POST)) {
            header('location: ?p=alert&alert=Email envoyé!');
        } else {
            header('location: ?p=alert&alert=Problème avec l\'envoie, réessayez');
        }
    }

    public function alert() {
        $home = $this->Gen->getInfos();
        echo $this->twig->render('navbar_main.twig');
        echo $this->twig->render('alert.twig', [
            'infos' => $home,
            'alert' => $_GET['alert']
        ]);
    }

    public function dl() {
        if (isset($_GET['pdf'])) {
            $pdf = $_GET['pdf'];
            header("Content-type: application/pdf");
            header("Content-Disposition: attachment; filename=$pdf");
            readfile($pdf);
        }
    }

    public function liste() {
        $posts = $this->Pman->GetPosts();
        echo $this->twig->render('content_list.twig', [
            'posts' => $posts,
        ]);
        echo $this->twig->render('navbar_blog.twig');
    }

    public function post($id) {
        $post = new \model\entity\Post($id, "form");
        $comments = $this->Cman->getComments($id);
        echo $this->twig->render('content_post.twig', [
            'post' => $post,
            'comments' => $comments]
        );
        echo $this->twig->render('navbar_blog.twig');
    }

    public function add_comment() {
        if ($this->Cman->add_comment($_POST)) {
            header('location: index.php?p=blog&$d=post&id=' . $_POST['postid']);
        } else {
            //Probleme
        }
    }

    public function admin() {
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']->getUserlvl() <= 2) {
                $unvalid_comments = $this->Cman->getUnvalid_Comments();
                $posts = $this->Pman->GetPosts();
                echo $this->twig->render('navbar_admin.twig', [
                    'hide_admin' => true
                ]);
                echo $this->twig->render('content_admin.twig', [
                    'liste' => $posts,
                    'unvalid_comments' => $unvalid_comments
                ]);
            } else {
                header('location: ?p=alert&alert=Vous devez être modérateur pour effectuer cette action');
            }
        } else {
            header('location: ?p=alert&alert=Vous devez être connecté pour effectuer cette action');
        }
    }

    public function valid_comments() {
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']->getUserlvl() <= 2) {
                $this->Cman->valid_comment($_POST);
                header('location: ?p=admin');
            } else {
                header('location: ?p=alert&alert=Vous devez être modérateur pour effectuer cette action');
            }
        } else {
            header('location: ?p=alert&alert=Vous devez être connecté pour effectuer cette action');
        }
    }

    public function add_post() {
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']->getUserlvl() == 1) {
                $this->Pman->PostPost($_POST);
                header('location: ?p=admin');
            } else {
                header('location: ?p=alert&alert=Vous devez être modérateur pour effectuer cette action');
            }
        } else {
            header('location: ?p=alert&alert=Vous devez être connecté pour effectuer cette action');
        }
    }

    public function mod_post() {
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']->getUserlvl() == 1) {
                $list = $this->Uman->getNameList();
                $post = new \model\entity\Post($_GET['id'], "no_form");
                echo $this->twig->render('modif_post.twig', [
                    'post' => $post,
                    'list' => $list
                ]);
                echo $this->twig->render('navbar_admin.twig', [
                    'hide_mod' => true
                ]);
            } else {
                header('location: ?p=alert&alert=Vous devez être administrateur pour effectuer cette action');
            }
        } else {
            header('location: ?p=alert&alert=Vous devez être connecté pour effectuer cette action');
        }
    }

    public function valid_mod() {
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']->getUserlvl() == 1) {
                $this->Pman->ModPost($_POST);
                header('location: ?p=blog&d=post&id=' . $_POST['postid']);
            } else {
                header('location: ?p=alert&alert=Vous devez être administrateur pour effectuer cette action');
            }
        } else {
            header('location: ?p=alert&alert=Vous devez être connecté pour effectuer cette action');
        }
    }

    public function del_post() {
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']->getUserlvl() == 1) {
                $this->Pman->DeletePost($_GET['id']);
                header('location: ?p=admin');
            } else {
                header('location: ?p=alert&alert=Vous devez être administrateur pour effectuer cette action');
            }
        } else {
            header('location: ?p=alert&alert=Vous devez être connecté pour effectuer cette action');
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
                header('location: ?p=alert&alert=Mauvais Nom utilisateur ou mot de passe');
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
