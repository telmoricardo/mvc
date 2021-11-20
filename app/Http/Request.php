<?php

namespace App\Http;

class Request
{
    /**
     * Instancia do router
     * @var
     */
    private $router;
    /**
     * Método Http da requisição
     * @var string
     */
    private $httpMethod;

    /**
     * URI da pagina
     * @var string
     */
    private $uri;

    /**
     * Parametros da URL ($_GET)
     * @var array
     */
    private $queryParams = [];

    /**
     * Variavies recebidas no post da pagaina ($_POST)
     * @var array
     */
    private $postVars = [];

    /**
     * cabeçalho da requisição
     * @var array
     */
    private $headers = [];

    /**
     * @param string $httpMethod
     * @param string $uri
     * @param array $queryParams
     * @param array $postVars
     * @param array $headers
     */
    public function __construct($router)
    {
        $this->router      = $router;
        $this->queryParams = $_GET ?? [];
        $this->headers     = getallheaders();
        $this->httpMethod  = $_SERVER['REQUEST_METHOD'] ?? '';
        $this->setUri();
        $this->setPostVars();

    }

    /**
     * METODO RESPONSAVEL POR DEFINIAR AS VARIAVEIS DO POST
     * @return false|void
     */
    public function setPostVars(){
        if($this->httpMethod == 'GET') return false;

        //POST PADRÃO
        $this->postVars    = $_POST ?? [];

        //post json
        $inputRaw = file_get_contents('php://input');
        $this->postVars = (strlen($inputRaw) && empty($_POST)) ? json_decode($inputRaw,true) : $this->postVars;

    }

    /**
     * Metodo responsavel por definir a URI
     */
    private function setUri(){
        $this->uri = $_SERVER['REQUEST_URI'] ?? '';

        //remove gets da uri
        $xURI = explode('?', $this->uri);
        $this->uri = $xURI[0];
    }

    /**
     * metodo responsavel por instancia de router
     * @return router
     */
    public function getRouter(){
        return $this->router;
    }

    /**
     * @return string
     */
    public function getHttpMethod()
    {
        return $this->httpMethod;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @return array
     */
    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    /**
     * @return array
     */
    public function getPostVars(): array
    {
        return $this->postVars;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }



}