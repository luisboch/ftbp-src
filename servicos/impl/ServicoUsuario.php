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

    public function validar(Entidade $entidade) {
        if($entidade->getTipo() == 'Aluno'){
            $this->validarAluno($entidade);
        }
    }
    
    public function validarAluno(Aluno $aluno){
        // TODO validar usuário
    }
    
    public function login($email, $senha){
        $v  = new ValidacaoExecao();
        if($email == null){
            $v->addError("Email inválido");
        }
        if($senha == null){
            $v->addError("Senha inválida");
        }
        
        if(!$v->isEmtpy()){
            throw $v;
        }
        
        return $this->usuarioDAO->login($email, $senha);
    }
}

?>
