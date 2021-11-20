<?php

namespace App\Controller\admin;

use App\Utils\View;

class Alert
{
    /**
     * Metodo responsavel por retornar uma mensagem de erro
     * @param $message
     * @return string
     */
    public static function getError($message){
        return View::render('admin/alert/status',[
            'tipo' => 'danger',
            'mensagem' => $message
        ]);
    }

    /**
     * Metodo responsavel por retornar uma mensagem de sucesso
     * @param $message
     * @return string
     */
    public static function getSuccess($message){
        return View::render('admin/alert/status',[
           'tipo' => 'success',
           'mensagem' => $message
        ]);
    }
}