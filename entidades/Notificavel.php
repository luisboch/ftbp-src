<?php

/**
 * Define quais entidades deverão ser notificadas.
 * @author Luis
 */
interface Notificavel {
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
}

?>
