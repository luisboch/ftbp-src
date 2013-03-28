<?php

require_once 'ftbp-src/daos/EntidadeDAO.php';
require_once 'ftbp-src/entidades/basico/Aviso.php';
require_once 'DAOBasico.php';



class AvisoDAO extends DAOBasico {

    public function executarInsert(Entidade $entidade) {

        $sql = "INSERT INTO aviso(
                    titulo, descricao, data_criacao, usuario_id)
                VALUES (?, ?, now(), 1)";

        $p = $this->getConn()->prepare($sql);

        $p->setParameter(1, $entidade->getNome(), PreparedStatement::STRING);
        $p->setParameter(2, $entidade->getDescricao(), PreparedStatement::STRING);

        $p->execute();

        // Pega o id gerado na sequence 
        $p = $this->getConn()->query("select currval('aviso_id_seq') as id");
        $p->next();
        $array = $p->fetchArray();
        
        $entidade->setId($array['id']);
        
        
    }

    public function executarUpdate(Entidade $entidade) {

        $sql = "UPDATE departamento
                   SET nome            = $1
                   WHERE id =$2";

        $p = $this->getConn()->prepare($sql);

        $p->setParameter(1, $entidade->getNome(), PreparedStatement::STRING);
        $p->setParameter(2, $entidade->getId(), PreparedStatement::INTEGER);

        $p->execute();
    }

    public function executarDelete(Entidade $entidade) {
        $sql = " delete from      
        departamento where id=$1";
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
