<?php

namespace controller;

use model\entity\Comment;

session_start();

class BlogController extends ControllerMain {

    public function postList() {
        $posts = $this->pman->getPosts();
        try {
            echo $this->twig->render(
                'content_list.twig', [
                    'posts' => $posts,
                ]
            );
        } catch (\Twig_Error_Loader $e) {
        } catch (\Twig_Error_Runtime $e) {
        } catch (\Twig_Error_Syntax $e) {
        }
        try {
            echo $this->twig->render('navbar_blog.twig');
        } catch (\Twig_Error_Loader $e) {
        } catch (\Twig_Error_Runtime $e) {
        } catch (\Twig_Error_Syntax $e) {
        }
    }

    public function post() {
        $post = $this->pman->getPost($_GET['id']);
        if($post->getError() === 0){
            $comments = $this->cman->getComments($_GET['id']);
            try {
                echo $this->twig->render(
                    'content_post.twig', [
                        'post' => $post,
                        'comments' => $comments]
                );
            } catch (\Twig_Error_Loader $e) {
            } catch (\Twig_Error_Runtime $e) {
            } catch (\Twig_Error_Syntax $e) {
            }
            try {
                echo $this->twig->render('navbar_blog.twig');
            } catch (\Twig_Error_Loader $e) {
            } catch (\Twig_Error_Runtime $e) {
            } catch (\Twig_Error_Syntax $e) {
            }
        }else{
            header('location: ?p=alert&alert=Ce post n\'éxiste pas');
        }
    }

    public function addComment() {
        $comment = new Comment($_POST);
        if($comment->getError()===0){
            $this->cman->addComment($comment);
            header('location: ?p=blog&d=post&id=' . $_POST['postid']);
        }else{
            header('location: ?p=alert&alert=Le formulaire de commentaire n\'a pas été rempli correctement');
        }

    }
}
