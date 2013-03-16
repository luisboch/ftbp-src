<?php

require_once 'ftbp-src/servicos/Servico.php';
require_once 'ftbp-src/servicos/execoes/ValidacaoExecao.php';
require_once 'ftbp-src/servicos/execoes/AcessoExecao.php';
/*
 * ServicoBasico.php
 */

/**
 * Description of ServicoBasico
 *
 * @author Luis
 * @since Feb 24, 2013
 */
abstract class ServicoBasico implements EntidadeServico {

    /**
     * @var type 
     */
    protected static $logger;

    /**
     *
     * @var ServicoPesquisa  
     */
    protected $servicoPesquisa;

    /**
     *
     * @var ServicoNotificacao
     */
    protected $servicoNotificacao;

    /**
     *
     * @var DAOBasico
     */
    protected $dao;

    /**
     * 
     * @param DAOBasico $dao
     * @param boolean $loadCoreServices
     */
    function __construct(DAOBasico $dao, $loadCoreServices = true) {

        $this->dao = $dao;
        $this->dao->connect();

        if($iniciarServicos){
            require_once 'ftbp-src/servicos/impl/ServicoNotificacao.php';
            require_once 'ftbp-src/servicos/impl/ServicoPesquisa.php';

            // Monta os servicos
            $this->servicoPesquisa = new ServicoPesquisa();
            $this->servicoNotificacao = new ServicoNotificacao();
        }
        if (self::$logger === NULL) {
            self::$logger = Logger::getLogger(__CLASS__);
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
        
        try {
            
            if($autoCommit){
                // Inicia a transação
                $this->dao->getConn()->begin();
            }
            
            // Executa o insert da entidade
            $this->dao->executarInsert($entidade);
            
            // Checa se é notificável e salva as notificações caso for
            if ($entidade instanceof Notificavel) {
                $this->salvarNotificacoes($entidade);
            }
            
            // Checa se é Pesquisável e salva na tabela de pesquisa.
            if($entidade instanceof Pesquisavel){
                $this->inserirPesquisa($entidade);
            }
            
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
        try {
            if($autoCommit){
                // Inicia a transação
                $this->dao->getConn()->begin();
            }
            // Executa o update na entidade
            $this->dao->executarUpdate($entidade);

            // Checa se é notificável e salva as notificações caso for
            if ($entidade instanceof Notificavel) {
                $this->salvarNotificacoes($entidade);
            }
            
            // Checa se é Pesquisável e salva na tabela de pesquisa.
            if($entidade instanceof Pesquisavel){
                $this->atualizarPesquisa($entidade);
            }
            
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

    /**
     * Insere as notificações para todos os usuários da lista do Notificavel
     * @param Notificavel $entidade
     */
    protected function salvarNotificacoes(Notificavel $entidade) {
        
        $list = $entidade->getUsuariosAlvo();

        if (is_array($list)) {
            
            for ($i = 0; $i < count($list); $i++) {
                
                $u = $list[$i];
                $n = new Notificacao();
                $n->setUsuario($u);
                $n->setData($entidade->getData());
                $n->setDataExpiracao($entidade->getDataExpiracao());
                $n->setDescricao($entidade->getMensagem());
                $n->setLink($entidade->getLink());
                
                // Salva a notificação evitando a abertura de nova transação
                // pois já estamos dentro de uma.
                $this->servicoNotificacao->inserir($n, false);
                
            }
        }
    }

    /**
     * Remove os links para a entidade, e coloca-os novamente, atualizando-o
     * @param Pesquisavel $pesquisavel
     */
    protected function atualizarPesquisa(Pesquisavel $pesquisavel){
        $this->servicoPesquisa->atualizar($pesquisavel, false);
    }

    /**
     * Adiciona a entidade à tabela de pesquisa.
     * @param Pesquisavel $pesquisavel
     */
    public function inserirPesquisa(Pesquisavel $pesquisavel){
        $this->servicoPesquisa->atualizar($pesquisavel, false);
    }

    /**
     * @throws AcessoExecao
     */
    public function checarAcesso() {
        // usando debug_backtrace() e get_class mapear o caller e validar o acesso do usuário.
    }

    public function remover(Entidade $entidade) {
        // Todo avaliar quais entidades não podem ser removidas, 
        // e apenas atualizar o status para false (deleção lógica)
        $this->dao->executarDelete($entidade);
    }

    /**
     * 
     * @return DAOBasico
     */
    public function getDao() {
        return $this->dao;
    }

    /**
     * @param integer $id
     * @return Entidade
     */
    public function getById($id) {
        return $this->dao->getById($id);
    }

}

?>
