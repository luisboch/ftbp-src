<?php

require_once 'ftbp-src/entidades/Entidade.php';
require_once 'ftbp-src/entidades/Notificavel.php';
require_once 'ftbp-src/entidades/Pesquisavel.php';
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

class Evento implements Entidade, Notificavel, Pesquisavel{

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
    private $dataEvento;

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
    
    /**
     * 
     * @return DateTime
     */
    public function getDataEvento() {
        return $this->dataEvento;
    }

    public function setDataEvento($dataEvento) {
        $this->dataEvento = $dataEvento;
    }

    public function getBreveDescricao() {
        return "Evento $this->titulo, cadastrado em " . $this->getDataCriacao()->format('d/m/y'). ' às '.$this->getDataCriacao()->format('H:i');
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

    public function getLink() {
        return 'Ver/evento/' . $this->id;
    }

    public function getMensagem($new = false) {
        return ($new ? 'Novo ' : '') . 'Evento ' . ($new ? 'cadastrado' : 'alterado') . ' "' . $this->titulo . '"';
    }

    public function getNotificarEmail() {
         return false;
    }

    public function getPalavrasChave() {
        $arr = array();
        if ($this->titulo != '') {
            $arr = explode(' ', $this->titulo);
        }
        $arr[] = $this->id;
        return $arr;
    }

    public function getTipo() {
        return __CLASS__;
    }

}

?>