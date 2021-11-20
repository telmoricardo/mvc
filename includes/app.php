<?php

require __DIR__.'/../vendor/autoload.php';


use \App\Utils\View;
use \WilliamCosta\DotEnv\Environment;
use WilliamCosta\DatabaseManager\Database;
use \App\Http\Middleware\Queue as MiddlewareQueue;

//carrega variaveis de ambiente
Environment::load(__DIR__.'/../');

//define as configuraçõs do banco de dados
Database::config(
    getenv('DB_HOST'),
    getenv('DB_NAME'),
    getenv('DB_USER'),
    getenv('DB_PASS'),
    getenv('DB_PORT')
);

//define a constante da url
define('URL', getenv('URL'));

//DEFINIR OS VALOR PADRÃO DAS VARIAVIES
View::init([
    'URL' => URL
]);

//definir o mapeamento de Middleware
MiddlewareQueue::setMap([
    'maintenance'           => \App\Http\Middleware\Maintenance::class,
    'required-admin-logout' => \App\Http\Middleware\RequireAdmingLogout::class,
    'required-admin-login'  => \App\Http\Middleware\RequireAdmingLogin::class,
    'api'                   => \App\Http\Middleware\Api::class,
    'user-basic-auth'       => \App\Http\Middleware\UserBasicAuth::class,
    'jwt-auth'              => \App\Http\Middleware\JWTAuth::class,
]);

//definir o mapeamento de Middleware padroes para todas as rotas
MiddlewareQueue::setDefault([
    'maintenance'
]);