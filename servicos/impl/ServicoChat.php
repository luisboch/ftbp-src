<?php
require_once 'ftbp-src/entidades/basico/Usuario.php';
require_once 'ftbp-src/daos/impl/ChatDAO.php';
require_once 'ftbp-src/session/SessionManager.php';
/*
 * Chat.php
 */

/**
 * Description of Chat
 *
 * @author Luis
 * @since Feb 24, 2013
 */
class Chat {
    
    /**
     *
     * @var ChatDAO
     */
    private $chatDAO;
    
    /**
     *
     * @var SessionManager
     */
    private $session;
    
    function __construct() {
        $this->chatDAO = new ChatDAO();
        $this->session = SessionManager::getInstance();
    }

    
    /**
     * 
     * @param Usuario $from
     * @param Usuario $to
     * @param string $message
     */
    public function enviarMensagem(Usuario $from, Usuario $to, $message){
        $this->chatDAO->enviarMensagem($from, $to, $message);
    }
    
    /**
     * 
     * @return array
     */
    public function carregarUsuariosAtivos() {
        return $this->chatDAO->carregarUsuariosAtivos($this->session->getUsuario());
    }
    
    /**
     * 
     * @param Usuario $from
     * @param Usuario $to
     * @return array
     */
    public function carregarMensagens(Usuario $from, Usuario $to) {
        return $this->chatDAO->carregarMensagens($from, $to);
    }
    
    /**
     * Verfica se existe mensagem para o usuário.
     * @param Usuario $from
     * @param Usuario $to
     * @return boolean true se existe, false se não.
     */
    public function  existeMensagem(Usuario $from,Usuario $to){
        return $this->chatDAO->existeMensagem($from, $to);
    }
    
    public function logout() {
        $this->chatDAO->logout($this->session->getUsuario());
    }
    
}

?>
