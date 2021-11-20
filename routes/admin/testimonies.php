<?php

use \App\Http\Response;
use \App\Controller\admin;

//ROTA DE LISTAGEM DDE DEPOIMENTOS
$router->get('/admin/testimonies', [
    'middlewares' => [
        'required-admin-login'
    ],
    function ($request) {
        return new Response(200, admin\Testimony::getTestimonies($request));
    }
]);

//ROTA DE CADASTRO DE UM NOVO DEPOIMENTO
$router->get('/admin/testimonies/new', [
    'middlewares' => [
        'required-admin-login'
    ],
    function ($request) {
        return new Response(200, admin\Testimony::getNewTestimonies($request));
    }
]);

//ROTA DE CADASTRO DE UM NOVO DEPOIMENTO (POST)
$router->post('/admin/testimonies/new', [
    'middlewares' => [
        'required-admin-login'
    ],
    function ($request) {
        return new Response(200, admin\Testimony::insertTestimonies($request));
    }
]);

//ROTA DE EDICAO DE UM DEPOIMENTO
$router->get('/admin/testimonies/{id}/edit', [
    'middlewares' => [
        'required-admin-login'
    ],
    function ($request,$id) {
        return new Response(200, admin\Testimony::editTestimonies($request,$id));
    }
]);

//ROTA DE EDICAO DE UM DEPOIMENTO
$router->POST('/admin/testimonies/{id}/edit', [
    'middlewares' => [
        'required-admin-login'
    ],
    function ($request,$id) {
        return new Response(200, admin\Testimony::updateTestimonies($request,$id));
    }
]);

//ROTA DE EXCLUSÃO DE UM DEPOIMENTO
$router->get('/admin/testimonies/{id}/delete', [
    'middlewares' => [
        'required-admin-login'
    ],
    function ($request,$id) {
        return new Response(200, admin\Testimony::deleteTestimonies($request,$id));
    }
]);

//ROTA DE EXCLUSÃO DE UM DEPOIMENTO
$router->post('/admin/testimonies/{id}/delete', [
    'middlewares' => [
        'required-admin-login'
    ],
    function ($request,$id) {
        return new Response(200, admin\Testimony::exclusaoTestimonies($request,$id));
    }
]);