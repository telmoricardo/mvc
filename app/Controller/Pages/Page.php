<?php

namespace App\Controller\Pages;

use \App\Utils\View;


class Page{

    public static function getHeader(){
        return View::render('pages/header');
    }

    public static function getFooter(){
        return View::render('pages/footer');
    }

    /*
     * Método responsável por retornar o conteúdo (view) da nossa home
     * @return string
     */
    public static function getPage($title, $content){
        return View::render('pages/page', [
            "title" => $title,
            "header" => self::getHeader(),
            "content" => $content,
            "footer" => self::getFooter()
        ]);
    }

    public static function getPagination($request, $obPagination){
        //paginas
        $pages = $obPagination->getPages();

        //verifica a quantidade de paginas
        if(count($pages) <= 1) return '';

        //links
        $links = '';

        //url autal sem (gets)
        $url = $request->getRouter()->getCurrentUrl();

        //get
        $queryParams = $request->getQueryParams();

        //renderiza os links
        foreach ($pages as $page):

            //altera a páina
            $queryParams['page'] = $page['page'];

            //link
            $link = $url.'?'.http_build_query($queryParams);

            //view
            $links .= View::render('pages/pagination/link', [
                "page" => $page['page'],
                "link" => $link,
                'active' => $page['current'] ? 'active' : ''

            ]);
        endforeach;

        //renderiza box paginação
        return View::render('pages/pagination/box', [
            "links" => $links

        ]);

    }

}
