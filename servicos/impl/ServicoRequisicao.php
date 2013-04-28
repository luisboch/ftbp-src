<?php
require_once 'ftbp-src/servicos/Servico.php';
require_once 'ftbp-src/servicos/impl/ServicoBasico.php';
require_once 'ftbp-src/daos/impl/RequisicaoDAO.php';
require_once 'ftbp-src/session/SessionManager.php';

/**
 * ServicoRequisicao.php
 */

/**
 * Description of ServicoRequisicao
 *
 * @author luis
 */
class ServicoRequisicao extends ServicoBasico {
    
    function __construct() {
        parent::__construct(new RequisicaoDAO());
    }

    
    public function validar(Entidade $entidade) {
        
        if($this->stado===self::CRIACAO){
            $entidade->setStatus('ABERTO');
        }
        
        $v = new ValidacaoExecao();
        
        if($entidade->getCriadoPor() == null){
            $entidade->setCriadoPor(SessionManager::getInstance()->getUsuario());
        }
        
        if($entidade->getUsuario() == NULL){
            $v->addError("Selecione o usuário destino.");
        }
        
        if($entidade->getTitulo() == ''){
            $v->addError("Titulo inválido", 'titulo');
        }
        
        if($entidade->getDescricao() == ''){
            $v->addError("Descrição inválida.", 'descricao');
        }
        
        if($entidade->getIteracoes() != null && is_array($entidade->getIteracoes())){
            foreach ($entidade->getIteracoes() as $obj){
                if($obj->getMensagem() == ''){
                    $v->addError('Mensagem inválida');
                    $add = true;
                }
                
                if($obj->getUsuario() == null){
                    $v->addError('Usuário inválido');
                    $add = true;
                }
                
                if(!$v->isEmtpy()){
                    break;
                }
            }
        }
        
        if(!$v->isEmtpy()){
            throw $v;
        }
    }    
}

?>
