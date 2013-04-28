<?php
require_once 'ftbp-src/entidades/basico/Requisicao.php';
require_once 'ftbp-src/entidades/basico/RequisicaoIteracao.php';
require_once 'ftbp-src/daos/impl/RequisicaoDAO.php';
/*
 * RequisicaoLazy.php
 */

/**
 *
 * @author Luis
 */
class RequisicaoLazy extends Requisicao{
    /**
     *
     * @var RequisicaoDAO
     */
    private $dao;
    
    /**
     *
     * @var boolean
     */
    private $iteracoesCarregado = false;
    
    function __construct(RequisicaoDAO $dao) {
        $this->dao = $dao;
    }
    
    public function getIteracoes() {
        if(!$this->iteracoesCarregado){
            $this->dao->carregarIteracoes($this);
        }
        return parent::getIteracoes();
    }
    
    public function setIteracoes($iteracoes) {
        parent::setIteracoes($iteracoes);
        $this->iteracoesCarregado = true;
    }
    
}

?>
