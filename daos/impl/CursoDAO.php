<?php

require_once 'ftbp-src/daos/EntidadeDAO.php';
require_once 'ftbp-src/daos/impl/DAOUtil.php';
require_once 'ftbp-src/entidades/basico/Curso.php';
//require_once 'ftbp-src/entidades/basico/Usuario.php';
require_once 'DAOBasico.php';

class CursoDAO extends DAOBasico {

    public function executarInsert(Entidade $entidade) {

        $sql = "select nextval('curso_id_seq') as id";

        $rs = $this->getConn()->query($sql);

        $rs->next();

        $arr = $rs->fetchArray();

        $id = $arr['id'];

        $sql = "INSERT INTO curso(
                        id, nome, descricao, data_vestibular, coordenador, email, corpo_docente, 
                        publico_alvo, valor, duracao, videoapres, areacurso_id, nivelgraduacao, 
                        contatosecretaria, excluida, credito)
                    VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12, $13, $14, false, $15)";

        $p = $this->getConn()->prepare($sql);

        // Set params on prepared statement
        $p->setParameter(1, $id, PreparedStatement::INTEGER);
        $p->setParameter(2, $entidade->getNome(), PreparedStatement::STRING);
        $p->setParameter(3, $entidade->getDescricao(), PreparedStatement::STRING);

        if ($entidade->getDataVestibular() != null) {
            $p->setParameter(4, DAOUtil::toDataBaseTime($entidade->getDataVestibular()), PreparedStatement::STRING);
        } else {
            $p->setParameter(4, null, PreparedStatement::STRING);
        }

        $p->setParameter(5, $entidade->getCoordenador(), PreparedStatement::STRING);
        $p->setParameter(6, $entidade->getEmail(), PreparedStatement::STRING);
        $p->setParameter(7, $entidade->getCorpoDocente(), PreparedStatement::STRING);
        $p->setParameter(8, $entidade->getPublicoAlvo(), PreparedStatement::STRING);
        $p->setParameter(9, $entidade->getValor(), PreparedStatement::DOUBLE);
        $p->setParameter(10, $entidade->getDuracao(), PreparedStatement::DOUBLE);
        $p->setParameter(11, $entidade->getVideoApresentacao(), PreparedStatement::STRING);

        //arrumar o getareacurso para objeto
        $p->setParameter(12, $entidade->getAreaCurso(), PreparedStatement::INTEGER);
        $p->setParameter(13, $entidade->getNivelGraduacao(), PreparedStatement::STRING);
        $p->setParameter(14, $entidade->getContatoSecretaria(), PreparedStatement::STRING);
        $p->setParameter(15, $entidade->getCredito(), PreparedStatement::INTEGER);


        $p->execute();

        $entidade->setId($id);
    }

    public function executarUpdate(Entidade $entidade) {
        $sql = "UPDATE curso
                    SET nome=$1, descricao=$2, data_vestibular=$3, coordenador=$4, 
                    email=$5, corpo_docente=$6, publico_alvo=$7, valor=$8, duracao=$9, 
                    videoapres=$10, areacurso_id=$11, nivelgraduacao=$12, contatosecretaria=$13, 
                    credito=$15
                WHERE id=$14";
        $p = $this->getConn()->prepare($sql);

        $p->setParameter(1, $entidade->getNome(), PreparedStatement::STRING);
        $p->setParameter(2, $entidade->getDescricao(), PreparedStatement::STRING);

        if ($entidade->getDataVestibular() != null) {
            $p->setParameter(3, DAOUtil::toDataBaseTime($entidade->getDataVestibular()), PreparedStatement::STRING);
        } else {
            $p->setParameter(3, null, PreparedStatement::STRING);
        }

        $p->setParameter(4, $entidade->getCoordenador(), PreparedStatement::STRING);
        $p->setParameter(5, $entidade->getEmail(), PreparedStatement::STRING);
        $p->setParameter(6, $entidade->getCorpoDocente(), PreparedStatement::STRING);
        $p->setParameter(7, $entidade->getPublicoAlvo(), PreparedStatement::STRING);
        $p->setParameter(8, $entidade->getValor(), PreparedStatement::DOUBLE);
        $p->setParameter(9, $entidade->getDuracao(), PreparedStatement::DOUBLE);
        $p->setParameter(10, $entidade->getVideoApresentacao(), PreparedStatement::STRING);
        //arrumar o getareacurso para objeto
        $p->setParameter(11, $entidade->getAreaCurso(), PreparedStatement::INTEGER);
        $p->setParameter(12, $entidade->getNivelGraduacao(), PreparedStatement::STRING);
        $p->setParameter(13, $entidade->getContatoSecretaria(), PreparedStatement::STRING);
        $p->setParameter(14, $entidade->getId(), PreparedStatement::INTEGER);
        $p->setParameter(15, $entidade->getCredito(), PreparedStatement::INTEGER);
        $p->execute();
    }

    public function executarDelete(Entidade $entidade) {
        $sql = " update aviso set excluida = true
                    where id=$1";
        $p = $this->getConn()->prepare($sql);
        $p->setParameter(1, $entidade->getId(), PreparedStatement::INTEGER);
        $p->execute();
    }

    public function getById($id) {
        $sql = "SELECT id, data_criacao, nome, descricao, data_vestibular, coordenador, email, corpo_docente, 
                        publico_alvo, valor, duracao, videoapres, areacurso_id, nivelgraduacao, 
                        contatosecretaria, excluida, credito
                    FROM curso where id = $1";

        $p = $this->getConn()->prepare($sql);
        $p->setParameter(1, $id, PreparedStatement::INTEGER);
        $rs = $p->getResult();

        if (!$rs->next()) {
            throw new NoResultException("Curso não encontrado");
        }

        return $this->montarCurso($rs);
    }

    /**
     * 
     * @param ResultSet $rs
     * @return Curso
     */
    public function montarCurso(ResultSet $rs) {
        $arr = $rs->fetchArray();
        $cr = new Curso();
        $cr->setId($arr['id']);
        $cr->setNome($arr['nome']);
        $cr->setDescricao($arr['descricao']);
        $cr->setAreaCurso($arr['areacurso_id']);
        $cr->setContatoSecretaria($arr['contatosecretaria']);
        $cr->setCoordenador($arr['coordenador']);
        $cr->setCorpoDocente($arr["corpo_docente"]);
        $cr->setDataVestibular(DAOUtil::toDateTime($arr["data_vestibular"]));
        $cr->setDataCriacao(DAOUtil::toDateTime($arr["data_criacao"]));
        $cr->setDuracao($arr['duracao']);
        $cr->setNivelGraduacao($arr['nivelgraduacao']);
        $cr->setPublicoAlvo($arr['publico_alvo']);
        $cr->setValor($arr['valor']);
        $cr->setVideoApresentacao($arr['videoapres']);
        $cr->setEmail($arr['email']);
        $cr->setCredito($arr['credito']);
        return $cr;
    }

    /**
     * 
     * @return array
     */
    public function carregarCurso() {

        $sql = "SELECT id, nome, data_criacao, descricao, data_vestibular, coordenador, email, corpo_docente, 
                        publico_alvo, valor, duracao, videoapres, areacurso_id, nivelgraduacao, 
                        contatosecretaria, excluida, credito
                    FROM curso";

        $p = $this->getConn()->prepare($sql);

        // Pega o resultado
        $rs = $p->getResult();

        // Itera sobre o resultado
        $list = array();
        while ($rs->next()) {

            // Monta o objeto 
            $list[] = $this->montarCurso($rs);
        }

        // Retorna a lista montada.
        return $list;
    }

    public function carregarUltimosAvisos(Usuario $usuario) {

        // Prepara a querie ordenando pela data decrescente

        $sql = "select usu.nome as criadopor, 
                    avi.titulo as titulo, 
                    avi.descricao as descricao, 
                    ad.usuario_id as id_destino,
                    avi.id as id,
                    avi.data_criacao as data_criacao,
                    ad.lido as lido
                    from usuarios usu
                        join aviso avi on usu.id = avi.usuario_id
                        inner join aviso_destinatario ad on avi.id =  ad.aviso_id
                    where 
                        ad.usuario_id = $1
                        and avi.excluida = false
                        and ad.excluida = false
                    order by avi.id desc limit 10";

        $p = $this->getConn()->prepare($sql);

        // Seta os parãmetros
        $p->setParameter(1, $usuario->getId(), PreparedStatement::INTEGER);

        // Pega o resultado
        $rs = $p->getResult();

        // Itera sobre o resultado
        $list = array();
        while ($rs->next()) {

            // Monta o objeto 
            $list[] = $this->montarAviso($rs, $usuario);
        }

        // Retorna a lista montada.
        return $list;
    }

    public function avisoLido(Entidade $entidade, Usuario $usuario) {
        $sql = "update aviso_destinatario set lido = true 
                    where aviso_id=$1
                        and usuario_id = $2";

        $p = $this->getConn()->prepare($sql);
        $p->setParameter(1, $entidade->getId(), PreparedStatement::INTEGER);
        $p->setParameter(2, $usuario->getId(), PreparedStatement::INTEGER);
        $p->execute();
    }

    public function carregarMeusAviso(Entidade $entidade) {

        $sql = "select usu.nome as criadopor,
                    av.titulo as titulo, 
                    av.descricao as descricao,
                    av.data_criacao as data_criacao,
                    av.id as id
                    from usuarios usu
                        join aviso av on av.usuario_id = usu.id
                    where 
                        av.usuario_id= $1
                        and av.excluida = false
                        order by av.id desc
              ";

        $p = $this->getConn()->prepare($sql);

        // Seta os parãmetros
        $p->setParameter(1, $entidade->getId(), PreparedStatement::INTEGER);

        // Pega o resultado
        $rs = $p->getResult();

        // Itera sobre o resultado
        $list = array();
        while ($rs->next()) {

            // Monta o objeto 
            $list[] = $this->montarAviso($rs, $usuario);
        }

        // Retorna a lista montada.
        return $list;
    }

    public function deletarAvisoDestinatario(Entidade $entidade, Usuario $usuario) {
        $sql = "update aviso_destinatario set excluida = true 
                    where aviso_id=$1
                        and usuario_id = $2";

        $p = $this->getConn()->prepare($sql);
        $p->setParameter(1, $entidade->getId(), PreparedStatement::INTEGER);
        $p->setParameter(2, $usuario->getId(), PreparedStatement::INTEGER);
        $p->execute();
    }

}

?>