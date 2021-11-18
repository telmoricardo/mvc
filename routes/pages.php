<?php

use \App\Http\Response;
use \App\Controller\Pages;

//rota home
$router->get('/', [
    function(){
        return new Response(200, Pages\Home::getHome());
    }
]);

//rota sobre
$router->get('/sobre', [
    function(){
        return new Response(200, Pages\About::getAbout());
    }

]);

//rota depoimentos
$router->get('/depoimentos', [
    function($request){
        return new Response(200, Pages\Testimony::getTestimonies($request));
    }

]);
//rota depoimentos (insert)
$router->post('/depoimentos', [
    function($request){
        return new Response(200, Pages\Testimony::insertTestimonies($request));
    }

]);

//rota dinamica
$router->get('/pagina/{idPagina}/{acao}', [
    function($idPagina,$acao){
        return new Response(200, 'Pagina '.$idPagina.' - '.$acao);
    }
]);
