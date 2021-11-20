<?php

require __DIR__.'/includes/app.php';
use \App\Http\Router;
//INICIAR O ROUTE
$router = new Router(URL);

//inclui as rotas de paginas
include __DIR__.'/routes/pages.php';

//inclui as rotas de admin
include __DIR__.'/routes/admin.php';

//inclui as rotas de API
include __DIR__.'/routes/api.php';


//imprime o response da rota
$router->run()->sendResponse();
