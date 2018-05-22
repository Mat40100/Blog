<?php

namespace controller;

session_start();

class Controller
{

    protected $Uman;
    protected $Pman;
    protected $Cman;
    protected $Gen;
    protected $twig;
    protected $db;

    public function __construct() 
    {
        $this->twig = \model\DBfactory::twig();
        $this->twig->addGlobal("session", $_SESSION['user']);

        $this->Uman = new \model\UsersManager();
        $this->Pman = new \model\PostsManager();
        $this->Cman = new \model\CommentManager();
        $this->Gen = new \model\Generic();
        $this->db = \model\DBfactory::Getinstance();
    }

    public function generic() 
    {
        $home = $this->Gen->getInfos();
        echo $this->twig->render(
            'content_home.twig', [
            'infos' => $home
            ]
        );
        echo $this->twig->render('navbar_main.twig');
    }

    public function mail() 
    {
        if ($this->Gen->mailContact($_POST)) {
            header('location: ?p=alert&alert=Email envoyé!');
        } else {
            header('location: ?p=alert&alert=Problème avec l\'envoie, réessayez');
        }
    }

    public function alert() 
    {
        $home = $this->Gen->getInfos();
        echo $this->twig->render('navbar_main.twig');
        echo $this->twig->render(
            'alert.twig', [
            'infos' => $home,
            'alert' => $_GET['alert']
            ]
        );
    }

    public function dl() 
    {
        if (isset($_GET['pdf'])) {
            $pdf = $_GET['pdf'];
            header("Content-type: application/pdf");
            header("Content-Disposition: attachment; filename=$pdf");
            readfile($pdf);
        }
    }

    public function liste() 
    {
        $posts = $this->Pman->getPosts();
        echo $this->twig->render(
            'content_list.twig', [
            'posts' => $posts,
            ]
        );
        echo $this->twig->render('navbar_blog.twig');
    }

    public function post($id) 
    {
        $post = new \model\entity\Post($id, "form");
        $comments = $this->Cman->getComments($id);
        echo $this->twig->render(
            'content_post.twig', [
            'post' => $post,
            'comments' => $comments]
        );
        echo $this->twig->render('navbar_blog.twig');
    }

    public function addComment() 
    {
        if ($this->Cman->addComment($_POST)) {
            header('location: ?p=alert&alert=Vous remplir completement les formulaires afin que le commentaire soit envoyé');
        } else {
            header('location: ?p=alert&alert=Vous devez être modérateur pour effectuer cette action');
        }
    }

    public function admin() 
    {
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']->getUserlvl() <= 2) {
                $unvalidComments = $this->Cman->getUnvalidComments();
                $posts = $this->Pman->getPosts();
                echo $this->twig->render(
                    'navbar_admin.twig', [
                    'hide_admin' => true
                    ]
                );
                echo $this->twig->render(
                    'content_admin.twig', [
                    'liste' => $posts,
                    'unvalid_comments' => $unvalidComments
                    ]
                );
            } else {
                header('location: ?p=alert&alert=Vous devez être modérateur pour effectuer cette action');
            }
        } else {
            header('location: ?p=alert&alert=Vous devez être connecté pour effectuer cette action');
        }
    }

    public function validComments() 
    {
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']->getUserlvl() <= 2) {
                $this->Cman->validComment($_POST);
                header('location: ?p=admin');
            } else {
                header('location: ?p=alert&alert=Vous devez être modérateur pour effectuer cette action');
            }
        } else {
            header('location: ?p=alert&alert=Vous devez être connecté pour effectuer cette action');
        }
    }

    public function addPost() 
    {
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']->getUserlvl() == 1) {
                $this->Pman->postPost($_POST);
                header('location: ?p=admin');
            } else {
                header('location: ?p=alert&alert=Vous devez être modérateur pour effectuer cette action');
            }
        } else {
            header('location: ?p=alert&alert=Vous devez être connecté pour effectuer cette action');
        }
    }

    public function modPost() 
    {
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']->getUserlvl() == 1) {
                $list = $this->Uman->getNameList();
                $post = new \model\entity\Post($_GET['id'], "no_form");
                echo $this->twig->render(
                    'modif_post.twig', [
                    'post' => $post,
                    'list' => $list
                    ]
                );
                echo $this->twig->render(
                    'navbar_admin.twig', [
                    'hide_mod' => true
                    ]
                );
            } else {
                header('location: ?p=alert&alert=Vous devez être administrateur pour effectuer cette action');
            }
        } else {
            header('location: ?p=alert&alert=Vous devez être connecté pour effectuer cette action');
        }
    }

    public function validMod() 
    {
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']->getUserlvl() == 1) {
                $this->Pman->modPost($_POST);
                header('location: ?p=blog&d=post&id=' . $_POST['postid']);
            } else {
                header('location: ?p=alert&alert=Vous devez être administrateur pour effectuer cette action');
            }
        } else {
            header('location: ?p=alert&alert=Vous devez être connecté pour effectuer cette action');
        }
    }

    public function delPost() 
    {
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']->getUserlvl() == 1) {
                $this->Pman->deletePost($_GET['id']);
                header('location: ?p=admin');
            } else {
                header('location: ?p=alert&alert=Vous devez être administrateur pour effectuer cette action');
            }
        } else {
            header('location: ?p=alert&alert=Vous devez être connecté pour effectuer cette action');
        }
    }

    public function log() 
    {
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

    public function disconnect() 
    {
        $_SESSION = array();
        session_destroy;
        header('location: ?p=home#about');
    }

}
