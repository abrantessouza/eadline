<?php

namespace App\Controllers;

use Eadline\Controller\Action;
use \Firebase\JWT\JWT;

class IndexController extends Action{

  public function login(){
    $this->render("login");
  }

  public function auth(){

      define('SECRET_KEY','Your-Secret-Key');  /// secret key can be a random string and keep in secret from anyone
      define('ALGORITHM','HS512');

      $tokenId    = base64_encode(mcrypt_create_iv(32));
      $issuedAt   = time();
      $notBefore  = $issuedAt + 10;  //Adding 10 seconds
      $expire     = $notBefore + 7200; // Adding 60 seconds
      $serverName = 'http://localhost/php-json/'; /// set your domain name


      /*
       * Create the token as an array
       */
      $data = [
          'iat'  => $issuedAt,         // Issued at: time when the token was generated
          'jti'  => $tokenId,          // Json Token Id: an unique identifier for the token
          'iss'  => $serverName,       // Issuer
          'nbf'  => $notBefore,        // Not before
          'exp'  => $expire,           // Expire
          'data' => [                  // Data related to the logged user you can set your required data
              'id'   => 13, // id from the users table
              'name' =>"teste", //  name
          ]
      ];
      $secretKey = base64_decode(SECRET_KEY);
      /// Here we will transform this array into JWT:
      $jwt = JWT::encode(
          $data, //Data to be encoded in the JWT
          $secretKey, // The signing key
          ALGORITHM
      );
      $unencodedArray = ['jwt' => $jwt];
      echo  "{'status' : 'success','resp':".json_encode($unencodedArray)."}";
  }
  
}

?>
