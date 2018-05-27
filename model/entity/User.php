<?php

namespace model\entity;

use model\Formatcontent;

class User
{
    protected $Uman;
    protected $ticket;
    public $userlvl;
    public $nickname;
    public $userid;

    public function __construct() 
    {
        $this->Uman= new \model\UsersManager();
        $this->setUserlvl("4");
    }
    public function getNickname() 
    {
        return $this->nickname;
    }
    public function getUserlvl() 
    {
        return $this->userlvl;
    }
    public function getUserid() 
    {
        return $this->userid;
    }
    private function setUserid($userid)
    {
        $this->userid = $userid;
    }
    private function setUserlvl($userlvl) 
    {
        $this->userlvl = $userlvl;
    }
    private function setNickname($nickname) 
    { 
        $this->nickname = $nickname;
    }
    private function setTicket() 
    {
        $this->ticket = hash('sha512', session_id().microtime().rand(0, 9999999999));
        setcookie('ticket', $this->ticket, time()+(60*20));
    }
    protected function testIp() 
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $count = $this->Uman->getCountIp($ip);
        if ($count>10) {
            return false;
        } else {
            return true;
        }
    }
    public function verifTicket()
    {
        if (isset($_COOKIE['ticket']) && ($this->ticket == $_COOKIE['ticket'])) {
            $this->setTicket();
            return 'ok';
        } elseif (!isset($_COOKIE['ticket'])) {
            return 'timed_out';
        } else {
            setcookie('ticket', '', time() - 3600, '/');
            return 'wrong_ticket';
        }
    }
    public function connect($email,$pwd)
    {
        if ($this->testIp()) {
            if ($this->Uman->testPwd(Formatcontent::formatBdd($email), Formatcontent::formatBdd($pwd)) {
                $infos = $this->Uman->getInfos($email);
                $this->setNickname($infos['nickname']);
                $this->setUserlvl($infos['userlvl']);
                $this->setUserid($infos['userid']);
                $this->setTicket();
                return 'ok';                
            } else {
                session_destroy();
                $this->Uman->wrongPass($_SERVER['REMOTE_ADDR'], $email);
                return 'false_mdp';
            }
        } else {
            session_destroy();
            return 'false_ip';
        }
    }
    public function disconnect()
    {
        session_destroy();
        header('location : ?p=home');
    }
    public function __destruct() 
    {
    }
}
