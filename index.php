<?php
require_once __DIR__ . '/vendor/autoload.php';

    $loader = new Twig_Loader_Filesystem(__DIR__.'/controller/view');
    $twig = new Twig_Environment($loader, array(
        'cache' => False//__DIR__.'/tmp'
    ));    
require('./controller/controller.php');




