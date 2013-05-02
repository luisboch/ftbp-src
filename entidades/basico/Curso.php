<?php

/**
 * Description of Curso
 *
 * @author felipe
 */
class Curso implements Entidade{
    
    /**
     * @var integer 
     */
    private $id;
    
    /**
     * @var string 
     */
    
    private $nome;
    
    /**
     *
     * @var String
     */
    
    private $corpoDocente;
    
    /**
     *
     * @var String 
     */
    private $publicoAlvo;
    
    /**
     *
     * @var float
     */
    
    private $valor;
    
    /**
     *
     * @var float
     */
    
    private $duracao;
    
    /**
     *
     * @var String
     */
    
    private $videoApresentacao;
    
    /**
     *
     * @var AreaCurso
     */
    
    private $areaCurso;
    
    /**
     *
     * @var String
     */
    
    private $nivelGraduacao;
    
    /**
     *
     * @var email
     */
    
    private $email;
    
    /**
     *
     * @var String
     */
    
    private $contatoSecretaria;
    
    /**
     *
     * @var Date
     */
    private $dataVestibular;
    
    /**
     *
     * @return String
     */
    private $descricao;
    
    /**
     *
     * @return String
     */
    private $coordenador;
    
    /**
     *
     * @return boolean
     */
    private $excluida;
    
    /**
     *
     * @return Int
     */
    private $credito;
    
    public function getDescricao() {
        return $this->descricao;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    public function getCoordenador() {
        return $this->coordenador;
    }

    public function setCoordenador($coordenador) {
        $this->coordenador = $coordenador;
    }

    public function getExcluida() {
        return $this->excluida;
    }

    public function setExcluida($excluida) {
        $this->excluida = $excluida;
    }

    public function getCredito() {
        return $this->credito;
    }

    public function setCredito($credito) {
        $this->credito = $credito;
    }

        
    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getCorpoDocente() {
        return $this->corpoDocente;
    }

    public function setCorpoDocente($corpoDocente) {
        $this->corpoDocente = $corpoDocente;
    }

    public function getPublicoAlvo() {
        return $this->publicoAlvo;
    }

    public function setPublicoAlvo($publicoAlvo) {
        $this->publicoAlvo = $publicoAlvo;
    }

    public function getValor() {
        return $this->valor;
    }

    public function setValor($valor) {
        $this->valor = $valor;
    }

    public function getDuracao() {
        return $this->duracao;
    }

    public function setDuracao($duracao) {
        $this->duracao = $duracao;
    }

    public function getVideoApresentacao() {
        return $this->videoApresentacao;
    }

    public function setVideoApresentacao($videoApresentacao) {
        $this->videoApresentacao = $videoApresentacao;
    }

    public function getAreaCurso() {
        return $this->areaCurso;
    }

    public function setAreaCurso($areaCurso) {
        $this->areaCurso = $areaCurso;
    }

    public function getNivelGraduacao() {
        return $this->nivelGraduacao;
    }

    public function setNivelGraduacao($nivelGraduacao) {
        $this->nivelGraduacao = $nivelGraduacao;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getContatoSecretaria() {
        return $this->contatoSecretaria;
    }

    public function setContatoSecretaria($contatoSecretaria) {
        $this->contatoSecretaria = $contatoSecretaria;
    }
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getDataVestibular() {
        return $this->dataVestibular;
    }

    public function setDataVestibular($dataVestibular) {
        $this->dataVestibular = $dataVestibular;
    }

    public function getDataCriacao() {
        return $this->dataCriacao;
    }

}

?>
