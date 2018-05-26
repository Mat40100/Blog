<?php

namespace model;

use model\entity\Post;

class PostsManager
{

    protected $uman;
    protected $db;

    public function __construct()
    {
        $this->uman = new UsersManager();
        $this->db = DBfactory::getInstance();
    }

    public function getPost($postid)
    {
        $req = $this->db->prepare('SELECT * FROM posts WHERE postid = :postid');
        $req->execute(
            array(
            'postid' => $postid
            )
        );
        $data = $req->fetch(\PDO::FETCH_ASSOC);
        $post = new Post($data,"form");
        if($post->getError()===0){
            return $post;
        }
        else{
            return false;
        }
    }

    public function getPosts() 
    {
        $req = $this->db->query('SELECT * from posts');
        while ($data = $req->fetch(\PDO::FETCH_ASSOC)) {
            $post= new Post($data,"form");
            if($post->getError()===0){
                $posts[]=$post;
            }
        }
        return $posts;
    }

    public function postPost(array $post)
    {
        $req = $this->db->prepare('INSERT INTO posts(authorid, title, last_mod, chapo, content) VALUES(:authorid, :title, :last_mod, :chapo, :content)');
        $req->execute(
            array(
            'authorid' => $_SESSION['user']->getUserid(),
            'title' => $post['title'],
            'last_mod' => date("Y-m-d H:i:s"),
            'chapo' => $post['chapo'],
            'content' => $post['content']
            )
        );
    }

    public function deletePost(int $postid) 
    {
        $req = $this->db->prepare('DELETE FROM posts WHERE postid=?');
        $req->execute(array($postid));
    }

    public function modPost(array $post) 
    {
        $req = $this->db->prepare('UPDATE posts SET authorid=?,title=?,chapo=?,content=?,last_mod=? WHERE postid=?');
        $req->execute(
            array(
            $post['authorid'],
            $post['title'],
            $post['chapo'],
            $post['content'],
            date("Y-m-d H:i:s"),
            $post['postid']
            )
        );
    }
}
