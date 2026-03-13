<?php
date_default_timezone_set('America/Bogota');
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', '..' . DS . '..' . DS . '..');
require_once '..' . DS . '..' . DS . '..' . DS . 'confi' . DS . 'Config.php';
require_once CONTROL_PATH . 'dimension' . DS . 'ControlDimension.php';

$instancia = ControlDimension::singleton_dimension();
$rs        = $instancia->activarDimensionControl();
echo $rs;
