<?php
require_once 'TicketModel.php'; // Asegúrate de incluir el modelo
$idVenta = $_POST['id'] ?? null; // Obtiene el ID de la venta desde el POST

if ($idVenta) {
    $ticketModel = new TicketModel();
    $venta = $ticketModel->obtenerVentaPorId($idVenta);

    if ($venta) {
        // Aquí puedes generar el HTML del tiquete
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>Tiquete de Venta # <?$venta['venta_grupal'] ?></title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 20px;
                }
                h1, h2, h3, h4 {
                    margin: 0;
                }
                .ticket {
                    border: 1px solid #000;
                    padding: 10px;
                    width: 300px; /* Ajusta el ancho del tiquete */
                    margin: 0 auto; /* Centra el tiquete */
                }
            </style>
        </head>
        <body>
            <div class="ticket">
                <h2>Tiquete de Venta</h2>
                <p>ID Venta: <?= $venta['venta_grupal'] ?></p>
                <p>Producto: <?= $venta['producto'] ?></p>
                <p>Precio: <?= '$' . number_format($venta['precio'], 2) ?></p>
                <p>Cantidad: <?= $venta['cantidad'] ?></p>
                <p>Método de Pago: <?= $venta['metodopago'] ?></p>
                <p>Fecha: <?= $venta['fecha'] ?></p>
                <p>Hora: <?= $venta['hora'] ?></p>
            </div>
        </body>
        </html>
        <?php
    } else {
        echo "Venta no encontrada.";
    }
} else {
    echo "ID de venta no proporcionado.";
}
?>
