<?php

require_once 'ftbp-src/entidades/Entidade.php';
require_once 'ftbp-src/daos/impl/DAOUtil.php';
/*
 * Aviso.php
 */

/**
 * Description of Aviso
 *
 * @author Felipe
 * @since Feb 27, 2013
 */

class Evento implements Entidade{

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
    private $data;

    /**
     *
     * @return String 
     */
    private $local;
    
    /**
     *
     * @return String 
     */
    
    private $contato;
    
    /**
     *
     * @var DataTime
     */
    private $dataCriacao;
    
    public function getDataCriacao() {
        return $this->dataCriacao;
    }

    public function setDataCriacao($dataCriacao) {
        $this->dataCriacao = $dataCriacao;
    }

    public function getLocal() {
        return $this->local;
    }

    public function setLocal($local) {
        $this->local = $local;
    }

    public function getContato() {
        return $this->contato;
    }

    public function setContato($contato) {
        $this->contato = $contato;
    }
    
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
    public function getData() {
        return $this->data;
    }

    public function setData($data) {
        $this->data = $data;
    }

}

?>