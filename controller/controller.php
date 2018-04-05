<?php

require './autoloader.php';
Autoloader::register('model');
require './model/entity/User.php';

session_start();

class Controller {

    private $twig;

    public function __construct() {
        $this->twig = DBfactory::twig();
        $this->twig->addGlobal("session", $_SESSION['user']);
    }

    public function liste() {
        $posts = PostsManager::GetPosts();
        echo $this->twig->render('content_list.twig', [
            'posts' => $posts,
        ]);
        echo $this->twig->render('navbar_blog.twig');
    }

    public function post($id) {
        $post = PostsManager::GetPost($id);
        $comments = CommentManager::getComments($id);
        $post['content'] = Formatcontent::format($post['content']);
        echo $this->twig->render('content_post.twig', ['post' => $post, 'comments' => $comments]);
        echo $this->twig->render('navbar_blog.twig');
    }

    public function acceuil() {
        $home = acceuil::getInfos();
        echo $this->twig->render('content_home.twig', ['infos' => $home]);
        echo $this->twig->render('navbar_main.twig');
    }

    public function admin() {
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']->getUserlvl() <= 2) {
                $unvalid_comments = CommentManager::getUnvalid_Comments();
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
        if (CommentManager::add_comment($_POST)) {
            header('location: index.php?p=blog&$d=post&id=' . $_POST['postid']);
        } else {
            //Probleme
        }
    }

    public function valid_comment() {
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']->getUserlvl() <= 2) {
                foreach ($_POST as $commentid => $value) {
                    CommentManager::valid_comment($commentid);
                }
                header('location: ?p=admin');
            }
        }
    }

    public function log() {
        $_SESSION['user'] = new User($_POST['email'], $_POST['pwd']);
    }

    public function disconnect() {
        $_SESSION = array();
        session_destroy;
        sleep(0.5);
        header('location: ?p=home#about');
    }

}