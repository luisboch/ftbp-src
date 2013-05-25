<?php

require_once 'ftbp-src/entidades/Entidade.php';
require_once 'ftbp-src/entidades/Pesquisavel.php';

/**
 * Description of Pesquisa
 *
 * @author luis
 */
class Pesquisa implements Entidade, Pesquisavel{
    private $id;
    private $breveDescricao;
    private $entidadeId;
    private $link;
    private $titulo;
    private $tipo;
    
    function __construct($breveDescricao, $entidadeId, $link, $titulo, $tipo) {
        $this->breveDescricao = $breveDescricao;
        $this->entidadeId = $entidadeId;
        $this->link = $link;
        $this->titulo = $titulo;
        $this->tipo = $tipo;
    }
    
    public function getBreveDescricao() {
        return $this->breveDescricao;
    }

    public function setBreveDescricao($breveDescricao) {
        $this->breveDescricao = $breveDescricao;
    }

    public function getEntidadeId() {
        return $this->entidadeId;
    }

    public function setEntidadeId($entidadeId) {
        $this->entidadeId = $entidadeId;
    }

    public function getLink() {
        return $this->link;
    }

    public function setLink($link) {
        $this->link = $link;
    }

    public function getTitulo() {
        return $this->titulo;
    }

    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    public function getTipo() {
        return $this->tipo;
    }

    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }
    public function getDataCriacao() {
        
    }

    public function getId() {
        return $this->id;
    }

    /**
     * 
     * @param type $id
     * @throws IllegalStateException always
     */
    public function setId($id) {
        throw new IllegalStateException("Cant set id on this entity");
    }

    public function getEntidade() {
        return null;
    }
    
    public function getPalavrasChave() {
        return array();
    }

}

?>
