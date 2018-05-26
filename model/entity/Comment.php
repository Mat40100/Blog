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
    public $email;
    public $firstName;
    public $lastName;
    public $lastMod;
    public $comment;
    public $error = 0;

    public function __construct($array) {
        foreach($array as $key => $value){
            if(isset($value)){

            }else{
                $this->setError(1);
            }
        }
        if($this->getError()=== 0){
            $this->setPostid($array['postid']);
            $this->setComment($array['comment']);
            $this->setFirstName($array['first_name']);
            $this->setLastName($array['last_name']);
            $this->setEmail($array['email']);
            $this->setLastMod();
        }
    }

    public function getError() {
        return $this->error;
    }

    public function getPostid() {
        return $this->postid;
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

    protected function setError($error) {
        $this->error = $error;
    }

    protected function setPostid($postid) {
        intval($postid);
        if ($postid > 0) {
            $this->postid = $postid;
        } else {
            $this->error = 1;
        }
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
