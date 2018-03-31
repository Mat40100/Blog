<?php

class PostsManager{
        
    public function GetPost($postid){
        $db = DBfactory::Getinstance();
        $req = $db->prepare('SELECT * FROM posts WHERE postid = :postid');
        $req->execute(array(
            'postid' => $postid
                ));
        $post = $req->fetch();
        return $post;
    }
    static public function GetPosts(){
        $db = DBfactory::Getinstance();
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

