<?php

namespace model;

use model\entity\Comment;

class CommentManager {

    protected $pman;
    protected $db;

    public function __construct() {
        $this->pman = new \model\PostsManager;
        $this->db = DBfactory::getInstance();
    }

    public function addComment(Comment $comment) {
            $req = $this->db->prepare('INSERT INTO comments(postid, last_name, first_name, email, last_mod, comment) VALUES(:postid, :last_name, :first_name, :email, :last_mod, :comment)');
            $req->execute(
                    array(
                        'comment' => $comment->getComment(),
                        'last_mod' => $comment->getLastMod(),
                        'email' => $comment->getEmail(),
                        'first_name' => $comment->getFirstName(),
                        'last_name' => $comment->getLastName(),
                        'postid' => $comment->getPostid()
                    )
            );
            return true;
    }

    public function getComments($postid) {
        $req = $this->db->prepare('SELECT * FROM comments WHERE postid=:postid AND valid=1');
        $req->execute(
                array(
                    'postid' => $postid
                )
        );
        while ($data = $req->fetch(\PDO::FETCH_ASSOC)) {
            $comment = new Comment($data);
            if($comment->getError()===0){
                $comments[]=$comment;
            }
        }
        return $comments;
    }

    public function getUnvalidComments() {
        $req = $this->db->prepare('SELECT * FROM comments WHERE valid=0');
        $req->execute();
        
        while ($data = $req->fetch(\PDO::FETCH_ASSOC)) {
            $comment = new Comment($data);
            $post = $this->pman->getPost($comment->getPostid());
            if($comment->getError()=== 0){
                $comment->setTitle($post->getTitle());
                $comments[] = $comment;
            }
        }
        return $comments;
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
