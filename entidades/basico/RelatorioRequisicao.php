<?php

require_once 'ftbp-src/entidades/Entidade.php';
require_once 'ftbp-src/entidades/Notificavel.php';
require_once 'ftbp-src/entidades/Pesquisavel.php';
/*
 * AreaCurso.php
 */

/**
 * 
 * @author jefferson
 * @since mar 04, 2013
 */
class Relatorio implements Entidade{
    
    /**
     *
     * @var integer
     */
    private $tipo;
    
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

}

?>
