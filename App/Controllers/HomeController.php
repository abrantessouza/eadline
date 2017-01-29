<?php
/**
 * Created by PhpStorm.
 * User: thiago
 * Date: 28/01/17
 * Time: 18:56
 */

namespace App\Controllers;


use Eadline\Controller\Action;

class HomeController extends Action
{
    public function home(){
        $this->render("dash");
    }

    public function trainingmanager(){
        $this->render("training");
    }
}