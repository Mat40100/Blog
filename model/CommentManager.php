<?php

namespace model;

class CommentManager {

    protected $Pman;
    protected $db;

    public function __construct() {
        $this->Pman = new \model\PostsManager;
        $this->db = DBfactory::getInstance();
    }

    public function addComment(\model\entity\Comment $comment) {
        if ($comment->getError() === 0) {
            $req = $this->db->prepare('INSERT INTO comments(postid, last_name, first_name, email, last_mod, comment) VALUES(:postid, :last_name, :first_name, :email, :last_mod, :comment)');
            $req->execute(
                    array(
                        'comment' => $comment->getComment(),
                        'last_mod' => $comment->getLast_mod(),
                        'email' => $comment->getEmail(),
                        'first_name' => $comment->getFirst_name(),
                        'last_name' => $comment->getLast_name(),
                        'postid' => $comment->getPostid()
                    )
            );
            return true;
        } else {
            return false;
        }
    }

    public function getComments($postid) {
        $req = $this->db->prepare('SELECT * FROM comments WHERE postid=:postid AND valid=1');
        $req->execute(
                array(
                    'postid' => $postid
                )
        );
        while ($donnees = $req->fetch(\PDO::FETCH_ASSOC)) {
            $result[] = $donnees;
        }
        return $result;
    }

    public function getUnvalidComments() {
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

    public function validComment(array $comments) {
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
