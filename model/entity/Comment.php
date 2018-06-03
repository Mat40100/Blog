<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace model\entity;

/**
 * Description of Comment
 *
 * @author Mathieu
 */
class Comment {

    private $postId;
    private $commentId;
    private $title;
    private $email;
    private $firstName;
    private $lastName;
    private $lastMod;
    private $comment;
    private $error;

    /**
     * Comment constructor.
     * @param $array
     */
    public function __construct($array) {
        $this->error = 0;
        foreach($array as $key => $value){
            if(!isset($value)){
                $this->setError();
            }
        }
        if(!array_key_exists("comment",$array) || !array_key_exists("first_name",$array) || !array_key_exists("last_name",$array) || !array_key_exists("email",$array) ){
            $this->setError();
        }
        if($this->getError()=== 0){
            $this->setPostId($array['postid']);
            $this->setComment(\model\Formatcontent::formatBdd($array['comment']));
            $this->setFirstName(\model\Formatcontent::formatBdd($array['first_name']));
            $this->setLastName(\model\Formatcontent::formatBdd($array['last_name']));
            $this->setEmail(\model\Formatcontent::formatBdd($array['email']));
            $this->setLastMod();
            if(isset($array['comment_id'])){
                $this->setCommentId($array['comment_id']);
            }
        }
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getError() {
        return $this->error;
    }

    public function getPostId() {
        return $this->postId;
    }

    public function getCommentId()
    {
        return $this->commentId;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function getLastMod() {
        return $this->lastMod;
    }

    public function getComment() {
        return $this->comment;
    }

    public function setError() {
        $this->error = ++$this->error;
    }

    public function setPostId($postId) {
        intval($postId);
        if ($postId > 0) {
            $this->postId = $postId;
        } else {
            $this->setError();
        }
    }

    public function setCommentId($commentId)
    {
        intval($commentId);
        if($commentId>0) {
            $this->commentId = $commentId;
        }else{
            $this->setError();
        }
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    public function setLastMod() {
        $this->lastMod = date("Y-m-d H:i:s");
    }

    public function setComment($comment) {
        $this->comment = $comment;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

}
