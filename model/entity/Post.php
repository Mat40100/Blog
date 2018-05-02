<?php

namespace model\entity;

class Post
{

    protected $Pman;
    public $postid;
    public $authorid;
    public $authorname;
    public $title;
    public $chapo;
    public $content;
    public $last_mod;

    public function __construct($postid, $form) 
    {

        $this->Pman = new\model\PostsManager();
        $infos = $this->Pman->getPost($postid);

        $this->_setPostid($infos['postid']);
        $this->_setAuthorid($infos['authorid']);
        $this->_setTitle($infos['title']);
        $this->_setLastMod($infos['last_mod']);
        $this->_setChapo($infos['chapo']);
        $this->_setAuthorname($infos['authorname']);

        if ($form == "form") {
            $this->_setContent(\model\Formatcontent::format($infos['content']));
        } elseif ($form == "no_form") {
            $this->_setContent($infos['content']);
        }
    }

    public function getAuthorname() 
    {
        return $this->authorname;
    }

    public function getPostid() 
    {
        return $this->postid;
    }

    public function getAuthorid() 
    {
        return $this->authorid;
    }

    public function getTitle() 
    {
        return $this->title;
    }

    public function getChapo() 
    {
        return $this->chapo;
    }

    public function getContent() 
    {
        return $this->content;
    }

    public function getLastMod() 
    {
        return $this->last_mod;
    }

    private function _setAuthorname($authorname) 
    {
        $this->authorname = $authorname;
    }

    private function _setPostid($postid) 
    {
        $this->postid = $postid;
    }

    private function _setAuthorid($authorid) 
    {
        $this->authorid = $authorid;
    }

    private function _setTitle($title) 
    {
        $this->title = $title;
    }

    private function _setChapo($chapo) 
    {
        $this->chapo = $chapo;
    }

    private function _setContent($content) 
    {
        $this->content = $content;
    }

    private function _setLastMod($last_mod) 
    {
        $this->last_mod = $last_mod;
    }

}
