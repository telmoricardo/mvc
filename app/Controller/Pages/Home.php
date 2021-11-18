<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Organization;

class Home extends Page {

    /*
     * Método responsável por retornar o conteúdo (view) da nossa home
     * @return string
     */
    public static function getHome(){
        $organization = new Organization();

        $content = View::render('pages/home', [
            "name" => $organization->name
        ]);

        return parent::getPage('HOME - Telmo Ricardo', $content);
    }

}
