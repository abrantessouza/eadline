<?php

namespace App\Controllers;

use Eadline\Controller\HashPassword;
use Eadline\DI\Container;
use Eadline\Controller\Action;
use Eadline\Controller\CrypToken;


class IndexController extends Action{


  public function login(){
    $this->render("login");
  }

  public function auth(){
      $success = false;
      $tokenGen = null;
      $token = new CrypToken($_SERVER['HTTP_HOST']);
      $token->setSecretKey("3@dL!n3#*.*");
      if(isset($_GET['token'])){
          $resp = $token->validationToken($_GET['token']);
          echo json_encode(array("redirect"=>$resp['success']));
      }else{
          $input = $this->input();
          $users = Container::getModel("users");
          $checkUser = $users->select()->columns(['users.name','users.password','users.hashsalt'])
                          ->innerjoin("levelusers",array("levelusers_id","id"))
                          ->where("email","=",$input->get('user'))
                          ->run();
          if(HashPassword::validation($input->get('password'),$checkUser[0]['password'], $checkUser[0]['hashsalt'])){
              $arrUser = ["name"=>$this->input()->get('user'),"id"=>1];
              $token->setInfoUser($arrUser);
              $success = true;
              $tokenGen = $token->genToken();
          }


          echo json_encode(array("output"=>$success,"token"=>$tokenGen));
      }
  }

  public function requestRegister(){
      $users = Container::getModel("users");
      $input = $this->input();
      $pass =  HashPassword::hash($input->get('password'));
      $res = $users->insert()
                   ->addValues(['name','email','password','hashsalt','levelusers_id'],
                               [$input->get('name'),
                                $input->get('email'),
                                $pass['hashpass'],
                                $pass['hashsalt']
                                  ,3])->run();
      echo json_encode(array("output"=>$res));
  }

  
}

?>
