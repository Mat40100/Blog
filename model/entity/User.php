<?php

namespace model\entity;

use model\Formatcontent;

class User
{
    private $ticket;
    private $userLvl;
    private $nickname;
    private $userId;

    public function __construct($user)
    {
        $this->setNickname($user['nickname']);
        $this->setUserLvl($user['userlvl']);
        $this->setUserId($user['userid']);
    }
    public function getNickname() 
    {
        return $this->nickname;
    }
    public function getUserLvl()
    {
        return $this->userLvl;
    }
    public function getUserId()
    {
        return $this->userId;
    }
    private function setUserId($userId)
    {
        $this->userId = $userId;
    }
    private function setUserLvl($userlvl)
    {
        $this->userLvl = $userlvl;
    }
    private function setNickname($nickname) 
    { 
        $this->nickname = $nickname;
    }
    public function setTicket()
    {
        $this->ticket = hash('sha512', session_id().microtime().rand(0, 9999999999));
        setcookie('ticket', $this->ticket, time()+(60*20));
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

    public function disconnect()
    {
        session_destroy();
        header('location : ?p=home');
    }
    public function __destruct() 
    {
    }
}
