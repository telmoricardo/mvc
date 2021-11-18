<?php

namespace App\Model\Entity;

use \WilliamCosta\DatabaseManager\Database;

class Testimony
{
    public $id;
    public $nome;
    public $mensagem;
    public $data;


    public function cadastrar(){
        $this->data = date('Y-m-d H:i:s');

        $this->id = (new Database('depoimentos'))->insert([
            'nome' => $this->nome,
            'mensagem' => $this->mensagem,
            'data' => $this->data
        ]);
        //sucesso
        return true;
    }

    /**
     * Metodo responseval por retornas depoimnentps
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $field
     * @return \PDOStatement
     */
    public function getTestimonies($where=null, $order=null, $limit= null, $fields = '*'){
        return (new Database('depoimentos'))->select($where,$order,$limit,$fields);
    }

}