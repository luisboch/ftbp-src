<?php
require_once 'ftbp-src/servicos/impl/ServicoBasico.php';
require_once 'ftbp-src/daos/impl/NotificacaoDAO.php';
require_once 'ftbp-src/servicos/execoes/ValidacaoExecao.php';


/**
 * Description of ServicoNotificacao
 *
 * @author luis
 */
class ServicoNotificacao extends ServicoBasico {
    
    function __construct() {
        parent::__construct(new NotificacaoDAO());
    }

    public function validar(Entidade $entidade) {
       
       $v = new ValidacaoExecao();
       
       if($entidade->getData() == null){
           $entidade->setData(new DateTime());
       }
       
       if($entidade->getLink() === NULL){
           $entidade->setLink('');
       }
       
       if($entidade->getUsuario() === null){
           $v->addError("Usuário não setado para a notificação.");
       }
       
       if(!$v->isEmtpy()){
           throw $v;
       }
    }    
}

?>
