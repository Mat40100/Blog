<?php
require_once './model/factory.php';
require './model/post_manager.php';
require './model/acceuil.php';
require './model/users_manager.php';
require './model/format_content.php';
require './model/comment_manager.php';


class Controller{
    private $twig;
    
    public function __construct() {
        $this->twig = $twig = DBfactory::twig();
    }
    public function liste(){
        $posts = PostsManager::GetPosts();      
        echo $this->twig->render('content_list.twig', ['posts'=>$posts]);
        echo $this->twig->render('navbar.twig');
    }
    public function post($id){
        $post = PostsManager::GetPost($id);
        $comments = comment_manager::getComments($id);
        $post['content']= Format_content::format($post['content']);
        echo $this->twig->render('content_post.twig', ['post'=>$post,'comments'=>$comments]);
        echo $this->twig->render('navbar.twig');
    }
    public function acceuil(){
        $home = acceuil::getInfos();
        echo $this->twig->render('content_home.twig', ['infos' => $home]);
        echo $this->twig->render('navbar.twig');
    }
    public function add_comment(){
        comment_manager::add_comment($_POST);
        echo "<script type='text/javascript'>document.location.replace('index.php?p=blog&d=post&id=".$_GET['post']."');</script>";

    }
    public function ERR(){
        
    }
}
