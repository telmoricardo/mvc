<?php

namespace App\Http\Middleware;

use App\Session\Admin\Login as SessionAdminLogin;

class RequireAdmingLogin
{
    /**
     * Metodo responsavel por executar o middleware
     * @param $request
     * @param \Closure $next
     * @return Response
     */
    public function handle($request, $next){
        //verifica se usuario esta logado
        if(!SessionAdminLogin::isLogged()){
           $request->getRouter()->redirect('/admin/login');
        }
        //executa o próximo nivel do middleware
        return $next($request);
    }
}