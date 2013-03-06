<?php

/*
 * AreaCurso.php
 */

/**
 * 
 * @author jefferson
 * @since mar 04, 2013
 */
class AreaCurso implements Entidade{
    
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
     * @var DateTime
     */
    private $dataCriacao;
    
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

    public function getDataCriacao() {
        return $this->dataCriacao;
    }

    public function setDataCriacao($dataCriacao) {
        $this->dataCriacao = $dataCriacao;
    }
}

?>
