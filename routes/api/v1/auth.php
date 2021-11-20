<?php

use \App\Http\Response;
use \App\Controller\Api\Auth;


//ROTA AUTORIZAÇÃO DA API
$router->post('/api/v1/auth', [
    function($request){
        return new Response(201, Auth::generateToken($request),'application/json');
    }
]);