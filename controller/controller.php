<?php
    require(__DIR__.'/class/PostsManager.php');
    
    $var = new PostsManager();
    $posts = $var->GetPosts();
    print_r($posts);
    echo $twig->render('home.twig', ['post'=>$posts]);

