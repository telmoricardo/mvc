<?php

namespace App\Controller\Pages;

use App\Http\Request;
use \App\Utils\View;
use \App\Model\Entity\Testimony as EntityTestimony;
use \WilliamCosta\DatabaseManager\Pagination;

class Testimony extends Page {

    /**
     * Método responsável por retornar o conteúdo (view) de depoimentos
     * @param Request
     * @return string
     */
    public static function getTestimonies($request){
        //voew de depoimentos
        $content = View::render('pages/testimonies', [
            'itens' => self::getTestimonyItems($request, $pagination),
            "pagination" => parent::getPagination($request, $pagination)
        ]);

        return parent::getPage('Depoimentos -Telmo Ricardo', $content);
    }

    /**
     * metodo responsavel por cadastrar um depoimento
     * @param $request
     * @return string
     */
    public static function insertTestimonies($request){
        $postVars = $request->getPostVars();

        $obj = new EntityTestimony();
        $obj->nome = $postVars['nome'];
        $obj->mensagem = $postVars['mensagem'];
        $obj->cadastrar();

        //retprma a pagína de listagem de depoimentos
        return self::getTestimonies($request);
    }

    /**
     * metodo responsavel para obter os depoimentos
     * @param Request $request
     * @param Pagination $pagination
     * @return string
     */
    private static function getTestimonyItems($request, &$pagination){
        //depoimentos
        $itens = '';

        //qauntidade de tottal de registros
        $qtdTotal = EntityTestimony::getTestimonies(null, null,null,'COUNT(*) as qtd')->fetchObject()->qtd;

        //pagina atual
        $queryParams = $request->getQueryParams();

        $paginaAtual = $queryParams['page'] ?? 1;

        //instancia da paginação
        $pagination = new Pagination($qtdTotal, $paginaAtual, 2);

        //resultados da pagina de depoimentos
        $results = EntityTestimony::getTestimonies(null, 'id DESC', $pagination->getLimit());

        //RENDERIZA O ITEM
        while($obj = $results->fetchObject(EntityTestimony::class)){
            $itens .= View::render('pages/testimony/item', [
               'nome' => $obj->nome,
                'mensagem' => $obj->mensagem,
                'data' => date('d/m/Y', strtotime($obj->data))
            ]);
        }
        return $itens;
    }

}
