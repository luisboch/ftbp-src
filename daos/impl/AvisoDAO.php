<?php

require_once 'ftbp-src/daos/EntidadeDAO.php';
require_once 'ftbp-src/entidades/basico/Aviso.php';
require_once 'DAOBasico.php';



class AvisoDAO extends DAOBasico {

    public function executarInsert(Entidade $entidade) {
        
        $sql = "select nextval('aviso_id_seq') as id";
        
        $rs = $this->getConn()->query($sql);
        
        $rs->next();
        
        $arr = $rs->fetchArray();
        
        $id = $arr['id'];
        
        $sql = "INSERT INTO aviso(
                    id, titulo, descricao, data_criacao, usuario_id)
                VALUES ($1,$2, $3, now(), $4)";

        $p = $this->getConn()->prepare($sql);

        $p->setParameter(1, $id, PreparedStatement::INTEGER);
        $p->setParameter(2, $entidade->getNome(), PreparedStatement::STRING);
        $p->setParameter(3, $entidade->getDescricao(), PreparedStatement::STRING);
        $p->setParameter(4, $entidade->getCriadoPor()->getId(), PreparedStatement::INTEGER);

        $p->execute();
        
        $sql = "insert into aviso_destinatario( aviso_id, usuario_id) values ($1, $2)";
        
        $p = $this->getConn()->prepare($sql);
        
        $usuarios = $entidade->getUsuariosAlvo();
        
        foreach ($usuarios as $v) {
            $p->setParameter(1, $id, PreparedStatement::INTEGER);
            $p->setParameter(2, $v->getId(), PreparedStatement::INTEGER);
            $p->execute();
        }

        $entidade->setId($id);
    }

    public function executarUpdate(Entidade $entidade) {
        throw new Exception("Not implemented yet!");
    }

    public function executarDelete(Entidade $entidade) {
        $sql = " delete from      
        aviso where id=$1";
        $p = $this->getConn()->prepare($sql);
        $p->setParameter(1, $entidade->getId(), PreparedStatement::INTEGER);
        $p->execute();
    }

    public function getById($id) {
        $sql = "select *  
                 from aviso
                 where id = $1";

        $p = $this->getConn()->prepare($sql);
        $p->setParameter(1, $id, PreparedStatement::INTEGER);
        $rs = $p->getResult();

        if (!$rs->next()) {
            throw new NoResultException("Aviso não encontrado");
        }

        return $this->montarAviso($rs);
    }

    /**
     * 
     * @param ResultSet $rs
     * @return Aviso
     */
    public function montarAviso(ResultSet $rs) {
        $arr = $rs->fetchArray();
        $av = new Aviso();
        $av->setId($arr['id']);
        //$dp->setNome($arr['nome']);
        return $av;
    }

    /**
     * 
     * @return array
     */
    public function carregarAviso() {
        
        $sql = "select *  
                  from aviso
              ";
        
        $rs = $this->getConn()->query($sql);
        
        $list = array();
        while ($rs->next()) {
            $list[] = $this->montarAviso($rs);
        }
        return $list;
    }

}

?>
