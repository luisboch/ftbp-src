<?php
require_once './Aluno.php';
/*
 * Professor.php
 */

/**
 * Description of Professor
 *
 * @author Luis
 * @since Feb 23, 2013
 */
class Professor extends Usuario{
    /**
     *
     * @var List<Curso>
     */
    private $cursos;
    
    /**
     * 
     * @return List<Curso>
     */
    public function getCursos() {
        return $this->cursos;
    }
    /**
     * 
     * @param List<Curso> $cursos
     */
    public function setCursos($cursos) {
        $this->cursos = $cursos;
    }

    public function getType() {
        return __CLASS__;
    }
}

?>
