<?php
require_once '../entidades/Entidade.php';

/*
 * EntidadeDAO.php
 */

/**
 *
 * @author Luis
 * @since Feb 23, 2013
 */

interface EntidadeDAO {
    function executeInsert(Entidade $entidade);
    function executeUpdate(Entidade $entidade);
    function executeDelete(Entidade $entidade);
}

?>
