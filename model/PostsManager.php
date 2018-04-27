<?php

class PostsManager {

    protected $Uman;

    public function __construct() {
        $this->Uman = new UsersManager();
    }

    public function GetPost($postid) {
        $db = DBfactory::Getinstance();
        $req = $db->prepare('SELECT * FROM posts WHERE postid = :postid');
        $req->execute(array(
            'postid' => $postid
        ));
        $data = $req->fetch(PDO::FETCH_ASSOC);
        $data['authorname'] = $this->Uman->getNickname($data['authorid']);
        return $data;
    }

    public function GetPosts() {
        $db = DBfactory::Getinstance();
        $req = $db->query('SELECT * from posts');
        while ($data = $req->fetch(PDO::FETCH_ASSOC)) {
            $posts[] = new Post(
                    $data['postid'], "form"
            );
        }
        return $posts;
    }

    public function PostPost(array $post) {
        $db = DBfactory::Getinstance();
        $req = $db->prepare('INSERT INTO posts(authorid, title, last_mod, chapo, content) VALUES(:authorid, :title, :last_mod, :chapo, :content)');
        $req->execute(array(
            'authorid' => $_SESSION['user']->getUserid(),
            'title' => $post['title'],
            'last_mod' => date("Y-m-d H:i:s"),
            'chapo' => $post['chapo'],
            'content' => $post['content']
        ));
    }

    public function DeletePost($postid) {
        $db = DBfactory::Getinstance();
        $req = $db->prepare('DELETE FROM posts WHERE postid=?');
        $req->execute(array($postid));
    }

    public function ModPost($post) {
        $db = DBfactory::Getinstance();
        $req = $db->prepare('UPDATE posts SET title=?,chapo=?,content=?,last_mod=? WHERE postid=?');
        $req->execute(array(
            $post['title'],
            $post['chapo'],
            $post['content'],
            date("Y-m-d H:i:s"),
            $post['postid']
        ));
    }

}
