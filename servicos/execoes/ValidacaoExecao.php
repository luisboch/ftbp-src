<?php

/*
 * ValidacaoExecao.php
 */

/**
 * Description of ExeceaoValidacao
 *
 * @author Luis
 * @since Feb 24, 2013
 */
class ValidacaoExecao extends Exception {

    /**
     *
     * @var List<ErroValidacao>
     */
    private $errors = array();

    public function __construct($message = "Errors found while executing an action", $code = 0, $previous = NULL) {
        parent::__construct($message, $code, $previous);
    }

    /**
     *
     * @return List<ErroValidacao>
     */
    public function getErrors() {
        return $this->errors;
    }

    /**
     *
     * @return boolean
     */
    public function isEmtpy() {
        return count($this->errors) == 0;
    }

    public function addError($message, $field = NULL) {
        $err = new ErroValidacao($message, $field);
        $this->errors[] = $err;
    }

}

class ErroValidacao {

    /**
     *
     * @var string
     */
    private $mensagem;

    /**
     *
     * @var string
     */
    private $field;

    function __construct($mensagem, $field = NULL) {
        $this->mensagem = $mensagem;
        $this->field = $field;
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
     * @return string
     */
    public function getCampo() {
        return $this->field;
    }

    /**
     *
     * @param string $campo
     */
    public function setCampo($campo) {
        $this->field = $campo;
    }

}

?>
