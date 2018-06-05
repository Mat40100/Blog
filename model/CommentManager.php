<?php

namespace model;

use model\entity\Comment;

/**
 * Class CommentManager
 * @package model
 */
class CommentManager {

    /**
     * @var PostsManager
     */
    protected $postsManager;
    /**
     * @var \PDO
     */
    protected $db;

    /**
     * CommentManager constructor.
     */
    public function __construct() {
        $this->postsManager = new PostsManager;
        $this->db = DBfactory::getInstance();
    }

    /**
     * @param Comment $comment
     * @return bool
     */
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

    /**
     * @param $postId
     * @return array
     */
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

    /**
     * @return array
     */
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

    /**
     * @param array $array
     */
    public function commentValidation(array $array)
    {
        foreach ($array as $id => $value) {
            $comment = $this->getComment($id);

            if ($comment->getError() != 0) {
                header('location: ?p=alert&alert=Erreur systeme');
            }

            if ($value == "Valider") {
                $this->validComment($comment);
            }

            if ($value == "Supprimer") {
                $this->deleteComment($comment);
            }
        }
    }

    /**
     * @param $commentId
     * @return Comment
     */
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

    /**
     * @param Comment $comment
     */
    public function validComment(Comment $comment)
    {
        $req = $this->db->prepare('UPDATE comments SET valid=1 WHERE comment_id= ?');
        $req->execute(array($comment->getCommentId()));
    }

    /**
     * @param Comment $comment
     */
    public function deleteComment(Comment $comment){
        $req = $this->db->prepare('DELETE FROM comments WHERE comment_id= ?');
        $req->execute(array($comment->getCommentId()));
    }

}
