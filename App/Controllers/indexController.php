<?php

namespace App\Controllers;

use Eadline\Controller\Action;

class IndexController extends Action{

  public function login(){
    $this->render("login");
  }
  
}

?>
