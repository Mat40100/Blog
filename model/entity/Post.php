<?php

namespace model\entity;

use model\UsersManager;

class Post
{
    protected $usersManager;
    public $postId;
    public $authorId;
    public $authorName;
    public $title;
    public $chapo;
    public $content;
    public $lastMod;
    public $error;

    public function __construct($array, $form)
    {
        $this->usersManager=new UsersManager();
        $this->error = 0;
        foreach($array as $key => $value) {
            if (!isset($value)) {
                $this->setError();
            }
        }
        if($this->getError()=== 0){
            $this->setPostId($array['postid']);
            $this->setAuthorId($array['authorid']);
            $this->setTitle($array['title']);
            $this->setLastMod($array['last_mod']);
            $this->setChapo($array['chapo']);
            if ($form == "form") {
                $this->setContent(\model\Formatcontent::format($array['content']));
            } elseif ($form == "no_form") {
                $this->setContent($array['content']);
            }
        }
    }

    public function getError()
    {
        return $this->error;
    }

    public function getAuthorName()
    {
        return $this->authorName;
    }

    public function getPostId()
    {
        return $this->postId;
    }

    public function getAuthorId()
    {
        return $this->authorId;
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
        return $this->lastMod;
    }

    private function setError()
    {
        $this->error = ++$this->error;
    }

    private function setPostId($postid)
    {
        intval($postid);
        if($postid>0){
            $this->postId = $postid;
        }else{
            $this->setError();
        }
    }

    private function setAuthorId($authorid)
    {
        intval($authorid);
        if($authorid>0){
            $this->authorId = $authorid;
            $this->authorName = $this->usersManager->getNickname($authorid);
        }else{
            $this->setError();
        }
    }

    private function setTitle($title) 
    {
        $this->title = $title;
    }

    private function setChapo($chapo) 
    {
        $this->chapo = $chapo;
    }

    private function setContent($content) 
    {
        $this->content = $content;
    }

    private function setLastMod($last_mod) 
    {
        $this->lastMod = $last_mod;
    }

}
