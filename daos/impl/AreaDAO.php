<?php

require_once 'ftbp-src/daos/EntidadeDAO.php';
require_once 'ftbp-src/entidades/basico/AreaCurso.php';
require_once 'DAOBasico.php';

class AreaDAO extends DAOBasico {

    public function executarInsert(Entidade $entidade) {

        $sql = "insert    
                  into area_curso(nome)
                values ($1)";

        $p = $this->getConn()->prepare($sql);

        $p->setParameter(1, $entidade->getNome(), PreparedStatement::STRING);

        $p->execute();

        // Pega o id gerado na sequence 
        $p = $this->getConn()->query("select currval('area_curso_id_seq') as id");

        $p->next();

        $array = $p->fetchArray();

        $entidade->setId($array['id']);
    }

    public function executarUpdate(Entidade $entidade) {

        $sql = "update area_curso
                   set nome = $1
                 where id = $2";

        $p = $this->getConn()->prepare($sql);

        $p->setParameter(1, $entidade->getNome(), PreparedStatement::STRING);
        $p->setParameter(2, $entidade->getId(), PreparedStatement::INTEGER);

        $p->execute();
    }

    public function executarDelete(Entidade $entidade) {
        $sql = " delete 
                   from area_curso 
                  where id = $1";
        $p = $this->getConn()->prepare($sql);
        $p->setParameter(1, $entidade->getId(), PreparedStatement::INTEGER);
        $p->execute();
    }

    /**
     * 
     * @param integer $id
     * @return AreaCurso
     * @throws NoResultException
     */
    public function getById($id) {
        $sql = "select *  
                 from area_curso
                 where id = $1";

        $p = $this->getConn()->prepare($sql);
        $p->setParameter(1, $id, PreparedStatement::INTEGER);
        $rs = $p->getResult();

        if (!$rs->next()) {
            throw new NoResultException("Area Curso não encontrada");
        }

        return $this->montarArea($rs);
    }

    /**
     * 
     * @param ResultSet $rs
     * @return AreaCurso
     */
    public function montarArea(ResultSet $rs) {
        $arr = $rs->fetchArray();
        $dp = new AreaCurso();
        $dp->setId($arr['id']);
        $dp->setNome($arr['nome']);
        $dp->setDataCriacao(DAOUtil::toDateTime($arr['data_criacao']));

        return $dp;
    }

}

?>