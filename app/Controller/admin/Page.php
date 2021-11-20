<?php

namespace App\Controller\admin;

use App\Utils\View;

class Page
{
    /**
     * Modulos disponiveis no painel
     * @var array
     */
    private static $modules = [
        'home' =>[
            'label' => 'Home',
            'link' => URL.'/admin'
        ],
        'testimonies' =>[
            'label' => 'Depoimentos',
            'link' => URL.'/admin/testimonies'
        ],
        'users' =>[
            'label' => 'Usuários',
            'link' => URL.'/admin/users'
        ]
    ];
    /*
     * Método responsável por retornar o conteúdo (view)
     * @return string
     */
    public static function getPage($title, $content){
        return View::render('admin/page', [
            "title" => $title,
            "content" => $content
        ]);
    }

    /**
     * @param $currentModule
     * @return string
     */
    private static function getMenu($currentModule){
        //links do menu
        $links = '';

        //itera os modeulos
        foreach (self::$modules as $hash=>$module):
            $links .= View::render('admin/menu/link', [
               'label' => $module['label'],
               'link' => $module['link'],
               'current' => $hash == $currentModule ? 'text-danger' : ''
            ]);
        endforeach;

        //retorna a renderização do menu
        return View::render('admin/menu/box',[
            'links'=> $links
        ]);
    }

    /**
     * Método responsável por renderizar a view do painel com conteudos dinamicos
     * @param string $title
     * @param string $content
     * @param string $currentModule
     * @return string
     */
    public static function getPanel($title, $content,$currentModule){
        //renderiza a view do painel
        $contentPanel = View::render('admin/painel', [
           'menu' =>   self::getMenu($currentModule),
           'content' => $content
        ]);

        //retona a pagina renderizada
        return self::getPage($title,$contentPanel);
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
            $links .= View::render('admin/pagination/link', [
                "page" => $page['page'],
                "link" => $link,
                'active' => $page['current'] ? 'active' : ''

            ]);
        endforeach;

        //renderiza box paginação
        return View::render('admin/pagination/box', [
            "links" => $links

        ]);

    }
}