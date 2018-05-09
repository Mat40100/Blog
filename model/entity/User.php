<?php

namespace model\entity;

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
        $this->_setUserlvl("4");
    }
    public function getNickname() 
    {
        return $this->nickname;
    }
    public function getUserlvl() 
    {
        return $this->userlvl;
    }
    function getUserid() 
    {
        return $this->userid;
    }
    private function _setUserid($userid)
    {
        $this->userid = $userid;
    }
    private function _setUserlvl($userlvl) 
    {
        $this->userlvl = $userlvl;
    }
    private function _setNickname($nickname) 
    { 
        $this->nickname = $nickname;
    }
    private function _setTicket() 
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
            $this->_setTicket();
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
            if ($this->Uman->testPwd($email, $pwd)) {
                $infos = $this->Uman->getInfos($email);
                $this->_setNickname($infos['nickname']);
                $this->_setUserlvl($infos['userlvl']);
                $this->_setUserid($infos['userid']);
                $this->_setTicket();
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
    function __destruct() 
    {
    }
}
