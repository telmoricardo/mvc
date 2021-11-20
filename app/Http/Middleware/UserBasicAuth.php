<?php

namespace App\Http\Middleware;

use App\Model\Entity\User;

class UserBasicAuth
{
    /**
     * Metodo responseval por retornar uma instancia de usuário autenticado
     */
    private function getBasicAuthUser(){
        //verifica a existencia dos dados de acesso
        if(!isset($_SERVER['PHP_AUTH_USER']) or !isset($_SERVER['PHP_AUTH_PW'])){
            return false;
        }

        //busca o usuario pelo e-mail
        $objUser = User::getUserByEmail($_SERVER['PHP_AUTH_USER']);

        ///verifica a instancia
        if(!$objUser instanceof User){
            return false;
        }

        //valida a senha a retorna o usuario
        return password_verify($_SERVER['PHP_AUTH_PW'], $objUser->senha) ? $objUser : false;
    }

    /**
     * Metodo resonsavel por validar acesso via basic auth
     * @param $request
     */
    private function basicAuth($request){
        //verifica o usuario recebiod
        if($objUser = $this->getBasicAuthUser()){
            $request->user = $objUser;
            return true;
        }

        //emite o erro de senha invalidad
        throw new \Exception("Usuario ou senha invalidos", 403);
    }



    /**
     * Metodo responsavel por executar o middleware
     * @param $request
     * @param \Closure $next
     * @return Response
     */
    public function handle($request, $next){
        //BASIC a validação do acesso via basic auth
        $this->basicAuth($request);


        //executa o próximo nivel do middleware
       return $next($request);
    }

}