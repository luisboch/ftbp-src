<?php

require_once 'ftbp-src/entidades/basico/Requisicao.php';

/**
 * 
 * RequisicaoIteracao.php
 */

/**
 * Description of RequisicaoIteracao
 *
 * @author luis
 */
class RequisicaoIteracao {
    /**
     *
     * @var Usuario
     */
    private $usuario;
    /**
     *
     * @var string
     */
    private $mensagem;
    
    /**
     *
     * @var DateTime
     */
    private $dataCriacao;
    /**
     *
     * @var Requisicao
     */
    private $requisicao;
    

    /**
     * 
     * @return Usuario
     */
    public function getUsuario() {
        return $this->usuario;
    }

    /**
     * 
     * @param Usuario $usuario
     */
    public function setUsuario(Usuario $usuario) {
        $this->usuario = $usuario;
    }

    /**
     * 
     * @return string
     */
    public function getMensagem() {
        return $this->mensagem;
    }

    /**
     * 
     * @param string $mensagem
     */
    public function setMensagem($mensagem) {
        $this->mensagem = $mensagem;
    }

    /**
     * 
     * @return DateTime
     */
    public function getDataCriacao() {
        return $this->dataCriacao;
    }

    /**
     * 
     * @param DateTime $dataCriacao
     */
    public function setDataCriacao(DateTime $dataCriacao) {
        $this->dataCriacao = $dataCriacao;
    }

    /**
     * 
     * @return Requisicao
     */
    public function getRequisicao() {
        return $this->requisicao;
    }

    /**
     * 
     * @param Requisicao $requisicao
     */
    public function setRequisicao(Requisicao $requisicao) {
        $this->requisicao = $requisicao;
    }
}

?>