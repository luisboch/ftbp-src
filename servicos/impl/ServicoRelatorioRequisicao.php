<?php
require_once 'ftbp-src/servicos/impl/ServicoBasico.php';
require_once 'ftbp-src/daos/impl/RelatorioRequisicaoDAO.php';

/*
 * DepartamentoServico.php
 */

/**
 * Description of DepartamentoServico
 *
 * @author luis
 */
class ServicoRelatorioRequisicao extends ServicoBasico{
    
    /**
     * @var DepartamentoDAO
     */
    private $relatorioRequisicaoDAO;
    
    function __construct() {
        parent::__construct(new RelatorioRequisicaoDAO());
        $this->relatorioRequisicaoDAO = $this->dao;
    }

    public function validar(Entidade $entidade) {
        $v = new ValidacaoExecao();
        
        if($entidade->getTipo() == ''){
            $v->addError('Tipo invalido', 'tipo');
        }
        if($entidade->getDataInicio() == ''){
            $v->addError('Data de inicio Invalida', 'dataInicio');
        }
        if($entidade->getDataFim() == ''){
            $v->addError('Data final Invalida', 'dataFim');
        }
        
        if(!$v->isEmtpy()){
            throw $v;
        }
    }   
    
   /**
     * 
     * @return array
     */
    public function gerarRelatorioFechamento(Entidade $entidade) {
        $this->validar($entidade);
        return $this->relatorioRequisicaoDAO->gerarRelatorioFechamento($entidade);
    }
  
    public function gerarRelatorioAbertura(Entidade $entidade) {
        $this->validar($entidade);
        return $this->relatorioRequisicaoDAO->gerarRelatorioAbertura($entidade);
    }
}

?>
