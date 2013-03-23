<?php
define('BASEPATH', dirname(__FILE__).'/');
set_include_path(get_include_path() . ':/var/www/includes');
require_once 'ftbp-src/daos/impl/ChatDAO.php';
$usuario = new Usuario();
$usuario->setId(4);
$dao = new ChatDAO();
echo '<pre>';
print_r($dao->carregarUsuariosAtivos($usuario));
?>
