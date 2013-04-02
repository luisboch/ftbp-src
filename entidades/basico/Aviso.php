<?php

require_once 'ftbp-src/entidades/Entidade.php';
//require_once 'ftbp-src/entidades/Notificavel.php';
//require_once 'ftbp-src/entidades/Pesquisavel.php';
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
class Aviso implements Entidade{//, Pesquisavel, Notificavel {

    /**
     *
     * @var integer
     */
    private $id;
    
    /**
     *
     * @var string 
     */
    
    private $nome;
    
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
    
    /**
     *
     * @var array
     */
    private $usuariosAlvo = array();
    
    
    /**
     *
     * @var Usuario 
     */
    private $criadoPor;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getNome() {
        return $this->nome;
    }
    
    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getDescricao() {
        return $this->descricao;
    }
    public function getUsuariosAlvo() {
        return $this->usuariosAlvo;
    }

    public function setUsuariosAlvo($usuariosAlvo) {
        $this->usuariosAlvo = $usuariosAlvo;
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
    
    public function getCriadoPor() {
        return $this->criadoPor;
    }

    public function setCriadoPor(Usuario $criadoPor) {
        $this->criadoPor = $criadoPor;
    }

}

?>