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
class UsuarioLazy extends Usuario {

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
    private $grupoCarregado = false;
    private $departamentoId;
    private $grupoId;

    function __construct(UsuarioDAO $dao) {
        $this->dao = $dao;
    }

    /**
     * @return Departamento
     */
    public function getDepartamento() {
        if (!$this->dpCarregado) {
            $this->dao->carregarDepartamentoUsuario($this, $this->departamentoId);
        }
        return parent::getDepartamento();
    }

    /**
     * @param Departamento $departamento
     */
    public function setDepartamento($departamento) {
        parent::setDepartamento($departamento);
        $this->dpCarregado = true;
    }

    /**
     * @return integer
     */
    public function getDepartamentoId() {
        return $this->departamentoId;
    }

    /**
     * 
     * @param integer $departamentoId
     */
    public function setDepartamentoId($departamentoId) {
        if ($departamentoId == null) {
            $this->dpCarregado = true;
        }
        $this->departamentoId = $departamentoId;
    }

    /**
     * @param Grupo $grupo
     */
    public function setGrupo(Grupo $grupo) {
        parent::setGrupo($grupo);
        $this->grupoCarregado = true;
    }

    /**
     * @return Grupo
     */
    public function getGrupo() {
        
        if (!$this->grupoCarregado) {
            $this->dao->carregarGrupo($this, $this->grupoId);
        }
        
        return parent::getGrupo();
    }

    /**
     * @return integer
     */
    public function getGrupoId() {
        return $this->grupoId;
    }

    /**
     * @param integer $grupoId
     */
    public function setGrupoId($grupoId) {
        $this->grupoId = $grupoId;
    }

}

?>
