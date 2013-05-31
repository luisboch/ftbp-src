<?php

require_once 'ftbp-src/entidades/Entidade.php';

/*
 * AreaCurso.php
 */

/**
 * 
 * @author jefferson
 * @since mar 04, 2013
 */
class RelatorioRequisicao implements Entidade{
    
    /**
     *
     * @var integer
     */
    private $tipo;
    
    /**
     * 
     *@var usuario 
     */
    private $usuario;
    
    /**
     *
     * @var departamento 
     */
    private $departamento;
    
    /**
     *
     * @var DateTime
     */
    private $dataInicio;
    
    /**
     *
     * @var DateTime
     */
    private $dataFim;
    
    /**
     *
     * @return int
     */
    private $qtde;
    
    public function getTipo() {
        return $this->tipo;
    }

    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    public function getDataInicio() {
        return $this->dataInicio;
    }

    public function setDataInicio($dataInicio) {
        $this->dataInicio = $dataInicio;
    }

    public function getDataFim() {
        return $this->dataFim;
    }

    public function setDataFim($dataFim) {
        $this->dataFim = $dataFim;
    }

    public function getDataCriacao() {
      return null;
    }

    public function getId() {
      return null;  
    }

    public function setId($id) {
      return null;  
    }
    public function getUsuario() {
        return $this->usuario;
    }

    public function setUsuario($usuario) {
        $this->usuario = $usuario;
    }

    public function getDepartamento() {
        return $this->departamento;
    }

    public function setDepartamento($departamento) {
        $this->departamento = $departamento;
    }

    public function getQtde() {
        return $this->qtde;
    }

    public function setQtde($qtde) {
        $this->qtde = $qtde;
    }


}

?>
