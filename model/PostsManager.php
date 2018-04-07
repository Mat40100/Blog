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
        $data = $req->fetch(PDO::FETCH_ASSOC);
        
        $post = new Post(
                $data['postid'],
                $data['authorid'],
                $this->Uman->getNickname($data['authorid']),
                $data['title'],
                $data['chapo'],
                $data['content'],
                $data['last_mod']
                );
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
    public function PostPost(array $post){
        $db = DBfactory::Getinstance();
        $req = $db->prepare('INSERT INTO posts(authorid, title, last_mod, chapo, content) VALUES(:authorid, :title, :last_mod, :chapo, :content)');
        $req->execute(array(
            'authorid' => $_SESSION['user']->getUserid,
            'title' => $post['title'],
            'last_mod' => date("Y-m-d H:i:s"),
            'chapo' => $post['chapo'],
            'content' => $post['content']
        ));
    }
    public function DeletePost(){
        
    }
    public function ModPost(){
        
    }
}

