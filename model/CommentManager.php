<?php

namespace model;

class CommentManager {

    protected $Pman;
    protected $db;

    public function __construct() {
        $this->Pman = new \model\PostsManager;
        $this->db = DBfactory::Getinstance();
    }

    public function add_comment(array $comment) {
        if (empty($_POST['comment']) or empty($_POST['email']) or empty($_POST['first_name']) or empty($_POST['last_name']) or empty($_POST['postid'])) {
            return false;
        }
        $req = $this->db->prepare('INSERT INTO comments(postid, last_name, first_name, email, last_mod, comment) VALUES(:postid, :last_name, :first_name, :email, :last_mod, :comment)');
        $req->execute(array(
            'comment' => $_POST['comment'],
            'last_mod' => date("Y-m-d H:i:s"),
            'email' => $_POST['email'],
            'first_name' => $_POST['first_name'],
            'last_name' => $_POST['last_name'],
            'postid' => $_POST['postid']
        ));
        return true;
    }

    public function getComments($postid) {
        $req = $this->db->prepare('SELECT * FROM comments WHERE postid=:postid AND valid=1');
        $req->execute(array(
            'postid' => $postid
        ));
        while ($donnees = $req->fetch(\PDO::FETCH_ASSOC)) {
            $result[] = $donnees;
        }
        return $result;
    }

    public function getUnvalid_Comments() {
        $req = $this->db->prepare('SELECT * FROM comments WHERE valid=0');
        $req->execute();
        $i = 0;
        while ($data = $req->fetch(\PDO::FETCH_ASSOC)) {
            $post = $this->Pman->GetPost($data['postid']);
            $result[] = $data;
            $result[$i][title] = $post['title'];
            $i++;
        }
        return $result;
    }

    public function valid_comment(array $comments) {
        foreach ($comments as $key => $value) {
            if ($value == "Valider") {
                $req = $this->db->prepare('UPDATE comments SET valid=1 WHERE comment_id= ?');
                $req->execute(array($key));
            } elseif ($value == "Supprimer") {
                $req = $this->db->prepare('DELETE FROM comments WHERE comment_id= ?');
                $req->execute(array($key));
            }
        }
    }

}
