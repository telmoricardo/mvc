<?php

namespace App\Http\Middleware;

use App\Http\Response;

class Queue{

    /**
     * mapeamento de middlewares
     * @var array
     */
    private static $map = [];

    /**
     * mapeamento de middlewares de todas as rotas
     * @var array
     */
    private static $default = [];

    /**
     * Fila de $middleware a serem executadas
     * @var array
     */
    private $middleware = [];

    /**
     * Função de execuçao do controlador
     * @var \Closure
     */
    private $controller;

    /**
     * Argumentos da função do controllador
     * @var array
     */
    private $controllerArgs = [];

    /**
     *  * metodo responsavel por contruir a classe de fila de middlewares
     * @param array $middleware
     * @param Closure $controller
     * @param array $controllerArgs
     */
    public function __construct(array $middleware, $controller, array $controllerArgs)
    {
        $this->middleware = array_merge(self::$default, $middleware);
        $this->controller = $controller;
        $this->controllerArgs = $controllerArgs;
    }

    /**
     * Método respospavel por definir o mapeamento de middlewares
     * @param array $map
     */
    public static function setMap(array $map)
    {
        self::$map = $map;
    }

    /**
     * Método respospavel por definir o mapeamento de middlewares para todos as rotas
     * @param array $default
     */
    public static function setDefault(array $default)
    {
        self::$default = $default;
    }

    /**
     * metodo responsavel por executar o proximo nivel da middlewares
     * @param $request
     * @return response
     */
    public function next($request){
       //verifica se a fila esta vazia
        if(empty($this->middleware)) return call_user_func_array($this->controller, $this->controllerArgs);

        //middleware
        $middleware = array_shift($this->middleware);

        //verifica o mapeamento
        if(!isset(self::$map[$middleware])){
            throw new \Exception("Problemas ao processar o middleware da requisição", 500);
        }

        $queue = $this;
        $next = function($request) use($queue){
            return $queue->next($request);
        };

        //executa o middleares
        return (new self::$map[$middleware])->handle($request, $next);

    }



}