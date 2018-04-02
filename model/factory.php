<?php

class DBfactory{
    private static $instance;
    private static $twig;
    
    private function __construct(){}
    
    static function twig(){
        require_once './vendor/autoload.php';
        $loader = new Twig_Loader_Filesystem('./view');
            self::$twig = new Twig_Environment($loader, array(
                'cache' => False,//__DIR__.'/tmp'
                'debug' => true
                ));
            self::$twig->addExtension(new Twig_Extension_Debug());
        return self::$twig;
    }
    
    static function Getinstance(){
        if(is_null(self::$instance)){
            try {
                $db = require('dbconfig.php');
                self::$instance = new PDO('mysql:host='.$db['host'].';dbname='.$db['dbname'].'',$db['username'],$db['password']);
                
            } catch(Exception $exc) {
                self::$instance = $exc->getMessage();
            }
        }
        
        return self::$instance;
    }

}