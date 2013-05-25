<?php

require_once 'ftbp-src/daos/EntidadeDAO.php';
require_once 'ftbp-src/daos/impl/DAOUtil.php';
require_once 'ftbp-src/entidades/basico/Curso.php';
require_once 'ftbp-src/entidades/basico/CursoArquivo.php';
require_once 'ftbp-src/entidades/basico/AreaCurso.php';
require_once 'ftbp-src/entidades/basico/Departamento.php';
require_once 'ftbp-src/entidades/basico/Usuario.php';
require_once 'ftbp-src/daos/impl/lazy/CursoLazy.php';

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

        if ($entidade->getAreaCurso() == null) {
            $p->setParameter(12, null, PreparedStatement::INTEGER);
        } else {
            $p->setParameter(12, $entidade->getAreaCurso()->getId(), PreparedStatement::INTEGER);
        }

        $p->setParameter(13, $entidade->getNivelGraduacao(), PreparedStatement::STRING);
        $p->setParameter(14, $entidade->getContatoSecretaria(), PreparedStatement::STRING);
        $p->setParameter(15, $entidade->getCredito(), PreparedStatement::INTEGER);


        $p->execute();
        
        // Se possui uploads salva
        $this->inserirUploads($entidade);
        
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

        if ($entidade->getAreaCurso() == null) {
            $p->setParameter(11, null, PreparedStatement::INTEGER);
        } else {
            $p->setParameter(11, $entidade->getAreaCurso()->getId(), PreparedStatement::INTEGER);
        }

        $p->setParameter(12, $entidade->getNivelGraduacao(), PreparedStatement::STRING);
        $p->setParameter(13, $entidade->getContatoSecretaria(), PreparedStatement::STRING);
        $p->setParameter(14, $entidade->getId(), PreparedStatement::INTEGER);
        $p->setParameter(15, $entidade->getCredito(), PreparedStatement::INTEGER);
        $p->execute();

        $this->inserirUploads($entidade);
    }

    public function inserirUploads(Curso $entidade) {
        // Primeiro exclui todos os arquivos já salvos para o curso.

        $sql1 = "delete 
                   from curso_arquivos 
                  where curso_id = $1 ";
        // Prepara a querie.
        $p1 = $this->getConn()->prepare($sql1);

        $p1->setParameter(1, $entidade->getId(), PreparedStatement::INTEGER);

        $p1->execute();

        if ($entidade->getArquivos() != '' && is_array($entidade->getArquivos())
                && count($entidade->getArquivos()) > 0 ) {
            // Valida os dados básicos

            $sql2 = "insert 
                   into curso_arquivos(
                        curso_id, 
                        departamento_id, 
                        descricao, 
                        caminho, 
                        data_upload, 
                        usuario_id)
                 values ($1, $2, $3, $4, $5, $6)";
            $p2 = $this->getConn()->prepare($sql2);

            // Executa um loop nos arquivos
            foreach ($entidade->getArquivos() as $v) {

                if ($v->getSetor() == null) {
                    throw new InvalidArgumentException("Arquivo precisa de um setor associado!");
                }

                if ($v->getUsuario() == null) {
                    throw new InvalidArgumentException("Arquivo precisa de um usuario associado!");
                }

                // Seta os novos parâmetros.
                $p2->setParameter(1, $entidade->getId(), PreparedStatement::INTEGER);
                $p2->setParameter(2, $v->getSetor()->getId(), PreparedStatement::INTEGER);
                $p2->setParameter(3, $v->getDescricao(), PreparedStatement::STRING);
                $p2->setParameter(4, $v->getCaminho(), PreparedStatement::STRING);
                $p2->setParameter(5, DAOUtil::toDataBaseTime($v->getDataUpload()), PreparedStatement::STRING);
                $p2->setParameter(6, $v->getUsuario()->getId(), PreparedStatement::INTEGER);

                $p2->execute();
            }
        }
    }

    public function executarDelete(Entidade $entidade) {
        $sql = " update aviso set excluida = true
                    where id=$1";
        $p = $this->getConn()->prepare($sql);
        $p->setParameter(1, $entidade->getId(), PreparedStatement::INTEGER);
        $p->execute();
    }

    public function getById($id) {
        $sql = "select c.id, c.data_criacao, c.nome, c.descricao, data_vestibular, coordenador, email, corpo_docente, 
                       publico_alvo, valor, duracao, videoapres, areacurso_id, nivelgraduacao, 
                       contatosecretaria, excluida, credito,
                       ac.nome as ac_nome,
                       ac.data_criacao as ac_data_criacao
                  from curso c
                  left join area_curso ac on (ac.id = c.areacurso_id)
                 where c.id = $1";

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
        $cr = new CursoLazy($this);
        $cr->setId($arr['id']);
        $cr->setNome($arr['nome']);
        $cr->setDescricao($arr['descricao']);

        // Monta a area.
        if ($arr['areacurso_id'] != null) {
            
            $area = new AreaCurso();
            $area->setId($arr['areacurso_id']);
            $area->setNome($arr['ac_nome']);
            
            if ($arr['ac_data_criacao'] != null) {
                $area->setDataCriacao(DAOUtil::toDateTime($arr['ac_data_criacao']));
            }
            $cr->setAreaCurso($area);
        }

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

    /**
     * Carrega os arquivos do curso.
     * Atenção: todas as entidades relacionadas ( Departamento, Usuário ) 
     * são carregadas, porém do usuário apenas nome, email e id são preenchidos.
     * @param Curso $curso
     */
    public function carregarArquivos(Curso $curso) {

        // Prepara a consulta
        $sql = "select 
                       ca.descricao as arq_desc, 
                       ca.caminho as arq_caminho, 
                       ca.data_upload, 
                       u.id as usu_id, 
                       u.nome as usu_nome, 
                       u.email as usu_email, 
                       d.id as dp_id, 
                       d.nome as dp_nome, 
                       d.data_criacao as dp_data_criacao
                  from curso_arquivos ca 
                  join usuarios u on (u.id = ca.usuario_id)
                  join departamento d on (d.id = ca.departamento_id)
                 where curso_id = $1";
        $p = $this->getConn()->prepare($sql);

        // Seta o parâmetro
        $p->setParameter(1, $curso->getId(), PreparedStatement::INTEGER);

        //Recupera o resultado
        $rs = $p->getResult();

        $list[] = array();

        while ($rs->next()) {

            $arr = $rs->fetchArray();

            // Monta o usuário com atributos básicos.
            $u = new Usuario();
            $u->setId($arr['usu_id']);
            $u->setNome($arr['usu_nome']);
            $u->setEmail($arr['usu_email']);

            $d = new Departamento();
            $d->setId($arr['dp_id']);
            $d->setNome($arr['dp_nome']);
            $d->setDataCriacao(DAOUtil::toDateTime($arr['dp_data_criacao']));

            // Cria a instancia do Arquivo
            $arq = new CursoArquivo();

            // Seta os atributos.
            $arq->setCurso($curso);
            $arq->setSetor($d);
            $arq->setUsuario($u);
            $arq->setDataUpload(DAOUtil::toDateTime($arr['data_upload']));
            $arq->setCaminho($arr['arq_caminho']);
            $arq->setDescricao($arr['arq_desc']);
            $list[] = $arq;
        }

        $curso->setArquivos($list);
    }

}

?>