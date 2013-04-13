<?php
require_once 'ftbp-src/entidades/Entidade.php';

/**
 * Define quais entidades deverão ser notificadas.
 * @author Luis
 */
interface Notificavel extends Referenciavel{
    
    /**
     * @return boolean
     */
    function getNotificarEmail();
    
    /**
     * @param boolean $new Declare if instance is new or not
     * @return string
     */
    function getMensagem($new = false);
    
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
