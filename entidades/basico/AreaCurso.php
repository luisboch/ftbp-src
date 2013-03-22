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
class AreaCurso implements Entidade, Pesquisavel, Notificavel{
    
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

    /**
     * ************ métodos de retorno da pesquisa - inicio
     */
    public function getBreveDescricao() {
        return 'Area '.$this->nome.', cadastrada em '.$this->getDataCriacao()->format('d/M/y');
    }

    public function getEntidade() {
        return $this;
    }

    public function getLink() {
        return 'AreaController/item/'.$this->getId();
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

    public function getTitulo() {
        return "Area " . $this->nome;
    }
    
    /**
     * ************ métodos de retorno da pesquisa - fim
     */
    
    /**
     * ************ métodos de retorno da notificacao - inicio
     */
    

    public function getData() {
        return new DateTime();
    }

    public function getDataExpiracao() {
        return null;
    }

    public function getMensagem($new = false) {
        return ($new ? 'Nova ' : '') . 'Area ' . ($new ? 'cadastrada' : 'alterada') . ' "' . $this->nome . '"';
    }

    public function getNotificarEmail() {
        return false;
    }
    /**
     * ************ métodos de retorno da notificacao - fim
     */
    
}

?>
