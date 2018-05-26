<?php

namespace controller;

session_start();

class Controller {

    protected $uman;
    protected $pman;
    protected $cman;
    protected $gen;
    protected $twig;
    protected $db;

    public function __construct() {
        $this->twig = \model\DBfactory::twig();
        $this->twig->addGlobal("session", $_SESSION['user']);

        $this->uman = new \model\UsersManager();
        $this->pman = new \model\PostsManager();
        $this->cman = new \model\CommentManager();
        $this->gen = new \model\Generic();
        $this->db = \model\DBfactory::Getinstance();
    }

    public function generic() {
        $home = $this->gen->getInfos();
        try {
            echo $this->twig->render(
                'content_home.twig', [
                    'infos' => $home
                ]
            );
        } catch (\Twig_Error_Loader $e) {
        } catch (\Twig_Error_Runtime $e) {
        } catch (\Twig_Error_Syntax $e) {
        }
        try {
            echo $this->twig->render('navbar_main.twig');
        } catch (\Twig_Error_Loader $e) {
        } catch (\Twig_Error_Runtime $e) {
        } catch (\Twig_Error_Syntax $e) {
        }
    }

    public function mail() {
        if ($this->gen->mailContact($_POST)) {
            header('location: ?p=alert&alert=Email envoyé!');
        } else {
            header('location: ?p=alert&alert=Problème avec l\'envoie, réessayez');
        }
    }

    public function alert() {
        $home = $this->gen->getInfos();
        try {
            echo $this->twig->render('navbar_main.twig');
        } catch (\Twig_Error_Loader $e) {
        } catch (\Twig_Error_Runtime $e) {
        } catch (\Twig_Error_Syntax $e) {
        }
        try {
            echo $this->twig->render(
                'alert.twig', [
                    'infos' => $home,
                    'alert' => $_GET['alert']
                ]
            );
        } catch (\Twig_Error_Loader $e) {
        } catch (\Twig_Error_Runtime $e) {
        } catch (\Twig_Error_Syntax $e) {
        }
    }

    public function dl() {
        if (isset($_GET['pdf'])) {
            $pdf = $_GET['pdf'];
            header("Content-type: application/pdf");
            header("Content-Disposition: attachment; filename=$pdf");
            readfile($pdf);
        }
    }

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
                $this->cman->validComment($_POST);
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

    public function log() {
        $_SESSION['user'] = new \model\entity\User();
        switch ($_SESSION['user']->connect($_POST['email'], $_POST['pwd'])) {
            case 'ok':
                if ($_SESSION['user']->getUserlvl() > 2) {
                    header('location: ?p=home');
                } elseif ($_SESSION['user']->getUserlvl() <= 2) {
                    header('location: ?p=admin');
                }
                break;
            case 'false_mdp':
                header('location: ?p=alert&alert=Mauvais Nom utilisateur ou mot de passe');
                break;
            case 'false_ip':
                header('location: ?p=alert&alert=Trop de tentatives, retentez plus tard.');
                break;
            default:
                break;
        }
    }

    public function disconnect() {
        $_SESSION = array();
        session_destroy;
        header('location: ?p=home#about');
    }

}
