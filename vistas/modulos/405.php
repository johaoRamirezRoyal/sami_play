<?php
require_once CONTROL_PATH . 'Session.php';
$objss = new Session;
$objss->iniciar();
if (!$_SESSION['rol']) {
	$er = '2';
	$error = base64_encode($er);
	$salir = new Session;
	$salir->iniciar();
	$salir->outsession();
	header('Location:login?er=' . $error);
	exit();
}
include_once VISTA_PATH . 'cabeza.php';
include_once VISTA_PATH . 'navegacion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <style>
        body {
            background-image: url('https://www.seoptimer.com/storage/images/2021/08/what-activities-are-involved-in-website-maintenance-min.png');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            position: relative;
            margin: 0;
        }
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.5); /* Ajusta el color y la opacidad */
            z-index: -1;
        }
        .container {
            position: relative;
            z-index: 1;
            text-align: center;
            margin-top: 100px;
            color: #333; /* Ajusta para que contraste con el fondo */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Página en Mantenimiento</h1>
        <p>Estamos trabajando para mejorar la experiencia. Por favor, vuelve más tarde.</p>
        <a href="<?= BASE_URL ?>inicio" class="btn btn-primary">&larr; Volver al inicio</a>
    </div>
</body>
</html>


<?php
include_once VISTA_PATH . 'script_and_final.php';
?>