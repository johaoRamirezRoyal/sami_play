<?php
date_default_timezone_set('America/Bogota');
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', '..' . DS . '..' . DS . '..');
require_once '..' . DS . '..' . DS . '..' . DS . 'confi' . DS . 'Config.php';
require_once CONTROL_PATH . 'usuarios' . DS . 'ControlUsuarios.php';

$instancia = ControlUsuarios::singleton_usuario();
$rs        = $instancia->passwordNewControl();
echo $rs;
