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
        $this->secretKey = "E@dLin3T0k3n";
        $this->algorithm = "HS512";
        $this->tokenId = base64_encode(mcrypt_create_iv(32));
        $this->issuedAt = time();
        $this->notBefore = $this->issuedAt + 10;
        $this->expire = $this->notBefore + 10;
        $this->serverName = $serverName;
    }

    public function setInfoUser(Array $dataUser){
        $this->name = $dataUser['name'];
        $this->id = $dataUser['id'];
    }

    public function genToken(){
        $payload = [
            'iat'  => $this->issuedAt,         // Issued at: time when the token was generated
            'jti'  => $this->tokenId,          // Json Token Id: an unique identifier for the token
            'iss'  => $this->serverName,       // Issuer
            'nbf'  => $this->notBefore,        // Not before
            'exp'  => $this->expire,           // Expire
            'data' => [                  // Data related to the logged user you can set your required data
                'id'   => $this->id, // id from the users table
                'name' =>$this->name, //  name
            ]
        ];
        $secretKey = base64_decode($this->secretKey);
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
            $decoded = JWT::decode($token, base64_decode($this->secretKey), array('HS512'));
            $response['success'] = true;
            $response['decode'] = $decoded;
            return $response;

        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

}