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
            $v->addError("Email do usuário inválido", "email");
        }

        if ($usuario->getNome() == null) {
            $v->addError("Nome do usuário inválido", "nome");
        }

        // Valida a senha somente se for novo usuário
        if ($usuario->getSenha() == null && $usuario->getId() == null) {
            $v->addError("Senha do usuário inválida", "senha");
        }
        
        if ($usuario->getDepartamento() == null && $usuario->getTipoUsuario() == TipoUsuario::FUNCIONARIO) {
            $v->addError("Departamento do usuário inválido", "senha");
        }

        // Checa se a data de criação foi setada, se não coloca como agora.
        if ($usuario->getDataCriacao() == null) {
            $usuario->setDataCriacao(new DateTime());
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
    
    public function carregarTodosOsUsuarios(){
        return $this->usuarioDAO->carregarTodosOsUsuarios();
    }
    
    public function carregarResponsavelDepartamento($ids){
        return $this->usuarioDAO->carregarResponsavelDepartamento($ids);
    }
    public function carregarUsuariosDepartamento($dep){
        return $this->usuarioDAO->carregarUsuariosDepartamento($dep);
    }
    public function getByIds($ids){
        return $this->usuarioDAO->getByIds($ids);
    }
    
}

?>
