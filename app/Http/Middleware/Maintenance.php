<?php

namespace App\Http\Middleware;

class Maintenance
{
    /**
     * Metodo responsavel por executar o middleware
     * @param $request
     * @param \Closure $next
     * @return Response
     */
    public function handle($request, $next){
        //verifica o estado de manutenção da pagina
        if(getenv('MAINTENANCE') == 'true'){
            throw new \Exception("Pagina em manutenção. Tente mais tarde", 200);
        }
        //executa o próximo nivel do middleware
       return $next($request);
    }

}