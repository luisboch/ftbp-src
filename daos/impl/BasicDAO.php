<?php
require_once './database-api/database.php';
require_once '../';
/*
 * BasicDAO.php
 */

/**
 * Description of BasicDAO
 *
 * @author Luis
 * @since Feb 23, 2013
 */
class BasicDAO implements EntidadeDAO{
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
    
    public function connect(){
        DatabaseManager::getConnection();
    }
}

?>
