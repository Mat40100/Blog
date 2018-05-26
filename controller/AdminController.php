<?php

namespace controller;

session_start();

class AdminController extends ControllerMain {

    public function admin() {
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']->getUserlvl() <= 2) {
                $unvalidComments = $this->cman->getUnvalidComments();
                $posts = $this->pman->getPosts();
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
                            'unvalid_comments' => $unvalidComments
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
                print_r($_POST);
                //$this->cman->validComment($_POST);
                //header('location: ?p=admin');
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
                $this->pman->postPost($_POST);
                header('location: ?p=admin');
            } else {
                header('location: ?p=alert&alert=Vous devez être modérateur pour effectuer cette action');
            }
        } else {
            header('location: ?p=alert&alert=Vous devez être connecté pour effectuer cette action');
        }
    }

    public function modPost() {
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']->getUserlvl() == 1) {
                $list = $this->uman->getNameList();
                $post = new \model\entity\Post($_GET['id'], "no_form");
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
                $this->pman->modPost($_POST);
                header('location: ?p=blog&d=post&id=' . $_POST['postid']);
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
                $this->pman->deletePost($_GET['id']);
                header('location: ?p=admin');
            } else {
                header('location: ?p=alert&alert=Vous devez être administrateur pour effectuer cette action');
            }
        } else {
            header('location: ?p=alert&alert=Vous devez être connecté pour effectuer cette action');
        }
    }
}
