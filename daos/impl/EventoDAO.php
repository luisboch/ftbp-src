<?php

require_once 'ftbp-src/daos/EntidadeDAO.php';
require_once 'ftbp-src/entidades/basico/Evento.php';
require_once 'ftbp-src/daos/impl/DAOUtil.php';
require_once 'DAOBasico.php';

class EventoDAO extends DAOBasico {

    public function executarInsert(Entidade $entidade) {

        $sql = "select nextval('evento_id_seq') as id";

        $rs = $this->getConn()->query($sql);

        $rs->next();

        $arr = $rs->fetchArray();

        $id = $arr['id'];

        $sql = "INSERT INTO evento(
                            id, titulo, descricao, data, local, contato, excluida, data_criacao)
                    VALUES ($1,$2, $3, $4, $5, $6, false,now())";

        $p = $this->getConn()->prepare($sql);

        $p->setParameter(1, $id, PreparedStatement::INTEGER);
        $p->setParameter(2, $entidade->getTitulo(), PreparedStatement::STRING);
        $p->setParameter(3, $entidade->getDescricao(), PreparedStatement::STRING);
        $p->setParameter(4, DAOUtil::toDataBaseTime($entidade->getDataEvento()), PreparedStatement::STRING);
        $p->setParameter(5, $entidade->getLocal(), PreparedStatement::STRING);
        $p->setParameter(6, $entidade->getContato(), PreparedStatement::STRING);

        $p->execute();

        $entidade->setId($id);
    }

    public function executarUpdate(Entidade $entidade) {

        $sql = " update evento
                    set titulo=$2, descricao=$3, data=$4, local=$5, 
                        contato=$6
                    where id=$1";

        $p = $this->getConn()->prepare($sql);

        $p->setParameter(1, $entidade->getId(), PreparedStatement::INTEGER);
        $p->setParameter(2, $entidade->getTitulo(), PreparedStatement::STRING);
        $p->setParameter(3, $entidade->getDescricao(), PreparedStatement::STRING);
        $p->setParameter(4, DAOUtil::toDataBaseTime($entidade->getDataEvento()), PreparedStatement::STRING);
        $p->setParameter(5, $entidade->getLocal(), PreparedStatement::STRING);
        $p->setParameter(6, $entidade->getContato(), PreparedStatement::STRING);

        $p->execute();
    }

    public function executarDelete(Entidade $entidade) {

        throw new Exception("Not implemented yet!");
    }

    public function getById($id) {
        $sql = "select id, titulo, descricao, data, local, contato, excluida, data_criacao
                  from evento where id = $1
              order by data desc";

        $p = $this->getConn()->prepare($sql);
        $p->setParameter(1, $id, PreparedStatement::INTEGER);
        $rs = $p->getResult();

        if (!$rs->next()) {
            throw new NoResultException("Evento não encontrado");
        }

        return $this->montarEvento($rs);
    }

    /**
     * 
     * @param ResultSet $rs
     * @return Curso
     */
    public function montarEvento(ResultSet $rs) {

        $arr = $rs->fetchArray();
        $ev = new Evento();
        $ev->setId($arr['id']);
        $ev->setTitulo($arr['titulo']);
        $ev->setDescricao($arr['descricao']);
        $ev->setDataEvento(DAOUtil::toDateTime($arr['data']));
        $ev->setLocal($arr['local']);
        $ev->setContato($arr['contato']);
        $ev->setDataCriacao(DAOUtil::toDateTime($arr['data_criacao']));

        return $ev;
    }

    /**
     * 
     * @return array
     */
    public function carregarEvento($limite = null) {

        $sql = "select id, titulo, data_criacao, descricao, data, local, contato, excluida
                  from evento
              order by data desc";
        if ($limite !== null) {
            $sql .= "
                 limit $1 ";
        }

        $p = $this->getConn()->prepare($sql);

        if ($limite !== null) {
            $p->setParameter(1, $limite, PreparedStatement::INTEGER);
        }

        // Pega o resultado
        $rs = $p->getResult();

        // Itera sobre o resultado
        $list = array();
        while ($rs->next()) {
            // Monta o objeto 
            $list[] = $this->montarEvento($rs);
        }

        // Retorna a lista montada.
        return $list;
    }

}
?>