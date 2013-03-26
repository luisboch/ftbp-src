<?php
require_once 'ftbp-src/servicos/impl/ServicoBasico.php';
require_once 'ftbp-src/daos/impl/AvisoDAO.php';

/*
 * DepartamentoServico.php
 */

/**
 * Description of DepartamentoServico
 *
 * @author luis
 */
class ServicoAviso extends ServicoBasico{
    
    /**
     * @var AvisoDAO
     */
    private $avisoDAO;
    
    function __construct() {
        parent::__construct(new AvisoDAO());
        $this->avisoDAO = $this->dao;
    }

    public function validar(Entidade $entidade) {
        $v = new ValidacaoExecao();
        
        if($entidade->getTitulo() == ''){
            $v->addError('titulo aviso inválido', 'titulo');
        }
        
        if($entidade->getDataCriacao() == null){
            $entidade->setDataCriacao(new DateTime());
        }
        
        if(!$v->isEmtpy()){
            throw $v;
        }
    }
    /**
     * 
     * @return array
     */
    public function carregarDepartamentos() {
        return $this->deparamentoDAO->carregarDepartamentos();
    }
}

?>
