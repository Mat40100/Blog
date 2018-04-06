<?php

require './model/DBfactory.php';
require './model/Formatcontent.php';
require './model/PostsManager.php';
require './model/CommentManager.php';
require './model/UsersManager.php';
require './model/Acceuil.php';
require './model/entity/User.php';

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

    public function liste() {
        $posts = $this->Pman->GetPosts();
        echo $this->twig->render('content_list.twig', [
            'posts' => $posts,
        ]);
        echo $this->twig->render('navbar_blog.twig');
    }

    public function post($id) {
        $post = $this->Pman->GetPost($id);
        $comments = $this->Cman->getComments($id);
        $post['content'] = Formatcontent::format($post['content']);
        echo $this->twig->render('content_post.twig', ['post' => $post, 'comments' => $comments]);
        echo $this->twig->render('navbar_blog.twig');
    }

    public function acceuil() {
        $home = $this->Acc->getInfos();
        echo $this->twig->render('content_home.twig', ['infos' => $home]);
        echo $this->twig->render('navbar_main.twig');
    }

    public function admin() {
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']->getUserlvl() <= 2) {
                $unvalid_comments = $this->Cman->getUnvalid_Comments();
                echo $this->twig->render('navbar_admin.twig');
                echo $this->twig->render('content_admin.twig', ['unvalid_comments' => $unvalid_comments]);
            } else {
                header('location: ?p=home#about');
            }
        } else {
            header('location: ?p=home#about');
        }
    }

    public function add_comment() {
        if ($this->Cman->add_comment($_POST)) {
            header('location: index.php?p=blog&$d=post&id=' . $_POST['postid']);
        } else {
            //Probleme
        }
    }

    public function valid_comment() {
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']->getUserlvl() <= 2) {
                foreach ($_POST as $commentid => $value) {
                    $this->Cman->valid_comment($commentid);
                }
                header('location: ?p=admin');
            }
        }
    }

    public function log() {
        $_SESSION['user'] = new User();
        $_SESSION['user']->connect($_POST['email'], $_POST['pwd']);
    }

    public function disconnect() {
        $_SESSION = array();
        session_destroy;
        sleep(0.5);
        header('location: ?p=home#about');
    }

}