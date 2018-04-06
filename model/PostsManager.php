<?php

class PostsManager{
    protected $Uman;
    
    public function __construct() {
        $this->Uman = new UsersManager();
    }
       
    public function GetPost($postid){
        $db = DBfactory::Getinstance();
        $req = $db->prepare('SELECT * FROM posts WHERE postid = :postid');
        $req->execute(array(
            'postid' => $postid
                ));
        $post = $req->fetch(PDO::FETCH_ASSOC);
        $post['author_nickname']= $this->Uman->getNickname($post['authorid']);
        return $post;
    }
    public function GetPosts(){
        $db = DBfactory::Getinstance();
        $req = $db->query('SELECT * from posts');
        while($donnees=$req->fetch(PDO::FETCH_ASSOC)){
            $donnees['author_nickname']= $this->Uman->getNickname($donnees['authorid']);
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

