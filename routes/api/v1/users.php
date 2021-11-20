<?php

use \App\Http\Response;
use \App\Controller\Api\User;


//ROTA RAIZ
$router->get('/api/v1/users', [
    'middlewares' => [
      'api',
      'user-basic-auth'
    ],
    function($request){
        return new Response(200, User::getUsers($request),'application/json');
    }
]);

//ROTA DE CONSULTA DE USUARIO ATUAL
$router->get('/api/v1/users/me', [
    'middlewares' => [
        'api',
        'jwt-auth'
    ],
    function($request){
        return new Response(200, User::getCurrentUsers($request),'application/json');
    }
]);

//ROTA DE CONSULTA INDIVIDUAL DE DEPOIMENTOS
$router->get('/api/v1/users/{id}', [
    'middlewares' => [
        'api',
        'user-basic-auth'
    ],
    function($request,$id){
        return new Response(200, User::getUserById($request, $id),'application/json');
    }
]);

//ROTA DE CADASTRO DE DEPOIMENTOS
$router->post('/api/v1/users', [
    'middlewares' => [
        'api',
        'jwt-auth'
    ],
    function($request){
        return new Response(201, User::setNewUser($request),'application/json');
    }
]);

//ROTA DE ATUALIZAÇÃO DE DEPOIMENTOS
$router->put('/api/v1/users/{id}', [
    'middlewares' => [
        'api',
        'user-basic-auth'
    ],
    function($request, $id){
        return new Response(200, User::setEditUser($request, $id),'application/json');
    }
]);

//ROTA DE EXCLUSÃO DE DEPOIMENTOS
$router->delete('/api/v1/users/{id}', [
    'middlewares' => [
        'api',
        'user-basic-auth'
    ],
    function($request, $id){
        return new Response(200, User::setDeleteUser($request, $id),'application/json');
    }
]);