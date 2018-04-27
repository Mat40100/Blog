<?php

namespace model\entity;

class Post {
    private $Pman;
    public $postid;
    public $authorid;
    public $authorname;
    public $title;
    public $chapo;
    public $content;
    public $last_mod;

    public function __construct($postid,$form) {
        
        $this->Pman = new\model\PostsManager();
        $infos = $this->Pman->GetPost($postid);
        
        $this->setPostid($infos['postid']);
        $this->setAuthorid($infos['authorid']);
        $this->setTitle($infos['title']);
        $this->setLast_mod($infos['last_mod']);
        $this->setChapo($infos['chapo']);
        $this->setAuthorname($infos['authorname']);
        
        if($form== "form"){
            $this->setContent(\model\Formatcontent::format($infos['content']));
        }elseif($form == "no_form"){
            $this->setContent($infos['content']);
        }
    }

    public function getAuthorname() {
        return $this->authorname;
    }

    public function getPostid() {
        return $this->postid;
    }

    public function getAuthorid() {
        return $this->authorid;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getChapo() {
        return $this->chapo;
    }

    public function getContent() {
        return $this->content;
    }

    public function getLast_mod() {
        return $this->last_mod;
    }

    private function setAuthorname($authorname) {
        $this->authorname = $authorname;
    }

    private function setPostid($postid) {
        $this->postid = $postid;
    }

    private function setAuthorid($authorid) {
        $this->authorid = $authorid;
    }

    private function setTitle($title) {
        $this->title = $title;
    }

    private function setChapo($chapo) {
        $this->chapo = $chapo;
    }

    private function setContent($content) {
        $this->content = $content;
    }

    private function setLast_mod($last_mod) {
        $this->last_mod = $last_mod;
    }

}
