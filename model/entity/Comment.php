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

    public $postid;
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
        if($this->getError()=== 0){
            $this->setPostid($array['postid']);
            $this->setComment($array['comment']);
            $this->setFirstName($array['first_name']);
            $this->setLastName($array['last_name']);
            $this->setEmail($array['email']);
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

    public function getPostid() {
        return $this->postid;
    }

    /**
     * @return mixed
     */
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

    protected function setPostid($postid) {
        intval($postid);
        if ($postid > 0) {
            $this->postid = $postid;
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
