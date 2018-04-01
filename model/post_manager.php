<?php

class PostsManager{
       
    private function __construct() {
    }
    static public function GetPost($postid){
        $db = DBfactory::Getinstance();
        $req = $db->prepare('SELECT * FROM posts WHERE postid = :postid');
        $req->execute(array(
            'postid' => $postid
                ));
        $post = $req->fetch(PDO::FETCH_ASSOC);
        $post['author_nickname']= users_manager::giveNickname($post['authorid']);
        return $post;
    }
    static public function GetPosts(){
        $db = DBfactory::Getinstance();
        $req = $db->query('SELECT * from posts');
        while($donnees=$req->fetch(PDO::FETCH_ASSOC)){
            $donnees['author_nickname']= users_manager::giveNickname($donnees['authorid']);
            $posts[]=$donnees;
        }
        return $posts;
    }
    public function PostPost(){
        
    }
    public function DeletePost(){
        
    }
    public function ModPost(){
        
    }
}

