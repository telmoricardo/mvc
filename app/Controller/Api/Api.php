<?php

namespace App\Controller\Api;

class Api
{
    /**
     * metodo responsavel por retornar os detalhes da API
     * @param Request $request
     * @return array
     */
    public static function getDetails($request){
        return[
            'nome' => 'API - TELMO RICARDO',
            'versao' => 'v1.0.0',
            'author' => 'Telmo Ricardo',
            'email' => 'telmoricardorosa@gmail.com',
        ];

    }

    /**
     * metodo responsavel por retornar detalhes da paginação
     * @param $request
     * @param $pagination
     */
    protected static function getPagination($request, $pagination){
        //query params
        $queryParams = $request->getQueryParams();

        //pagina
        $pages = $pagination->getPages();

        //retorno
        return [
            'paginaAtual' => isset($queryParams['page']) ? (int)$queryParams['page'] : 1,
            'quantidadePaginas' => !empty($pages) ? count($pages) : 1,
        ];

    }
}