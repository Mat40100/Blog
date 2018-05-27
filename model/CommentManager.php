<?php

namespace model;

use model\entity\Comment;

class CommentManager {

    protected $postsManager;
    protected $db;

    public function __construct() {
        $this->postsManager = new PostsManager;
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
                        'postid' => $comment->getPostId()
                    )
            );
            return true;
    }

    public function getComments($postId) {
        $req = $this->db->prepare('SELECT * FROM comments WHERE postid=:postId AND valid=1');
        $req->execute(
                array(
                    'postId' => $postId
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
            $post = $this->postsManager->getPost($comment->getPostId(),"form");
            if($comment->getError()=== 0){
                $comment->setTitle($post->getTitle());
                $comments[] = $comment;
            }
        }
        return $comments;
    }

    public function commentValidation(array $array)
    {
        foreach ($array as $id => $value) {
            $comment = $this->getComment($id);
            if ($comment->getError() === 0) {
                if ($value == "Valider") {
                    $this->validComment($comment);
                } else {
                    $this->deleteComment($comment);
                }
            }
        }
    }

    public function getComment($commentId){
        $req = $this->db->prepare('SELECT * FROM comments WHERE comment_id=:commentId');
        $req->execute(
            array(
                'commentId' => $commentId
            )
        );
        $data = $req->fetch(\PDO::FETCH_ASSOC);
        $comment = new Comment($data);
        return $comment;
    }

    private function validComment(Comment $comment)
    {
        $req = $this->db->prepare('UPDATE comments SET valid=1 WHERE comment_id= ?');
        $req->execute(array($comment->getCommentId()));
    }

    private function deleteComment(Comment $comment){
        $req = $this->db->prepare('DELETE FROM comments WHERE comment_id= ?');
        $req->execute(array($comment->getCommentId()));
    }

}
