<?php

namespace controller;

use model\entity\Post;

session_start();

class AdminController extends ControllerMain {

    public function admin() {
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']->getUserlvl() <= 2) {
                $unvalidComments = $this->commentManager->getUnvalidComments();
                $posts = $this->postsManager->getPosts();
                try {
                    echo $this->twig->render(
                        'navbar_admin.twig', [
                            'hide_admin' => true
                        ]
                    );
                } catch (\Twig_Error_Loader $e) {
                } catch (\Twig_Error_Runtime $e) {
                } catch (\Twig_Error_Syntax $e) {
                }
                try {
                    echo $this->twig->render(
                        'content_admin.twig', [
                            'liste' => $posts,
                            'unvalidcomments' => $unvalidComments
                        ]
                    );
                } catch (\Twig_Error_Loader $e) {
                } catch (\Twig_Error_Runtime $e) {
                } catch (\Twig_Error_Syntax $e) {
                }
            } else {
                header('location: ?p=alert&alert=Vous devez être modérateur pour effectuer cette action');
            }
        } else {
            header('location: ?p=alert&alert=Vous devez être connecté pour effectuer cette action');
        }
    }

    public function validComments() {
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']->getUserlvl() <= 2) {
                $this->commentManager->commentValidation($_POST);
                header('location: ?p=admin');
            } else {
                header('location: ?p=alert&alert=Vous devez être modérateur pour effectuer cette action');
            }
        } else {
            header('location: ?p=alert&alert=Vous devez être connecté pour effectuer cette action');
        }
    }

    public function addPost() {
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']->getUserlvl() == 1) {
                $_POST['authorid']=$_SESSION['user']->getUserid();
                $post = new Post($_POST,"no_form");
                if($post->getError()===0){
                    $this->postsManager->postPost($post);
                    header('location: ?p=admin');
                }else{
                    header('location: ?p=alert&alert=Le formulaire n\'a pas été rempli correctement');
                }
            } else {
                header('location: ?p=alert&alert=Vous devez être administrateur pour effectuer cette action');
            }
        } else {
            header('location: ?p=alert&alert=Vous devez être connecté pour effectuer cette action');
        }
    }

    public function modPost() {
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']->getUserlvl() == 1) {
                $list = $this->usersManager->getUsers();
                $post = $this->postsManager->getPost($_GET['id'],"no_form");
                try {
                    echo $this->twig->render(
                        'modif_post.twig', [
                            'post' => $post,
                            'list' => $list
                        ]
                    );
                } catch (\Twig_Error_Loader $e) {
                } catch (\Twig_Error_Runtime $e) {
                } catch (\Twig_Error_Syntax $e) {
                }
                try {
                    echo $this->twig->render(
                        'navbar_admin.twig', [
                            'hide_mod' => true
                        ]
                    );
                } catch (\Twig_Error_Loader $e) {
                } catch (\Twig_Error_Runtime $e) {
                } catch (\Twig_Error_Syntax $e) {
                }
            } else {
                header('location: ?p=alert&alert=Vous devez être administrateur pour effectuer cette action');
            }
        } else {
            header('location: ?p=alert&alert=Vous devez être connecté pour effectuer cette action');
        }
    }

    public function validMod() {
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']->getUserlvl() == 1) {
                $post = new Post($_POST,"no_form");
               if($post->getError()===0){
                   $this->postsManager->modPost($post);
                   header('location: ?p=blog&d=post&id=' . $_POST['postid']);
               }else{
                   header('location: ?p=alert&alert=Vous n\'avez pas rempli le formulaire de modification correctement');
               }
            } else {
                header('location: ?p=alert&alert=Vous devez être administrateur pour effectuer cette action');
            }
        } else {
            header('location: ?p=alert&alert=Vous devez être connecté pour effectuer cette action');
        }
    }

    public function delPost() {
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']->getUserlvl() == 1) {
                $post = $this->postsManager->getPost($_GET['id'],"no_form");
                if($post->getError()===0){
                    $this->postsManager->deletePost($post);
                    header('location: ?p=admin');
                }else{
                    header('location: ?p=alert&alert=Ce post n\'existe pas');
                }

            } else {
                header('location: ?p=alert&alert=Vous devez être administrateur pour effectuer cette action');
            }
        } else {
            header('location: ?p=alert&alert=Vous devez être connecté pour effectuer cette action');
        }
    }
}
