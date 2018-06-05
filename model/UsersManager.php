<?php

namespace model;

use model\entity\User;

/**
 * Class UsersManager
 * @package model
 */
class UsersManager
{

    /**
     * UsersManager constructor.
     */
    public function __construct()
    {
        ;
    }


    /**
     * @param $userid
     * @return User
     */
    public function getUser($userid)
    {
        $db = DBfactory::getInstance();
        $req = $db->prepare('SELECT nickname,userlvl,userid FROM users WHERE userid = ?');
        $req->execute(array($userid));
        $result = $req->fetch(\PDO::FETCH_ASSOC);
        $user = new User($result);
        return $user;
    }

    /**
     * @return array
     */
    public function getUsers(){
        $db = DBfactory::getInstance();
        $req = $db->query('SELECT userid, userlvl, nickname FROM users');
        while($data=$req->fetch(\PDO::FETCH_ASSOC)){
            $users[] = new User($data);
        }
        return $users;
    }

    /**
     * @param $ip
     * @return int
     */
    public function getCountIp($ip)
    {
        $db = DBfactory::getInstance();
        $req = $db->prepare('SELECT ip FROM connexion_failed WHERE ip = ?');
        $req->execute(array($ip));
        $data = $req->rowCount();
        return $data;
    }

    /**
     * @param $email
     * @param $pwd
     * @return string
     */
    public function connect($email, $pwd)
    {
        $db = DBfactory::getInstance();
        $req = $db->prepare('SELECT pwd,userid FROM users WHERE email = ?');
        $req->execute(array(strtolower($email)));
        $result = $req->fetch(\PDO::FETCH_ASSOC);
        
        if($this->getCountIp($_SERVER['REMOTE_ADDR'])>= 10){
            session_destroy();
            return "false_ip";
        }

        if (!password_verify($pwd, $result['pwd'])) {
            session_destroy();
            $this->wrongPass($_SERVER['REMOTE_ADDR'], $email);
            return "false_mdp";
        }

        $_SESSION['user'] = $this->getUser($result['userid']);
        $_SESSION['user']->setTicket();
        return "ok";
    }

    /**
     * @param $ip
     * @param $email
     */
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
