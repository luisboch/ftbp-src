<?php
require_once 'ftbp-src/servicos/impl/ServicoBasico.php';
require_once 'ftbp-src/daos/impl/CursoDAO.php';

/*
 * CursoServico.php
 */

/**
 * Description of CursoServico
 *
 * @author felipe
 */
class ServicoCurso extends ServicoBasico{
    
    /**
     * @var CursoDAO
     */
    private $cursoDAO;
    
    function __construct() {
        parent::__construct(new CursoDAO());
        $this->cursoDAO = $this->dao;
    }

    public function validar(Entidade $entidade) {
        $v = new ValidacaoExecao();
        
        if($entidade->getNome() == ''){
            $v->addError('nome curso inválido ->  curso '. $entidade->getNome(), 'curso');
        }
        
        if($entidade->getDescricao() == ''){
            $v->addError('Descrição curso inválido ->  Descrição '. $entidade->getDescricao(), 'descricao');
        }
        
        if(!$v->isEmtpy()){
            throw $v;
        }
        
    }
    
    public function carregarCurso(){
        return $this->cursoDAO->carregarCurso();
    }
    
}

?>
