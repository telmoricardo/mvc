<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Organization;

class About extends Page {

    /*
     * Método responsável por retornar o conteúdo (view) da nossa pagina de sobre
     * @return string
     */
    public static function getAbout(){
        $organization = new Organization();

        $content = View::render('pages/about', [
            "name" => $organization->name,
            "description" => $organization->description,
            "site" => $organization->site
        ]);

        return parent::getPage('Sobre -Telmo Ricardo', $content);
    }

}
