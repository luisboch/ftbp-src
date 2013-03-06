<?php
require_once 'ftbp-src/entidades/Entidade.php';

/**
 * Define quais entidades deverão ser notificadas.
 * @author Luis
 */
interface Notificavel extends Entidade{
    /**
     * @return List<Usuario> 
     */
    function getUsuariosAlvo();
    /**
     * @return string
     */
    function getLink();
    
    /**
     * @return boolean
     */
    function getNotificarEmail();
    
    /**
     * @return string
     */
    function getMensagem();
    
    /**
     * @return DateTime
     */
    function getData();
    
    /**
     * @return DateTime
     */
    function getDataExpiracao();
}

?>
