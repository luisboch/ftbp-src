<?php
require_once 'database-api/database.php';
require_once 'ftbp-src/daos/EntidadeDAO.php';
/*
 * BasicDAO.php
 */

/**
 * Description of BasicDAO
 *
 * @author Luis
 * @since Feb 23, 2013
 */
abstract class DAOBasico implements EntidadeDAO{
    /**
     *
     * @var Connection
     */
    private $conn;
    
    function __construct() {
    }
    
    public function getConn() {
        return $this->conn;
    }

    public function setConn(Connection $conn) {
        $this->conn = $conn;
    }
    
    /**
     * Realiza a conexão com o banco de dados, 
     */
    public function connect(){
        $this->conn =DatabaseManager::getConnection();
    }
    
    /**
     * Verifica se não está conectedo, se tiver desconecta, e reconecta ao final
     *  do processo
     */
    public function reconnect(){
        if($this->conn!==NULL && $this->conn->isConnected()){
            $this->conn->close();
        }
        $this->connect();
    }
    
}

?>
