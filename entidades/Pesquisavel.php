<?php
require_once 'Referenciavel.php';
/*
 * Pesquisavel.php
 */

/**
 * @author Luis
 * @since Feb 23, 2013
 */
interface Pesquisavel extends Referenciavel{
    
    /**
     * @param boolean $new Declare if instance is new or not
     * @return string
     */
    function getTitulo();
    
    /**
     * @return string
     */
    function getBreveDescricao();
    
    /**
     * @return List<string>
     */
    function getPalavrasChave();
    
    /**
     * @return string
     */
    function getTipo();
    
    /**
     * @return Entidade 
     */
    function getEntidade();
    
}

?>
