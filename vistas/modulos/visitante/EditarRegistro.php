<?php
require_once CONTROL_PATH . 'Session.php';
require_once CONTROL_PATH . 'visitante' . DS . 'ControlVisitante.php';

$objss = new Session;
$objss->iniciar();

if (!$_SESSION['rol']) {
    header('Location:../login');
    exit();
}

$instancia = ControlVisitante::singleton_visitante();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $registro = $instancia->obtenerVisitantePorId($id);

    if (!$registro) {
        echo "Registro no encontrado.";
        exit();
    }
} else {
    echo "ID no proporcionado.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $visitante = $_POST['visitante'];
    $acudiente = $_POST['acudiente'];
    $celular = $_POST['celular'];
    $horaingreso = $_POST['horaingreso'];
    $duracion = $_POST['duracion'];
    $horasalida = $_POST['horasalida'];

    $instancia->actualizarVisitante($id, $visitante, $acudiente, $celular, $horaingreso, $duracion, $horasalida);
    header('Location: ../asistencia');
    exit();
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Registro</title>
    <!-- Aquí irían tus hojas de estilo y scripts -->
</head>
<body>
    <h1>Editar Registro</h1>
    <form method="POST">
        <div>
            <label for="visitante">Visitante:</label>
            <input type="text" name="visitante" value="<?= htmlspecialchars($registro['visitante']) ?>" required>
        </div>
        <div>
            <label for="acudiente">Acudiente:</label>
            <input type="text" name="acudiente" value="<?= htmlspecialchars($registro['acudiente']) ?>" required>
        </div>
        <div>
            <label for="celular">Celular:</label>
            <input type="text" name="celular" value="<?= htmlspecialchars($registro['celular']) ?>" required>
        </div>
        <div>
            <label for="horaingreso">Hora de Ingreso:</label>
            <input type="time" name="horaingreso" value="<?= htmlspecialchars($registro['horaingreso']) ?>" required>
        </div>
        <div>
            <label for="duracion">Duración:</label>
            <input type="number" name="duracion" value="<?= htmlspecialchars($registro['duracion']) ?>" required>
        </div>
        <div>
            <label for="horasalida">Hora de Salida:</label>
            <input type="time" name="horasalida" value="<?= htmlspecialchars($registro['horasalida']) ?>" required>
        </div>
        <button type="submit">Actualizar</button>
    </form>
</body>
</html>
