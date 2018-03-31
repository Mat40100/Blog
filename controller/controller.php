<?php

require './model/postmanager.php';


class Controller{
    private $twig;
    
    public function __construct() {
        $this->twig = $twig = PostsManager::twig();
    }
    public function liste(){
        $posts = PostsManager::GetPosts();
        echo $this->twig->render('listposts.twig', ['posts'=>$posts]);
    }
    public function post($id){
        $post = PostsManager::GetPost($id);
        echo $this->twig->render('post.twig', ['post'=>$post]);
    }
}
