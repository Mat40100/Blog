<?php

namespace model;


class DBfactory
{

    protected static $instance;
    protected static $twig;

    private function __construct() 
    {
        
    }

    public static function twig() 
    {
        $loader = new \Twig\Loader\FilesystemLoader('./view');
        self::$twig = new \Twig\Environment(
            $loader, array(
            'cache' => false, //__DIR__.'/tmp'
            'debug' => true
            )
        );
        self::$twig->addExtension(new \Twig\Extension\DebugExtension());
        return self::$twig;
    }

    public static function getInstance() 
    {
        if (is_null(self::$instance)) {
                $db = include 'dbconfig.php';
                self::$instance = new \PDO('mysql:host=' . $db['host'] . ';dbname=' . $db['dbname'] . '', $db['username'], $db['password']);
        }

        return self::$instance;
    }

}
