<?php

namespace App\Http\Middleware;

class Api
{
    /**
     * Metodo responsavel por executar o middleware
     * @param $request
     * @param \Closure $next
     * @return Response
     */
    public function handle($request, $next){
       //Alterar o content type para json
        $request->getRouter()->setContentType('application/json');
        //executa o próximo nivel do middleware
       return $next($request);
    }

}