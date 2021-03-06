<?php

namespace App\Http;
use \Closure;
use \Exception;
use \ReflectionFunction;
use App\Http\Middleware\Queue as MiddlewareQuere;

class Router
{
    /**
     * URL COMPLETA DO PROJETO (raiz)
     * @var string
     */
    private $url = '';

    /**
     * Prefixo de todas as rotas
     * @var string
     */
    private $prefix = '';

    /**
     * Indice de rotas
     * @var array
     */
    private $routes = [];

    /**
     * Instancia de Request
     * @var Request
     */
    private $request;

    private $contentType = 'text/html';


    /**
     * Método responsavel por iniciar a classe
     * @param $url
     */
    public function __construct($url)
    {
        $this->request = new Request($this);
        $this->url = $url;
        $this->setPrefix();
    }

    /**
     * metodo resposavel por mudar o valor content type
     * @param $contentType
     */
    public function setContentType($contentType){
        $this->contentType = $contentType;
    }

    /**
     * Metodo responsavel por definir o prefixo das rotas
     */
    private function setPrefix(){
        //informações da url atual
        $parseUrl = parse_url($this->url);

        //define o prefixo
        $this->prefix = $parseUrl['path'] ?? '';
    }

    /**
     * Método responsavel por adicionar uma rota na classe
     * @param string $method
     * @param string $route
     * @param array $params
     */
    private function addRouter($method, $route, $params = []){
        //validação dos parametros
        foreach ($params as $key=>$value):
            if($value instanceof Closure):
                $params['controller'] = $value;
                unset($params[$key]);
                continue;
            endif;
        endforeach;

        //middlewares das rotas
        $params['middlewares'] = $params['middlewares']  ?? [];

        //variaveis das rota
        $params['variables'] = [];

        //padrão de validação das variaveis das rotas
        $patterVariables = '/{(.*?)}/';
        if(preg_match_all($patterVariables,$route,$matches)):
            $route = preg_replace($patterVariables, '(.*?)',$route);
            $params['variables'] = $matches[1];
        endif;

        //remove a barra no final da rota
        $route = rtrim($route, '/');

        //padrão de validação da url
        $patterRoute = '/^'.str_replace('/','\/', $route).'$/';

        //adicionar a rota dentro da classe
        $this->routes[$patterRoute][$method] = $params;
    }



    /***
     * Método responsável por definir uma rota de GET
     * @param string $route
     * @param array $params
     */
    public function get($route, $params = []){
        $this->addRouter('GET',$route, $params);
    }

    /***
     * Método responsável por definir uma rota de POST
     * @param string $route
     * @param array $params
     */
    public function post($route, $params = []){
        $this->addRouter('POST',$route, $params);
    }

    /***
     * Método responsável por definir uma rota de PUT
     * @param string $route
     * @param array $params
     */
    public function put($route, $params = []){
        $this->addRouter('PUT',$route, $params);
    }

    /***
     * Método responsável por definir uma rota de DELETE
     * @param string $route
     * @param array $params
     */
    public function delete($route, $params = []){
        $this->addRouter('DELETE',$route, $params);
    }

    /**
     * metodo responsavel por retornar a URI desconsiderando o PREFIXO
     */
    private function getUri(){
        //uri da request
        $uri = $this->request->getUri();

        //fatia a uri com prefixo
        $xUri = strlen($this->prefix) ? explode($this->prefix,$uri) : [$uri];

        //retorna a uri sem prefixo
        return rtrim(end($xUri), '/');
    }

    /**
     * Metodo responsavel por retornar os dados da rota atual
     * @return array
     */
    private function getRoute(){
        //URI
        $uri = $this->getUri();

        //method
        $httpMethod = $this->request->getHttpMethod();

        //valida as rotas
        foreach ($this->routes as $patternRoute=>$methods):
            //verifica se a uri bate o padrão
           if(preg_match($patternRoute,$uri,$matches)):
               //verifica o método
               if(isset($methods[$httpMethod])):
                   //remove a primeira opção
                   unset($matches[0]);

                    //varaives processadas
                    $keys = $methods[$httpMethod]['variables'];
                    $methods[$httpMethod]['variables'] = array_combine($keys,$matches);
                    $methods[$httpMethod]['variables']['request'] = $this->request;

                   //retorno dos parametros da rota
                   return $methods[$httpMethod];
               endif;
                //metodo não permitido
               throw new Exception("Método não permitido!", 405);
           endif;
        endforeach;
        //url não encontrada
        throw new Exception("Url não encontrada!", 404);
    }

    /**
     * metodo responsavel por executar a rota atual
     * @return Response
     */
    public function run(){
        try {
            //obtem a rota atual
           $route = $this->getRoute();

           //verifica o controlador
            if(!isset($route['controller'])):
                throw new Exception("A Url não pode ser processada!", 500);
            endif;

            //argumentos da função
            $args = [];

            //refleticon
            $reflection = new ReflectionFunction(($route['controller']));
            foreach ($reflection->getParameters() as $parameter):
                $name = $parameter->getName();
                $args[$name] = $route['variables'][$name] ?? '';
            endforeach;

            //RETORNA A EXECUÇÃO DA FILA DE MIDDLEWARE
            return (new MiddlewareQuere($route['middlewares'],$route['controller'], $args))->next($this->request);

        }catch (Exception $e){
            return new Response($e->getCode(), $this->getErrorMessage($e->getMessage()), $this->contentType);
        }
    }

    /**
     * metodo responsavel por erro de acordo com content type
     * @param $message
     */
    private function getErrorMessage($message){
        switch ($this->contentType){
            case 'application/json':
                return [
                    'error' => $message
                ];
                break;

            default:
                return $message;
                break;
        }
    }

    /**
     * metodo responsavem retornar a url atual
     */
    public function getCurrentUrl(){
        return $this->url.$this->getUri();
    }

    /**
     * metodo responsavel por redirecionar a URL
     * @param string $router
     */
    public function redirect($router){
        //url
        $url = $this->url.$router;

        //executa o redirect
        header('location: ' .$url);
        exit;
    }

}