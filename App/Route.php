<?php

namespace App;
use Eadline\Init\Bootstrap;

class Route extends Bootstrap{
  protected function initRoutes(){
    $routes['login'] = array("route"=>"/","controller"=>"indexController","action"=>"login");
    $routes['authentication'] = array("route"=>'/auth',"controller"=>"indexController","action"=>"auth");
    $this->setRoute($routes);
  }
}

 ?>
