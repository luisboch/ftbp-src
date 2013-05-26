<?php

/**
 *
 * @author luis
 */
class ServicoAcao {

    private $acoes = array();

    function __construct($inserir = null, $atualizar = null) {
        $this->acoes['inserir'] = $inserir;
        $this->acoes['atualizar'] = $atualizar;
    }

    function antesDeAtualizar($novo, $velho) {
        if ($this->acoes['atualizar'] !== null) {
            $this->acoes['atualizar']($novo, $velho);
        }
    }

    function antesDoSalvar($novo) {
        if ($this->acoes['inserir']  !== null) {
            $this->acoes['inserir']($novo);
        }
    }

}

?>
