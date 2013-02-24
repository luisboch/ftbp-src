<?php
require_once './ftbp-src/entidades/basico/Usuario.php';
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
    
    
}

?>
