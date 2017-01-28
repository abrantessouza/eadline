<?php
namespace Eadline\Controller;

abstract class Action{
  protected $views;
  private $action;
  private $template;
  protected $decoded;

  public function __construct(){
    $this->views = new \stdClass;
    $this->template = "App/Views/layout.phtml";
  }

  protected function render($action, $layout = true){
    $this->action = $action;
    if($layout == true && file_exists($this->template)){
      include_once $this->template;
    }else{
      $this->content();
    }
  }

  protected function content(){
    $current =  get_class($this);
    $singleClassName = strtolower(str_replace("Controller","",str_replace("App\\Controllers\\","",$current)));
    include_once "App/Views/".$singleClassName."/".$this->action.".phtml";
  }

  protected function input(){
    $content = trim(file_get_contents("php://input"));
    $this->decoded = json_decode($content);
    return $this;
  }

  protected function get($param){
      return $this->decoded->$param;
  }

}
 ?>
