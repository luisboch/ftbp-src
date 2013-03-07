<?php

require_once 'ftbp-src/servicos/Servico.php';
require_once 'ftbp-src/servicos/impl/ServicoBasico.php';
require_once 'ftbp-src/daos/impl/UsuarioDAO.php';
/*
 * ServicoUsuario.php
 */

/**
 * Description of ServicoUsuario
 *
 * @author Luis
 * @since Feb 24, 2013
 */
class ServicoUsuario extends ServicoBasico {

    /**
     * @var UsuarioDAO
     */
    protected $usuarioDAO;
    
    
    function __construct() {
        parent::__construct(new UsuarioDAO());
        $this->usuarioDAO = $this->dao;
    }

    /**
     * @param Usuario $entidade
     */
    public function validar(Entidade $entidade) {
        $this->validarUsuario($entidade);
    }

    /**
     * @param Usuario $usuario
     * @throws ValidacaoExecao
     */
    public function validarUsuario(Usuario $usuario) {
        $v = new ValidacaoExecao();
        
        if ($usuario->getEmail() == null) {
            $v->addError("Email inválido", "email");
        }
        
        if ($usuario->getNome() == null) {
            $v->addError("Nome inválido", "nome");
        }
        
        if ($usuario->getSenha() == null) {
            $v->addError("Senha inválida", "senha");
        }
        
        if (!$v->isEmtpy()) {
            throw $v;
        }
    }
    
    /**
     * 
     * @param string $email
     * @param string $senha
     * @return Usuario
     * @throws ValidacaoExecao
     */
    public function login($email, $senha) {
        $v = new ValidacaoExecao();
        if ($email == null) {
            $v->addError("Email inválido");
        }
        if ($senha == null) {
            $v->addError("Senha inválida");
        }

        if (!$v->isEmtpy()) {
            throw $v;
        }

        return $this->usuarioDAO->login($email, $senha);
    }

}

?>
