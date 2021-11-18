<?php

require __DIR__.'/../vendor/autoload.php';


use \App\Utils\View;
use \WilliamCosta\DotEnv\Environment;
use WilliamCosta\DatabaseManager\Database;

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