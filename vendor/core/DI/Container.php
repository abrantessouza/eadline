<?php
/**
 * Created by PhpStorm.
 * User: Thiago
 * Date: 26/01/2017
 * Time: 17:09
 */

namespace Eadline\DI;

use App\Conn;
class Container
{
    public static function getModel($model){
        $class =  "\\App\\Models\\".ucfirst($model);
        return new $class(Conn::connDb());
    }
}