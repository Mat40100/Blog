<?php

namespace controller;

session_start();

class ControllerMain {

    protected $usersManager;
    protected $postsManager;
    protected $commentManager;
    protected $gen;
    protected $twig;
    protected $db;

    public function __construct() {
        $this->twig = \model\DBfactory::twig();
        $this->twig->addGlobal("session", $_SESSION['user']);

        $this->usersManager = new \model\UsersManager();
        $this->postsManager = new \model\PostsManager();
        $this->commentManager = new \model\CommentManager();
        $this->gen = new \model\Generic();
        $this->db = \model\DBfactory::Getinstance();
    }
}
