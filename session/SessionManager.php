<?php

require_once 'ftbp-src/entidades/basico/Usuario.php';
require_once 'ftbp-src/daos/impl/lazy/UsuarioLazy.php';

/*
 * SessionManager.php
 */

/**
 * Description of SessionManager
 *
 * @author luis
 */
class SessionManager {
    
    /**
     *
     * @var Usuario
     */
    private $usuario;
    
    /**
     *
     * @var SessionManager
     */
    private static $instance;

    private function __construct() {
        
    }

    /**
     * 
     * @return SessionManager
     */
    public static function getInstance() {
        
        if (self::$instance === null) {
            // is an Singleton
            @session_start();

            if (!isset($_SESSION['_SS']) || $_SESSION['_SS'] == '') {
                self::$instance = new SessionManager();
                $_SESSION['_SS'] = self::$instance;
            } else {
                self::$instance = $_SESSION['_SS'];
            }
        }
        
        return self::$instance;
    }
    
    public function close(){
        $_SESSION['_SS'] = null;
        self::$instance = null;
        @session_destroy();
    }
    
    public function getUsuario() {
        return $this->usuario;
    }

    public function setUsuario(Usuario $usuario) {
        $this->usuario = $usuario;
    }

}
?>