<?php

namespace App\Model\Entity;
use \WilliamCosta\DatabaseManager\Database;

class User
{
    public $id;
    public $nome;
    public $email;
    public $senha;

    /**
     * metodo responsavel por cadastrar
     * @return bool
     */
    public function cadastrar(){

        $this->id = (new Database('usuarios'))->insert([
            'nome' => $this->nome,
            'email' => $this->email,
            'senha' => $this->senha
        ]);
        //sucesso
        return true;
    }

    /**
     * metodo responsavel por atualizar
     * @return bool
     */
    public function atualizar(){

        return  (new Database('usuarios'))->update('id = '.$this->id, [
            'nome' => $this->nome,
            'email' => $this->email,
            'senha' => $this->senha
        ]);

    }

    /**
     * metodo responsavel por excluir
     * @return bool
     */
    public function excluir(){
        return  (new Database('usuarios'))->delete('id = '.$this->id);
    }

    public static function getUserByEmail($email){
        return (new Database('usuarios'))->select('email = "'.$email.'"')->fetchObject(self::class);
    }

    /**
     * Metodo responseval por retornas usuarios
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $field
     * @return \PDOStatement
     */
    public function getUsers($where=null, $order=null, $limit= null, $fields = '*'){
        return (new Database('usuarios'))->select($where,$order,$limit,$fields);
    }

    /**
     * Metodo responseval por retornas um usuario com base no seu ID
     * @param integer $id
     */
    public function getUserById($id){
        return self::getUsers('id = '.$id)->fetchObject(self::class);
    }
}