<?php

/*
 * Mensagems.php
 */

/**
 * Description of Mensagems
 *
 * @author luis
 */
class Mensagens {

    const INFO = 'INFO';
    const WARN = 'WARN';
    const ERROR = 'ERROR';
    const SYS_ERROR = 'SYS_ERROR';

    private $index = -1;
    private $msgs = array();
    private $tipos = array();
    private static $instance = NULL;

    private function __construct() {
        
    }

    /**
     * 
     * @return Mensagens
     */
    public static function getInstance() {
        $instance = self::$instance;
        if (self::$instance === NULL) {
            self::$instance = new Mensagens();
        }

        return self::$instance;
    }

    public function addMsg($message, $tipo = NULL) {
        $this->index++;
        $this->msgs[$this->index] = $message;

        if ($tipo === NULL) {
            $tipo = self::INFO;
        }

        $this->tipos[$this->index] = $tipo;
    }

    public function getMsgs() {
        return $this->msgs;
    }

    public function setMsgs($msgs) {
        $this->msgs = $msgs;
    }

    public function getTipos() {
        return $this->tipos;
    }

    public function setTipos($tipos) {
        $this->tipos = $tipos;
    }

    /**
     * 
     * @param boolean $includeHeader
     * @return string
     */
    public function criarXml($includeHeader = false) {

        $return = '';

        if ($includeHeader) {
            header('Content-Type: text/xml; charset=utf-8');
            $return .= '<?xml version="1.0" encoding="UTF-8"?>
                        <root>';
        }

        $msgs = $this->getMsgs();
        $types = $this->getTipos();
        
        if (count($msgs) > 0) {
            $return .= '<messages>';
            foreach ($msgs as $k => $v) {
                $return .= '<message>';
                $return .= '<text>' . $v . '</text>';
                $return .= '<type>' . $types[$k] . '</type>';
                $return .= '</message>';
            }

            $return .= '</messages>';
        }
        
        if ($includeHeader) {
            $return .= '</root>';
        }
        return $return;
    }

}

?>
