<?php

require_once 'ftbp-src/entidades/basico/Notificacao.php';
require_once 'ftbp-src/daos/impl/DAOBasico.php';
require_once 'ftbp-src/daos/impl/DAOUtil.php';

/**
 * Description of NotificacaoDAO
 *
 * @author luis
 */
class NotificacaoDAO extends DAOBasico {

    /**
     * @param Notificacao $entidade
     */
    public function executarDelete(Entidade $entidade) {
        // Prepara a querie
        $sql = "delete 
                  from notificacoes 
                 where id = $1";
        $p = $this->getConn()->prepare($sql);

        // seta os parãmetros
        $p->setParameter(1, $entidade->getId(), PreparedStatement::INTEGER);

        // e finalmente executa.
        $p->execute();
    }

    /**
     * 
     * @param Notificacao $entidade
     */
    public function executarInsert(Entidade $entidade) {

        
        // Pega todos os ids dos usuários que existem.
        $rs = $this->getConn()->query("select id from usuarios");
        $usuarios = array();
        
        while($rs->next()){
            $arr = $rs->fetchArray();
            $usuarios[] = $arr['id'];
        }
        
        // Prepara a querie
        $sqlInisert = "
            insert
              into notificacoes(
                   id, 
                   usuario_id,
                   descricao, 
                   data,
                   data_expiracao,
                   link)
            values ( nextval('notificacoes_id_seq') , $1, $2, $3, $4, $5)";
        
        $p = $this->getConn()->prepare($sqlInisert);

        // seta os parãmetros
        $p->setParameter(2, $entidade->getDescricao(), PreparedStatement::STRING);
        $p->setParameter(3, DAOUtil::toDataBaseTime($entidade->getData()), PreparedStatement::STRING);

        if ($entidade->getDataExpiracao() != null) {
            $p->setParameter(4, DAOUtil::toDataBaseTime($entidade->getDataExpiracao()), PreparedStatement::STRING);
        } else {
            $p->setParameter(4, NULL, PreparedStatement::STRING);
        }
        $p->setParameter(5, $entidade->getLink(), PreparedStatement::STRING);
        
        // Insere a notificação pra cada usuário.
        foreach( $usuarios as $v) {
            $p->setParameter(1, $v, PreparedStatement::INTEGER);
            $p->execute();
        }
    }

    /**
     * @param Notificacao $entidade
     */
    public function executarUpdate(Entidade $entidade) {

        // Prepara a querie
        $sql = "update notificacoes
                  set excluida= " . ($entidade->getExcluida() ? 'true' : 'false') . ",
                      visualizada= " . ($entidade->getVisualizada() ? 'true' : 'false') . "
                      link = $1
                where id = $2";
        $p = $this->getConn()->prepare($sql);

        // Seta os parãmetros
        $p->setParameter(1, $entidade->getLink(), PreparedStatement::STRING);
        $p->setParameter(2, $entidade->getId(), PreparedStatement::INTEGER);
        
        // e finalmente executa.
        $p->execute();
    }

    /**
     * @param integer $id
     * @return Notificacao 
     */
    public function getById($id) {

        // Valida o parãmetro
        if ($id == null) {
            throw new InvalidArgumentException(
            "Trying to get Entity with empty id!");
        }

        // Prepara a querie
        $sql = "select *
                  from notificacoes
                 where id = $1";
        $p = $this->getConn()->prepare($sql);

        // Seta os parãmetros
        $p->setParameter(1, $id, PreparedStatement::INTEGER);

        $rs = $p->getResult();

        if (!$rs->next()) {
            throw new NoResultException(
            "Entidade Notificação não encotrada com o id: " . $id);
        }
        return $this->montarNotificacao($rs);
    }

    /**
     * @param Usuario $usuario
     * @return List<Notificacao>
     */
    public function getByUser(Usuario $usuario) {
        
        // Prepara a querie ordenando pela data decrescente
        $sql = "select *
                  from notificacoes
                 where usuario_id = $1
                   and excluida = false
                   and data_expiracao >= now()
              order by \"data\" desc";
        
        $p = $this->getConn()->prepare($sql);
        
        // Seta os parãmetros
        $p->setParameter(1, $usuario->getId(), PreparedStatement::INTEGER);
        
        // Pega o resultado
        $rs = $p->getResult();
        
        // Itera sobre o resultado
        $list = array();
        while($rs->next()){
            
            // Monta o objeto 
            $list[] = $this->montarNotificacao($rs, $usuario);
        }
        
        // Retorna a lista montada.
        return $list;
    }

    /**
     * Monta a classe com a linha padrão do banco de dados.
     * @param ResultSet $rs
     * @param Usuario $usuario
     * @return Notificacao
     */
    public function montarNotificacao(ResultSet $rs, $usuario = null){
        
        $arr = $rs->fetchArray();
        
        $n = new Notificacao();
        
        $n->setId($arr['id']);
        $n->setData(DAOUtil::toDateTime($arr['data']));
        $n->setDataCriacao(DAOUtil::toDateTime($arr['data_criacao']));
        
        if($arr['data_expiracao'] != ''){
            $n->setDataExpiracao(DAOUtil::toDateTime($arr['data_expiracao']));
        }
        
        $n->setDescricao($arr['descricao']);
        $n->setExcluida($arr['excluida']);
        $n->setVisualizada($arr['visualizada']);
        $n->setLink($arr['link']);
        
        // TODO carregar usuário quando necessário.
        if($usuario !== null){
            $n->setUsuario($usuario);
        }
        
        return $n;
        
    }
    
    public function carregarUltimasNotificacoes(Usuario $usuario) {
         
        // Prepara a querie ordenando pela data decrescente
        $sql = "select *
                  from notificacoes
                 where usuario_id = $1
                   and excluida = false
                   and data_expiracao >= now()
              order by \"data\" desc limit 10";
        
        $p = $this->getConn()->prepare($sql);
        
        // Seta os parãmetros
        $p->setParameter(1, $usuario->getId(), PreparedStatement::INTEGER);
        
        // Pega o resultado
        $rs = $p->getResult();
        
        // Itera sobre o resultado
        $list = array();
        while($rs->next()){
            
            // Monta o objeto 
            $list[] = $this->montarNotificacao($rs, $usuario);
        }
        
        // Retorna a lista montada.
        return $list;
    }
}

?>