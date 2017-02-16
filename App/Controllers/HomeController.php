<?php
/**
 * Created by PhpStorm.
 * User: thiago
 * Date: 28/01/17
 * Time: 18:56
 */

namespace App\Controllers;


use Eadline\Controller\Action;
use Eadline\Controller\CrypToken;
use Eadline\DI\Container;

class HomeController extends Action
{
    public function home(){
        $this->render("dash");
    }

    public function trainingmanager(){
        $this->render("training");
    }

    public function savetraining(){
        $input = (object)$_POST;
        $token = new CrypToken($_SERVER['HTTP_HOST']);
        $token->setSecretKey("3@dL!n3#*.*");
        $resp = $token->validationToken($input->token);
        $destination = "./ups";
       // print_r($resp);
        $response = [];

        if(strpos($_FILES["file"]["type"],"image") !== false){
            if($resp['decode']->data->permission == "Professor" || $resp['decode']->data->permission == "Administrador"){
                $course = Container::getModel("courses");
                $res = $course->insert()
                    ->addValues(['namecourse','avatar_course','users_id','sumary'],[$input->training_name,$_FILES["file"]["name"], $resp['decode']->data->id, $input->sumary])->run();
                if($res){
                    if(is_dir($destination)){
                        if(move_uploaded_file( $_FILES['file']['tmp_name'] , $destination."/".$_FILES['file']['name'] )){
                            $response["response"] = true;
                        }
                    }else{
                            $response["response"] = false;
                            $response["message"] = "Falha ao Gravar";
                    }

                }
            }

        }
        echo json_encode($response) ;
        /*
        if(strpos($_FILES["file"]["type"],"image") !== false){
            $course = Container::getModel("courses");
            $input = $this->input();
            $res = $course->insert()
                ->addValues(['name', 'email', 'password', 'hashsalt', 'levelusers_id'],
                    [])->run();
        }else{

        }
        */
    }
}