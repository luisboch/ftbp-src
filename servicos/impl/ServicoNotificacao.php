<?php
require_once 'ftbp-src/servicos/impl/ServicoBasico.php';
require_once 'ftbp-src/daos/impl/NotificacaoDAO.php';


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
        // TODO validar entidade
    }    
}

?>
