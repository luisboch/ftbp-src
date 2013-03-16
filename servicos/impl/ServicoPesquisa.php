<?php
require_once 'ftbp-src/servicos/impl/ServicoBasico.php';
require_once 'ftbp-src/daos/impl/PesquisaDAO.php';

/**
 * ServicoPesquisa
 *
 * @author luis
 */
class ServicoPesquisa extends ServicoBasico{
    
    
    function __construct() {
        parent::__construct(new PesquisaDAO(), false);
        
    }

    public function validar(Entidade $entidade) {
        // TODO validar entidade
    }    
}

?>
