<?php

namespace App\Controllers;

use Eadline\Controller\Action;
use Eadline\Controller\CrypToken;


class IndexController extends Action{


  public function login(){
    $this->render("login");
  }

  public function auth(){
      $token = new CrypToken($_SERVER['HTTP_HOST']);
      $token->setSecretKey("3@dL!n3#*.*");
      if(isset($_GET['token'])){
          $resp = $token->validationToken($_GET['token']);
          echo json_encode(array("redirect"=>$resp['success']));

      }else{
          $content = trim(file_get_contents("php://input"));
          $decoded = json_decode($content, true);
          $arrUser = ["name"=>$decoded['user'],"id"=>42];
          $token->setInfoUser($arrUser);

          echo json_encode(array("token"=>$token->genToken()));
      }
  }

  
}

?>
