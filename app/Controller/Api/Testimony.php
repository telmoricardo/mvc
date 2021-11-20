<?php

namespace App\Controller\Api;

use App\Http\Request;
use App\Model\Entity\Testimony as EntityTestimony;
use App\Utils\View;
use WilliamCosta\DatabaseManager\Pagination;

class Testimony extends Api
{
    /**
     * metodo responsavel para obter os depoimentos
     * @param Request $request
     * @param Pagination $pagination
     * @return string
     */
    private static function getTestimonyItems($request, &$pagination){
        //depoimentos
        $itens = [];

        //qauntidade de tottal de registros
        $qtdTotal = EntityTestimony::getTestimonies(null, null,null,'COUNT(*) as qtd')->fetchObject()->qtd;

        //pagina atual
        $queryParams = $request->getQueryParams();

        $paginaAtual = $queryParams['page'] ?? 1;

        //instancia da paginação
        $pagination = new Pagination($qtdTotal, $paginaAtual, 3);

        //resultados da pagina de depoimentos
        $results = EntityTestimony::getTestimonies(null, 'id DESC', $pagination->getLimit());

        //RENDERIZA O ITEM
        while($obj = $results->fetchObject(EntityTestimony::class)){
            $itens[] = [
                'id' => (int)$obj->id,
                'nome' => $obj->nome,
                'mensagem' => $obj->mensagem,
                'data' => $obj->data
            ];
        }
        return $itens;
    }

    /**
     * metodo responsavel por retornar os depoimentos cadastrados
     * @param Request $request
     * @return array
     */
    public static function getTestimonies($request){
        return[
            'depoimentos' => self::getTestimonyItems($request, $pagination),
            'paginacao' => parent::getPagination($request, $pagination),
        ];

    }

    /**
     * metodo responsavel por retornar os detalhes de um depoimento
     * @param $request
     * @param $id
     * @return array
     */
    public static function getTestimonyById($request, $id){
        //busca depoimento
        $obj = EntityTestimony::getTestimonyById($id);

        //valida a instancia
        if(!$obj instanceof EntityTestimony){
            throw new \Exception("O depoimento ".$id." não foi encontrado", 404);
        }

        return [
            'id' => (int)$obj->id,
            'nome' => $obj->nome,
            'mensagem' => $obj->mensagem,
            'data' => $obj->data
        ];
    }

    /**
     * meotodo responsavel por cadastrar depoimento
     * @param $request
     */
    public static function setNewTestimony($request){
        //postvars
        $postVars = $request->getPostVars();

        //valida os campos obrigatorios
        if(!isset($postVars['nome']) or !isset($postVars['mensagem'])){
            throw new \Exception("Os campos 'nome' e 'mensagem' são obrigatórios", 400);
        }

        $obj = new EntityTestimony();
        $obj->nome = $postVars['nome'];
        $obj->mensagem = $postVars['mensagem'];
        $obj->cadastrar();

        //retorna os detalhes do cadastro
        return [
            'id' => (int)$obj->id,
            'nome' => $obj->nome,
            'mensagem' => $obj->mensagem,
            'data' => $obj->data
        ];
    }

    /**
     * meotodo responsavel por atualizar depoimento
     * @param $request
     */
    public static function setEditTestimony($request, $id){
        //postvars
        $postVars = $request->getPostVars();

        //valida os campos obrigatorios
        if(!isset($postVars['nome']) or !isset($postVars['mensagem'])){
            throw new \Exception("Os campos 'nome' e 'mensagem' são obrigatórios", 400);
        }

        //busca o depoimento
        $obj = EntityTestimony::getTestimonyById($id);

        //valida a instancia
        if(!$obj instanceof EntityTestimony){
            throw new \Exception("O depoimento ".$id." não foi encontrado", 404);
        }

        $obj->nome = $postVars['nome'];
        $obj->mensagem = $postVars['mensagem'];
        $obj->atualizar();

        //retorna os detalhes do depoimento atualizado
        return [
            'id' => (int)$obj->id,
            'nome' => $obj->nome,
            'mensagem' => $obj->mensagem,
            'data' => $obj->data
        ];
    }

    /**
     * meotodo responsavel por excluir depoimento
     * @param $request
     */
    public static function setDeleteTestimony($request, $id){
        //busca o depoimento
        $obj = EntityTestimony::getTestimonyById($id);

        //valida a instancia
        if(!$obj instanceof EntityTestimony){
            throw new \Exception("O depoimento ".$id." não foi encontrado", 404);
        }

        $obj->excluir();

        //retorna o sucesso da exlusão
        return [
            'sucesso' => true
        ];
    }


}