<?php

namespace model;

class DBfactory {

    private static $instance;
    private static $twig;

    private function __construct() {
        
    }

    static function twig() {
        $loader = new \Twig\Loader\FilesystemLoader('./view');
        self::$twig = new \Twig\Environment($loader, array(
            'cache' => False, //__DIR__.'/tmp'
            'debug' => true
        ));
        self::$twig->addExtension(new \Twig\Extension\DebugExtension());
        return self::$twig;
    }

    static function Getinstance() {
        if (is_null(self::$instance)) {
            try {
                $db = require('dbconfig.php');
                self::$instance = new \PDO('mysql:host=' . $db['host'] . ';dbname=' . $db['dbname'] . '', $db['username'], $db['password']);
            } catch (Exception $exc) {
                self::$instance = $exc->getMessage();
            }
        }

        return self::$instance;
    }

}
