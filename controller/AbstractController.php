<?php

namespace controller;

session_start();

/**
 * Class AbstractController
 * @package controller
 */
class AbstractController
{

    protected $usersManager;
    protected $postsManager;
    protected $commentManager;
    protected $gen;

    /** @var \Twig\Environment */
    protected $twig;
    protected $db;

    /**
     * AbstractController constructor.
     */
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
