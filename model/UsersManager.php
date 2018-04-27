<?php

namespace model;

class UsersManager {

    protected $db;

    public function __construct() {
        $this->db = DBfactory::Getinstance();
    }

    public function getNickname($id) {
        $req = $this->db->prepare('SELECT nickname FROM users WHERE userid = ?');
        $req->execute(array(strtolower($id)));
        $result = $req->fetch(\PDO::FETCH_ASSOC);
        return $result['nickname'];
    }

    public function getInfos($email) {
        $req = $this->db->prepare('SELECT nickname,userlvl,userid FROM users WHERE email = ?');
        $req->execute(array($email));
        return $result = $req->fetch(\PDO::FETCH_ASSOC);
    }

    public function getCount_Ip($ip) {
        $req = $this->db->prepare('SELECT ip FROM connexion_failed WHERE ip = ?');
        $req->execute(array($ip));
        $data = $req->rowCount();
        return $data;
    }

    public function testPwd($email, $pwd) {
        $req = $this->db->prepare('SELECT pwd FROM users WHERE email = ?');
        $req->execute(array(strtolower($email)));
        $result = $req->fetch(\PDO::FETCH_ASSOC);
        if (password_verify($pwd, $result['pwd'])) {
            return true;
        } else {
            return false;
        }
    }

    public function wrong_pass($ip, $email) {
        $req = $this->db->prepare('INSERT INTO connexion_failed(ip,email,time) VALUES(:ip, :email, :time)');
        $req->execute(array(
            'ip' => $ip,
            'email' => $email,
            'time' => date("Y-m-d H:i:s")
        ));
    }

}
