<?php

require_once 'ftbp-src/servicos/impl/ServicoBasico.php';
require_once 'ftbp-src/daos/impl/resultados/CursoRelatorioResultado.php';
require_once 'ftbp-src/daos/impl/RelatorioCursoDAO.php';


/**
 * Description of ServicoRelatorioCurso
 *
 * @author luis
 */
class ServicoRelatorioCurso extends ServicoBasico {
    
    /**
     *
     * @var RelatorioCursoDAO
     */
    private $relatorioCursoDAO;
    
    function __construct() {
        
        parent::__construct(new RelatorioCursoDAO());
        $this->relatorioCursoDAO =  $this->dao;
        
    }
    
    public function validar(Entidade $entidade) {
        
    }
    
    /**
     * Realiza a pesquisa do relatório, de acordo com o agrupamento, 
     * veja {@link CursoAgrupamento}
     * @param CursoAgrupamento $agruparPor
     * @return CursoRelatorioResultado[] Description
     */
    public function dadosRelatorioVisualizacao($agruparPor = CursoAgrupamento::CURSO) {
        return $this->relatorioCursoDAO->dadosRelatorioVisualizacao($agruparPor);
    }
    
}

?>
