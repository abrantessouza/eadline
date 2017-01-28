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
          $checkUser = $users->select()->columns(['users.id as idUser','users.name','users.password','users.hashsalt',"levelusers.typeUser as tipo"])
                          ->innerjoin("levelusers",array("levelusers_id","id"))
                          ->where("email","=",$input->get('user'))
                          ->run();

          if(HashPassword::validation($input->get('password'),$checkUser[0]['password'], $checkUser[0]['hashsalt'])){
              $arrUser = ["name"=>$checkUser[0]['name'],"id"=>$checkUser[0]['idUser'],"permission"=>$checkUser[0]['tipo']];
              $token->setInfoUser($arrUser);
              $success = true;
              $tokenGen = $token->genToken();
              $users->update()->setColumns(['lastoken' => $tokenGen])->where("id", "=", $checkUser[0]['idUser'])->run();
          }


          echo json_encode(array("output"=>$success,"token"=>$tokenGen));
      }
  }

  public function requestRegister(){
      $users = Container::getModel("users");
      $input = $this->input();
      $pass =  HashPassword::hash($input->get('password'));
      $msg = "";
      $res = false;
      $checkEmailUsed = $users->select()->columns(['email'])->where("email","=",$input->get("email"))->run();
      if(strlen($checkEmailUsed[0]['email']) > 0){
         $msg = "E-mail existente";
      }else{
          if(filter_var($input->get("email"),FILTER_VALIDATE_EMAIL)){
              $res = $users->insert()
                  ->addValues(['name', 'email', 'password', 'hashsalt', 'levelusers_id'],
                      [$input->get('name'),
                          $input->get('email'),
                          $pass['hashpass'],
                          $pass['hashsalt']
                          , 3])->run();
          }else{
              $msg = "E-mail Inválido!!";
          }
      }

      echo json_encode(array("output"=>$res, "msg" => $msg));
  }

  
}

?>
