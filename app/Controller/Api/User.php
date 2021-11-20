<?php

namespace App\Controller\Api;

use App\Http\Request;
use App\Model\Entity\User as EntityUser;
use WilliamCosta\DatabaseManager\Pagination;

class User extends Api
{
    /**
     * metodo responsavel para obter os usuarios
     * @param Request $request
     * @param Pagination $pagination
     * @return string
     */
    private static function getUserItems($request, &$pagination){
        //depoimentos
        $itens = [];

        //qauntidade de tottal de registros
        $qtdTotal = EntityUser::getUsers(null, null,null,'COUNT(*) as qtd')->fetchObject()->qtd;

        //pagina atual
        $queryParams = $request->getQueryParams();

        $paginaAtual = $queryParams['page'] ?? 1;

        //instancia da paginação
        $pagination = new Pagination($qtdTotal, $paginaAtual, 3);

        //resultados da pagina de depoimentos
        $results = EntityUser::getUsers(null, 'id DESC', $pagination->getLimit());

        //RENDERIZA O ITEM
        while($obj = $results->fetchObject(EntityUser::class)){
            $itens[] = [
                'id' => (int)$obj->id,
                'nome' => $obj->nome,
                'email' => $obj->email
            ];
        }
        return $itens;
    }

    /**
     * metodo responsavel por retornar os usuarios cadastrados
     * @param Request $request
     * @return array
     */
    public static function getUsers($request){
        return[
            'usuarios' => self::getUserItems($request, $pagination),
            'paginacao' => parent::getPagination($request, $pagination),
        ];

    }

    /**
     * metodo responsavel por retornar o usuario atualmente conectado
     * @param $request
     */
    public static function getCurrentUsers($request){
        //usuario atual
        $obj = $request->user;

        return [
            'id' => (int)$obj->id,
            'nome' => $obj->nome,
            'email' => $obj->email
        ];
    }

    /**
     * metodo responsavel por retornar os detalhes de um depoimento
     * @param $request
     * @param $id
     * @return array
     */
    public static function getUserById($request, $id){
        //busca depoimento
        $obj = EntityUser::getUserById($id);

        //valida a instancia
        if(!$obj instanceof EntityUser){
            throw new \Exception("O depoimento ".$id." não foi encontrado", 404);
        }

        return [
            'id' => (int)$obj->id,
            'nome' => $obj->nome,
            'email' => $obj->email
        ];
    }

    /**
     * meotodo responsavel por cadastrar depoimento
     * @param $request
     */
    public static function setNewUser($request){
        //postvars
        $postVars = $request->getPostVars();

        //valida os campos obrigatorios
        if(!isset($postVars['nome']) or !isset($postVars['email']) or !isset($postVars['senha']) ) {
            throw new \Exception("Os campos 'nome', 'email' e 'senha' são obrigatórios", 400);
        }

        //valida a duplicação de usuario
        $objUserEmail = EntityUser::getUserByEmail($postVars['email']);
        if($objUserEmail instanceof EntityUser){
            throw new \Exception('E-mail '.$postVars['email'].' já foi cadastrado por outro usuario', 400);
        }

        //novo usuario
        $obj = new EntityUser();
        $obj->nome = $postVars['nome'];
        $obj->email = $postVars['email'];
        $obj->senha = password_hash($postVars['senha'], PASSWORD_DEFAULT);
        $obj->cadastrar();

        //retorna os detalhes do cadastro
        return [
            'id' => (int)$obj->id,
            'nome' => $obj->nome,
            'email' => $obj->email
        ];
    }

    /**
     * meotodo responsavel por atualizar depoimento
     * @param $request
     */
    public static function setEditUser($request, $id){
        //postvars
        $postVars = $request->getPostVars();

        //valida os campos obrigatorios
        if(!isset($postVars['nome']) or !isset($postVars['email']) or !isset($postVars['senha']) ) {
            throw new \Exception("Os campos 'nome', 'email' e 'senha' são obrigatórios", 400);
        }

        //busca o depoimento
        $obj = EntityUser::getUserById($id);

        //valida a instancia
        if(!$obj instanceof EntityUser){
            throw new \Exception("O usuairo ".$id." não foi encontrado", 404);
        }

        //valida a duplicação de usuario
        $objUserEmail = EntityUser::getUserByEmail($postVars['email']);
        if($objUserEmail instanceof EntityUser && $objUserEmail->id != $obj->id){
            throw new \Exception('E-mail '.$postVars['email'].' já foi cadastrado por outro usuario', 400);
        }

        $obj->nome = $postVars['nome'];
        $obj->email = $postVars['email'];
        $obj->senha = password_hash($postVars['senha'], PASSWORD_DEFAULT);
        $obj->atualizar();

        //retorna os detalhes do depoimento atualizado
        return [
            'id' => (int)$obj->id,
            'nome' => $obj->nome,
            'email' => $obj->email
        ];
    }

    /**
     * meotodo responsavel por excluir depoimento
     * @param $request
     */
    public static function setDeleteUser($request, $id){
        //busca o depoimento
        $obj = EntityUser::getUserById($id);

        //valida a instancia
        if(!$obj instanceof EntityUser){
            throw new \Exception("O Usuário ".$id." não foi encontrado", 404);
        }

        //impede a exclusão do usuario logado
        if($obj->id == $request->user->id){
            throw new \Exception("Não é possivel excluir o cadastro conectado", 404);
        }

        $obj->excluir();

        //retorna o sucesso da exlusão
        return [
            'sucesso' => true
        ];
    }


}