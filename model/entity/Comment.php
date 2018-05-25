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
    public $first_name;
    public $last_name;
    public $last_mod;
    public $comment;
    public $error = 0;

    public function __construct($array) {
        $this->setPostid($array['postid']);
        $this->setComment($array['comment']);
        $this->setFirst_name($array['first_name']);
        $this->setLast_name($array['last_name']);
        $this->setEmail($array['email']);
        $this->setLast_mod();
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

    public function getFirst_name() {
        return $this->first_name;
    }

    public function getLast_name() {
        return $this->last_name;
    }

    public function getLast_mod() {
        return $this->last_mod;
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

    protected function setFirst_name($first_name) {
        $this->first_name = $first_name;
    }

    protected function setLast_name($last_name) {
        $this->last_name = $last_name;
    }

    protected function setLast_mod() {
        $this->last_mod = date("Y-m-d H:i:s");
    }

    protected function setComment($comment) {
        $this->comment = $comment;
    }

    protected function setEmail($email) {
        $this->email = $email;
    }

}
