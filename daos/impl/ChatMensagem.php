<?php

/*
 * ChatMensagem.java
 */

/**
 * Description of ChatMensagem
 *
 * @author luis
 */
class ChatMensagem {

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
     * @var boolean
     */
    private $read;
    
    /**
     *
     * @var DateTime
     */
    private $data;

    /**
     * 
     * @param Usuario $usuario
     * @param string $mensagem
     */
    function __construct(Usuario $usuario = null, $mensagem = null, $read = null, $data = null) {
        $this->usuario = $usuario;
        $this->mensagem = $mensagem;
        $this->read = $read;
        $this->data = $data;
    }

    /**
     * 
     * @return Usuario
     */
    public function getUsuario() {
        return $this->usuario;
    }

    /**
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
     * @param boolean $mensagem
     */
    public function setMensagem($mensagem) {
        $this->mensagem = $mensagem;
    }

    /**
     * 
     * @return boolean
     */
    public function getRead() {
        return $this->read;
    }

    /**
     * 
     * @return boolean
     */
    public function isRead() {
        return $this->read;
    }

    /**
     * 
     * @param boolean $read
     */
    public function setRead($read) {
        $this->read = $read;
    }

    public function __toString() {
        return $this->mensagem;
    }
    
    /**
     * 
     * @return DateTime
     */
    public function getData() {
        return $this->data;
    }

    /**
     * @param DateTime $data
     */
    public function setData(DateTime $data) {
        $this->data = $data;
    }


}

?>