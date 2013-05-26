<?php

require_once 'ftbp-src/entidades/basico/Requisicao.php';
require_once 'ftbp-src/entidades/basico/RequisicaoIteracao.php';
require_once 'ftbp-src/daos/impl/UsuarioDAO.php';
require_once 'ftbp-src/daos/impl/lazy/RequisicaoLazy.php';
require_once 'ftbp-src/daos/impl/DAOUtil.php';

/**
 * 
 * RequisicaoDAO.php
 */

/**
 * Description of RequisicaoDAO
 *
 * @author luis
 */
class RequisicaoDAO extends DAOBasico {

   private $usuarioDAO;
   
   function __construct() {
       parent::__construct();
       $this->usuarioDAO = new UsuarioDAO();
   }

    public function executarDelete(Entidade $entidade) {

        // Primeiro exclui todas as iterações.
        $sql = "delete from requisicoes_iteracoes where requisicao_id = $1";
        $p = $this->getConn()->prepare($sql);
        $p->setParameter(1, $entidade->getId(), PreparedStatement::INTEGER);
        $p->execute();

        // Depois exclui a requisicao em si.
        $sql = "delete from requisicao where id = $1";
        $p = $this->getConn()->prepare($sql);
        $p->setParameter(1, $entidade->getId(), PreparedStatement::INTEGER);
        $p->execute();
        
    }

    public function executarInsert(Entidade $entidade) {
        
        // Valida os dados básicos
        if ($entidade->getCriadoPor() === NULL) {
            throw new InvalidArgumentException("Requisicao sem criador não é permitido.");
        }

        if ($entidade->getUsuario() === NULL) {
            throw new InvalidArgumentException("Requisicao sem usuario não é permitido.");
        }

        // Pega o proximo id da requisicao.
        $sql = "select nextval('requisicoes_id_seq') as id";
        $p = $this->getConn()->prepare($sql);
        $rs = $p->getResult();
        $rs->next();
        $arr = $rs->fetchArray();
        $id = $arr['id'];

        // Insere a requisicao
        $sql = "insert 
                  into requisicoes(id, titulo, descricao, usuario_id, criado_por, status, prioridade)
                values ($1, $2, $3, $4, $5, 'ABERTO', $6)";
        $p = $this->getConn()->prepare($sql);

        // Seta os parãmetros.
        $p->setParameter(1, $id, PreparedStatement::INTEGER);
        $p->setParameter(2, $entidade->getTitulo(), PreparedStatement::STRING);
        $p->setParameter(3, $entidade->getDescricao(), PreparedStatement::STRING);
        $p->setParameter(4, $entidade->getUsuario()->getId(), PreparedStatement::INTEGER);
        $p->setParameter(5, $entidade->getCriadoPor()->getId(), PreparedStatement::INTEGER);
        $p->setParameter(6, $entidade->getPrioridade(), PreparedStatement::STRING);

        // Executa o insert.
        $p->execute();

        $entidade->setId($id);
    }

    /**
     * Este método não altera a data de criação da requisição, 
     * muito menos o usuario criador.
     * Apenas altera o titulo e a mensagem e as requisições.
     * @param Entidade $entidade
     * @throws InvalidArgumentException
     */
    public function executarUpdate(Entidade $entidade) {

        if ($entidade->getUsuario() === NULL) {
            throw new InvalidArgumentException("Requisicao sem usuario não é permitido.");
        }

        // Primeiro exclui todas as iterações.
        $sql = "delete from requisicoes_iteracoes where requisicao_id = $1";
        
        $p = $this->getConn()->prepare($sql);
        $p->setParameter(1, $entidade->getId(), PreparedStatement::INTEGER);
        $p->execute();

        // Executa o update na requisição
        $sql = "update requisicoes
                   set titulo = $1, 
                       descricao = $2, 
                       usuario_id = $3, 
                       status = $4,
                       prioridade = $5,
                       fechado_por = $6
                 where id = $7";
        
        $p = $this->getConn()->prepare($sql);
        // Seta os parãmetros.
        $p->setParameter(1, $entidade->getTitulo(), PreparedStatement::STRING);
        $p->setParameter(2, $entidade->getDescricao(), PreparedStatement::STRING);
        $p->setParameter(3, $entidade->getUsuario()->getId(), PreparedStatement::INTEGER);
        $p->setParameter(4, $entidade->getStatus(), PreparedStatement::STRING);
        $p->setParameter(5, $entidade->getPrioridade(), PreparedStatement::STRING);
        
        if($entidade->getFechadoPor() !== null){
            $p->setParameter(6, $entidade->getFechadoPor()->getId(), PreparedStatement::INTEGER);
        } else {
            $p->setParameter(6, null, PreparedStatement::INTEGER);
        }
        
        $p->setParameter(7, $entidade->getId(), PreparedStatement::INTEGER);
        
        $p->execute();
        
        // Insere todas as iterações novamente
        $sql = "insert 
                  into requisicoes_iteracoes(
                       requisicao_id, 
                       usuario_id, 
                       mensagem,
                       data_criacao)
                values ($1, $2, $3, $4)";
        
        $p = $this->getConn()->prepare($sql);
        
        // Seta os parametros e executa insert para cada iteração da requisição
        if ($entidade->getIteracoes() != null && is_array($entidade->getIteracoes())) {
            
            foreach ($entidade->getIteracoes() as $obj) {

                if ( $obj->getUsuario() === NULL) {
                    throw new InvalidArgumentException("Iteração sem usuario não é permitido.");
                }
                
                $p->setParameter(1, $entidade->getId(), PreparedStatement::INTEGER);
                $p->setParameter(2, $obj->getUsuario()->getId(), PreparedStatement::INTEGER);
                $p->setParameter(3, $obj->getMensagem(), PreparedStatement::STRING);
                $p->setParameter(4, DAOUtil::toDataBaseTime($obj->getDataCriacao()), PreparedStatement::STRING);
                
                $p->execute();
            }
        }
    }

    /**
     * 
     * @param integer $id
     * @return Requisicao
     * @throws NoResultException
     */
    public function getById($id) {
        
        $sql = "select * from requisicoes where id = $1";
        
        $p = $this->getConn()->prepare($sql);
        $p->setParameter(1, $id, PreparedStatement::INTEGER);
        
        $rs = $p->getResult();
        
        if(!$rs->next()){
            throw new NoResultException("Requisição id: ".$id." não encontrada.");
        }
        
        return $this->montarRequisicao($rs);
    }
    
    /**
     * @param Usuario $usuario
     * @return Requisicao[]
     */
    public function getByUsuario(Usuario $usuario, $limit = null) {
        
        $sql = "select *
                  from requisicoes 
                 where usuario_id = $1
                 ".($limit !== null?'limit $2':'');
        
        $p = $this->getConn()->prepare($sql);
        $p->setParameter(1, $usuario->getId(), PreparedStatement::INTEGER);
        
        if($limit!==null){
            $p->setParameter(2, $limit, PreparedStatement::INTEGER);
        }
        
        $rs = $p->getResult();
        
        $list = array();
        
        while($rs->next()){
            $list[] = $this->montarRequisicao($rs);
        }
        
        return $list;
        
    }
    
    /**
     * @param Usuario $usuario
     * @return Requisicao[]
     */
    public function getByCriador(Usuario $usuario) {
        
        $sql = "select * from requisicoes where criado_por = $1";
        
        $p = $this->getConn()->prepare($sql);
        $p->setParameter(1, $usuario->getId(), PreparedStatement::INTEGER);
        
        $rs = $p->getResult();
        
        $list = array();
        
        while($rs->next()){
            $list[] = $this->montarRequisicao($rs);
        }
        
        return $list;
        
    }
    
    /**
     * 
     * @param ResultSet $rs
     * @return Requisicao
     */
    private function montarRequisicao(ResultSet $rs) {
        $rq = new RequisicaoLazy($this);
        
        $arr = $rs->fetchArray();
        
        $rq->setId($arr['id']);
        $rq->setUsuario($this->usuarioDAO->getById($arr['usuario_id']));
        $rq->setCriadoPor($this->usuarioDAO->getById($arr['criado_por']));
        $rq->setDataCriacao(DAOUtil::toDateTime($arr['data_criacao']));
        $rq->setTitulo($arr['titulo']);
        $rq->setDescricao($arr['descricao']);
        $rq->setStatus($arr['status']);
        $rq->setPrioridade($arr['prioridade']);
        
        if($arr['fechado_por'] !== NULL){
            $rq->setFechadoPor($this->usuarioDAO->getById($arr['fechado_por']));
        }
        
        return $rq;
        
    }
    
    public function carregarIteracoes(Requisicao $rq) {
        
        $sql = "select ri.requisicao_id,
                       ri.mensagem, 
                       ri.data_criacao as it_criacao,
                       u.* 
                  from requisicoes_iteracoes ri 
                  join usuarios u on (ri.usuario_id = u.id)
                 where requisicao_id = $1
              order by ri.data_criacao ";
        
        $p = $this->getConn()->prepare($sql);
        
        $p->setParameter(1, $rq->getId(), PreparedStatement::INTEGER);
        
        $rs = $p->getResult();
        
        $list = array();
        
        while($rs->next()){
            
            $arr = $rs->fetchArray();
            
            $it = new RequisicaoIteracao();
            
            // Monta o usuário da iteração
            $u = $this->usuarioDAO->montarUsuario($rs);
            
            // Seta os valores
            $it->setUsuario($u);
            
            $it->setDataCriacao(DAOUtil::toDateTime($arr['it_criacao']));
            $it->setMensagem($arr['mensagem']);
            $it->setRequisicao($rq);
            
            $list[] = $it;
            
        }
        
        $rq->setIteracoes($list);
        
    }
    
    public function setConn(Connection $conn) {
        parent::setConn($conn);
        $this->usuarioDAO->setConn($conn);
    }
    
    public function connect() {
        parent::connect();
        $this->usuarioDAO->connect();
    }
    
}

?>
