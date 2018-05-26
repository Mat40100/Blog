<?php

namespace controller;

session_start();

class BlogController extends ControllerMain {

    public function liste() {
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

    public function post($id) {
        $post = new \model\entity\Post($id, "form");
        $comments = $this->cman->getComments($id);
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
    }

    public function addComment() {
        if ($this->cman->addComment(new \model\entity\Comment($_POST))) {
            header('location: ?p=blog&d=post&id=' . $_POST['postid']);
        } else {
            header('location: ?p=alert&alert=Le formulaire de commentaire n\'a pas été rempli correctement');
        }
    }
}
