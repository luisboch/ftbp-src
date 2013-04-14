<?php

require_once 'ftbp-src/daos/impl/UsuarioDAO.php';
require_once 'ftbp-src/daos/impl/ChatMensagem.php';

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

        // Checa se a pasta do chat existe, se existir ignora, se não tenta criar
        // se encontrar problema barra.
        if (!file_exists(APP_PATH . self::GLOBAL_PATH) ||
                !is_dir(APP_PATH . self::GLOBAL_PATH)) {
            if (mkdir(APP_PATH . self::GLOBAL_PATH) === false) {
                throw new Exception("Can't create chat directory!");
            }
        }
    }

    /**
     * 
     * @param Usuario $from
     * @param Usuario $to
     * @param string $message
     */
    public function enviarMensagem(Usuario $from, Usuario $to, $message) {
        $fromFile = APP_PATH . self::GLOBAL_PATH . $from->getId() . '/' . $to->getId() . '.xml';
        $toFile = APP_PATH . self::GLOBAL_PATH . $to->getId() . '/' . $from->getId() . '.xml';

        // Checa se o diretorio do usuário existe se não cria.
        if (!file_exists(APP_PATH . self::GLOBAL_PATH . $from->getId()) ||
                !is_dir(APP_PATH . self::GLOBAL_PATH . $from->getId())) {

            if (mkdir(APP_PATH . self::GLOBAL_PATH . $from->getId()) === false) {
                throw new Exception("Can't create chat directory to user " . $from->getId() . " !");
            }
        }

        // Checa se o diretorio do usuário alvo existe se não cria.
        if (!file_exists(APP_PATH . self::GLOBAL_PATH . $to->getId()) ||
                !is_dir(APP_PATH . self::GLOBAL_PATH . $to->getId())) {

            if (mkdir(APP_PATH . self::GLOBAL_PATH . $to->getId()) === false) {
                throw new Exception("Can't create chat directory to user " . $to->getId() . " !");
            }
        }

        // Cria a mensagen no Usuário que enviou a mensagem

        $fromDom = new DOMDocument("1.0", 'UTF-8');

        if (file_exists($fromFile)) {
            $fromDom->load($fromFile);
            $rootFrom = $fromDom->documentElement;
        } else {
            $rootFrom = $fromDom->createElement('root');
            $fromDom->appendChild($rootFrom);
        }

        $data = new DateTime();

        $msgNode = $rootFrom->appendChild(new DOMElement('mensagem'));

        $msgNode->appendChild(new DOMElement('texto', $message));
        $msgNode->appendChild(new DOMElement('lido', 'true'));
        $msgNode->appendChild(new DOMElement('usuario-id', $from->getId()));
        $msgNode->appendChild(new DOMElement('timestamp', $data->getTimestamp()));

        $rootFrom->appendChild($msgNode);

        // Cria a mensagem no xml do usuário alvo
        $toDom = new DOMDocument("1.0", 'UTF-8');

        if (file_exists($toFile)) {
            $toDom->load($toFile);
            $rootTo = $toDom->documentElement;
        } else {
            $rootTo = $toDom->createElement('root');
            $toDom->appendChild($rootTo);
        }


        $msgNode = $rootTo->appendChild(new DOMElement('mensagem'));

        $msgNode->appendChild(new DOMElement('texto', $message));
        $msgNode->appendChild(new DOMElement('lido', 'false'));
        $msgNode->appendChild(new DOMElement('usuario-id', $from->getId()));
        $msgNode->appendChild(new DOMElement('timestamp', $data->getTimestamp()));

        $rootTo->appendChild($msgNode);

        $toDom->save($toFile);
        $fromDom->save($fromFile);
    }

    public function carregarUsuariosAtivos(Usuario $usuario) {

        $file = APP_PATH . self::GLOBAL_PATH . 'usuarios.xml';

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

    /**
     * 
     * @param Usuario $from
     * @param Usuario $to
     * @return array
     */
    public function carregarMensagens(Usuario $from, Usuario $to) {

        $file = APP_PATH . self::GLOBAL_PATH . $from->getId() . '/' . $to->getId() . '.xml';

        $dom = new DOMDocument("1.0", 'UTF-8');

        $msgs = array();

        if (!file_exists($file)) {
            return $msgs;
        }

        $dom->load($file);

        $mensagens = $dom->getElementsByTagName('mensagem');

        for ($i = 0; $i < $mensagens->length; $i++) {

            $m = $mensagens->item($i);

            $usrIdNode = $m->getElementsByTagName('usuario-id');
            $usrId = $usrIdNode->item(0)->nodeValue;

            $lidoNode = $m->getElementsByTagName('lido');
            $lido = $lidoNode->item(0)->nodeValue;

            $lidoNode->item(0)->nodeValue = 'true';

            $textoNode = $m->getElementsByTagName('texto');
            $texto = $textoNode->item(0)->nodeValue;

            $dataNode = $m->getElementsByTagName('timestamp');
            $dataVal = $dataNode->item(0)->nodeValue;

            $data = new DateTime();
            $data->setTimestamp($dataVal);

            $msgs[] = new ChatMensagem(($usrId == $from->getId() ? $from : $to), $texto, $lido, $data);
        }

        // Save all changes on file
        $dom->save($file);

        return $msgs;
    }

    /**
     * Verfica se existe mensagem para o usuário.
     * @param Usuario $from
     * @param Usuario $to
     * @return boolean true se existe, false se não.
     */
    public function existeMensagem(Usuario $from, Usuario $to) {
        $file = APP_PATH . self::GLOBAL_PATH . $from->getId() . '/' . $to->getId() . '.xml';

        $dom = new DOMDocument("1.0", 'UTF-8');

        $msgs = array();

        if (!file_exists($file)) {
            return false;
        }

        $dom->load($file);

        $mensagens = $dom->getElementsByTagName('mensagem');

        for ($i = 0; $i < $mensagens->length; $i++) {

            $m = $mensagens->item($i);

            $lidoNode = $m->getElementsByTagName('lido');
            $lido = $lidoNode->item(0)->nodeValue;
            if ($lido === 'false') {
                return true;
            }
        }

        return false;
    }

    public function logout(Usuario $usuario) {

        $file = APP_PATH . self::GLOBAL_PATH . 'usuarios.xml';

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

        for ($i = 0; $i < $usuarios->length; $i++) {
            $u = $usuarios->item($i);

            $idNode = $u->getElementsByTagName('id');
            $id = $idNode->item(0)->nodeValue;

            if (((int) $id) == $usuario->getId()) {
                
                $root->removeChild($u);
                $dom->save($file);
                return;
            }
        }
    }

}

?>
