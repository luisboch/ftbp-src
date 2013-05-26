<?php
require_once 'ftbp-src/servicos/impl/ServicoBasico.php';
require_once 'ftbp-src/daos/impl/GrupoDAO.php';
require_once 'ftbp-src/servicos/execoes/ValidacaoExecao.php';

/**
 * @author luis
 */
class ServicoGrupo extends ServicoBasico{
    
    /**
     * @var GrupoDAO
     */
    private $grupoDAO;
    
    function __construct() {
        parent::__construct(new GrupoDAO());
        $this->grupoDAO = $this->dao;
    }

    public function validar(Entidade $entidade) {
        $v = new ValidacaoExecao();
        
        if($entidade->getNome() == ''){
            $v->addError('Nome do grupo inválido', 'nome');
        }
        
        if($entidade->getDataCriacao() == null){
            $entidade->setDataCriacao(new DateTime());
        }
        
        if(!$v->isEmtpy()){
            throw $v;
        }
    }
    /**
     * @return Grupo[]
     */
    public function carregarGrupos() {
        return $this->grupoDAO->carregarGrupos();
    }
    
}

?>
