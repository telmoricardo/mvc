<?php

use \App\Http\Response;
use \App\Controller\admin;

//ROTA DE LOGIN
$router->get('/admin/login', [
    'middlewares' => [
        'required-admin-logout'
    ],
    function($request){
        return new Response(200, admin\Login::getLogin($request));
    }
]);

//ROTA DE LOGIN
$router->post('/admin/login', [
    'middlewares' => [
        'required-admin-logout'
    ],
    function($request){
        //echo password_hash('1234', PASSWORD_DEFAULT);
        return new Response(200, admin\Login::setLogin($request));
    }
]);

//ROTA LOGOUT
$router->get('/admin/logout', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request){
        return new Response(200, admin\Login::setLogout($request));
    }
]);

