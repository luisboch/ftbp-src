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
        
    }

    public function executarUpdate(Entidade $entidade) {

        $sql = "update grupos
                   set nome = $1
                 where id = $2";

        $p = $this->getConn()->prepare($sql);

        $p->setParameter(1, $entidade->getNome(), PreparedStatement::STRING);
        $p->setParameter(2, $entidade->getId(), PreparedStatement::INTEGER);

        $p->execute();
    }

    public function executarDelete(Entidade $entidade) {
        $sql = " delete
                   from grupos 
                  where id = $1";
        $p = $this->getConn()->prepare($sql);
        $p->setParameter(1, $entidade->getId(), PreparedStatement::INTEGER);
        $p->execute();
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

}

?>
