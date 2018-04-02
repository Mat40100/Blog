<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of comment_manager
 *
 * @author Programmation
 */
class comment_manager {
    private function __construct() {
        ;
    }
    
    static public function add_comment(array $comment){
        if(empty($_POST['comment']) or empty($_POST['email']) or empty($_POST['first_name']) or empty($_POST['last_name']) or empty($_GET['post'])){
            return false;
        }
        $db = DBfactory::Getinstance();
        $req = $db->prepare('INSERT INTO comments(postid, last_name, first_name, email, last_mod, comment) VALUES(:postid, :last_name, :first_name, :email, :last_mod, :comment)');
        $req->execute(array(
            'comment' => $_POST['comment'],
            'last_mod' => date("Y-m-d H:i:s"),
            'email' => $_POST['email'],
            'first_name' => $_POST['first_name'],
            'last_name' => $_POST['last_name'],
            'postid' => $_POST['postid']
            ));
    }
    
    static public function getComments($postid){
        $db = DBfactory::Getinstance();
        $req = $db->prepare('SELECT * FROM comments WHERE postid=:postid AND valid=1');
        $req->execute(array(
            'postid' => $postid
        ));
        while($donnees=$req->fetch(PDO::FETCH_ASSOC)){
            $result[] = $donnees; 
        }
        return $result;
    }
}
