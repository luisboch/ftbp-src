<?php

require_once 'ftbp-src/daos/EntidadeDAO.php';
require_once 'ftbp-src/entidades/basico/Requisicao.php';
require_once 'ftbp-src/entidades/basico/RelatorioRequisicao.php';
require_once 'ftbp-src/entidades/basico/Usuario.php';
require_once 'ftbp-src/entidades/basico/Departamento.php';
require_once 'DAOBasico.php';

class RelatorioRequisicaoDAO extends DAOBasico {

    
    /**
     * 
     * @param ResultSet $rs
     * @return AreaCurso
     */
    public function montarRelatorio(ResultSet $rs) {
        $arr = $rs->fetchArray();
        $rl = new RelatorioRequisicao();
        
        $rl->setUsuario(new Usuario());
        $rl->getUsuario()->setNome($arr['nome']);
        
        $rl->setDepartamento(new Departamento());
        $rl->getDepartamento()->setNome($arr['departamento']);
        
        $rl->setQtde($arr['qtde']);

        return $rl;
    }
    
    public function gerarRelatorioFechamento(Entidade $entidade){
        $sql = "SELECT usu.nome, dp.nome departamento, count(*) qtde
                FROM requisicoes rq
                        join usuarios usu on usu.id = rq.fechado_por
                        inner join departamento dp on dp.id = usu.departamento_id
                where rq.status =  'FINALIZADO'
                and
                    to_char(rq.data_fechamento, 'YYYY-MM-DD') between $1 and $2
                group by usu.nome, dp.nome";

        
        $p = $this->getConn()->prepare($sql);
        
        $p->setParameter(1, $entidade->getDataInicio(), PreparedStatement::STRING);
        $p->setParameter(2, $entidade->getDataFim(), PreparedStatement::STRING);
        
        $rs = $p->getResult();

        $list = array();
        while($rs->next()){
            
            // Monta o objeto 
            $list[] = $this->montarRelatorio($rs);
        }
        
        // Retorna a lista montada.
        return $list;
    }
    
    public function gerarRelatorioAbertura(Entidade $entidade){
        $sql = "SELECT usu.nome, dp.nome departamento, count(*) qtde
                FROM requisicoes rq
                        join usuarios usu on usu.id = rq.fechado_por
                        inner join departamento dp on dp.id = usu.departamento_id
                where rq.status =  'FINALIZADO'
                and
                    to_char(rq.data_criacao, 'YYYY-MM-DD') between $1 and $2
                group by usu.nome, dp.nome";

        
        $p = $this->getConn()->prepare($sql);
        
        $p->setParameter(1, $entidade->getDataInicio(), PreparedStatement::STRING);
        $p->setParameter(2, $entidade->getDataFim(), PreparedStatement::STRING);
        
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