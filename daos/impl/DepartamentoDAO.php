<?php

require_once 'ftbp-src/daos/EntidadeDAO.php';
require_once 'DAOBasico.php';

class DepartamentoDAO extends DAOBasico {

    
    public function executarInsert(Entidade $entidade) {
        
        $sql = "insert
                  into departamento(nome)
                values ($1)";

        $p = $this->getConn()->prepare($sql);

        $p->setParameter(1, $entidade->getNome(), PreparedStatement::STRING);                    
                
        $p->execute();

        // Pega o id gerado na sequence 
        $p = $this->getConn()->query("select currval('departamento_id_seq') as id");
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
                 from departamento
                 where id = $1";

        $p = $this->getConn()->prepare($sql);
        $p->setParameter(1, $id, PreparedStatement::INTEGER);
        $rs = $p->getResult();

        if (!$rs->next()) {
            throw new NoResultException("Usuário não encontrado");
        }

        return $this->montarDepartamento($rs);
    }
    /**
     * 
     * @param ResultSet $rs
     * @return Departamento
     */
    public function montarDepartamento(ResultSet $rs){
        $arr = $rs->fetchArray();
        $dp = new Departamento();
        $dp ->setId($arr['id']);
        $dp ->setNome($arr['nome']);
        return $dp;               
    }
    
}  
        



?>
