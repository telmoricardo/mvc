<?php

namespace App\Controller\admin;

use App\Utils\View;

class Home extends  Page
{
    /**
     * metodo responsavel por renderização da view home do painel
     * @param $request
     */
    public static function getHome($request){

        //CONTEUDO DA HOME
        $content = View::render('admin/modules/home/index', [
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('Home > Telmo Ricardo', $content, 'home');

    }


}