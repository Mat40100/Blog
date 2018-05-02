<?php

namespace model;

class UsersManager
{

    public function __construct() 
    {
        ;
    }

    public function getNameList() 
    {
        $db = DBfactory::getInstance();
        $req = $db->query('SELECT userid, nickname FROM users');
        while ($donnees = $req->fetch(\PDO::FETCH_ASSOC)) {
            $result[] = $donnees;
        }
        return $result;
    }

    public function getNickname($id) 
    {
        $db = DBfactory::getInstance();
        $req = $db->prepare('SELECT nickname FROM users WHERE userid = ?');
        $req->execute(array(strtolower($id)));
        $result = $req->fetch(\PDO::FETCH_ASSOC);
        return $result['nickname'];
    }

    public function getInfos($email) 
    {
        $db = DBfactory::getInstance();
        $req = $db->prepare('SELECT nickname,userlvl,userid FROM users WHERE email = ?');
        $req->execute(array($email));
        return $result = $req->fetch(\PDO::FETCH_ASSOC);
    }

    public function getCountIp($ip) 
    {
        $db = DBfactory::getInstance();
        $req = $db->prepare('SELECT ip FROM connexion_failed WHERE ip = ?');
        $req->execute(array($ip));
        $data = $req->rowCount();
        return $data;
    }

    public function testPwd($email, $pwd) 
    {
        $db = DBfactory::getInstance();
        $req = $db->prepare('SELECT pwd FROM users WHERE email = ?');
        $req->execute(array(strtolower($email)));
        $result = $req->fetch(\PDO::FETCH_ASSOC);
        if (password_verify($pwd, $result['pwd'])) {
            return true;
        } else {
            return false;
        }
    }

    public function wrongPass($ip, $email) 
    {
        $db = DBfactory::getInstance();
        $req = $db->prepare('INSERT INTO connexion_failed(ip,email,time) VALUES(:ip, :email, :time)');
        $req->execute(
            array(
            'ip' => $ip,
            'email' => $email,
            'time' => date("Y-m-d H:i:s")
            )
        );
    }

}
