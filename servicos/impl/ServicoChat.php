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
     * @param List<Usuario> $to
     * @param string $message
     */
    public function enviarMensagem(Usuario $from, $to, $message){
        // from file
        $file = "chat/users/".$from->getId()."/";
    }

     /**
     *   
     * @param string $message
     * @param string $file arquivo a ser escrito
     * @throws Exception Quando encontrar um problema ao escrever o arquivo
     */
    public function writeMessage($message, $file){
         $fp = fopen($file, 'a+'); 
         if($fp === false){
             throw new Exception("Falha ao abrir/criar arquivo [$file]");
         }
         fwrite($fp, $message."\n");
         fclose($fp);
    }
    
    public function carregarUsuariosAtivos() {
        return $this->chatDAO->carregarUsuariosAtivos($this->session->getUsuario());
    }
    
    
}

?>
