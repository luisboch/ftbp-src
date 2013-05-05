<?php
require_once 'ftbp-src/entidades/basico/Curso.php';
require_once 'ftbp-src/entidades/basico/CursoArquivo.php';
/**
 * 
 * CursoLazy.php
 */

/**
 * Description of CursoLazy
 *
 * @author luis
 */
class CursoLazy extends Curso{
    /**
     * @var boolean
     */
    private $arquivosCarregados = false;
    
    /**
     * @var CursoDAO
     */
    private $cursoDAO;
    
    function __construct(CursoDAO $cursoDAO) {
        $this->cursoDAO = $cursoDAO;
    }

    
    public function setArquivos($arquivos) {
        parent::setArquivos($arquivos);
        $this->arquivosCarregados = true;
    }
    
    public function getArquivos() {
        if(!$this->arquivosCarregados){
            $this->cursoDAO->carregarArquivos($this);
        }
        return parent::getArquivos();
    }
}

?>
