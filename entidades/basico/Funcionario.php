<?php
require_once './Usuario.php';
/*
 * Funcionario.php
 */

/**
 * @author Luis
 * @since Feb 23, 2013
 */
class Funcionario extends Usuario {
    public function getType() {
        return __CLASS__;
    }    
}

?>
