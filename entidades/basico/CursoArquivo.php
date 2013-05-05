<?php
require_once 'ftbp-src/entidades/basico/Usuario.php';
require_once 'ftbp-src/entidades/basico/Curso.php';
require_once 'ftbp-src/entidades/basico/Departamento.php';
/**
 * 
 * CursoArquivo.php
 */

/**
 * Description of CursoArquivo
 *
 * @author luis
 */
class CursoArquivo {
    
    /**
     * @var Departamento
     */
    private $curso;
    
    /**
     *
     * @var Departamento
     */
    private $setor;
    
    /**
     *
     * @var string
     */
    private $descricao;
    
    /**
     *
     * @var string
     */
    private $caminho;
    /**
     *
     * @var DateTime
     */
    private $dataUpload;
    
    /**
     *
     * @var Usuario
     */
    private $usuario;
    
    public function getCurso() {
        return $this->curso;
    }

    public function setCurso(Curso $curso) {
        $this->curso = $curso;
    }

    public function getSetor() {
        return $this->setor;
    }

    /**
     * @param Departamento $setor
     */
    public function setSetor($setor) {
        $this->setor = $setor;
    }

    public function getDescricao() {
        return $this->descricao;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    public function getCaminho() {
        return $this->caminho;
    }

    public function setCaminho($caminho) {
        $this->caminho = $caminho;
    }

    public function getDataUpload() {
        return $this->dataUpload;
    }

    public function setDataUpload(DateTime $dataUpload) {
        $this->dataUpload = $dataUpload;
    }

    public function getUsuario() {
        return $this->usuario;
    }

    public function setUsuario(Usuario $usuario) {
        $this->usuario = $usuario;
    }
}

?>
