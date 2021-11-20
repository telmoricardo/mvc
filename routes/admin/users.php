<?php

use \App\Http\Response;
use \App\Controller\admin;

//ROTA DE LISTAGEM DE USUÁRIO
$router->get('/admin/users', [
    'middlewares' => [
        'required-admin-login'
    ],
    function ($request) {
        return new Response(200, admin\User::getUsers($request));
    }
]);

//ROTA DE CADASTRO DE UM NOVO USUÁRIO
$router->get('/admin/users/new', [
    'middlewares' => [
        'required-admin-login'
    ],
    function ($request) {
        return new Response(200, admin\User::getNewUsers($request));
    }
]);

//ROTA DE CADASTRO DE UM NOVO USUÁRIO (POST)
$router->post('/admin/users/new', [
    'middlewares' => [
        'required-admin-login'
    ],
    function ($request) {
        return new Response(200, admin\User::insertUsers($request));
    }
]);

//ROTA DE EDICAO DE UM USUÁRIO
$router->get('/admin/users/{id}/edit', [
    'middlewares' => [
        'required-admin-login'
    ],
    function ($request,$id) {
        return new Response(200, admin\User::editUsers($request,$id));
    }
]);

//ROTA DE EDICAO DE UM USUÁRIO
$router->POST('/admin/users/{id}/edit', [
    'middlewares' => [
        'required-admin-login'
    ],
    function ($request,$id) {
        return new Response(200, admin\User::updateUsers($request,$id));
    }
]);

//ROTA DE EXCLUSÃO DE UM USUÁRIO
$router->get('/admin/users/{id}/delete', [
    'middlewares' => [
        'required-admin-login'
    ],
    function ($request,$id) {
        return new Response(200, admin\User::deleteUsers($request,$id));
    }
]);

//ROTA DE EXCLUSÃO DE UM USUÁRIO
$router->post('/admin/users/{id}/delete', [
    'middlewares' => [
        'required-admin-login'
    ],
    function ($request,$id) {
        return new Response(200, admin\User::exclusaoUsers($request,$id));
    }
]);