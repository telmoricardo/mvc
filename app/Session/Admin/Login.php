<?php

namespace App\Session\Admin;

class Login
{
    /**
     * Metodo responsavel por iniciar a sessão
     */
    private static function init(){
        //verifica se a sessão não está ativa
        if(session_status() !== PHP_SESSION_ACTIVE){
            session_start();
        }
    }

    /**
     * Metodo responsavel por criar o login do usuario
     * @param $user
     * @return boolen
     */
    public static function login($user){
        //inicia a sessão
        self::init();

        //defini a sessão do usuario
        $_SESSION['admin']['usuario'] = [
            'id' => $user->id,
            'nome' => $user->nome,
            'email' => $user->email
        ];

        //sucesso
        return true;
    }

    /**
     * Metodo responsavel por verificar se o usuario esta logado
     */
    public static function isLogged(){
        //inicia a sessão
        self::init();

        //retorna a verificação
        return isset($_SESSION['admin']['usuario']['id']);
    }

    /**
     * Metodo responsavel por executar o logout do usuario
     * @return bool
     */
    public static function logout(){
        //INICIA A SESSÃO
        self::init();

        //DESLOGA O USUARIO
        unset($_SESSION['admin']['usuario']);

        //sucesso
        return true;
    }

}