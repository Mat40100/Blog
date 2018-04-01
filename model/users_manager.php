<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of users
 *
 * @author Programmation
 */
class users_manager {
    
    protected $nickname;
    
    public static function giveNickname($id) {
        $db = DBfactory::Getinstance();
        $req = $db->prepare('SELECT nickname FROM users WHERE userid = :id');
        $req->execute(array(
            'id' => $id
                ));
        $result = $req->fetch(PDO::FETCH_ASSOC);
        return $result['nickname'];
    }
}
