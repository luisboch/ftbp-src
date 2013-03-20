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
    
    private $departamentoId;
    
    function __construct(UsuarioDAO $dao) {
        $this->dao = $dao;
    }

    /**
     * 
     * @return Departamento
     */
    public function getDepartamento() {
        if(!$this->dpCarregado){
            $this->dao->carregarDepartamentoUsuario($this, $this->departamentoId);
        }
        return parent::getDepartamento();
    }
    
    /**
     * 
     * @param Departamento $departamento
     */
    public function setDepartamento($departamento) {
        parent::setDepartamento($departamento);
        $this->dpCarregado = true;
    }
    
    public function getDepartamentoId() {
        return $this->departamentoId;
    }

    public function setDepartamentoId($departamentoId) {
        if($departamentoId == null){
            $this->dpCarregado = true;
        }
        $this->departamentoId = $departamentoId;
    }
}

?>
