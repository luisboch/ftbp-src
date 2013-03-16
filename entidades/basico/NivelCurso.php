<?php

/*
 * NivelCurso
 */

/**
 * Description of NivelCurso
 *
 * @author Luis
 * @since Feb 27, 2013
 */
class NivelCurso implements Entidade{
    
    private $id;
    private $nome;
    private $dataCriacao;
    

    public function getId() {
        return $this->id;
    }

    public function getNome() {
        return $this->nome;
    }
    public function getDataCriacao() {
        return $this->dataCriacao;
    }

       
    public function setId($id) {
        $this->id = $id;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function setDataCriacao($dataCriacao) {
        $this->dataCriacao = $dataCriacao;
    }


}

?>
