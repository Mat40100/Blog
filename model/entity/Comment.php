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

    public $postId;
    public $commentId;
    public $title;
    public $email;
    public $firstName;
    public $lastName;
    public $lastMod;
    public $comment;
    public $error;

    public function __construct($array) {
        $this->error = 0;
        foreach($array as $key => $value){

            if(!isset($value)){
                echo $key;
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

    protected function setError() {
        $this->error = ++$this->error;
    }

    protected function setPostId($postId) {
        intval($postId);
        if ($postId > 0) {
            $this->postId = $postId;
        } else {
            $this->setError();
        }
    }

    private function setCommentId($commentId)
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

    protected function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    protected function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    protected function setLastMod() {
        $this->lastMod = date("Y-m-d H:i:s");
    }

    protected function setComment($comment) {
        $this->comment = $comment;
    }

    protected function setEmail($email) {
        $this->email = $email;
    }

}
