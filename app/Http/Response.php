<?php

namespace App\Http;

class Response
{
    /**
     * Codigo do status http
     * @var int
     */
    private $httpCode = 200;

    /**
     * Cabeçalho do response
     * @var array
     */
    private $headers = [];

    /**
     * Tipo de conteudo que sendo retornado
     * @var string
     */
    private $contentType = "text/html";

    /**
     * Conteudo do response
     * @var mixed
     */
    private $content;

    /**
     * Metodo responsavel por iniciar a classe e definir os valores
     * @param int $httpCode
     * @param string $contentType
     * @param mixed $content
     */
    public function __construct(int $httpCode, $content, string $contentType='text/html')
    {
        $this->httpCode = $httpCode;
        $this->content = $content;
        $this->setContentType($contentType);
    }

    /**
     * metodo responsavbel por alterar o content tupe do response
     * @param $contentType
     */
    public function setContentType($contentType){
        $this->contentType = $contentType;
        $this->addHeader("Content-Type", $contentType);
    }

    /**
     * Metodo responsavel por adcionar um registro no cabeçalho no response
     * @param string $key
     * @param string $value
     */
    public function addHeader($key, $value){
        $this->headers[$key] = $value;
    }

    /**
     * Metodo responsavel por enviar os headers para o navegador
     */
    private function sendHeader(){
        //status
        http_response_code($this->httpCode);

        //enviar
        foreach ($this->headers as $key=> $value):
            header($key.': '.$value);
        endforeach;
    }

    /**
     * Método responsavel por enviar a resposta para o usuario
     */
    public function sendResponse(){
        //enviar os headers
        $this->sendHeader();

        //imprime os conteudos
        switch ($this->contentType):
            case 'text/html':
                echo $this->content;
                break;

        endswitch;
    }


}