<?php
require_once './Usuario.php';
/*
 * Aluno.php
 */

/**
 * @author Luis
 * @since Feb 23, 2013
 */
class Aluno extends Usuario{
    public function getType() {
        return __CLASS__;
    }    
}

?>
