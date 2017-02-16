<?php

namespace App;
use Eadline\Init\Bootstrap;

class Route extends Bootstrap{
  protected function initRoutes(){
    $routes['login'] = array("route"=>"/","controller"=>"indexController","action"=>"login");
    $routes['authentication'] = array("route"=>'/auth',"controller"=>"indexController","action"=>"auth");
    $routes['requestregister'] = array("route"=>'/register',"controller"=>"indexController","action"=>"requestRegister");
    $routes['home'] = array("route"=>'/home',"controller"=>"homeController","action"=>"home");
    $routes['trainingmanager'] = array("route"=>'/trainingmanager',"controller"=>"homeController","action"=>"trainingmanager");
    $routes['savetraining'] = array("route"=>'/savetraining',"controller"=>"homeController","action"=>"savetraining");
    $this->setRoute($routes);
  }
}

 ?>
