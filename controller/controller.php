<?php

require './model/DBfactory.php';
require './model/Formatcontent.php';
require './model/PostsManager.php';
require './model/CommentManager.php';
require './model/UsersManager.php';
require './model/Acceuil.php';
require './model/entity/User.php';
require './model/entity/Post.php';

session_start();

class Controller {

    protected $Uman;
    protected $Pman;
    protected $Cman;
    protected $Acc;
    private $twig;

    public function __construct() {
        $this->twig = DBfactory::twig();
        $this->twig->addGlobal("session", $_SESSION['user']);

        $this->Uman = new UsersManager();
        $this->Pman = new PostsManager();
        $this->Cman = new CommentManager();
        $this->Acc = new Acceuil();
    }

    public function acceuil() {
        $home = $this->Acc->getInfos();
        echo $this->twig->render('content_home.twig', [
            'infos' => $home
        ]);
        echo $this->twig->render('navbar_main.twig');
    }

    public function alert() {
        $home = $this->Acc->getInfos();
        echo $this->twig->render('navbar_main.twig');
        echo $this->twig->render('alert.twig', [
            'infos' => $home,
            'alert' => $_GET['alert']
        ]);
    }

    public function liste() {
        $posts = $this->Pman->GetPosts();
        echo $this->twig->render('content_list.twig', [
            'posts' => $posts,
        ]);
        echo $this->twig->render('navbar_blog.twig');
    }

    public function post($id) {
        $post = new Post($id, "form");
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
        }else{
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
                $post = new Post($_GET['id'], "no_form");
                echo $this->twig->render('modif_post.twig', [
                    'post' => $post
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
        $_SESSION['user'] = new User();
        switch ($_SESSION['user']->connect($_POST['email'], $_POST['pwd'])) {
            case 'ok':
                header('location: ?p=admin');
                break;
            case 'false_mdp':
                header('location: ?p=alert&alert=Mauvais log/mdp');
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
