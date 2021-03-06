<?php

require_once 'ftbp-src/entidades/basico/CursoArquivo.php';

/**
 * Description of Curso
 *
 * @author felipe
 */
class Curso implements Entidade, Notificavel, Pesquisavel {

    /**
     * @var integer 
     */
    private $id;

    /**
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
     * @var String
     */
    private $corpoDocente;

    /**
     *
     * @var String 
     */
    private $publicoAlvo;

    /**
     *
     * @var float
     */
    private $valor;

    /**
     *
     * @var float
     */
    private $duracao;

    /**
     *
     * @var String
     */
    private $videoApresentacao;

    /**
     *
     * @var AreaCurso
     */
    private $areaCurso;

    /**
     *
     * @var String
     */
    private $nivelGraduacao;

    /**
     *
     * @var Usuario
     */
    private $contato;
    /**
     *
     * @var Date
     */
    private $dataVestibular;

    /**
     *
     * @return String
     */
    private $descricao;

    /**
     *
     * @return Usuario
     */
    private $coordenador;

    /**
     *
     * @return boolean
     */
    private $excluida;

    /**
     * @var CursoArquivo[]
     */
    private $arquivos = array();
    
    private $acessos = 0;

    public function getDescricao() {
        return $this->descricao;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    /**
     * 
     * @return Usuario
     */
    public function getCoordenador() {
        return $this->coordenador;
    }

    /**
     * 
     * @param Usuario $coordenador
     */
    public function setCoordenador($coordenador) {
        $this->coordenador = $coordenador;
    }

    public function getExcluida() {
        return $this->excluida;
    }

    public function setExcluida($excluida) {
        $this->excluida = $excluida;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }
    
    public function getContato() {
        return $this->contato;
    }

    /**
     * 
     * @param Usuario $contato
     */
    public function setContato( $contato) {
        $this->contato = $contato;
    }
    
    public function getCorpoDocente() {
        return $this->corpoDocente;
    }

    public function setCorpoDocente($corpoDocente) {
        $this->corpoDocente = $corpoDocente;
    }

    public function getPublicoAlvo() {
        return $this->publicoAlvo;
    }

    public function setPublicoAlvo($publicoAlvo) {
        $this->publicoAlvo = $publicoAlvo;
    }

    public function getValor() {
        return $this->valor;
    }

    public function setValor($valor) {
        $this->valor = $valor;
    }

    public function getDuracao() {
        return $this->duracao;
    }

    public function setDuracao($duracao) {
        $this->duracao = $duracao;
    }

    public function getVideoApresentacao() {
        return $this->videoApresentacao;
    }

    public function setVideoApresentacao($videoApresentacao) {
        $this->videoApresentacao = $videoApresentacao;
    }

    public function getAreaCurso() {
        return $this->areaCurso;
    }

    public function setAreaCurso($areaCurso) {
        $this->areaCurso = $areaCurso;
    }

    public function getNivelGraduacao() {
        return $this->nivelGraduacao;
    }

    public function setNivelGraduacao($nivelGraduacao) {
        $this->nivelGraduacao = $nivelGraduacao;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    /**
     * 
     * @return DateTime
     */
    public function getDataVestibular() {
        return $this->dataVestibular;
    }

    /**
     * 
     * @param DateTime $dataVestibular
     */
    public function setDataVestibular(DateTime $dataVestibular) {
        $this->dataVestibular = $dataVestibular;
    }

    public function getDataCriacao() {
        return $this->dataCriacao;
    }
    
    /**
     * @param DateTime $dataCriacao
     */
    public function setDataCriacao(DateTime $dataCriacao) {
        $this->dataCriacao = $dataCriacao;
    }

    /* Inicio de implementações da notificação */

    /**
     * @return DateTime
     */
    public function getData() {
        return new DateTime();
    }

    /**
     * @return DateTime
     */
    public function getDataExpiracao() {
        return null;
    }

    public function getLink() {
        return 'Ver/curso/' . $this->getId();
    }

    public function getMensagem($new = false) {
        if ($new) {
            return "Novo curso cadastrado \"" . $this->nome . "\"";
        } else {
            return "Curso atualizado \"" . $this->nome . "\"";
        }
    }

    public function getNotificarEmail() {
        return false;
    }

    /* Fim de implementações da notificação */

    /* Inicio de implementações da pesquisa */

    public function getBreveDescricao() {
        return 'Curso ' . $this->nome . ', cadastrado em ' . $this->getDataCriacao()->format('d/m/y');
    }

    public function getEntidade() {
        return $this;
    }

    public function getPalavrasChave() {

        $palavras = array();

        // Adiciona o nome do curso
        if ($this->nome != '') {
            $palavras[] = $this->nome;
        }

        // Adiciona o nível de graduação.
        $nivel = explode(' ', $this->nivelGraduacao);
        foreach ($nivel as $v) {
            if ($v != '') {
                $palavras[] = $v;
            }
        }

        return $palavras;
    }

    public function getTipo() {
        return 'Curso';
    }

    public function getTitulo() {
        return 'Curso ' . $this->nome;
    }

    /**
     * 
     * @return CursoArquivo[]
     */
    public function getArquivos() {
        return $this->arquivos;
    }

    /**
     * @param CursoArquivo[] $arquivos
     */
    public function setArquivos($arquivos) {
        $this->arquivos = $arquivos;
    }

    /**
     * 
     * @return int
     */
    public function getAcessos() {
        return $this->acessos;
    }

    /**
     * 
     * @param int $acessos
     */
    public function setAcessos($acessos) {
        $this->acessos = $acessos;
    }
    
    public function adicionarArquivo(CursoArquivo $arq) {
        // Confirma que os arquivos já foram carregados.
        $this->getArquivos();
        
        // Então adiciona
        $this->arquivos[] = $arq;
    }

    public function removerArquivo(CursoArquivo $arq) {
        // Confirma que os arquivos já foram carregados.
        $this->getArquivos();
        
        foreach($this->getArquivos() as $k => $a){
            if($a === $arq){
                unset($this->arquivos[$k]);
            }
        }
    }
    
}

?>
