<?php
require_once 'ftbp-src/servicos/impl/ServicoBasico.php';
require_once 'ftbp-src/daos/impl/EventoDAO.php';

/*
 * ServicoServico.php
 */

/**
 * Description of EventoServico
 *
 * @author Felipe
 */

class ServicoEvento extends ServicoBasico{
    
    /**
     * @var EventoDAO
     */
    
    private $eventoDAO;
    
    function __construct() {
        parent::__construct(new EventoDAO());
        $this->eventoDAO = $this->dao;
    }

    public function validar(Entidade $entidade) {
        $v = new ValidacaoExecao();
        
        if($entidade->getTitulo() == ''){
            $v->addError('titulo evento inválido ->  titulo '. $entidade->getTitulo(), 'titulo');
        }
        
        if($entidade->getDescricao() == ''){
            $v->addError('Descrição evento inválido ->  Descrição '. $entidade->getDescricao(), 'descricao');
        }
        
        if(!$v->isEmtpy()){
            throw $v;
        }
    }
    /**
     * 
     * @return array
     */
    public function carregarEvento() {
        return $this->eventoDAO->carregarEvento();
    }
    
}

?>
