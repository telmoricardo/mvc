<?php

namespace App\Controller\admin;

use App\Http\Request;
use App\Model\Entity\Testimony as EntityTestimony;
use App\Utils\View;
use WilliamCosta\DatabaseManager\Pagination;

class Testimony extends  Page
{
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
        $pagination = new Pagination($qtdTotal, $paginaAtual, 3);

        //resultados da pagina de depoimentos
        $results = EntityTestimony::getTestimonies(null, 'id DESC', $pagination->getLimit());

        //RENDERIZA O ITEM
        while($obj = $results->fetchObject(EntityTestimony::class)){
            $itens .= View::render('admin/modules/testimonies/item', [
                'id' => $obj->id,
                'nome' => $obj->nome,
                'mensagem' => $obj->mensagem,
                'data' => date('d/m/Y', strtotime($obj->data))
            ]);
        }
        return $itens;
    }

    /**
     * metodo responsavel por renderização da view listagem de depoimentos
     * @param $request
     */
    public static function getTestimonies($request){

        //CONTEUDO DA HOME
        $content = View::render('admin/modules/testimonies/index', [
            'itens' => self::getTestimonyItems($request, $pagination),
            "pagination" => parent::getPagination($request, $pagination),
            "status" => self::getStatus($request)
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('Depoimentos > Telmo Ricardo', $content, 'testimonies');

    }

    /**
     * Metodo responsavel por retornar um formulario de cadastro de um novo depoimentos
     * @param $request
     */
    public static function getNewTestimonies($request){
        //CONTEUDO DO FORMULÁRIO
        $content = View::render('admin/modules/testimonies/form', [
            'title' => 'Cadastrar depoimneto',
            'nome' => '',
            'mensagem' => '',
            'status' => ''
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('Cadastrar depoimentos > Telmo Ricardo', $content, 'testimonies');
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
        $request->getRouter()->redirect('/admin/testimonies/'.$obj->id.'/edit?status=created');
    }

    private static function getStatus($request){
        $queryParams = $request->getQueryParams();

        if(!isset($queryParams['status'])) return '';

        //mensgem de status
        switch ($queryParams['status']){
            case 'created':
                return Alert::getSuccess('Depoimento criado com sucesso!');
                break;

            case 'updated':
                return Alert::getSuccess('Depoimento atualizado com sucesso!');
                break;
            case 'deleted':
                return Alert::getSuccess('Depoimento excluído com sucesso!');
                break;
        }
    }

    /**
     * metodo responsavel por o formulario de edicao um depoimento
     * @param $request
     * @return string
     */
    public static function editTestimonies($request, $id){

        //obtem
        $obj = EntityTestimony::getTestimonyById($id);

        //valida a instancia
        if(!$obj instanceof EntityTestimony){
            $request->getRouter()->redirect('/admin/testimonies');
        }

        $content = View::render('admin/modules/testimonies/form', [
            'title' => 'Editar depoimento',
            'nome' => $obj->nome,
            'mensagem' => $obj->mensagem,
            'status' => self::getStatus($request)
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('Editar depoimento > Telmo Ricardo', $content, 'testimonies');
    }

    /**
     * metodo responsavel por gravar um depoimento
     * @param $request
     * @return string
     */
    public static function updateTestimonies($request, $id){

        //obtem o depoimento do banco de dados
        $obj = EntityTestimony::getTestimonyById($id);

        //valida a instancia
        if(!$obj instanceof EntityTestimony){
            $request->getRouter()->redirect('/admin/testimonies');
        }

        $postVars = $request->getPostVars();

        $obj->nome = $postVars['nome'] ??  $obj->nome;
        $obj->mensagem = $postVars['mensagem'] ?? $obj->mensagem;
        $obj->atualizar();

        //retprma a pagína de listagem de depoimentos
        $request->getRouter()->redirect('/admin/testimonies/'.$obj->id.'/edit?status=updated');

    }

    /**
     * metodo responsavel por o formulario de exclusão um depoimento
     * @param $request
     * @return string
     */
    public static function deleteTestimonies($request, $id){
        //obtem
        $obj = EntityTestimony::getTestimonyById($id);

        //valida a instancia
        if(!$obj instanceof EntityTestimony){
            $request->getRouter()->redirect('/admin/testimonies');
        }

        $content = View::render('admin/modules/testimonies/delete', [
            'nome' => $obj->nome,
            'mensagem' => $obj->mensagem

        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('Excluir depoimento > Telmo Ricardo', $content, 'testimonies');
    }

    /**
     * metodo responsavel por excluir um depoimento
     * @param $request
     * @return string
     */
    public static function exclusaoTestimonies($request, $id){

        //obtem o depoimento do banco de dados
        $obj = EntityTestimony::getTestimonyById($id);

        $obj->excluir();

        //retprma a pagína de listagem de depoimentos
        $request->getRouter()->redirect('/admin/testimonies?status=deleted');

    }


}