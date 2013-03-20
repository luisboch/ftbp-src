<?php

/*
 * TipoUsuario.php
 */

/**
 * Description of TipoUsuario
 *
 * @author Luis
 * @since Feb 27, 2013
 */
class TipoUsuario {
    const ANONIMO = 1;
    const ALUNO = 2;
    const FUNCIONARIO = 3;
    const PROFESSOR = 4;
    
    public static function valueOf($string) {
        $value = (int) $string;
        if($value > 0 && $value < 5){
            return $value;
        }
        
        throw new InvalidArgumentException(
                "Valor de '".$string."', não é um Tipo Usuario válido");
        
    }
}

?>
