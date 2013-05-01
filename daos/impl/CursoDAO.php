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
                        contatosecretaria, excluida, nao_sei)
                    VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12, $13, $14, false, '246924')";

        $p = $this->getConn()->prepare($sql);

        $p->setParameter(1, $id, PreparedStatement::INTEGER);
        $p->setParameter(2, $entidade->getNome(), PreparedStatement::STRING);
        $p->setParameter(3, $entidade->getDescricao(), PreparedStatement::STRING);
        $p->setParameter(4, $entidade->getDataVestibular(), PreparedStatement::STRING);
        $p->setParameter(5, $entidade->getCoordenador(), PreparedStatement::STRING);
        $p->setParameter(6, $entidade->getEmail(), PreparedStatement::STRING);
        $p->setParameter(7, $entidade->getCorpoDocente(), PreparedStatement::STRING);
        $p->setParameter(8, $entidade->getPublicoAlvo(), PreparedStatement::STRING);
        $p->setParameter(9, $entidade->getValor(), PreparedStatement::DOUBLE);
        $p->setParameter(10, $entidade->getDuracao(), PreparedStatement::DOUBLE);
        //$p->setParameter(11, $entidade->getVideoApresentacao(), PreparedStatement::STRING);
        $p->setParameter(11, 'xxxtubexxx', PreparedStatement::STRING);
        //arrumar o getareacurso para objeto
        $p->setParameter(12, $entidade->getAreaCurso(), PreparedStatement::INTEGER);
        $p->setParameter(13, $entidade->getNivelGraduacao(), PreparedStatement::STRING);
        $p->setParameter(14, $entidade->getContatoSecretaria(), PreparedStatement::STRING);
        //$p->setParameter(15, 0, PreparedStatement::INTEGER);
        //$p->setParameter(16, 'não sei', PreparedStatement::STRING);
        
        $p->execute();
        
        $entidade->setId($id);
    }

    public function executarUpdate(Entidade $entidade) {
        throw new Exception("Not implemented yet!");
    }
    
    public function executarDelete(Entidade $entidade) {
        $sql = " update aviso set excluida = true
                    where id=$1";
        $p = $this->getConn()->prepare($sql);
        $p->setParameter(1, $entidade->getId(), PreparedStatement::INTEGER);
        $p->execute();
    }

    public function getById($id) {
        $sql = "select av.id as id, av.titulo as titulo, 
                    av.descricao as descricao, av.data_criacao as data_criacao, 
                    usu.nome criadopor
                from aviso av 
                    left join usuarios usu on usu.id = av.usuario_id
                where 
                    av.id = $1
                    and av.excluida = false
                order by av.id desc";

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
        $av->setTitulo($arr['titulo']);
        $av->setDescricao($arr['descricao']);
        $av->setLido($arr['lido']);
        $av->setDataCriacao(DAOUtil::toDateTime($arr['data_criacao']));
        $av->setCriadoPor(new Usuario());
        $av->getCriadoPor()->setNome($arr['criadopor']);
        return $av;
    }

    /**
     * 
     * @return array
     */
    public function carregarAviso(Entidade $entidade) {
        
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
                    order by avi.id desc
              ";
        
        $p = $this->getConn()->prepare($sql);
        
        // Seta os parãmetros
        $p->setParameter(1, $entidade->getId(), PreparedStatement::INTEGER);
        
        // Pega o resultado
        $rs = $p->getResult();
        
        // Itera sobre o resultado
        $list = array();
        while($rs->next()){
            
            // Monta o objeto 
            $list[] = $this->montarAviso($rs, $usuario);
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
        while($rs->next()){
            
            // Monta o objeto 
            $list[] = $this->montarAviso($rs, $usuario);
        }
        
        // Retorna a lista montada.
        return $list;
    }
    
    public function avisoLido(Entidade $entidade, Usuario $usuario){
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
        while($rs->next()){
            
            // Monta o objeto 
            $list[] = $this->montarAviso($rs, $usuario);
        }
        
        // Retorna a lista montada.
        return $list;
    }
    
    public function deletarAvisoDestinatario(Entidade $entidade, Usuario $usuario){
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