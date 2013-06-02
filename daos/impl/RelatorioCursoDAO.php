<?php

require_once 'ftbp-src/daos/impl/resultados/CursoRelatorioResultado.php';
require_once 'ftbp-src/daos/impl/DAOBasico.php';

/**
 * @author luis
 */
class RelatorioCursoDAO extends DAOBasico {

    /**
     * Realiza a pesquisa do relatório, de acordo com o agrupamento, veja {@link CursoAgrupamento}
     * 
     * @param CursoAgrupamento $agruparPor
     */
    public function dadosRelatorioVisualizacao($agruparPor = CursoAgrupamento::CURSO) {

        // Checa os valores default.
        if ($agruparPor == '') {
            $agruparPor = CursoAgrupamento::CURSO;
        }

        if ($agruparPor == CursoAgrupamento::CURSO) {
            $sql = "select nome as curso, acessos
                      from curso 
                     where excluida = false";
        }

        if ($agruparPor == CursoAgrupamento::CURSO_AREA) {
            $sql = "select a.nome as area, sum (c.acessos) as acessos
                      from curso c
                      join area_curso a on (c.areacurso_id = a.id)
                     where c.excluida = false
                  group by a.id";
        }

        if ($agruparPor == CursoAgrupamento::NIVEL) {

            $sql = "select nivelgraduacao, sum(acessos) as acessos
                      from curso 
                     where excluida = false
                  group by nivelgraduacao";
        }

        $result = $this->getConn()->query($sql, 'CursoRelatorioResultado');

        return $result;
    }

    public function executarDelete(\Entidade $entidade) {
        throw new IllegalStateException("Não implementado");
    }

    public function executarInsert(\Entidade $entidade) {
        throw new IllegalStateException("Não implementado");
    }

    public function executarUpdate(\Entidade $entidade) {
        throw new IllegalStateException("Não implementado");
    }

    public function getById($id) {
        throw new IllegalStateException("Não implementado");
    }

}

class CursoAgrupamento {

    const CURSO = 1;
    const CURSO_AREA = 2;
    const NIVEL = 3;

    /**
     * 
     * @param type $tipo
     * @return string cabeçalho de acordo com o tipo.
     */
    public function getCabecalho($tipo) {
        switch ($tipo) {
            case CursoAgrupamento::CURSO:
                return 'Curso';
                break;

            case CursoAgrupamento::CURSO_AREA :
                return 'Curso Area';
                break;
            case CursoAgrupamento::NIVEL:
                return 'Nível';
                break;
            default:
                return 'undefined';
                break;
        }
    }

}

?>