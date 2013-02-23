<?php
require_once '../entidades/Entidade.php';
/*
 * Servico.php
 */

/**
 * @author Luis
 * @since Feb 23, 2013
 */
interface EntidadeServico {
    function salvar(Entidade $entidade);
    function atualizar(Entidade $entidade);
    function remover(Entidade $entidade);
    function checarAcesso();
}

?>
