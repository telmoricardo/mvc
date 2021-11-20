<?php

namespace App\Controller\admin;

use App\Http\Request;
use App\Model\Entity\User as EntityUser;
use App\Utils\View;
use WilliamCosta\DatabaseManager\Pagination;

class User extends  Page
{
    /**
     * metodo responsavel para obter os usuários
     * @param Request $request
     * @param Pagination $pagination
     * @return string
     */
    private static function getUserItems($request, &$pagination){
        //usuários
        $itens = '';

        //qauntidade de tottal de registros
        $qtdTotal = EntityUser::getUsers(null, null,null,'COUNT(*) as qtd')->fetchObject()->qtd;

        //pagina atual
        $queryParams = $request->getQueryParams();

        $paginaAtual = $queryParams['page'] ?? 1;

        //instancia da paginação
        $pagination = new Pagination($qtdTotal, $paginaAtual, 3);

        //resultados da pagina de usuários
        $results = EntityUser::getUsers(null, 'id DESC', $pagination->getLimit());

        //RENDERIZA O ITEM
        while($obj = $results->fetchObject(EntityUser::class)){
            $itens .= View::render('admin/modules/users/item', [
                'id' => $obj->id,
                'nome' => $obj->nome,
                'email' => $obj->email,
            ]);
        }
        return $itens;
    }

    /**
     * metodo responsavel por renderização da view listagem de usuários
     * @param $request
     */
    public static function getUsers($request){

        //CONTEUDO DA HOME
        $content = View::render('admin/modules/users/index', [
            'itens' => self::getUserItems($request, $pagination),
            "pagination" => parent::getPagination($request, $pagination),
            "status" => self::getStatus($request)
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('usuários > Telmo Ricardo', $content, 'Users');

    }

    /**
     * Metodo responsavel por retornar um formulario de cadastro de um novo usuários
     * @param $request
     */
    public static function getNewUsers($request){
        //CONTEUDO DO FORMULÁRIO
        $content = View::render('admin/modules/users/form', [
            'title' => 'Cadastrar Usuário',
            'nome' => '',
            'email' => '',
            'senha' => '',
            'status' => self::getStatus($request)
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('Cadastrar usuário > Telmo Ricardo', $content, 'Users');
    }

    /**
     * metodo responsavel por cadastrar um depoimento
     * @param $request
     * @return string
     */
    public static function insertUsers($request){
        //pos vars
        $postVars = $request->getPostVars();
        $nome = $postVars['nome'] ?? '';
        $email = $postVars['email'] ?? '';
        $senha = $postVars['senha'] ?? '';

        //valida o e-mail do usuario
        $obj = EntityUser::getUserByEmail($email);

        if($obj instanceof EntityUser){
            $request->getRouter()->redirect('/admin/users/new?status=duplicated');
        }

        //nova instancia de usuario
        $obj = new EntityUser();
        $obj->nome = $nome;
        $obj->email = $email;
        $obj->senha = password_hash($senha, PASSWORD_DEFAULT);
        $obj->cadastrar();

        //retprma a pagína de listagem de usuários
        $request->getRouter()->redirect('/admin/users/'.$obj->id.'/edit?status=created');
    }

    private static function getStatus($request){
        $queryParams = $request->getQueryParams();

        if(!isset($queryParams['status'])) return '';

        //mensgem de status
        switch ($queryParams['status']){
            case 'created':
                return Alert::getSuccess('Usuário criado com sucesso!');
                break;

            case 'updated':
                return Alert::getSuccess('Usuário atualizado com sucesso!');
                break;

            case 'deleted':
                return Alert::getSuccess('Usuário excluído com sucesso!');
                break;

            case 'duplicated':
                return Alert::getError('O e-mail digitado já esta sendo usado por outro usuario!');
                break;
        }
    }

    /**
     * metodo responsavel por o formulario de edicao um depoimento
     * @param $request
     * @return string
     */
    public static function editUsers($request, $id){

        //obtem
        $obj = EntityUser::getUserById($id);

        //valida a instancia
        if(!$obj instanceof EntityUser){
            $request->getRouter()->redirect('/admin/users');
        }

        $content = View::render('admin/modules/users/form', [
            'title' => 'Editar usuário',
            'nome' => $obj->nome,
            'email' => $obj->email,
            'status' => self::getStatus($request)
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('Editar usuário > Telmo Ricardo', $content, 'users');
    }

    /**
     * metodo responsavel por gravar um depoimento
     * @param $request
     * @return string
     */
    public static function updateUsers($request, $id){

        //obtem
        $obj = EntityUser::getUserById($id);

        //valida a instancia
        if(!$obj instanceof EntityUser){
            $request->getRouter()->redirect('/admin/users');
        }

        //post vars
        $postVars = $request->getPostVars();
        $nome = $postVars['nome'] ?? '';
        $email = $postVars['email'] ?? '';
        $senha = $postVars['senha'] ?? '';

        //valida o e-mail do usuario
        $objUser = EntityUser::getUserByEmail($email);

        if($objUser instanceof EntityUser && $objUser->id != $id){
            $request->getRouter()->redirect('/admin/users/'.$id.'/edit?status=duplicated');
        }

        //nova instancia de usuario
        $obj->nome = $nome;
        $obj->email = $email;
        $obj->senha = password_hash($senha, PASSWORD_DEFAULT);
        $obj->atualizar();

        //retprma a pagína de listagem de usuários
        $request->getRouter()->redirect('/admin/users/'.$obj->id.'/edit?status=updated');

    }

    /**
     * metodo responsavel por o formulario de exclusão um usuario
     * @param $request
     * @return string
     */
    public static function deleteUsers($request, $id){
        //obtem
        $obj = EntityUser::getUserById($id);

        //valida a instancia
        if(!$obj instanceof EntityUser){
            $request->getRouter()->redirect('/admin/users');
        }

        $content = View::render('admin/modules/users/delete', [
            'nome' => $obj->nome,
            'email' => $obj->email,
            'senha' => $obj->senha,

        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('Excluir usuario > Telmo Ricardo', $content, 'Users');
    }

    /**
     * metodo responsavel por excluir um depoimento
     * @param $request
     * @return string
     */
    public static function exclusaoUsers($request, $id){

        //obtem o depoimento do banco de dados
        $obj = EntityUser::getUserById($id);

        $obj->excluir();

        //retprma a pagína de listagem de usuários
        $request->getRouter()->redirect('/admin/users?status=deleted');

    }


}