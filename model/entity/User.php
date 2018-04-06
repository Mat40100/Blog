<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User
 *
 * @author Programmation
 */
class User {
    protected $Uman;
    protected $ticket;
    protected $userlvl;
    public $nickname;

    public function __construct() {
        $this->Uman= new UsersManager();
    }
    public function getNickname() {
        return $this->nickname;
    }
    public function getUserlvl() {
        return $this->userlvl;
    }
    private function setUserlvl($userlvl) {
        $this->userlvl = $userlvl;
    }
    private function setNickname($nickname) { 
        $this->nickname = $nickname;
    }
    private function setTicket() {
        $this->ticket = hash('sha512', session_id().microtime().rand(0,9999999999));
        setcookie('ticket', $this->ticket,time()+(60*20));
    }
    private function test_ip() {
        $ip = $_SERVER['REMOTE_ADDR'];
        $count = $this->Uman->getCount_Ip($ip);
        if($count>10){
            return false;
        }else{
            return true;
        }
    }
    public function verif_ticket(){
        if(isset($_COOKIE['ticket']) and ($this->ticket == $_COOKIE['ticket'])){
            $this->setTicket();
        }else{
            $_SESSION=array();
            session_destroy();
            header('location: ?p=home');
        }
    }
    public function connect($email,$pwd){
        if($this->test_ip()){
            if($this->Uman->testPwd($email,$pwd)){
                $infos = UsersManager::getInfos($email);
                $this->setNickname($infos['nickname']);
                $this->setUserlvl($infos['userlvl']);
                $this->setTicket();
                return true;
            }else{
                $this->Uman->wrong_pass($_SERVER['REMOTE_ADDR']);
                return false;
            }
        }else{
            session_destroy();
            echo "<script>alert('Trop de tentatives, réessayez demain.')</script>";
            return false;
        }
    }
    public function disconnect(){
        session_destroy();
        header('location : ?p=home');
    }
}
