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
class UsersManager {
    public function getNickname($id) {
        $db = DBfactory::Getinstance();
        $req = $db->prepare('SELECT nickname FROM users WHERE userid = ?');
        $req->execute(array(strtolower($id)));
        $result = $req->fetch(PDO::FETCH_ASSOC);
        return $result['nickname'];
    }
    public function getInfos($email){
        $db = DBfactory::Getinstance();
        $req = $db->prepare('SELECT nickname,userlvl FROM users WHERE email = ?');
        $req->execute(array($email));
        return $result=$req->fetch(PDO::FETCH_ASSOC);
    }
    public function getCount_Ip($ip){
        $db = DBfactory::Getinstance();
        $recherche = $db->prepare('SELECT ip FROM connexion WHERE ip = ?');
        $recherche->execute(array($ip));
        return $count = $recherche->rowCount();
    }
    
    public function testPwd($email,$pwd){
        $db = DBfactory::Getinstance();
        $req = $db->prepare('SELECT pwd FROM users WHERE email = ?');
        $req->execute(array(strtolower($email)));
        $result = $req->fetch(PDO::FETCH_ASSOC);
        if(password_verify($pwd, $result['pwd'])){
            return true;
        }else{
            return false;
        }
    }
    public function wrong_pass($ip){
        $db = DBfactory::Getinstance();
        $req = $db->prepare('INSERT INTO connexion(ip) VALUES(?)');
        $req->execute(array($ip));
    }
}
