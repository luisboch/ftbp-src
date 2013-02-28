<?php
require_once 'ftbp-src/entidades/Entidade.php';

/*
 * EntidadeDAO.php
 */

/**
 * @author Luis
 * @since Feb 23, 2013
 */

interface EntidadeDAO {
    /**
     * @param Entidade $entidade
     */
    function executarInsert(Entidade $entidade);
    
    /**
     * 
     * @param Entidade $entidade
     */
    function executarUpdate(Entidade $entidade);
    
    /**
     * 
     * @param Entidade $entidade
     */
    function executarDelete(Entidade $entidade);
    /**
     * 
     * @param integer $id
     */
    function getById($id);
}

?>
