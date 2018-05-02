<?php

namespace model;

class Formatcontent
{
    private function __construct() 
    {
    }
    
    static public function format($content)
    {
        
        $content = htmlspecialchars($content);
        $content = preg_replace('`\[img\](.+)\[/img\]`isU', '<img class="img-fluid" style="max-width: 100%;height:auto;" src="$1">', $content);
        //gras
        $content = preg_replace('`\[g\](.+)\[/g\]`isU', '<strong>$1</strong>', $content); 
        //italique
        $content = preg_replace('`\[i\](.+)\[/i\]`isU', '<em>$1</em>', $content);
        //souligné
        $content = preg_replace('`\[s\](.+)\[/s\]`isU', '<u>$1</u>', $content);
        //paragraphe
        $content = preg_replace('`\[p\](.+)\[/p\]`isU', '<p>$1</p>', $content);
        
        $content = preg_replace('`\[l\]`isU', '</br>', $content);
        
        return $content;  
    }
}
