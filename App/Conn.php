<?php
namespace App;


class Conn
{

    public static function connDb(){
        return new \PDO("mysql:host=localhost;dbname=eadline","root","");
    }
}
