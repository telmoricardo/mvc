<?php

namespace App\Controller\Admin;

use App\Model\Entity\User;
use App\Utils\View;

class Login extends  Page
{
    /**
     * metodo responsavel por retornar a renderização da pagina login
     * @param $request
     */
    public static function getLogin($request, $errorMessage = null){
        $status = !is_null($errorMessage) ? View::render('admin/login/status', [
            'mensagem' => $errorMessage
        ]) : '';

        $content = View::render('admin/login', [
            'status' => $status
        ]);

        //
        return parent::getPage('Login > Telmo Ricardo', $content);
    }

    public static function setLogin($request){
       $postVars = $request->getPostVars();
       $email = $postVars['email'] ?? '';
       $senha = $postVars['senha'] ?? '';

       //buscar o usuario
        $obj = User::getUserByEmail($email);

        if(!$obj instanceof User){
            return self::getLogin($request, 'Email ou senha invalidos');
        }

        if(!password_verify($senha, $obj->senha)){
            return self::getLogin($request, 'Email ou senha invalidos');
        }
    }
}