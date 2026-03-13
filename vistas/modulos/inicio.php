<?php
require_once CONTROL_PATH . 'Session.php';
$objss = new Session;
$objss->iniciar();
if (!$_SESSION['rol']) {
    $er    = '2';
    $error = base64_encode($er);
    $salir = new Session;
    $salir->iniciar();
    $salir->outsession();
    header('Location:login?er=' . $error);
    exit();
}
include_once VISTA_PATH . 'cabeza.php';
include_once VISTA_PATH . 'navegacion.php';
include_once VISTA_PATH . 'modulos' . DS . 'configuracion' . DS . 'index.php';
include_once VISTA_PATH . 'script_and_final.php';
