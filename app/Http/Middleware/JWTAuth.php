<?php

namespace App\Http\Middleware;

use App\Controller\Api\Auth;
use App\Model\Entity\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTAuth
{
    /**
     * Metodo responseval por retornar uma instancia de usuário autenticado
     * @param Request $request
     */
    private function getJWTAuthUser($request){

        //headers
        $headers = $request->getHeaders();

        $token = str_replace('Bearer ', '', $headers['Authorization']);
        $jwt = isset($headers['Authorization']) ? $token : '';

        try {
            $decoded = (array)JWT::decode($jwt, new Key(getenv('JWT_KEY'), 'HS256'));
            $email = $decoded['email'];
            //busca o usuario pelo e-mail
            $objUser = User::getUserByEmail($email);
            //print_r($objUser); exit();
            //retorna o usuario
            return $objUser instanceof User ? $objUser : false;
        }catch (\Exception $e){
            throw new \Exception("Token inválido", 403);
        }




    }

    /**
     * Metodo resonsavel por validar acesso via jwt
     * @param $request
     */
    private function auth($request){
        //verifica o usuario recebiod
        if($objUser = $this->getJWTAuthUser($request)){
            $request->user = $objUser;
            return true;
        }

        //emite o erro de senha invalidad
        throw new \Exception("Acesso negado", 401);
    }



    /**
     * Metodo responsavel por executar o middleware
     * @param $request
     * @param \Closure $next
     * @return Response
     */
    public function handle($request, $next){
        //REALIZAR validação do acesso via JWT
        $this->auth($request);

        //executa o próximo nivel do middleware
       return $next($request);
    }

}