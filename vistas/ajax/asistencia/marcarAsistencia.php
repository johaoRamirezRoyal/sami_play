<?php
date_default_timezone_set('America/Bogota');
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', '..' . DS . '..' . DS . '..');
require_once '..' . DS . '..' . DS . '..' . DS . 'confi' . DS . 'Config.php';
require_once CONTROL_PATH . 'asistencia' . DS . 'ControlAsistencia.php';

$instancia = ControlAsistencia::singleton_asistencia();
$dato      = $instancia->tomarAsistenciaControl($_POST['id'], $_POST['estado'], $_POST['id_log'], $_POST['fecha'], $_POST['asistencia']);

echo json_encode($dato);