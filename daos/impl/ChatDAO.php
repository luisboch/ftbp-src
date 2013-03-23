<?php

require_once 'ftbp-src/daos/impl/UsuarioDAO.php';

/**
 * Description of ChatDAO
 *
 * @author luis
 */
class ChatDAO {

    /**
     *
     * @var UsuarioDAO
     */
    private $usuarioDAO;

    const GLOBAL_PATH = 'chat/';

    function __construct() {
        $this->usuarioDAO = new UsuarioDAO();
        $this->usuarioDAO->connect();
    }

    /**
     * 
     * @param Usuario $from
     * @param List<Usuario> $to
     * @param string $message
     */
    public function enviarMensagem(Usuario $from, $to, $message) {
// from file
        $file = "users/" . $from->getId() . "/";
    }

    public function carregarUsuariosAtivos(Usuario $usuario) {

        $file = BASEPATH . self::GLOBAL_PATH . 'usuarios.xml';
        
        $dom = new DOMDocument("1.0", 'UTF-8');

        /**
         * @var DOMElement
         */
        $root = null;
        if (file_exists($file)) {

            $dom->load($file);
            $root = $dom->documentElement;
        } else {

            $root = $dom->createElement('root');

            $dom->appendChild($root);
        }

        $usuarios = $dom->getElementsByTagName('usuario');

        $userNode = null;

        // Data usada para comparação com os acessos de cada usuário
        $dataAtual = new DateTime();
        $dataAtual = $dataAtual->sub(new DateInterval('PT1M'));

        // Registras os ids dos usuários que estão ativos;
        $list = array();

        for ($i = 0; $i < $usuarios->length; $i++) {
            $u = $usuarios->item($i);

            $idNode = $u->getElementsByTagName('id');
            $id = $idNode->item(0)->nodeValue;

            if (((int) $id) == $usuario->getId()) {
                
                echo 'found ......\n';
                if ($userNode !== null) {
                    $root->removeChild($userNode);
                }
                
                $userNode = $u;
                
            } else {

                // check if we need to remove item
                $timeNode = $u->getElementsByTagName('ultimoacesso');
                $acesso = $timeNode->item(0)->nodeValue;

                $d = new DateTime();
                $d->setTimestamp((int) $acesso);

                if ($d->getTimestamp() > $dataAtual->getTimestamp()) {
                    $list[] = $id;
                } else {
                    $root->removeChild($u);
                }
            }
        }

        $data = new DateTime();

        // Se o usuário não existe cria no documento
        if ($userNode === null) {

            $userNode = $root->appendChild(new DOMElement('usuario'));


            $userNode->appendChild(new DOMElement('id', $usuario->getId()));

            $userNode->appendChild(new DOMElement('ultimoacesso', $data->getTimestamp()));

            $userNode->appendChild(new DOMElement('ultimoacessostr', $data->format('Y-m-d H:i:s')));

            $root->appendChild($userNode);
        } else { // Atualiza a data de acesso
            $timeNode = $userNode->getElementsByTagName('ultimoacesso');
            $timeNode->item(0)->nodeValue = $data->getTimestamp();

            $timeStrNode = $userNode->getElementsByTagName('ultimoacessostr');
            $timeStrNode->item(0)->nodeValue = $data->format('Y-m-d H:i:s');
        }

        $dom->save($file);

        return $this->usuarioDAO->getByIds($list);
    }

}
?>
