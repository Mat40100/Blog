<?php

namespace model;

class PostsManager {

    protected $Uman;
    protected $db;

    public function __construct() {
        $this->Uman = new UsersManager();
        $this->db = DBfactory::Getinstance();
    }

    public function GetPost($postid) {
        $req = $this->db->prepare('SELECT * FROM posts WHERE postid = :postid');
        $req->execute(array(
            'postid' => $postid
        ));
        $data = $req->fetch(\PDO::FETCH_ASSOC);
        $data['authorname'] = $this->Uman->getNickname($data['authorid']);
        return $data;
    }

    public function GetPosts() {
        $req = $this->db->query('SELECT * from posts');
        while ($data = $req->fetch(\PDO::FETCH_ASSOC)) {
            $posts[] = new entity\Post(
                    $data['postid'], "form"
            );
        }
        return $posts;
    }

    public function PostPost(array $post) {
        $req = $this->db->prepare('INSERT INTO posts(authorid, title, last_mod, chapo, content) VALUES(:authorid, :title, :last_mod, :chapo, :content)');
        $req->execute(array(
            'authorid' => $_SESSION['user']->getUserid(),
            'title' => $post['title'],
            'last_mod' => date("Y-m-d H:i:s"),
            'chapo' => $post['chapo'],
            'content' => $post['content']
        ));
    }

    public function DeletePost($postid) {
        $req = $this->db->prepare('DELETE FROM posts WHERE postid=?');
        $req->execute(array($postid));
    }

    public function ModPost($post) {
        $req = $this->db->prepare('UPDATE posts SET authorid=?,title=?,chapo=?,content=?,last_mod=? WHERE postid=?');
        $req->execute(array(
            $post['authorid'],
            $post['title'],
            $post['chapo'],
            $post['content'],
            date("Y-m-d H:i:s"),
            $post['postid']
        ));
    }

}
