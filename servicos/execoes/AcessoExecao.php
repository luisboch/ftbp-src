<?php

/*
 * AcessoExecao.php
 */

/**
 *
 * @author Luis
 * @since Feb 24, 2013
 */
class AcessoExecao extends Exception{
    function __construct($message, $code = NULL, $previous = NULL) {
        if($code === NULL){
            parent::__construct($message);
        }else if($previous === NULL){
            parent::__construct($message, $code);
        } else {
            parent::__construct($message, $code, $previous);
        }
    }

}

?>
