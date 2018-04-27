<?php

class Autoloader {

    /**
     * Enregistre notre autoloader
     */
    static function register($target) {
        if($target='model'){
           spl_autoload_register(array(__CLASS__, 'autoload_model')); 
        }elseif($target='entity'){
            spl_autoload_register(array(__CLASS__, 'autoload_entity'));
        }
        
    }

    /**
     * Inclue le fichier correspondant à notre classe
     * @param $class string Le nom de la classe à charger
     */
    static function autoload_model($class) {
        require 'model/' . $class . '.php';
    }

    static function autoload_entity($class) {
        require 'model/entity/' . $class . '.php';
    }

}
