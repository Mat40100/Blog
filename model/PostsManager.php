<?php

namespace model;

use model\entity\Post;

class PostsManager
{

    protected $usersManager;
    protected $db;

    public function __construct()
    {
        $this->usersManager = new UsersManager();
        $this->db = DBfactory::getInstance();
    }

    public function getPost($postId, $form)
    {
        $req = $this->db->prepare('SELECT * FROM posts WHERE postid = :postid');
        $req->execute(
            array(
            'postid' => $postId
            )
        );
        $data = $req->fetch(\PDO::FETCH_ASSOC);
        $post = new Post($data,$form);
        return $post;
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

    public function postPost(Post $post)
    {
        $req = $this->db->prepare('INSERT INTO posts(authorid, title, last_mod, chapo, content) VALUES(:authorid, :title, :last_mod, :chapo, :content)');
        $req->execute(
            array(
            'authorid' => $post->getAuthorId(),
            'title' => $post->getTitle(),
            'last_mod' => date("Y-m-d H:i:s"),
            'chapo' => $post->getChapo(),
            'content' => $post->getContent()
            )
        );
    }

    public function deletePost(Post $post)
    {
        $req = $this->db->prepare('DELETE FROM posts WHERE postid=?');
        $req->execute(array($post->getPostId()));
    }

    public function modPost(Post $post)
    {
        $req = $this->db->prepare('UPDATE posts SET authorid=?,title=?,chapo=?,content=?,last_mod=? WHERE postid=?');
        $req->execute(
            array(
            $post->getAuthorId(),
            $post->getTitle(),
            $post->getChapo(),
            $post->getContent(),
            date("Y-m-d H:i:s"),
            $post->getPostId()
            )
        );
    }
}
