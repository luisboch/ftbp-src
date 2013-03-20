<?php
require_once 'ftbp-src/entidades/Entidade.php';
require_once 'ftbp-src/entidades/Pesquisavel.php';
require_once 'ftbp-src/entidades/basico/TipoUsuario.php';
require_once 'ftbp-src/entidades/basico/Departamento.php';
/**
 * Classe básica de definicao de usuário
 * @author Luis
 */

class Usuario implements Entidade, Pesquisavel, Notificavel{

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $nome;
    
    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $senha;

    /**
     * @var List<Notificavel>
     */
    private $notificacoes;
    
    /**
     * @var DateTime
     */
    private $dataCriacao;
    
    /**
     * @var Departamento
     */
    private $departamento;
    
    /**
     * @var boolean
     */
    private $responsavel = false;
    
    /**
     *
     * @var integer
     */
    private $tipoUsuario;
    /**
     * @return integer
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * @param integer $id
     */
    public function setId($id) {
        $this->id = $id;
    }
    /**
     * 
     * @return string
     */
    public function getNome() {
        return $this->nome;
    }
    
    /**
     * @param string $nome
     */
    public function setNome($nome) {
        $this->nome = $nome;
    }
    
    /**
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }  
    
    /**
     * @param string $email
     */
    public function setEmail($email) {
        $this->email = $email;
    }
    
    /**
     * @return string
     */
    public function getSenha() {
        return $this->senha;
    }
    
    /**
     * @param string $senha
     */
    public function setSenha($senha) {
        $this->senha = $senha;
    }
    
    /**
     * @return List<Notificacoes>
     */
    public function getNotificacoes() {
        return $this->notificacoes;
    }
    
    /**
     * @param List<Notificacoes> $notificacoes
     */
    public function setNotificacoes($notificacoes) {
        $this->notificacoes = $notificacoes;
    }
    /**
     * 
     * @param Notificavel $notificacao
     */
    public function addNotificacao(Notificavel $notificacao){
        $this->notificacoes[] = $notificacao;
    }
    
    /**
     * @return DateTime
     */
    public function getDataCriacao() {
        return $this->dataCriacao;
    }
    
    /**
     * @param DateTime $dataCriacao
     */
    public function setDataCriacao(DateTime $dataCriacao) {
        $this->dataCriacao = $dataCriacao;
    }
    
    /**
     * @return string
     */
    public function getBreveDescricao() {
        return 'Usuário registrado em: '.$this->dataCriacao->format('d/M/y');
    }
    
    /**
     * @return string
     */
    public function getLink() {
        return "UsuariosController/registro/".$this->id;
    }
    
    /**
     * @return array<string>
     */
    public function getPalavrasChave() {
        $palavras = explode(' ', $this->nome);
        $palavras[] = $this->email;
        $palavras[] = $this->id;
        return $palavras;
    }
    
    /**
     * @return string
     */
    public function getTipo() {
        return get_class($this);
    }
    
    /**
     * @return string
     */
    public function getTitulo() {
        return $this->nome . ' [ '.$this->email.' ]';
    }
    
    /**
     * 
     * @return Departamento
     */
    public function getDepartamento() {
        return $this->departamento;
    }

    /**
     * @param Departamento $departamento
     */
    public function setDepartamento(Departamento $departamento) {
        $this->departamento = $departamento;
    }
    /**
     * 
     * @return boolean 
     */
    public function getResponsavel() {
        return $this->responsavel;
    }
    /**
     * 
     * @param boolean $responsavel
     */
    public function setResponsavel($responsavel) {
        $this->responsavel = $responsavel;
    }
    
    /**
     * @return integer
     */
    public function getTipoUsuario() {
        return $this->tipoUsuario;
    }
    /**
     * 
     * @param integer $tipoUsuario
     */
    public function setTipoUsuario($tipoUsuario) {
        $this->tipoUsuario = $tipoUsuario;
    }
    /**
     * 
     * @return Usuario
     */
    public function getEntidade() {
        return $this;
    }
    
    public function __toString() {
        return 'Usuario{nome:'.$this->getNome().', id:'.$this->getId().'}';
    }

    public function getData() {
        return new DateTime();
    }

    public function getDataExpiracao() {
        return null;
    }

    public function getMensagem() {
        return ($this->id == null?'Novo usuario cadastrado ':'Usuario alterado ').'"'.$this->nome.'['.$this->email.']"' ;
    }

    public function getNotificarEmail() {
        return false;
    }

}

?>