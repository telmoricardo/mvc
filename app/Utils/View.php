<?php

namespace App\Utils;

class View{

    /**
     * variaveis padroes da View
     * @var array
     */
    private static $vars = [];

    /**
     * Método responsavel por definir os dados iniciais da classe
     * @param array $vars
     */
    public static function init($vars = []){
        self::$vars = $vars;
    }

    /**
     * Método responsável por retornar o conteudo de uma view
     * @param [type] $view
     * @return string
     */
    private static function getContentView($view) {
        $file = __DIR__ . '/../../resources/view/' .$view.'.html';
        return file_exists($file) ? file_get_contents($file) : '';
    }

    /**
     * Método responsável por retornar o conteudo renderizado de uma view
     * @param [type] $view
     * @param array $vars (string/numeric)
     * @return string
     */
    public static function render($view, $vars=[]) {
        //conteúdo da view
        $contentView = self::getContentView($view);

        //merge de variaveis da view
        $vars = array_merge(self::$vars, $vars);

        //chaves do array de variáveis
        $keys = array_keys($vars);
        $keys = array_map(function ($item){
            return '{{'.$item.'}}';
        }, $keys);

        // retorna o conteúdo renderizado
        return str_replace($keys, array_values($vars), $contentView);
    }
}
