<?php
/**
 * Created by PhpStorm.
 * User: thiago
 * Date: 25/01/17
 * Time: 23:00
 */

namespace Eadline\Controller;

use Firebase\JWT\JWT;

class CrypToken extends JWT
{
    private $secretKey;
    private $algorithm;
    private $tokenId;
    private $issuedAt;
    private $notBefore; //Adding 10 seconds
    private $expire; // Adding 60 seconds
    private $serverName; /// set your domain name
    private $name;
    private $id;



    public function __construct($serverName)
    {

        $this->algorithm = "HS512";
        $this->tokenId = base64_encode(mcrypt_create_iv(32));
        $this->issuedAt = time();
        $this->notBefore = $this->issuedAt + 10;
        $this->expire = $this->notBefore + 10;
        $this->serverName = $serverName;
    }

    private function getSecretKey(){
        if(strlen($this->secretKey)>0){
            return $this->secretKey;
        }else{
            return "I did not the secret key";
        }

    }

    public function setSecretKey($secretKey=null){
        if($secretKey == null){
            $this->secretKey = "I did not the secret key";
        }else{
            $this->secretKey = $secretKey;
        }
    }

    public function setInfoUser(Array $dataUser){
        $this->name = $dataUser['name'];
        $this->id = $dataUser['id'];
    }

    public function genToken(){
        $payload = [
            'iat'  => $this->issuedAt,         // Issued at: time when the token was generated
            'jti'  => $this->tokenId,          // Json Token Id: an unique identifier for the token
            'iss'  => $this->serverName,     // Issuer
                    // Expire
            'data' => [                  // Data related to the logged user you can set your required data
                'id'   => $this->id, // id from the users table
                'name' =>$this->name, //  name
            ]
        ];
        $secretKey = base64_decode($this->getSecretKey());
        /// Here we will transform this array into JWT:
        return JWT::encode(
            $payload, //Data to be encoded in the JWT
            $secretKey, // The signing key
            $this->algorithm
        );
        /*$unencodedArray = ['jwt' => $jwt];
        echo  json_encode($unencodedArray);*/
    }

    public function validationToken($token){
        $response = ['success'=>false];
        try {
            //$decoded = JWT::decode($token, $this->key, array('HS256'));
            try{
                if(JWT::decode($token, base64_decode($this->getSecretKey()), array('HS512'))){
                    $response = ['success'=>true];
                }

            }
            catch(\Exception $exception){
                $response['success'] = false;
            }



            //$response['decode'] = $decoded;

        } catch (Exception   $e) {
            throw new Exception('date error');
        }
        return $response;

    }

}