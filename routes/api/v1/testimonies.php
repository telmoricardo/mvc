<?php

use \App\Http\Response;
use \App\Controller\Api\Testimony;


//ROTA RAIZ
$router->get('/api/v1/testimonies', [
    'middlewares' => [
      'api'
    ],
    function($request){
        return new Response(200, Testimony::getTestimonies($request),'application/json');
    }
]);

//ROTA DE CONSULTA INDIVIDUAL DE DEPOIMENTOS
$router->get('/api/v1/testimonies/{id}', [
    'middlewares' => [
        'api'
    ],
    function($request,$id){
        return new Response(200, Testimony::getTestimonyById($request, $id),'application/json');
    }
]);

//ROTA DE CADASTRO DE DEPOIMENTOS
$router->post('/api/v1/testimonies', [
    'middlewares' => [
        'api',
        'user-basic-auth'
    ],
    function($request){
        return new Response(201, Testimony::setNewTestimony($request),'application/json');
    }
]);

//ROTA DE ATUALIZAÇÃO DE DEPOIMENTOS
$router->put('/api/v1/testimonies/{id}', [
    'middlewares' => [
        'api',
        'user-basic-auth'
    ],
    function($request, $id){
        return new Response(200, Testimony::setEditTestimony($request, $id),'application/json');
    }
]);

//ROTA DE EXCLUSÃO DE DEPOIMENTOS
$router->delete('/api/v1/testimonies/{id}', [
    'middlewares' => [
        'api',
        'user-basic-auth'
    ],
    function($request, $id){
        return new Response(200, Testimony::setDeleteTestimony($request, $id),'application/json');
    }
]);