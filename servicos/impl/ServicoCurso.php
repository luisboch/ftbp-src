<?php

require_once 'ftbp-src/servicos/impl/ServicoBasico.php';
require_once 'ftbp-src/daos/impl/CursoDAO.php';

/*
 * CursoServico.php
 */

/**
 * Description of CursoServico
 *
 * @author felipe
 */
class ServicoCurso extends ServicoBasico {

    /**
     * @var CursoDAO
     */
    private $cursoDAO;

    function __construct() {
        parent::__construct(new CursoDAO());
        $this->cursoDAO = $this->dao;
    }

    public function validar(Entidade $entidade) {
        $v = new ValidacaoExecao();
        /* @var $entidade Curso */
        // Check defaults
        if ($this->stado == self::CRIACAO) {
            $entidade->setDataCriacao(new DateTime());
        }


        if ($entidade->getNome() == '') {
            $v->addError('Nome do curso inválido ->  nome: ' . $entidade->getNome(), 'nome');
        }

        if ($entidade->getDuracao() == null || !is_numeric($entidade->getDuracao())) {
            $v->addError('Duração inválido "' . $entidade->getDuracao(). '", é aceito apenas número!', 'duracao');
        }
        
        $session = SessionManager::getInstance();
        foreach ($entidade->getArquivos() as $a) {

            if ($a->getUsuario() == null) {
                $a->setUsuario($session->getUsuario());
                $a->setSetor($session->getUsuario()->getDepartamento());
            }

            if ($a->getSetor() == null) {
                if ($session->getUsuario()->getDepartamento() == null) {
                    $v->addError("Você não possui um setor associado para efetuar upload");
                }
            }

            if ($a->getCaminho() == '') {
                $v->addError("Caminho do arquivo é obrigatório");
            }

            if ($a->getCurso() == null) {
                $a->setCurso($entidade);
            }

            if ($a->getDescricao() == '') {
                $v->addError("Descrição do arquivo é obrigatória");
            }

            if ($a->getDataUpload() == null) {
                $a->setDataUpload(new DateTime());
            }

            if (!$v->isEmtpy()) {
                throw $v;
            }
        }

        if (!$v->isEmtpy()) {
            throw $v;
        }
    }

    /**
     * @return Curso[]
     */
    public function carregarCurso($limit = NULL) {
        return $this->cursoDAO->carregarCurso();
    }
    
    

}

?>
