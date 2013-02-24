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
    function executarInsert(Entidade $entidade);
    function executarUpdate(Entidade $entidade);
    function executarDelete(Entidade $entidade);
}

?>
