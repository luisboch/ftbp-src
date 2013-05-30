<?php

require_once 'ftbp-src/entidades/Entidade.php';
require_once 'ftbp-src/entidades/basico/RequisicaoIteracao.php';
/**
 * 
 * Requisicao.php
 */

/**
 * Description of Requisicao
 *
 * @author luis
 */
class Requisicao implements Entidade{
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
     * @var Usuario
     */
    private $usuario;
    /**
     *
     * @var Usuario
     */
    private $criadoPor;
    
    /**
     *
     * @var Usuario
     */
    private $fechadoPor;
    
    /**
     *
     * @var DateTime 
     */
    private $dataFechamento;
    /**
     *
     * @var RequisicaoIteracao[]
     */
    private $iteracoes;
    
    /**
     * 
     * @return DateTime
     */
    private $dataCriacao;
    
    /**
     *
     * @var string
     */
    private $status;
    
    /**
     *
     * @var string
     */
    private $prioridade;
    
    public function getId() {
        return $this->id;
    }

    /**
     * 
     * @param integer $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * 
     * @return string
     */
    public function getTitulo() {
        return $this->titulo;
    }

    /**
     * 
     * @param string $titulo
     */
    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    /**
     * 
     * @return string
     */
    public function getDescricao() {
        return $this->descricao;
    }

    /**
     * 
     * @param string $descricao
     */
    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

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
     * @return Usuario
     */
    public function getCriadoPor() {
        return $this->criadoPor;
    }

    /**
     * 
     * @param Usuario $criadoPor
     */
    public function setCriadoPor(Usuario $criadoPor) {
        $this->criadoPor = $criadoPor;
    }

    /**
     * 
     * @return RequisicaoIteracao[]
     */
    public function getIteracoes() {
        return $this->iteracoes;
    }

    public function setIteracoes($iteracoes) {
        $this->iteracoes = $iteracoes;
    }
    
    /**
     * @param RequisicaoIteracao $it
     */
    public function addIteracao(RequisicaoIteracao $it){
        // Caso ainda não carregou as iterações, carrega, para não perder integridade.
        $this->getIteracoes();
        
        // Adiciona a iteração à lista de iterações
        $this->iteracoes[] = $it;
    }


    /**
     * @return DateTime
     */
    public function getDataCriacao() {
        return $this->dataCriacao;
    }

    /**
     * 
     * @param DateTime $dataCriacao
     */
    public function setDataCriacao($dataCriacao) {
        $this->dataCriacao = $dataCriacao;
    }
    
    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }
    
    /**
     * 
     * @return string
     */
    public function getPrioridade() {
        return $this->prioridade;
    }

    /**
     * 
     * @param string $prioridade
     */
    public function setPrioridade($prioridade) {
        $this->prioridade = $prioridade;
    }
    
    /**
     * 
     * @return Usuario
     */
    public function getFechadoPor() {
        return $this->fechadoPor;
    }

    public function setFechadoPor(Usuario $fechadoPor) {
        $this->fechadoPor = $fechadoPor;
    }

    /**
     * @return DateTime
     */
    public function getDataFechamento() {
        return $this->dataFechamento;
    }

    public function setDataFechamento($dataFechamento) {
        if($dataFechamento != null && !$dataFechamento instanceof DateTime){
            $encontrado = is_object($dataFechamento) ? get_class($dataFechamento) : (is_string($dataFechamento)?$dataFechamento:"");
            throw new InvalidArgumentException("Expecting instance of DateTime, found: ".$encontrado);
        }
        $this->dataFechamento = $dataFechamento;
    }
    
}

?>
