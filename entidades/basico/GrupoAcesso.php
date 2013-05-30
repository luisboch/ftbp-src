<?php
/**
 * Description of GrupoAcesso
 *
 * @author luis
 */
class GrupoAcesso {
    
    /**
     * Constante para acesso aos tipos de acesso.
     */
    CONST AVISO = 1;
    CONST CURSO = 2;
    CONST CURSO_AREA = 3;
    CONST EVENTO = 4;
    CONST USUARIO = 5;
    CONST REQUISICAO = 6;
    CONST SETOR = 7;
    CONST GRUPO_DE_USUARIO = 8;
    CONST RELATORIOS = 9;
    
    /**
     * @var int 
     */
    private $tipo;
    
    /**
     *
     * @var boolean
     */
    private $escrita;
    
    public function getTipo() {
        return $this->tipo;
    }

    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    
    /**
     * 
     * @return boolean
     */
    public function getEscrita() {
        return $this->escrita;
    }

    /**
     * 
     * @param boolean $escrita
     */
    public function setEscrita($escrita) {
        $this->escrita = $escrita;
    }
    
    public static function checarTipo($param) {
        if(is_object($param)){
            return self::checkTipoObjeto($param);
        } else if(is_string($param)) {
            return self::checkTipoString($param);
        } else if (is_int($param)){
            return $param;
        }
    }
    
    public static function checkTipoObjeto(Pesquisavel $objeto) {
        return self::checkTipoString($objeto->getTipo());
    }
    
    public static function checkTipoString($tipo) {
        switch ($tipo) {
            case 'Curso':
                return self::CURSO;
                break;
            case 'Grupo':
                return self::GRUPO;
                break;
            case 'Aviso':
                return self::AVISO;
                break;
            case 'AreaCurso':
                return self::CURSO_AREA;
                break;
            case 'Evento':
                return self::EVENTO;
                break;
            case 'Usuario':
                return self::USUARIO;
                break;
            case 'Requisicao':
                return self::REQUISICAO;
                break;
            case 'Departamento':
                return self::SETOR;
                break;

            default:
                throw new InvalidArgumentException("Tipo não encontrado.");
                break;
        }
    }
}

?>
