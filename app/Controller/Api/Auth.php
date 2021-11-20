<?php

namespace App\Controller\Api;
use \App\Model\Entity\User;
use Firebase\JWT\JWT;


class Auth extends Api
{
    /**
     * Método responsável por gerar um token JWT
     * @param $request
     * @return array
     */
    public static function generateToken($request){
        //POSTVARS
        $postVars = $request->getPostVars();

        //valida os campos obrigatorios
        if(!isset($postVars['email']) or !isset($postVars['senha']) ){
            throw new \Exception("Os campos email e senha são obrigatorios", 400);
        }

        //busca o usuario pelo email
        $obj = User::getUserByEmail($postVars['email']);
        if(!$obj instanceof User){
            throw new \Exception('O usuario ou senha são invalidos', 400);
        }

        $payload = array(
            'email' =>  $obj->email,
            "nome" => $obj->nome
        );

        $jwt = JWT::encode($payload, getenv('JWT_KEY'), 'HS256');
        //retorna o token gerado
        return[
          'token' =>  $jwt
        ];
    }
}