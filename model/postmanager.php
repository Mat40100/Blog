<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PostsManager
 *
 * @author Programmation
 */
require('Manager.php');

class PostsManager extends DBfactory {
        
    public function GetPost($postid){
        $db = PostsManager::Getinstance();
        $req = $db->prepare('SELECT * FROM posts WHERE postid = :postid');
        $req->execute(array(
            'postid' => $postid
                ));
        $post = $req->fetch();
        return $post;
    }
    static public function GetPosts(){
        $db = PostsManager::Getinstance();
        $req = $db->query('SELECT * from posts');
        while($donnees=$req->fetch()){
            $posts[]=$donnees;
        }
        return $posts;
    }
    public function PostPost(){
        
    }
    public function DeletePost($postid){
        
    }
    public function ModPost($postid){
        
    }
}
