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
    protected static $logger;
    /**
     *
     * @var DAOBasico
     */
    protected $dao;

    function __construct(DAOBasico $dao) {
        
        $this->dao = $dao;
        $this->dao->connect();
        
        if(self::$logger === NULL){
            self::$logger=  Logger::getLogger(__CLASS__);
        }
        
    }

    public function atualizar(Entidade $entidade) {
        $this->checarAcesso();
        try {
            $this->dao->getConn()->begin();
            $this->dao->executarUpdate($entidade);
            $this->dao->getConn()->commit();
        } catch (Exception $e) {
            $this->dao->getConn()->rollback();
            $this->dao->reconnect();
        }
    }
    /**
     * @throws AcessoExecao
     */
    public function checarAcesso() {
        // usando debug_backtrace() e get_class mapear o caller e validar o acesso do usuário.
    }

    public function remover(Entidade $entidade) {
        
    }

    /**
     * 
     * @return DAOBasico
     */
    public function getDao() {
        return $this->dao;
    }

    public function inserir(Entidade $entidade) {
        $this->checarAcesso();
         try {
            $this->dao->getConn()->begin();
            $this->dao->executarInsert($entidade);
            $this->dao->getConn()->commit();
        } catch (Exception $e) {
            $this->dao->getConn()->rollback();
            $this->dao->reconnect();
            throw $e;
        }
    }
    /**
     * 
     * @param integer $id
     * @return Entidade
     */
    public function getById($id){
        return $this->dao->getById($id);
    }

}

?>
