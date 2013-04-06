<?php
require_once 'ftbp-src/servicos/impl/ServicoBasico.php';
require_once 'ftbp-src/daos/impl/AvisoDAO.php';

/*
 * AvisoServico.php
 */

/**
 * Description of AvisoServico
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
        
        if($entidade->getNome() == ''){
            $v->addError('titulo aviso inválido ->  nome '. $entidade->getNome(), 'titulo');
        }
        
        if($entidade->getDescricao() == ''){
            $v->addError('Descrição aviso inválido ->  Descrição '. $entidade->getDescricao(), 'descricao');
        }
        
        if(count($entidade->getUsuariosAlvo()) === 0){
            $v->addError("Selecione ao menos um destinatário");
        }
        
        // Verifica se existe usuários duplicados na lista de usuarios alvo, se existir remove da lista.
        $usuariosValidos = array();
        
        foreach($entidade->getUsuariosAlvo() as $value){
            if(!$this->usuarioExisteNaLista($usuariosValidos, $value)){
                $usuariosValidos[] = $value;
            }
        }
        
        $entidade->setUsuariosAlvo($usuariosValidos);
       
        
        if(!$v->isEmtpy()){
            throw $v;
        }
    }
    /**
     * 
     * @return array
     */
    public function carregarAviso() {
        return $this->avisoDAO->carregarAviso();
    }
    
    /**
     *
     * @param array $array
     * @param Usuario $usuario 
     * @return boolean
     */
    private function usuarioExisteNaLista($array,Usuario $usuario){
        foreach ($array as $v) {
            if($v->getId() === $usuario->getId()){
                return true;
            }
        }
        return false;
    }
    
    public function carregarUltimosAvisos(Usuario $usuario) {
        return $this->avisoDAO->carregarUltimosAvisos($usuario);
    }
}

?>
