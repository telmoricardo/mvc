<?php

require __DIR__.'/includes/app.php';
use \App\Http\Router;
//INICIAR O ROUTE
$router = new Router(URL);

//inclui as rotas de paginas
include __DIR__.'/routes/pages.php';

//imprime o response da rota
$router->run()->sendResponse();
