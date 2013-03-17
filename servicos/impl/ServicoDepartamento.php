<?php
require_once 'ftbp-src/servicos/impl/ServicoBasico.php';
require_once 'ftbp-src/daos/impl/DepartamentoDAO.php';

/*
 * DepartamentoServico.php
 */

/**
 * Description of DepartamentoServico
 *
 * @author luis
 */
class ServicoDepartamento extends ServicoBasico{
    
    /**
     * @var DepartamentoDAO
     */
    private $deparamentoDAO;
    
    function __construct() {
        parent::__construct(new DepartamentoDAO());
        $this->deparamentoDAO = $this->dao;
    }

    public function validar(Entidade $entidade) {
        $v = new ValidacaoExecao();
        
        if($entidade->getNome() == ''){
            $v->addError('Nome do departamento inválido', 'nome');
        }
        
        if($entidade->getDataCriacao() == null){
            $entidade->setDataCriacao(new DateTime());
        }
        
        if(!$v->isEmtpy()){
            throw $v;
        }
    }    
}

?>
