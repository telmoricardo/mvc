<?php

namespace App\Model\Entity;

use \WilliamCosta\DatabaseManager\Database;

class Testimony
{
    public $id;
    public $nome;
    public $mensagem;
    public $data;

    /**
     * metodo responsavel por cadastrar
     * @return bool
     */
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
     * metodo responsavel por atualizar
     * @return bool
     */
    public function atualizar(){

        return  (new Database('depoimentos'))->update('id = '.$this->id, [
            'nome' => $this->nome,
            'mensagem' => $this->mensagem
        ]);

    }

    /**
     * metodo responsavel por excluir
     * @return bool
     */
    public function excluir(){
        return  (new Database('depoimentos'))->delete('id = '.$this->id);
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

    /**
     * Metodo responseval por retornas um depoimento com base no seu ID
     * @param integer $id
     */
    public function getTestimonyById($id){
        return self::getTestimonies('id = '.$id)->fetchObject(self::class);
    }

}