<?php

require_once 'ftbp-src/daos/EntidadeDAO.php';
require_once 'ftbp-src/daos/impl/DAOUtil.php';
require_once 'ftbp-src/entidades/basico/Aviso.php';
require_once 'ftbp-src/entidades/basico/Usuario.php';
require_once 'DAOBasico.php';



class AvisoDAO extends DAOBasico {

    public function executarInsert(Entidade $entidade) {
        
        $sql = "select nextval('aviso_id_seq') as id";
        
        $rs = $this->getConn()->query($sql);
        
        $rs->next();
        
        $arr = $rs->fetchArray();
        
        $id = $arr['id'];
        
        $sql = "INSERT INTO aviso(
                    id, titulo, descricao, data_criacao, usuario_id, excluida)
                VALUES ($1,$2, $3, now(), $4, false)";

        $p = $this->getConn()->prepare($sql);

        $p->setParameter(1, $id, PreparedStatement::INTEGER);
        $p->setParameter(2, $entidade->getTitulo(), PreparedStatement::STRING);
        $p->setParameter(3, $entidade->getDescricao(), PreparedStatement::STRING);
        $p->setParameter(4, $entidade->getCriadoPor()->getId(), PreparedStatement::INTEGER);

        $p->execute();
        
        $sql = "insert into aviso_destinatario( aviso_id, usuario_id, lido, excluida) values ($1, $2, false, false)";
        
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