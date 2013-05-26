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
        parent::__construct(new PesquisaDAO(), false);
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

        if ($entidade->getLink() == '') {
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
    
    
    
    /**
     * Salva a entidade, e se esta for instancia de Notificavel 
     * realiza a notificação, se for instancia de Pesquisavel, inclui
     * na tabela de pesquisa.
     * @param Entidade $entidade
     */
    public function inserir(Entidade $entidade, $autoCommit = true) {
        $this->checarAcesso();
        
        $this->validar($entidade);
        
        try {
            
            if($autoCommit){
                // Inicia a transação
                $this->dao->getConn()->begin();
            }
            
            // Executa o insert da entidade
            $this->dao->executarInsert($entidade);
            
            if($autoCommit){
                // Fecha a transação
                $this->dao->getConn()->commit();
            }
        } catch (Exception $e) {
            
            // Pega qualquer erro, tenta o rollback no banco (se usou transacao) 
            // e tenta reconexão
            if($autoCommit){
                $this->dao->getConn()->rollback();
                $this->dao->reconnect();
            }
            
            throw $e;
        }
    }
    
    /**
     * Salva a entidade, e se esta for instancia de Notificavel 
     * realiza a notificação, se for instancia de Pesquisavel, inclui
     * na tabela de pesquisa.
     * @param Entidade $entidade
     */
    public function atualizar(Entidade $entidade, $autoCommit = true) {
        
        $this->checarAcesso();
        
        $this->validar($entidade);
        
        try {
            if($autoCommit){
                // Inicia a transação
                $this->dao->getConn()->begin();
            }
            // Executa o update na entidade
            $this->dao->executarUpdate($entidade);
            
            if($autoCommit){
                // Fecha a transação
                $this->dao->getConn()->commit();
            }
            
        } catch (Exception $e) {
            
            // Pega qualquer erro, tenta o rollback no banco 
            // e tenta reconexão
            if($autoCommit){
                $this->dao->getConn()->rollback();
                $this->dao->reconnect();
            }
            
            throw $e;
        }
    }
    
    /**
     * @param string $text
     * @return List<Pesquisa>
     */
    public function pesquisar($text) {
        $restrito = SessionManager::getInstance()->getUsuario() === NULL;
        return $this->dao->pesquisar($text, $restrito);
    }

}

?>
