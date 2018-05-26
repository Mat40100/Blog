<?php

namespace controller;

session_start();

class ControllerMain {

    protected $uman;
    protected $pman;
    protected $cman;
    protected $gen;
    protected $twig;
    protected $db;

    public function __construct() {
        $this->twig = \model\DBfactory::twig();
        $this->twig->addGlobal("session", $_SESSION['user']);

        $this->uman = new \model\UsersManager();
        $this->pman = new \model\PostsManager();
        $this->cman = new \model\CommentManager();
        $this->gen = new \model\Generic();
        $this->db = \model\DBfactory::Getinstance();
    }
}
