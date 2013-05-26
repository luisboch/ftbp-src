<?php

require_once 'ftbp-src/servicos/listener/ServicoAcao.php';
require_once 'ftbp-src/servicos/Servico.php';
require_once 'ftbp-src/servicos/execoes/ValidacaoExecao.php';
require_once 'ftbp-src/servicos/execoes/AcessoExecao.php';
/*
 * ServicoBasico.php
 */

/**
 * Description of ServicoBasico
 *
 * @author Luis
 * @since Feb 24, 2013
 */
abstract class ServicoBasico implements EntidadeServico {

    const CRIACAO = 'CRIAÇAO';
    const ATUALIZACAO = 'ATUALIZAÇÃO';

    /**
     * @var type 
     */
    protected static $logger;

    /**
     *
     * @var ServicoPesquisa  
     */
    protected $servicoPesquisa;

    /**
     *
     * @var ServicoNotificacao
     */
    protected $servicoNotificacao;

    /**
     *
     * @var DAOBasico
     */
    protected $dao;
    protected $stado;

    /**
     *
     * @var ServicoAcao[]
     */
    private $listeners;

    /**
     * 
     * @param DAOBasico $dao
     * @param boolean $loadCoreServices
     */
    function __construct(DAOBasico $dao, $iniciarServicos = true) {

        $this->dao = $dao;
        $this->dao->connect();

        $this->listeners = new ArrayObject();

        if ($iniciarServicos) {
            require_once 'ftbp-src/servicos/impl/ServicoNotificacao.php';
            require_once 'ftbp-src/servicos/impl/ServicoPesquisa.php';

            // Monta os servicos
            $this->servicoPesquisa = new ServicoPesquisa();
            $this->servicoNotificacao = new ServicoNotificacao();
        }
        if (self::$logger === NULL) {
            self::$logger = Logger::getLogger(__CLASS__);
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

        $this->stado = self::CRIACAO;

        $this->validar($entidade);


        $this->antesDeInserir($entidade);
        
        try {

            if ($autoCommit) {
                // Inicia a transação
                $this->dao->getConn()->begin();
            }

            // Executa o insert da entidade
            $this->dao->executarInsert($entidade);

            // Checa se é notificável e salva as notificações caso for
            if ($entidade instanceof Notificavel) {
                $this->salvarNotificacoes($entidade, true);
            }

            // Checa se é Pesquisável e salva na tabela de pesquisa.
            if ($entidade instanceof Pesquisavel) {
                $this->inserirPesquisa($entidade);
            }

            if ($autoCommit) {
                // Fecha a transação
                $this->dao->getConn()->commit();
            }
        } catch (Exception $e) {

            // Pega qualquer erro, tenta o rollback no banco (se usou transacao) 
            // e tenta reconexão
            if ($autoCommit) {
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

        $this->stado = self::ATUALIZACAO;

        $this->validar($entidade);

        $this->antesDeAtualizar($entidade, $this->getById($entidade->getId()));

        try {


            if ($autoCommit) {
                // Inicia a transação
                $this->dao->getConn()->begin();
            }
            // Executa o update na entidade
            $this->dao->executarUpdate($entidade);

            // Checa se é notificável e salva as notificações caso for
            if ($entidade instanceof Notificavel) {
                $this->salvarNotificacoes($entidade);
            }

            // Checa se é Pesquisável e salva na tabela de pesquisa.
            if ($entidade instanceof Pesquisavel) {
                $this->atualizarPesquisa($entidade);
            }

            if ($autoCommit) {
                // Fecha a transação
                $this->dao->getConn()->commit();
            }
        } catch (Exception $e) {

            // Pega qualquer erro, tenta o rollback no banco 
            // e tenta reconexão
            if ($autoCommit) {
                $this->dao->getConn()->rollback();
                $this->dao->reconnect();
            }

            throw $e;
        }
    }

    /**
     * Insere as notificações para todos os usuários da lista do Notificavel
     * @param Notificavel $entidade
     */
    protected function salvarNotificacoes(Notificavel $entidade, $new = false) {

        $n = new Notificacao();
        $n->setData($entidade->getData());

        if ($entidade->getDataExpiracao() !== null) {
            $n->setDataExpiracao($entidade->getDataExpiracao());
        }

        $n->setDescricao($entidade->getMensagem($new));
        $n->setLink($entidade->getLink());

        // Salva a notificação evitando a abertura de nova transação
        // pois já estamos dentro de uma.
        $this->servicoNotificacao->inserir($n, false);
    }

    /**
     * Remove os links para a entidade, e coloca-os novamente, atualizando-o
     * @param Pesquisavel $pesquisavel
     */
    protected function atualizarPesquisa(Pesquisavel $pesquisavel) {
        $this->servicoPesquisa->atualizar($pesquisavel, false);
    }

    /**
     * Adiciona a entidade à tabela de pesquisa.
     * @param Pesquisavel $pesquisavel
     */
    public function inserirPesquisa(Pesquisavel $pesquisavel) {
        $this->servicoPesquisa->atualizar($pesquisavel, false);
    }

    /**
     * @throws AcessoExecao
     */
    public function checarAcesso() {
        // usando debug_backtrace() e get_class mapear o caller e validar o acesso do usuário.
    }

    public function remover(Entidade $entidade) {
        // Todo avaliar quais entidades não podem ser removidas, 
        // e apenas atualizar o status para false (deleção lógica)
        $this->dao->executarDelete($entidade);
    }

    /**
     * 
     * @return DAOBasico
     */
    public function getDao() {
        return $this->dao;
    }

    /**
     * @param integer $id
     * @return Entidade
     */
    public function getById($id) {
        return $this->dao->getById($id);
    }

    /**
     * É chamado antes de uma atualização, com o novo objeto e com o antigo.
     */
    private function antesDeAtualizar($novo, $velho) {
        foreach($this->listeners as $k => $v){
            $v->antesDeAtualizar($novo, $velho);
        }
    }

    private function antesDeInserir($new) {
        foreach($this->listeners as $k => $v){
            $v->antesDoSalvar($novo);
        }
    }

    public function adicionarListener(ServicoAcao $listener) {
        $this->listeners[] = $listener;
    }
}

?>
