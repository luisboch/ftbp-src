<?php
require_once 'ftbp-src/servicos/impl/ServicoBasico.php';
require_once 'ftbp-src/daos/impl/NotificacaoDAO.php';
require_once 'ftbp-src/servicos/execoes/ValidacaoExecao.php';


/**
 * Description of ServicoNotificacao
 *
 * @author luis
 */
class ServicoNotificacao extends ServicoBasico {
    
    /**
     *
     * @var NotificacaoDAO
     */
    private $notificaoDAO ;
    
    function __construct() {
        parent::__construct(new NotificacaoDAO(), false);
        $this->notificaoDAO = $this->dao;
    }

    public function validar(Entidade $entidade) {
       
       $v = new ValidacaoExecao();
       
       if($entidade->getData() == null){
           $entidade->setData(new DateTime());
       }
       
       if($entidade->getLink() === NULL){
           $entidade->setLink('');
       }
       
       if($entidade->getDataExpiracao() === null){
           $data = new DateTime();
           // Adiona um mes de tempo para expiração.
           $data->add(new DateInterval('P1M'));
           $entidade->setDataExpiracao($data);
       }
       
       if(!$v->isEmtpy()){
           throw $v;
       }
    }    
    
    /**
     * Salva a entidade, e se esta for instancia de Notificavel 
     * realiza a notificação, se for instancia de Pesquisavel, inclui
     * na tabela de pesquisa.
     * @param Entidade $entidade
     */
    public function inserir(Entidade $entidade, $autoCommit = true) {
        $this->checarAcesso();
        
        $this->validar($entidade);
        
        try {
            
            if($autoCommit){
                // Inicia a transação
                $this->dao->getConn()->begin();
            }
            
            // Executa o insert da entidade
            $this->dao->executarInsert($entidade);
            
            if($autoCommit){
                // Fecha a transação
                $this->dao->getConn()->commit();
            }
        } catch (Exception $e) {
            
            // Pega qualquer erro, tenta o rollback no banco (se usou transacao) 
            // e tenta reconexão
            if($autoCommit){
                $this->dao->getConn()->rollback();
                $this->dao->reconnect();
            }
            
            throw $e;
        }
    }
    
    /**
     * Salva a entidade, e se esta for instancia de Notificavel 
     * realiza a notificação, se for instancia de Pesquisavel, inclui
     * na tabela de pesquisa.
     * @param Entidade $entidade
     */
    public function atualizar(Entidade $entidade, $autoCommit = true) {
        
        $this->checarAcesso();
        
        $this->validar($entidade);
        
        try {
            if($autoCommit){
                // Inicia a transação
                $this->dao->getConn()->begin();
            }
            // Executa o update na entidade
            $this->dao->executarUpdate($entidade);
            
            if($autoCommit){
                // Fecha a transação
                $this->dao->getConn()->commit();
            }
            
        } catch (Exception $e) {
            
            // Pega qualquer erro, tenta o rollback no banco 
            // e tenta reconexão
            if($autoCommit){
                $this->dao->getConn()->rollback();
                $this->dao->reconnect();
            }
            
            throw $e;
        }
    }
    
    public function carregarUltimasNotificacoes(Usuario $usuario) {
        return $this->notificaoDAO->carregarUltimasNotificacoes($usuario);
    }
    
    public function getByUser(Usuario $usuario) {
        return $this->notificaoDAO->getByUser($usuario);
    }
    
    
}

?>
