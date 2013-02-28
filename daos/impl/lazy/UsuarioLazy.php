<?php
require_once 'ftbp-src/entidades/basico/Usuario.php';
/*
 * UsuarioLazy.php
 */

/**
 * Description of UsuarioLazy
 *
 * @author Luis
 * @since Feb 27, 2013
 */
class UsuarioLazy extends Usuario{
    /**
     *
     * @var UsuarioDAO
     */
    private $dao;
    
    /**
     *
     * @var boolean
     */
    private $dpCarregado = false;
    
    function __construct(UsuarioDAO $dao) {
        $this->dao = $dao;
    }

    /**
     * 
     * @return Departamento
     */
    public function getDepartamento() {
        if(!$this->dpCarregado){
            $this->dao->carregarDepartamentoUsuario($this);
        }
        return parent::getDepartamento();
    }
    
    /**
     * 
     * @param Departamento $departamento
     */
    public function setDepartamento(Departamento $departamento) {
        parent::setDepartamento($departamento);
        $this->dpCarregado = true;
    }
}

?>
