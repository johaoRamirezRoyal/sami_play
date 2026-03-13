<?php
require_once CONTROL_PATH . 'Session.php';
$objss = new Session;
$objss->iniciar();

// Verificación de rol de usuario
if (!$_SESSION['rol']) {
    $er = '2';
    $error = base64_encode($er);
    $salir = new Session;
    $salir->iniciar();
    $salir->outsession();
    header('Location:../login?er=' . $error);
    exit();
}

include_once VISTA_PATH . 'cabeza.php';
include_once VISTA_PATH . 'navegacion.php';
require_once CONTROL_PATH . 'tienda' . DS . 'TiendaController.php';

$instancia = ControlTienda::singleton_tienda();
$ventas = $instancia->mostrarHistorialVentasControl();

if (isset($_POST['buscar'])) {
    $resultado = $instancia->filtrarVentasPorFechaControl();
    $ventas = $resultado['ventas'];
    $totalVentas = $resultado['totalVentas'];
    $metodoPagoSuma = $resultado['metodoPagoSuma'];

    // Aquí debes agregar esto para las medias
    $totalVentasMedias = $resultado['totalVentasMedias'];  // Total de ventas de medias
    $metodoPagoMedias = $resultado['metodoPagoMedias'];    // Ventas de medias por método de pago
} else {
    $totalVentas = 0;
    $metodoPagoSuma = [];
    $totalVentasMedias = 0;   // Inicializa las medias
    $metodoPagoMedias = [];   // Inicializa el arreglo de métodos de pago de medias
}

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h4 class="m-0 font-weight-bold text-play">
                        <a href="index" class="text-decoration-none text-play">
                            <i class="fa fa-arrow-left"></i>
                        </a>
                        &nbsp; Historial Detallado
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="row">
                            <div class="col-lg-4 form-group">
                                <div class="input-group mb-3">
                                    <input type="date" class="form-control form-control filtro" id="fecha" name="fecha"
                                        value="<?= isset($_POST['fecha']) ? $_POST['fecha'] : '' ?>">
                                    <div class="input-group-append">
                                        <button class="btn btn-play btn-sm" type="submit" name="buscar">
                                            <i class="fa fa-search"></i>&nbsp;Buscar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                    <div class="table-responsive mt-2">
                        <table class="table table-hover border table-sm" width="100%" cellspacing="0">
                            <thead>
                                <tr class="text-center font-weight-bold">
                                    <th scope="col">Id Venta</th>
                                    <th scope="col">Productos</th>
                                    <th scope="col">Total Precio</th>
                                    <th scope="col">Método de Pago</th>
                                    <th scope="col">Fecha</th>
                                    <th scope="col">Hora</th>
                                    <th scope="col">Recibo Pago</th>
                                </tr>
                            </thead>
                            <tbody class="buscar">
                                <?php if (!empty($ventas)): ?>
                                    <?php foreach ($ventas as $venta): ?>
                                        <tr class="text-center">
                                            <td><?= htmlspecialchars($venta['venta_grupal']) ?></td>
                                            <td><?= htmlspecialchars($venta['productos']) ?></td>
                                            <td><?= htmlspecialchars($venta['total_precio']) ?></td>
                                            <td><?= htmlspecialchars($venta['metodopago']) ?></td>
                                            <td><?= htmlspecialchars($venta['fecha']) ?></td>
                                            <td><?= htmlspecialchars($venta['hora']) ?></td>
                                            <td>
                                                <a href="<?=BASE_URL?>imprimir/ventas/reciboPago?id_venta=<?= base64_encode($venta['venta_grupal']) ?>" target="_blank" class="btn btn-warning btn-sm">
                                                    <i class="fas fa-solid fa-file-invoice"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr class="text-center">
                                        <td colspan="7">No hay ventas registradas para la fecha seleccionada.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="row text-center">
                        <div class="col-sm">
                            <h3>Método de Pago:</h3>
                            <p>
                            <?php foreach ($metodoPagoSuma as $metodo => $suma): ?>
                                <p><?= htmlspecialchars($metodo) ?>: <?= htmlspecialchars($suma) ?></p>
                            <?php endforeach; ?>
                            </p>
                        </div>
                        <div class="col-sm">
                            <h3>Total Venta:</h3>
                            <h1>$ <?= htmlspecialchars($totalVentas + $totalVentasMedias) ?></h1>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<?php
include_once VISTA_PATH . 'script_and_final.php';
include_once VISTA_PATH . 'modulos' . DS . 'visitante' . DS . 'agregarRegistro.php';
?>
