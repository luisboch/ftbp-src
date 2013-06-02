<?php

/**
 * @author luis
 */
class CursoRelatorioResultado implements Entidade {
    
    private $curso;
    
    private $area;
    
    private $nivelgraduacao;
    
    private $acessos;
    
    public function getCurso() {
        return $this->curso;
    }

    public function setCurso($curso) {
        $this->curso = $curso;
    }

    public function getArea() {
        return $this->area;
    }

    public function setArea($area) {
        $this->area = $area;
    }

    public function getNivelgraduacao() {
        return $this->nivelgraduacao;
    }

    public function setNivelgraduacao($nivelgraduacao) {
        $this->nivelgraduacao = $nivelgraduacao;
    }

    public function getAcessos() {
        return $this->acessos;
    }

    public function setAcessos($acessos) {
        $this->acessos = $acessos;
    }

    public function getDataCriacao() {
        throw new IllegalStateException("Não implementado");
    }

    public function getId() {
        throw new IllegalStateException("Não implementado");
    }

    public function setId($id) {
        throw new IllegalStateException("Não implementado");
    }
    
}

?>
