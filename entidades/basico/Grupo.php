<?php

require_once 'ftbp-src/entidades/Entidade.php';
require_once 'ftbp-src/entidades/Notificavel.php';
require_once 'ftbp-src/entidades/Pesquisavel.php';
require_once 'ftbp-src/entidades/basico/GrupoAcesso.php';

/**
 * Description of Grupo
 *
 * @author Luis
 * @since May 26, 2013
 */
class Grupo implements Entidade, Pesquisavel, Notificavel {

    /**
     *
     * @var integer
     */
    private $id;

    /**
     *
     * @var string
     */
    private $nome;

    /**
     *
     * @var DateTime
     */
    private $dataCriacao;

    /**
     * 
     * @return GrupoAcesso[]
     */
    private $acessos;

    function __construct() {
        $this->acessos = array();
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getDataCriacao() {
        return $this->dataCriacao;
    }

    public function setDataCriacao($dataCriacao) {
        $this->dataCriacao = $dataCriacao;
    }

    public function getBreveDescricao() {
        return "Grupo $this->nome, cadastrado em " . $this->getDataCriacao()->format('d/M/y');
    }

    public function getData() {
        return new DateTime();
    }

    public function getDataExpiracao() {
        return null;
    }

    public function getEntidade() {
        return $this;
    }

    public function getLink() {
        return 'GrupoController/item/' . $this->id;
    }

    public function getMensagem($new = false) {
        return ($new ? 'Novo ' : '') . 'Grupo ' . ($new ? 'cadastrado' : 'alterado') . ' "' . $this->nome . '"';
    }

    public function getNotificarEmail() {
        return false;
    }

    public function getPalavrasChave() {
        $arr = array();
        if ($this->nome != '') {
            $arr = explode(' ', $this->nome);
        }
        $arr[] = $this->id;
        return $arr;
    }

    public function getTipo() {
        return 'Grupo';
    }

    public function getTitulo() {
        return "Grupo " . $this->nome;
    }

    public function getAcessos() {
        return $this->acessos;
    }

    public function setAcessos($acessos) {
        $this->acessos = $acessos;
    }
    
    public function temAcesso($acesso, $escrita = false) {
        
        // Verifica se a aplicação está bloqueada para escrita
        if(WRITE_LOCKED && $escrita){
            return false;
        }
        
        $tipo = GrupoAcesso::checarTipo($acesso);
        
        foreach ($this->acessos as $v) {
            /* @var $v GrupoAcesso */
            if ($v->getTipo() == $tipo) {
                
                if ($escrita) {
                    if ($v->getEscrita()) {
                        return true;
                    } else {
                        return false;
                    }
                }
                
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * @param GrupoAcesso $acesso
     */
    public function adicionarAcesso($acesso, $escrita = false){
        if(is_object($acesso)){
            $this->acessos[] = $acesso;
        } else{
            $a = new GrupoAcesso();
            $a->setTipo($acesso);
            $a->setEscrita($escrita);
            $this->adicionarAcesso($a);
        }
    }
    

}

?>
