<?php

require_once 'ftbp-src/servicos/impl/ServicoBasico.php';
require_once 'ftbp-src/daos/impl/PesquisaDAO.php';

/**
 * ServicoPesquisa
 *
 * @author luis
 */
class ServicoPesquisa extends ServicoBasico {

    function __construct() {
        parent::__construct(new PesquisaDAO());
    }

    public function validar(Entidade $entidade) {

        $v = new ValidacaoExecao();

        if ($entidade->getBreveDescricao() == null) {
            $v->addError("Entidade pesquisável sem breve descrição não é permitida", "breveDescricao");
        }

        if ($entidade->getEntidade() === NULL) {
            $v->addError("Entidade pesquisável sem referência não é permitida
                (getEntidade() está nulo.", "entidade");
        }

        if ($entidade->getLink()) {
            $v->addError("Entidade pesquisável sem link não é permitido.", "link");
        }

        if ($entidade->getPalavrasChave() == NULL || !is_array($entidade->getPalavrasChave()) || count($entidade->getPalavrasChave()) == 0) {
            $v->addError("Entidade pesquisável sem palavras chave não é permitido.", "palavrasChave");
        }

        if ($entidade->getTipo() == "") {
            $v->addError("Entidade pesquisável sem tipo não é permitido.", "tipo");
        }

        if ($entidade->getTitulo() == "") {
            $v->addError("Entidade pesquisável sem titulo não é permitido.", "titulo");
        }

        if (!$v->isEmtpy()) {
            throw $v;
        }
    }

}

?>
