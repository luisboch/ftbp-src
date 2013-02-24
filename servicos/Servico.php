<?php
require_once 'ftbp-src/entidades/Entidade.php';
/*
 * Servico.php
 */

/**
 * @author Luis
 * @since Feb 23, 2013
 */
interface EntidadeServico {
    function inserir(Entidade $entidade);
    function atualizar(Entidade $entidade);
    function remover(Entidade $entidade);
    function checarAcesso();
    function validar(Entidade $entidade);
}

?>
