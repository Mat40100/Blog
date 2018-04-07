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
        echo $this->twig->render('content_post.twig', [
            'post' => $post,
            'comments' => $comments]
                );
        echo $this->twig->render('navbar_blog.twig');
    }

    public function acceuil() {
        $home = $this->Acc->getInfos();
        echo $this->twig->render('content_home.twig', [
            'infos' => $home
                ]);
        echo $this->twig->render('navbar_main.twig');
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
                echo $this->twig->render('navbar_admin.twig');
                echo $this->twig->render('content_admin.twig', [
                    'liste' => $posts,
                    'unvalid_comments' => $unvalid_comments
                    ]);
            } else {
                header('location: ?p=home#about');
            }
        } else {
            header('location: ?p=home#about');
        }
    }

    public function valid_comments() {
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']->getUserlvl() <= 2) {
                $this->Cman->valid_comment($_POST);
                header('location: ?p=admin');
            }
        }
    }
    
    public function add_post(){
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']->getUserlvl() == 1) {
                $this->Pman->PostPost($_POST);
                header('location: ?p=admin'); 
            }else{
              header('location: ?p=admin');  
            }
        }
    }
    
    public function mod_post(){
        
    }
    
    public function del_post(){
        
    }

    public function log() {
        $_SESSION['user'] = new User();
        if($_SESSION['user']->connect($_POST['email'], $_POST['pwd'])){
           header('location: ?p=admin');
        }else{
           header('location: ?p=home#login');
        }        
    }

    public function disconnect() {
        $_SESSION = array();
        session_destroy;
        header('location: ?p=home#about');
    }

}