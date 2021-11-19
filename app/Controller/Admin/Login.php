<?php

namespace App\Controller\Admin;

use App\Model\Entity\User;
use App\Utils\View;
use App\Session\Admin\Login as SeessionAdminLogin;

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

        //cria a sessão de login
        SeessionAdminLogin::login($obj);

        //redireciona o usuario para home admin
        $request->getRouter()->redirect('/admin');
    }

    /**
     * metodo responsavel por deslogar o usuario
     * @param $request
     */
    public static function setLogout($request){
        //destroy a sessão de login
        SeessionAdminLogin::logout();

        //redireciona o usuario para a tela de login
        $request->getRouter()->redirect('/admin/login');
    }
}