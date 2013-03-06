<?php

require_once 'ftbp-src/daos/EntidadeDAO.php';
require_once 'DAOBasico.php';

class NivelCursoDAO extends DAOBasico {

    
    public function executarInsert(Entidade $entidade) {
        
        $sql = "INSERT    
                 INTO nivelCurso(
                                   nome
                 VALUES           ($1)";

        $p = $this->getConn()->prepare($sql);

        $p->setParameter(1, $entidade->getNome(), PreparedStatement::STRING);                    
                
        $p->execute();

        // Pega o id gerado na sequence 
        $p = $this->getConn()->query("select currval('nivelCurso_id_seq') as id");
        $p->next();
        $array = $p->fetchArray();

        $entidade->setId($array['id']);
    }

    public function executarUpdate(Entidade $entidade) {
        
        $sql = "UPDATE nivelCurso
                   SET nome = $1
                   WHERE id =$2";                                 
              
        $p = $this->getConn()->prepare($sql);

        $p->setParameter(1, $entidade->getNome(), PreparedStatement::STRING);                   
        $p->setParameter(2, $entidade->getId(), PreparedStatement::INTEGER);  
        
        $p->execute();
    }

    public function executarDelete(Entidade $entidade) {
        $sql = "delete from      
                    nivelCurso where id=$1";
        $p = $this->getConn()->prepare($sql);
        $p->setParameter(1, $entidade->getId(), PreparedStatement::INTEGER);
        $p->execute();
    }

    
    public function getById($id) {
        $sql = "select *  
                    from nivelCurso
                    where id = $1";

        $p = $this->getConn()->prepare($sql);
        $p->setParameter(1, $id, PreparedStatement::INTEGER);
        $rs = $p->getResult();

        if (!$rs->next()) {
            throw new NoResultException("Nivel do Curso não encontrado");
        }

        return $this->montarNivelCurso($rs);
    }
    /**
     * 
     * @param ResultSet $rs
     * @return NivelCurso
     */
    public function montarNivelCurso(ResultSet $rs){
        $arr = $rs->fetchArray();
        $nv = new NivelCurso();
        $nv ->setId($arr['id']);
        $nv ->setNome($arr['nome']);
        return $nv;               
    }
    
}  

?>
