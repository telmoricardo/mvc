<?php
use \App\Http\Response;
use \App\Controller\admin;

//ROTA ADMIN
$router->get('/admin', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request){
        return new Response(200, admin\Home::getHome($request));
    }
]);
