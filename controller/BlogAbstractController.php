<?php

namespace controller;

use model\entity\Comment;

session_start();

/**
 * Class BlogAbstractController
 * @package controller
 */
class BlogAbstractController extends AbstractController {

    /**
     *
     */
    public function postList() {
        $posts = $this->postsManager->getPosts();

        echo $this->twig->render(
            'content_list.twig', [
                'posts' => $posts,
            ]
        );

        echo $this->twig->render('navbar_blog.twig');

    }

    /**
     *
     */
    public function post() {
        $post = $this->postsManager->getPost($_GET['id'],"form");
        if($post->getError() === 0){

            echo $this->twig->render(
                'content_post.twig', [
                    'post' => $post,
                    'comments' => $comments]
            );

            echo $this->twig->render('navbar_blog.twig');

        }else{
            header('location: ?p=alert&alert=Ce post n\'éxiste pas');
        }
    }

    /**
     *
     */
    public function addComment() {
        $comment = new Comment($_POST);
        if($comment->getError()===0){
            $this->commentManager->addComment($comment);
            header('location: ?p=blog&d=post&id=' . $_POST['postid']);
        }else{
            header('location: ?p=alert&alert=Le formulaire de commentaire n\'a pas été rempli correctement');
        }

    }
}
