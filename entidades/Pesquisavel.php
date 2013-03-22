<?php

/*
 * Pesquisavel.php
 */

/**
 * @author Luis
 * @since Feb 23, 2013
 */
interface Pesquisavel{
    
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
    function getLink();
    
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
