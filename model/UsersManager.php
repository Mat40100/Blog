<?php

namespace model;

use model\entity\User;

class UsersManager
{

    public function __construct() 
    {
        ;
    }


    public function getUser($userid)
    {
        $db = DBfactory::getInstance();
        $req = $db->prepare('SELECT nickname,userlvl,userid FROM users WHERE userid = ?');
        $req->execute(array($userid));
        $result = $req->fetch(\PDO::FETCH_ASSOC);
        $user = new User($result);
        return $user;
    }
    public function getUsers(){
        $db = DBfactory::getInstance();
        $req = $db->query('SELECT userid, userlvl, nickname FROM users');
        while($data=$req->fetch(\PDO::FETCH_ASSOC)){
            $users[] = new User($data);
        }
        return $users;
    }
    public function getCountIp($ip) 
    {
        $db = DBfactory::getInstance();
        $req = $db->prepare('SELECT ip FROM connexion_failed WHERE ip = ?');
        $req->execute(array($ip));
        $data = $req->rowCount();
        return $data;
    }

    public function connect($email, $pwd)
    {
        if($this->getCountIp($_SERVER['REMOTE_ADDR'])>= 10){
            session_destroy();
            return "false_ip";
        }else {
            $db = DBfactory::getInstance();
            $req = $db->prepare('SELECT pwd,userid FROM users WHERE email = ?');
            $req->execute(array(strtolower($email)));
            $result = $req->fetch(\PDO::FETCH_ASSOC);
            if (password_verify($pwd, $result['pwd'])) {
                $_SESSION['user'] = $this->getUser($result['userid']);
                $_SESSION['user']->setTicket();
                return "ok";
            } else {
                session_destroy();
                $this->wrongPass($_SERVER['REMOTE_ADDR'], $email);
                return "false_mdp";
            }
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
