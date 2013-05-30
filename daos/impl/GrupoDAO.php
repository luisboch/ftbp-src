<?php

require_once 'ftbp-src/daos/EntidadeDAO.php';
require_once 'ftbp-src/entidades/basico/Grupo.php';
require_once 'DAOBasico.php';

class GrupoDAO extends DAOBasico {

    public function executarInsert(Entidade $entidade) {

        $sql = "insert
                  into grupos(nome)
                values ($1)";

        $p = $this->getConn()->prepare($sql);

        $p->setParameter(1, $entidade->getNome(), PreparedStatement::STRING);


        $p->execute();

        // Pega o id gerado na sequence 
        $p = $this->getConn()->query("select currval('grupos_id_seq') as id");
        $p->next();

        $array = $p->fetchArray();

        $entidade->setId($array['id']);
        
        $this->inserirAcessos($entidade);
        
    }

    public function executarUpdate(Entidade $entidade) {

        $sql = "update grupos
                   set nome = $1
                 where id = $2";

        $p = $this->getConn()->prepare($sql);

        $p->setParameter(1, $entidade->getNome(), PreparedStatement::STRING);
        $p->setParameter(2, $entidade->getId(), PreparedStatement::INTEGER);

        $p->execute();
        
        $this->excluirAcessos($entidade);
        $this->inserirAcessos($entidade);
        
    }

    public function executarDelete(Entidade $entidade) {
        $sql = " delete
                   from grupos adicionarAcesso
                  where id = $1";
        
        $p = $this->getConn()->prepare($sql);
        
        $p->setParameter(1, $entidade->getId(), PreparedStatement::INTEGER);
        
        $p->execute();
        
        $this->excluirAcessos($entidade);
    }

    public function getById($id) {
        $sql = "select *  
                  from grupos
                 where id = $1";

        $p = $this->getConn()->prepare($sql);
        $p->setParameter(1, $id, PreparedStatement::INTEGER);
        $rs = $p->getResult();

        if (!$rs->next()) {
            throw new NoResultException("Grupo não encontrado");
        }

        return $this->montarGrupo($rs);
    }

    /**
     * 
     * @param ResultSet $rs
     * @return Grupo
     */
    public function montarGrupo(ResultSet $rs) {
        
        $arr = $rs->fetchArray();

        $gp = new Grupo();
        $gp->setId($arr['id']);
        $gp->setNome($arr['nome']);
        $gp->setDataCriacao(DAOUtil::toDateTime($arr['data_criacao']));

        return $gp;
    }

    /**
     * 
     * @return Grupo[]
     */
    public function carregarGrupos() {

        $sql = "select *  
                  from grupos
              order by nome";

        $rs = $this->getConn()->query($sql);

        $list = array();
        while ($rs->next()) {
            $list[] = $this->montarGrupo($rs);
        }
        return $list;
    }

    private function excluirAcessos(Grupo $grupo) {

        $sql = "delete 
                  from grupo_acesso
                 where grupo_id = $1";

        $p = $this->getConn()->prepare($sql);

        $p->setParameter(1, $grupo->getId(), PreparedStatement::INTEGER);

        $p->execute();
    }

    private function inserirAcessos(Grupo $grupo) {

        $sql = "insert  
                  into grupo_acesso(grupo_id, acesso, escrita)
                values ($1, $2, $3)";

        $p = $this->getConn()->prepare($sql);

        foreach ($grupo->getAcessos() as $acesso) {
            /* @var $acesso GrupoAcesso */
            $p->setParameter(1, $grupo->getId(), PreparedStatement::INTEGER);
            $p->setParameter(2, $acesso->getTipo(), PreparedStatement::INTEGER);
            $p->setParameter(3, $acesso->getEscrita()?'true':'false', PreparedStatement::STRING);
            
            $p->execute();
        }
    }
    
    public function carregarAcesso(Grupo $grupo) {
        $sql = "select * 
                  from grupo_acesso
                 where grupo_id = $1";
        
        $p = $this->getConn()->prepare($sql);
        
        $p->setParameter(1, $grupo->getId(), PreparedStatement::INTEGER);
        
        $rs = $p->getResult();
        
        while($rs->next()){
            
            $arr = $rs->fetchArray();
            
            $acesso = new GrupoAcesso();
            
            $acesso->setTipo($arr['acesso']);
            $acesso->setEscrita($arr['escrita'] === 't');
            
            $grupo->adicionarAcesso($acesso);
            
        }
        
        return $grupo;
    }

}

?>
