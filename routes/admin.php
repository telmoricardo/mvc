<?php

use \App\Http\Response;
use \App\Controller\Admin;

//ROTA ADMIN
$router->get('/admin', [
    function(){
        return new Response(200, ":)");
    }
]);

//ROTA DE LOGIN
$router->get('/admin/login', [
    function($request){
        return new Response(200, Admin\Login::getLogin($request));
    }
]);

//ROTA DE LOGIN
$router->post('/admin/login', [

    function($request){
        //echo password_hash('1234', PASSWORD_DEFAULT);
        return new Response(200, Admin\Login::setLogin($request));
    }
]);

