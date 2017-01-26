<?php
/**
 * Created by PhpStorm.
 * User: Thiago
 * Date: 26/01/2017
 * Time: 12:35
 */

namespace App;


class Conn
{

    public static function connDb(){
        return new \PDO("mysql:host=localhost;dbname=eadline","root","");
    }
}