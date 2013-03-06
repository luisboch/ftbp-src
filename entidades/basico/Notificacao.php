<?php
require_once 'ftbp-src/entidades/Entidade.php';
require_once 'ftbp-src/entidades/basico/Usuario.php';

/**
 * Description of Notificacao
 *
 * @author luis
 */
class Notificacao implements Entidade{
    /**
     *
     * @var integer
     */
    private $id;
    /**
     *
     * @var Usuario
     */
    private $usuario;
    /**
     *
     * @var string
     */
    private $descricao;
    /**
     *
     * @var boolean
     */
    private $excluida;
    /**
     *
     * @var boolean
     */
    private $visualizada;
    /**
     *
     * @var DateTime
     */
    private $data;
    /**
     *
     * @var DateTime
     */
    private $dataExpiracao;
    /**
     *
     * @var DateTime
     */
    private $dataCriacao;
    
    /**
     * 
     * @return integer
     */
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
     * @return Usuario
     */
    public function getUsuario() {
        return $this->usuario;
    }

    public function setUsuario(Usuario $usuario) {
        $this->usuario = $usuario;
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
     * @return boolean
     */
    public function getExcluida() {
        return $this->excluida;
    }

    /**
     * 
     * @param boolean $excluida
     */
    public function setExcluida($excluida) {
        $this->excluida = $excluida;
    }

    /**
     * 
     * @return boolean
     */
    public function getVisualizada() {
        return $this->visualizada;
    }

    /**
     * 
     * @param boolean $excluida
     */
    public function setVisualizada($visualizada) {
        $this->visualizada = $visualizada;
    }

    /**
     * 
     * @return DateTime
     */
    public function getData() {
        return $this->data;
    }

    /**
     * 
     * @param DateTime $data
     */
    public function setData(DateTime $data) {
        $this->data = $data;
    }

    /**
     * 
     * @return DateTime
     */
    public function getDataExpiracao() {
        return $this->dataExpiracao;
    }

    /**
     * 
     * @param DateTime $dataExpiracao
     */
    public function setDataExpiracao(DateTime $dataExpiracao) {
        $this->dataExpiracao = $dataExpiracao;
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
}

?>
