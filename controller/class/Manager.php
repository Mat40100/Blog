<?php

class Manager
{
    protected function dbConnect()
    {
        $db = new PDO('mysql:host=db732316432.db.1and1.com;dbname=db732316432;char-set=utf8','dbo732316432','Kinder1234');
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);
        return $db;
    }
} 