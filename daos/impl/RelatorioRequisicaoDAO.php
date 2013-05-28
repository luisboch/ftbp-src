<?php

require_once 'ftbp-src/daos/EntidadeDAO.php';
require_once 'ftbp-src/entidades/basico/Requisicao.php';
require_once 'ftbp-src/entidades/basico/Relatorio.php';
require_once 'DAOBasico.php';

class RelatorioRequisicaoDAO extends DAOBasico {

    
    /**
     * 
     * @param ResultSet $rs
     * @return AreaCurso
     */
    public function montarRelatorio(ResultSet $rs) {
        $arr = $rs->fetchArray();
        $rq = new Requisicao();
        $rq->setId($arr['id']);
        $rq->setTitulo($arr['titulo']);
        $rq->setDataCriacao(DAOUtil::toDateTime($arr['data_criacao']));
        

        return $rq;
    }
    
    public function gerarRelatorio(Entidade $entidade){
        $sql = "SELECT rq.id, titulo, descricao, rq.usuario_id, rq.criado_por, rq.data_criacao,rqi.usuario_id,
                        status, rqi.usuario_id, usu.nome
                    FROM requisicoes rq
                    join requisicoes_iteracoes rqi on rq.id = rqi.requisicao_id
                    inner join usuarios usu on usu.id = rqi.usuario_id
                    where status = 'FINALIZADO'";

        $p = $this->getConn()->prepare($sql);
        
        $rs = $p->getResult();

        $list = array();
        while($rs->next()){
            
            // Monta o objeto 
            $list[] = $this->montarRelatorio($rs);
        }
        
        // Retorna a lista montada.
        return $list;
    }

    public function executarDelete(Entidade $entidade) {
        return null;
    }

    public function executarInsert(Entidade $entidade) {
        return null;
    }

    public function executarUpdate(Entidade $entidade) {
        return null;
    }

    public function getById($id) {
        return null;
    }

}

?>