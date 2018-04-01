<?php

class acceuil{
    protected $infos;
    
    static function getInfos() {
        
        $db = DBfactory::Getinstance();
        $req = $db -> query ('SELECT name,surname,chapo,email,adress,github,linkedin,twitter FROM users WHERE userlvl="BOS" ');
        $result=$req -> fetch(PDO::FETCH_ASSOC);
        
        return $result;
    }
    
}
