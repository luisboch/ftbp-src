<?php

require_once 'ftbp-src/entidades/Entidade.php';
require_once 'ftbp-src/entidades/Notificavel.php';
require_once 'ftbp-src/entidades/Pesquisavel.php';
require_once 'ftbp-src/daos/impl/DAOUtil.php';
/*
 * Departamento.php
 */

/**
 * Description of Departamento
 *
 * @author Luis
 * @since Feb 27, 2013
 */
class Aviso implements Entidade, Pesquisavel, Notificavel {

    /**
     *
     * @var integer
     */
    private $id;
    
    /**
     *
     * @var string 
     */
    
    private $titulo;
    
    /**
     *
     * @var string
     */
    private $descricao; 
    

    /**
     *
     * @var DateTime
     */
    private $dataCriacao;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getTitulo() {
        return $this->titulo;
    }
    
    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    public function getDescricao() {
        return $this->descricao;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    
    public function getDataCriacao() {
        return $this->dataCriacao;
    }

    public function setDataCriacao($dataCriacao) {
        $this->dataCriacao = $dataCriacao;
    }

    public function getData() {
        return new DateTime();
    }

    public function getDataExpiracao() {
        return null;
    }

    public function getEntidade() {
        return $this;
    }


    public function getNotificarEmail() {
        return false;
    }

    public function getPalavrasChave() {
        $arr = array();
        if ($this->nome != '') {
            $arr = explode(' ', $this->nome);
        }
        $arr[] = $this->id;
        return $arr;
    }

    public function getTipo() {
        return __CLASS__;
    }

}

?>
