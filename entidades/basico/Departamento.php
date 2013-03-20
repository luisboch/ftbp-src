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
class Departamento implements Entidade, Pesquisavel, Notificavel {

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

    public function getBreveDescricao() {
        return "Departamento $this->nome, cadastrado em " . $this->getDataCriacao()->format('d/M/y');
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
        return 'DepartamentoController/item/' . $this->id;
    }

    public function getMensagem() {
        return ($this->id == null ? 'Novo ' : '') . 'Departamento ' . ($this->id == null ? 'cadastrado' : 'alterado') . ' "' . $this->nome . '"';
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

    public function getTitulo() {
        return "Departamento " . $this->nome;
    }

}

?>
