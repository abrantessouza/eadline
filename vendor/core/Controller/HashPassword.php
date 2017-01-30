<?php
/**
 * Created by PhpStorm.
 * User: thiago
 * Date: 28/01/17
 * Time: 13:51
 */

namespace Eadline\Controller;


class HashPassword
{
    protected static $saltPrefix = "2a";
    protected static $defaultCost = 8;
    protected static $saltLength = 22;

    private function generateHash($cost, $salt){
        return sprintf('$%s$%02d$%s$', self::$saltPrefix, $cost, $salt);
    }

    public static function generateRandomSalt(){
        $seed = uniqid(mt_rand(), true);
        $salt = base64_encode($seed);
        $salt = str_replace("+",".", $salt);
        return substr($salt, 0, self::$saltLength);
    }

    public static function hash($string, $cost = null){
        if(empty($cost)){
            $cost = self::$defaultCost;
        }

        $salt = self::generateRandomSalt();
        $hashString = self::generateHash((int)$cost, $salt);
        return ["hashpass"=> crypt($string, $hashString),
                "hashsalt"=>$hashString];
    }

    public static function validation($string, $hash, $hashsalt){
        if(crypt($string, $hashsalt) === $hash){
            return true;
        }else{
            return false;
        }
    }

}