<?php
require_once './model/factory.php';
require './model/postmanager.php';
require './model/home.php';


class Controller{
    private $twig;
    
    public function __construct() {
        $this->twig = $twig = DBfactory::twig();
    }
    public function liste(){
        $posts = PostsManager::GetPosts();
        echo $this->twig->render('content_list.twig', ['posts'=>$posts]);
    }
    public function post($id){
        $post = PostsManager::GetPost($id);
        echo $this->twig->render('content_post.twig', ['post'=>$post]);
    }
    public function home(){
        $home = Home::getInfos();
        echo $this->twig->render('content_home.twig', ['infos' => $home]);
        echo $this->twig->render('navbar.twig');
    }
}
