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
    header('Location:../login?er=' . $error);
    exit();
}

// Incluye los archivos de encabezado y navegación
include_once VISTA_PATH . 'cabeza.php';
include_once VISTA_PATH . 'navegacion.php';
require_once CONTROL_PATH . 'tienda' . DS . 'TiendaController.php';
require_once CONTROL_PATH . 'visitante' . DS . 'ControlVisitante.php';


$instancia = ControlTienda::singleton_tienda();
$instancia2 = ControlVisitante::singleton_visitante();

$productos = $instancia->mostrarArticulosControl();
$ventas = $instancia->mostrarHistorialVentasControl();
$articulos = Inventario::obtenerArticulosModel();

if (isset($_POST['filtrar'])) {
    $articulos = $instancia->obtenerArticulosFiltradosControl($_POST['filtro']);
}


$carrito = $instancia->ObtenerCarritoControl();


//var_dump($carrito);

if (isset($_POST['carrito'])) {
    $instancia->AgregarElementoCarritoControl();
}

if (isset($_POST['agregar'])) {
    $instancia->agregarArticuloControl();
}

if (isset($_POST['eliminar'])) {
    $instancia->eliminarArticuloControl();
}

if (isset($_POST['update'])) {
    $instancia->procesarFormularioActualizacion();
}

if (isset($_POST['limpiarcarrito'])) {
    $instancia->LimpiarCarritoControl();
}

if (isset($_POST['cobrar'])) {
    $instancia->cobrarCarritoControl();
}

//seccion botones carrito
if (isset($_POST['sumar'])) {
    $instancia->SumarCarrito();
}

if (isset($_POST['restar'])) {
    $instancia->RestarCarrito();
}

if (isset($_POST['borrar'])) {
    $instancia->BorrarCarrito();
}


// Manejar el ticket
$ticket = null;
if (isset($_POST['imprimirTicket'])) {
    $ventaId = $_POST['venta_grupal'];
    // Aquí deberías obtener la información de la venta según el ID
    foreach ($ventas as $venta) {
        if ($venta['venta_grupal'] == $ventaId) {
            $ticket = $venta; // Guarda la venta para mostrar el ticket
            break;
        }
    }
}
?>

<style>
    @media print {
        body * {
            visibility: hidden;
        }

        #ticketModal,
        #ticketModal * {
            visibility: visible;
        }

        #ticketModal {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h4 class="m-0 font-weight-bold text-play">
                        <a href="<?= BASE_URL ?>inicio" class="text-decoration-none text-play">
                            <i class="fa fa-arrow-left"></i>
                        </a>
                        <!-- Botones para seleccionar tablas, tienda, compras, historial de ventas -->
                        &nbsp; Tienda
                    </h4>
                    <form method="POST" action="">
                        <div class="form-group">
                            <button type="submit" name="tablaSeleccion" value="productos" class="btn btn-primary" title="Inventario"><i class="fa fa-list-alt" aria-hidden="true"></i></button>
                            <button type="submit" name="tablaSeleccion" value="compras" class="btn btn-secondary" title="Historial de ventas"><i class="fa fa-history" aria-hidden="true"></i></button>
                            <button type="submit" name="tablaSeleccion" value="comprar" class="btn btn-success" title="Comprar"><i class="fa fa-cart-plus" aria-hidden="true"></i></button>
                            <button type="button" hidden class="btn btn-primary " data-toggle="modal" data-target="#producto">
                                <i class="fa fa-plus"></i>
                                &nbsp;
                                Comprar
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-body">

                    <?php
                    // Determina qué tabla mostrar
                    $tablaSeleccionada = isset($_POST['tablaSeleccion']) ? $_POST['tablaSeleccion'] : 'comprar'; // Cambiado a 'compras'
                    ?>

                    <!-- Tabla de productos, también se puede agregar productos -->
                    <div id="tabla-productos" class="tabla" style="<?= $tablaSeleccionada == 'productos' ? '' : 'display: none;' ?>">
                        <h3 class="font-weight-bold text-play text-center">
                            &nbsp; Productos
                        </h3>
                        <div class="col-lg-12">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#agregar_">
                                    Agregar productos
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive mt-2">
                            <table class="table table-hover border table-sm" width="100%" cellspacing="0">
                                <thead>
                                    <tr class="text-center font-weight-bold">
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Precio</th>
                                        <th scope="col">Stock</th>
                                        <th scope="col">Detalle</th>
                                        <th scope="col">IVA</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($articulos as $producto) { ?>
                                        <tr>
                                            <td class="text-center"><?= $producto['nombre'] ?></td>
                                            <td class="text-center"><?= '$' . $producto['precio'] ?></td>
                                            <td class="text-center"><?= $producto['cantidad'] ?></td>
                                            <td class="text-center"><?= $producto['variaciones'] ?? '' ?></td>
                                            <td class="text-center"><?= '$' . $producto['iva'] ?></td>
                                            <td>
                                                <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                                    data-target="#actualizar_"
                                                    data-id="<?= $producto['id'] ?>"
                                                    data-nombre="<?= $producto['nombre'] ?>"
                                                    data-cantidad="<?= $producto['cantidad'] ?>"
                                                    data-precio="<?= $producto['precio'] ?>">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div id="tabla-compras" class="tabla" style="<?= $tablaSeleccionada == 'compras' ? '' : 'display: none;' ?>">
                        <div>
                            <h3 class="text-center font-weight-bold text-play">
                                &nbsp; Historial de Ventas
                            </h3>
                            <a class="text-right" href="historial">Historial Completo</a>
                        </div>

                        <!-- Tabla de historial de ventas -->

                        <div class="table-responsive mt-2">
                            <table class="table table-hover border table-sm" width="100%" cellspacing="0">
                                <thead>
                                    <tr class="text-center font-weight-bold">
                                        <th scope="col">Id Venta</th>
                                        <th scope="col">Producto</th>
                                        <th scope="col">Precio</th>
                                        <th scope="col">Metodo de Pago</th>
                                        <th scope="col">Fecha</th>
                                        <th scope="col">Hora</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sumaTotalTienda = 0;
                                    $sumaTotalMedias = 0;
                                    foreach ($ventas as $venta) {
                                        if (strpos(strtolower($venta['productos']), 'medias') !== false) {
                                            $sumaTotalMedias += $venta['total_precio'];
                                        } else {
                                            $sumaTotalTienda += $venta['total_precio'];
                                        }

                                    ?>
                                        <tr>
                                            <td class="text-center"><?= $venta['venta_grupal'] ?></td>
                                            <td class="text-center"><?= $venta['productos'] ?></td>
                                            <td class="text-center"><?= '$' . $venta['total_precio'] ?></td>
                                            <td class="text-center"><?= $venta['metodopago'] ?></td>
                                            <td class="text-center"><?= $venta['fecha'] ?></td>
                                            <td class="text-center"><?= $venta['hora'] ?></td>
                                            <td class="text-center">
                                                <form method="POST" action="">
                                                    <input type="hidden" name="venta_grupal" value="<?= $venta['venta_grupal'] ?>">
                                                    <!-- Imprimir ticket PDF -->
                                                    <a href="<?= BASE_URL ?>imprimir/ventas/reciboPago?id_venta=<?= base64_encode($venta['venta_grupal']) ?>" target="_blank" class="btn btn-warning btn-sm">
                                                        <i class="fas fa-solid fa-file-invoice"></i>
                                                    </a>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2" class="text-right font-weight-bold">Suma Total Tienda:</td>
                                        <td class="text-center font-weight-bold"><?= '$' . number_format($sumaTotalTienda, 2) ?></td>
                                        <td colspan="4"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>



                    <?php
                    // Ordenar los productos por nombre en orden alfabético
                    usort($articulos, function ($a, $b) {
                        return strcmp($a['nombre'], $b['nombre']);
                    });
                    ?>

                    <div id="tabla-comprar" class="tabla" style="<?= $tablaSeleccionada == 'comprar' ? '' : 'display: none;' ?>">
                        <h3 class="text-center font-weight-bold text-play">
                            &nbsp; Comprar
                        </h3>
                        <div class="form-group justify-content-end">
                            <form method="POST">
                                <div class="input-group mb-3">
                                    <input type="text" name="filtro" class="form-control" placeholder="Filtrar productos...">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-success btn-sm" title="Buscar productos" name="filtrar">
                                            <i class="fa fa-search"></i>
                                            &nbsp;
                                            Buscar
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <script>
                            // Función mejorada para actualizar el precio total
                            function actualizarPrecio(idProducto, precioUnitario, categoria) {
                                // Construir el prefijo del ID basado en la categoría
                                var prefix = categoria ? categoria + '_' : '';
                                
                                // Obtener el elemento de cantidad
                                var cantidadInput = document.getElementById(prefix + 'cantidad_' + idProducto);
                                
                                // Verificar si el elemento existe
                                if (!cantidadInput) {
                                    console.error('No se encontró el elemento con ID: ' + prefix + 'cantidad_' + idProducto);
                                    return;
                                }
                                
                                // Obtener la cantidad
                                var cantidad = parseInt(cantidadInput.value);
                                
                                // Validar la cantidad
                                if (isNaN(cantidad)) cantidad = 1;
                                if (cantidad < 1) {
                                    cantidad = 1;
                                    cantidadInput.value = 1;
                                }
                                
                                // Calcular el precio total
                                var precioTotal = cantidad * precioUnitario;
                                
                                // Actualizar el texto del precio total
                                var precioTotalElement = document.getElementById(prefix + 'precio_total_' + idProducto);
                                if (precioTotalElement) {
                                    precioTotalElement.innerText = 'Total: $' + precioTotal.toFixed(2);
                                }
                                
                                // Actualizar el campo oculto del precio total
                                var precioTotalInput = document.getElementById(prefix + 'precio_total_input_' + idProducto);
                                if (precioTotalInput) {
                                    precioTotalInput.value = precioTotal;
                                }
                            }
                            </script>

                            <!-- Productos para comprar disponibles -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <a href="#todos_productos" class="nav-link active" id="productos-tab" data-toggle="tab" role="tab" aria-controls="productos" aria-selected="true">Todos los Productos</a>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <a href="#aseo" class="nav-link" id="aseo-tab" data-toggle="tab" role="tab" aria-controls="aseo" aria-selected="false">Aseo</a>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <a href="#papeleria" class="nav-link" id="papeleria-tab" data-toggle="tab" role="tab" aria-controls="papeleria" aria-selected="false">Papeleria</a>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <a href="#uniformes" class="nav-link" id="uniformes-tab" data-toggle="tab" role="tab" aria-controls="uniformes" aria-selected="false">Uniformes</a>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <a href="#zapatos" class="nav-link" id="zapatos-tab" data-toggle="tab" role="tab" aria-controls="zapatos" aria-selected="false">Zapatos</a>
                                        </li>
                                    </ul>

                                    <!-- Contenido de los tabs -->
                                    <div class="tab-content" id="myTabContent">
                                        <!-- Tab Todos los Productos -->
                                        <div class="tab-pane fade show active" id="todos_productos" role="tabpanel" aria-labelledby="productos-tab">
                                            <div class="row">
                                                <?php foreach ($articulos as $producto) { ?>
                                                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                                                        <div class="card">
                                                            <img class="card-img-top" src="<?= $producto['imagen'] ?? 'https://png.pngtree.com/element_our/sm/20180415/sm_5ad31a92cd6ca.jpg' ?>" alt="<?= $producto['nombre'] ?>" style="height: 200px; object-fit: cover;">
                                                            <div class="card-body text-center">
                                                                <h6 class="card-title"><span class="font-weight-bold"><?= $producto['nombre'] ?></span></h6>
                                                                <p class="card-text"><span class="font-weight-bold"><?= $producto['variaciones'] ?? '' ?></span></p>
                                                                <p class="card-text">Precio: $<?= number_format($producto['precio'], 2) ?></p>
                                                                <p class="card-text">Stock: <?= $producto['cantidad'] ?></p>
                                                                <p class="card-text">IVA: <?= number_format($producto['iva'], 2) ?></p>
                                                                <?php if ($producto['cantidad'] > 0) { ?>
                                                                    <form method="POST" action="">
                                                                        <input type="hidden" name="id" value="<?= $producto['id'] ?>">
                                                                        <input type="hidden" name="nombre_producto" value="<?= $producto['nombre'] . ' ' . ($producto['variaciones'] ?? '') ?>">
                                                                        <input type="hidden" name="precio_producto" value="<?= $producto['precio'] ?>">
                                                                        <input type="hidden" name="iva" value="<?= $producto['iva'] ?>">
                                                                        <div class="form-group">
                                                                            <label for="cantidad_<?= $producto['id'] ?>">Cantidad</label>
                                                                            <input type="number" name="cantidad_producto" id="cantidad_<?= $producto['id'] ?>" class="text-center" value="1" min="1" max="<?= $producto['cantidad'] ?>" onchange="actualizarPrecio(<?= $producto['id'] ?>, <?= $producto['precio'] ?>, '')">
                                                                        </div>
                                                                        <p id="precio_total_<?= $producto['id'] ?>">Total: $<?= number_format($producto['precio'], 2) ?></p>
                                                                        <input type="hidden" id="precio_total_input_<?= $producto['id'] ?>" name="precio_total_producto" value="<?= $producto['precio'] ?>">
                                                                        <button type="submit" name="carrito" class="btn btn-success btn-sm">Agregar al carrito</button>
                                                                    </form>
                                                                <?php } else { ?>
                                                                    <p class="text-danger">Agotado</p>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>

                                        <!-- Tab Aseo -->
                                        <div class="tab-pane fade" id="aseo" role="tabpanel" aria-labelledby="aseo-tab">
                                            <div class="row">
                                                <div class="col-12">
                                                    <h1>Aseo</h1>
                                                    <div class="row">
                                                        <?php 
                                                        $articulos_aseo = $instancia->traerProductosCategoriasControl(1);
                                                        foreach ($articulos_aseo as $producto) { ?>
                                                            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                                                                <div class="card">
                                                                    <img class="card-img-top" src="<?= $producto['imagen'] ?? 'https://png.pngtree.com/element_our/sm/20180415/sm_5ad31a92cd6ca.jpg' ?>" alt="<?= $producto['nombre'] ?>" style="height: 200px; object-fit: cover;">
                                                                    <div class="card-body text-center">
                                                                        <h6 class="card-title"><span class="font-weight-bold"><?= $producto['nombre'] ?></span></h6>
                                                                        <p class="card-text"><span class="font-weight-bold"><?= $producto['variaciones'] ?? '' ?></span></p>
                                                                        <p class="card-text">Precio: $<?= number_format($producto['precio'], 2) ?></p>
                                                                        <p class="card-text">Stock: <?= $producto['cantidad'] ?></p>
                                                                        <p class="card-text">IVA: <?= number_format($producto['iva'], 2) ?></p>
                                                                        <?php if ($producto['cantidad'] > 0) { ?>
                                                                            <form method="POST" action="">
                                                                                <input type="hidden" name="id" value="<?= $producto['id'] ?>">
                                                                                <input type="hidden" name="nombre_producto" value="<?= $producto['nombre'] . ' ' . ($producto['variaciones'] ?? '') ?>">
                                                                                <input type="hidden" name="precio_producto" value="<?= $producto['precio'] ?>">
                                                                                <input type="hidden" name="iva" value="<?= $producto['iva'] ?>">
                                                                                <div class="form-group">
                                                                                    <label for="aseo_cantidad_<?= $producto['id'] ?>">Cantidad</label>
                                                                                    <input type="number" name="cantidad_producto" id="aseo_cantidad_<?= $producto['id'] ?>" class="text-center" value="1" min="1" max="<?= $producto['cantidad'] ?>" onchange="actualizarPrecio(<?= $producto['id'] ?>, <?= $producto['precio'] ?>, 'aseo')">
                                                                                </div>
                                                                                <p id="aseo_precio_total_<?= $producto['id'] ?>">Total: $<?= number_format($producto['precio'], 2) ?></p>
                                                                                <input type="hidden" id="aseo_precio_total_input_<?= $producto['id'] ?>" name="precio_total_producto" value="<?= $producto['precio'] ?>">
                                                                                <button type="submit" name="carrito" class="btn btn-success btn-sm">Agregar al carrito</button>
                                                                            </form>
                                                                        <?php } else { ?>
                                                                            <p class="text-danger">Agotado</p>
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Tab Papeleria -->
                                        <div class="tab-pane fade" id="papeleria" role="tabpanel" aria-labelledby="papeleria-tab">
                                            <div class="row">
                                                <div class="col-12">
                                                    <h1>Papeleria</h1>
                                                    <div class="row">
                                                        <?php 
                                                        $articulos_papeleria = $instancia->traerProductosCategoriasControl(2);
                                                        foreach ($articulos_papeleria as $producto) { ?>
                                                            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                                                                <div class="card">
                                                                    <img class="card-img-top" src="<?= $producto['imagen'] ?? 'https://png.pngtree.com/element_our/sm/20180415/sm_5ad31a92cd6ca.jpg' ?>" alt="<?= $producto['nombre'] ?>" style="height: 200px; object-fit: cover;">
                                                                    <div class="card-body text-center">
                                                                        <h6 class="card-title"><span class="font-weight-bold"><?= $producto['nombre'] ?></span></h6>
                                                                        <p class="card-text"><span class="font-weight-bold"><?= $producto['variaciones'] ?? '' ?></span></p>
                                                                        <p class="card-text">Precio: $<?= number_format($producto['precio'], 2) ?></p>
                                                                        <p class="card-text">Stock: <?= $producto['cantidad'] ?></p>
                                                                        <p class="card-text">IVA: <?= number_format($producto['iva'], 2) ?></p>
                                                                        <?php if ($producto['cantidad'] > 0) { ?>
                                                                            <form method="POST" action="">
                                                                                <input type="hidden" name="id" value="<?= $producto['id'] ?>">
                                                                                <input type="hidden" name="nombre_producto" value="<?= $producto['nombre'] . ' ' . ($producto['variaciones'] ?? '') ?>">
                                                                                <input type="hidden" name="precio_producto" value="<?= $producto['precio'] ?>">
                                                                                <input type="hidden" name="iva" value="<?= $producto['iva'] ?>">
                                                                                <div class="form-group">
                                                                                    <label for="papeleria_cantidad_<?= $producto['id'] ?>">Cantidad</label>
                                                                                    <input type="number" name="cantidad_producto" id="papeleria_cantidad_<?= $producto['id'] ?>" class="text-center" value="1" min="1" max="<?= $producto['cantidad'] ?>" onchange="actualizarPrecio(<?= $producto['id'] ?>, <?= $producto['precio'] ?>, 'papeleria')">
                                                                                </div>
                                                                                <p id="papeleria_precio_total_<?= $producto['id'] ?>">Total: $<?= number_format($producto['precio'], 2) ?></p>
                                                                                <input type="hidden" id="papeleria_precio_total_input_<?= $producto['id'] ?>" name="precio_total_producto" value="<?= $producto['precio'] ?>">
                                                                                <button type="submit" name="carrito" class="btn btn-success btn-sm">Agregar al carrito</button>
                                                                            </form>
                                                                        <?php } else { ?>
                                                                            <p class="text-danger">Agotado</p>
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Tab Uniformes -->
                                        <div class="tab-pane fade" id="uniformes" role="tabpanel" aria-labelledby="uniformes-tab">
                                            <div class="row">
                                                <div class="col-12">
                                                    <h1>Uniformes</h1>
                                                    <div class="row">
                                                        <?php 
                                                        $articulos_uniformes = $instancia->traerProductosCategoriasControl(3);
                                                        foreach ($articulos_uniformes as $producto) { ?>
                                                            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                                                                <div class="card">
                                                                    <img class="card-img-top" src="<?= $producto['imagen'] ?? 'https://png.pngtree.com/element_our/sm/20180415/sm_5ad31a92cd6ca.jpg' ?>" alt="<?= $producto['nombre'] ?>" style="height: 200px; object-fit: cover;">
                                                                    <div class="card-body text-center">
                                                                        <h6 class="card-title"><span class="font-weight-bold"><?= $producto['nombre'] ?></span></h6>
                                                                        <p class="card-text"><span class="font-weight-bold"><?= $producto['variaciones'] ?? '' ?></span></p>
                                                                        <p class="card-text">Precio: $<?= number_format($producto['precio'], 2) ?></p>
                                                                        <p class="card-text">Stock: <?= $producto['cantidad'] ?></p>
                                                                        <p class="card-text">IVA: <?= number_format($producto['iva'], 2) ?></p>
                                                                        <?php if ($producto['cantidad'] > 0) { ?>
                                                                            <form method="POST" action="">
                                                                                <input type="hidden" name="id" value="<?= $producto['id'] ?>">
                                                                                <input type="hidden" name="nombre_producto" value="<?= $producto['nombre'] . ' ' . ($producto['variaciones'] ?? '') ?>">
                                                                                <input type="hidden" name="precio_producto" value="<?= $producto['precio'] ?>">
                                                                                <input type="hidden" name="iva" value="<?= $producto['iva'] ?>">
                                                                                <div class="form-group">
                                                                                    <label for="uniformes_cantidad_<?= $producto['id'] ?>">Cantidad</label>
                                                                                    <input type="number" name="cantidad_producto" id="uniformes_cantidad_<?= $producto['id'] ?>" class="text-center" value="1" min="1" max="<?= $producto['cantidad'] ?>" onchange="actualizarPrecio(<?= $producto['id'] ?>, <?= $producto['precio'] ?>, 'uniformes')">
                                                                                </div>
                                                                                <p id="uniformes_precio_total_<?= $producto['id'] ?>">Total: $<?= number_format($producto['precio'], 2) ?></p>
                                                                                <input type="hidden" id="uniformes_precio_total_input_<?= $producto['id'] ?>" name="precio_total_producto" value="<?= $producto['precio'] ?>">
                                                                                <button type="submit" name="carrito" class="btn btn-success btn-sm">Agregar al carrito</button>
                                                                            </form>
                                                                        <?php } else { ?>
                                                                            <p class="text-danger">Agotado</p>
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Tab Zapatos -->
                                        <div class="tab-pane fade" id="zapatos" role="tabpanel" aria-labelledby="zapatos-tab">
                                            <div class="row">
                                                <div class="col-12">
                                                    <h1>Zapatos</h1>
                                                    <div class="row">
                                                        <?php 
                                                        $articulos_zapatos = $instancia->traerProductosCategoriasControl(4);
                                                        foreach ($articulos_zapatos as $producto) { ?>
                                                            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                                                                <div class="card">
                                                                    <img class="card-img-top" src="<?= $producto['imagen'] ?? 'https://png.pngtree.com/element_our/sm/20180415/sm_5ad31a92cd6ca.jpg' ?>" alt="<?= $producto['nombre'] ?>" style="height: 200px; object-fit: cover;">
                                                                    <div class="card-body text-center">
                                                                        <h6 class="card-title"><span class="font-weight-bold"><?= $producto['nombre'] ?></span></h6>
                                                                        <p class="card-text"><span class="font-weight-bold"><?= $producto['variaciones'] ?? '' ?></span></p>
                                                                        <p class="card-text">Precio: $<?= number_format($producto['precio'], 2) ?></p>
                                                                        <p class="card-text">Stock: <?= $producto['cantidad'] ?></p>
                                                                        <p class="card-text">IVA: <?= number_format($producto['iva'], 2) ?></p>
                                                                        <?php if ($producto['cantidad'] > 0) { ?>
                                                                            <form method="POST" action="">
                                                                                <input type="hidden" name="id" value="<?= $producto['id'] ?>">
                                                                                <input type="hidden" name="nombre_producto" value="<?= $producto['nombre'] . ' ' . ($producto['variaciones'] ?? '') ?>">
                                                                                <input type="hidden" name="precio_producto" value="<?= $producto['precio'] ?>">
                                                                                <input type="hidden" name="iva" value="<?= $producto['iva'] ?>">
                                                                                <div class="form-group">
                                                                                    <label for="zapatos_cantidad_<?= $producto['id'] ?>">Cantidad</label>
                                                                                    <input type="number" name="cantidad_producto" id="zapatos_cantidad_<?= $producto['id'] ?>" class="text-center" value="1" min="1" max="<?= $producto['cantidad'] ?>" onchange="actualizarPrecio(<?= $producto['id'] ?>, <?= $producto['precio'] ?>, 'zapatos')">
                                                                                </div>
                                                                                <p id="zapatos_precio_total_<?= $producto['id'] ?>">Total: $<?= number_format($producto['precio'], 2) ?></p>
                                                                                <input type="hidden" id="zapatos_precio_total_input_<?= $producto['id'] ?>" name="precio_total_producto" value="<?= $producto['precio'] ?>">
                                                                                <button type="submit" name="carrito" class="btn btn-success btn-sm">Agregar al carrito</button>
                                                                            </form>
                                                                        <?php } else { ?>
                                                                            <p class="text-danger">Agotado</p>
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <script>
                            $(document).ready(function(){
                                $('#myTab a').on('click', function (e) {
                                    e.preventDefault();
                                    $(this).tab('show');
                                });
                            });
                            </script>
                    </div>


                    <?php if ($ticket): ?>
                        <div class="modal" id="ticketModal" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Información de la Compra</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>ID Venta:</strong> <?= $ticket['venta_grupal'] ?></p>
                                        <p><strong>Producto:</strong> <?= nl2br(str_replace(', ', "<br>", $ticket['productos'])) ?></p>
                                        <p><strong>Precio:</strong> <?= '$' . $ticket['total_precio'] ?></p>
                                        <p><strong>Método de Pago:</strong> <?= $ticket['metodopago'] ?></p>
                                        <p><strong>Fecha:</strong> <?= $ticket['fecha'] ?></p>
                                        <p><strong>Hora:</strong> <?= $ticket['hora'] ?></p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                        <button type="button" class="btn btn-primary" onclick="window.print()">Imprimir</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <script>
                            $(document).ready(function() {
                                $('#ticketModal').modal('show');
                            });
                        </script>
                    <?php endif; ?>

                </div>
            </div>
        </div>
        <? $total = 0 ?>

        <!-- Carrito de compras -->

        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">

                    <form method="POST">
                        <?php
                        $total = 0; // Inicializar el total
                        $total_iva = 0;
                        foreach ($carrito as $carritos) {
                            $subtotal = $carritos['precio'];
                            $total += $subtotal;

                            $subtotal_iva = $carritos['cantidad'] * $carritos['iva'];
                            $total_iva += $subtotal_iva;

                        ?>
                            <input type="hidden" name="id_producto[]" value="<?= $carritos['id'] ?>">
                            <input type="hidden" name="producto[]" value="<?= $carritos['producto'] ?>">
                            <input type="hidden" name="cantidad[]" value="<?= $carritos['cantidad'] ?>">
                            <input type="hidden" name="precio[]" value="<?= $carritos['precio'] ?>">
                            <input type="hidden" name="valor_unidad[]" value="<?= $carritos['valor_unidad'] ?>">
                            <input type="hidden" name="id_prod" value="<?= $carritos['id_producto'] ?>">
                            <input type="hidden" name="iva[]" value="<?= $carritos['cantidad'] * $carritos['iva'] ?>">
                        <?php } ?>

                        <div class="container">
                            <div class="row">
                                <h4 class="font-weight-bold text-play">
                                    &nbsp; Carrito
                                </h4>
                            </div>
                            <div class="row justify-content-between">
                                <div class="form-group col-lg-6">
                                    <select name="metodopago" class="form-control-lg">
                                        <option value="Efectivo">Efectivo</option>
                                        <option value="Transferencia">Transferencia</option>
                                        <option value="Datafono">Datafono</option>
                                        <option value="cortesia">Cortesía</option>
                                        <option value="socio">Cuenta Socio</option>
                                    </select>
                                </div>
                                <div class="form-group text-right col-lg-6">
                                    <button type="submit" name="limpiarcarrito" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Limpiar carrito" onclick="return confirm('¿Estás seguro de que quieres limpiar el carro?')">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                        Limpiar
                                    </button>
                                    <button type="submit" name="cobrar" data-toggle="tooltip" data-placement="top" title="Cobrar" class="btn btn-success" onclick="return confirm('¿Estás seguro de que quieres cobrar? Total: $<?= number_format($total, 2) ?>')">
                                        <i class="fa fa-coins" aria-hidden="true"></i>
                                        Cobrar
                                    </button>
                                </div>
                            </div>
                            <div class="row mt-4 mb-4">
                                <div class="col-lg-12">
                                    <div class="form-group row">
                                        <div class="col-lg-12">
                                            <label for="comprador">Cedula del comprador<span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-12 d-flex justify-content-end">
                                            <input type="text" name="cedula_comprador" class="form-control" id="cedula" placeholder="Ingresa la cedula" required value="<?= isset($_POST['cedula_comprador']) ? htmlspecialchars($_POST['cedula_comprador']) : '' ?>">
                                            <button class="btn btn-play btn-md" type="submit" name="form_cedula">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php if (isset($_POST['form_cedula'])) { ?>
                                <?php
                                $cedula = $_POST['cedula_comprador'];
                                $informacion_usuario = $instancia->traerInfoCompradorControl($cedula);

                                $tipo_documento = ($informacion_usuario['tipo_doc'] != null) ? $informacion_usuario['tipo_doc'] : '';
                                $nombre_usuario = $informacion_usuario['nombre'] . ' ' . $informacion_usuario['apellido'];
                                $telefono = ($informacion_usuario['telefono'] != '') ? $informacion_usuario['telefono'] : '';
                                $id_user = ($informacion_usuario['id_user'] != null) ? $informacion_usuario['id_user'] : null;
                                ?>
                                <div class="row mt-4 mb-4">
                                    <div class="col-lg-12">
                                        <div class="form-group row">
                                            <input type="hidden" name="id_user" value="<?= $id_user ?>">
                                            <div class="col-lg-6 form-group">
                                                <label for="cedula">Tipo de Documento</label>
                                                <input type="text" name="tipo_documento" class="form-control" id="tipo_documento" value="<?= $tipo_documento ?>">
                                            </div>
                                            <div class="col-lg-6 form-group">
                                                <input type="hidden" name="cedula_comprador_registrada" value="<?= $cedula ?>">
                                                <label for="cedula">Cedula</label>
                                                <input type="text" name="cedula_comprador" class="form-control" id="cedula" value="<?= $cedula ?>" disabled>
                                            </div>
                                            <div class="col-lg-6 form-group">
                                                <label for="comprador">Nombre del usuario</label>
                                                <input type="text" name="nombre_usuario" class="form-control" id="nombre_usuario" value="<?= $nombre_usuario ?>">
                                            </div>
                                            <div class="col-lg-6 form-group">
                                                <label for="ciudad">Ciudad</label>
                                                <input type="text" name="ciudad" class="form-control" id="ciudad">
                                            </div>
                                            <div class="col-lg-6 form-group">
                                                <label for="direccion">Dirección</label>
                                                <input type="text" name="direccion" class="form-control" id="direccion">
                                            </div>
                                            <div class="col-lg-6 form-group">
                                                <label for="telefono">Teléfono</label>
                                                <input type="text" name="telefono" class="form-control" id="telefono" value="<?= $telefono ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>

                        </div>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover border table-sm" width="100%" cellspacing="0">
                        <thead>
                            <tr class="text-center font-weight-bold">
                                <th scope="col">Nombre</th>
                                <th scope="col">Cantidad</th>
                                <th scope="col">Precio</th>
                                <th scope="col">IVA</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($carrito as $carritos) { ?>
                                <tr>
                                    <td class="text-center"><?= $carritos['producto'] ?></td>
                                    <form method="post" action="">
                                        <td class="text-center"><?= $carritos['cantidad'] ?></td>
                                        <td class="text-center"><?= '$' . $carritos['precio'] ?></td>
                                        <td class="text-center">$<?= number_format($carritos['cantidad'] * $carritos['iva'], 2) ?></td>

                                        <td class="text-center">
                                            <!-- ID del producto en la tabla carrito -->
                                            <input type="hidden" name="id_carrito" value="<?= $carritos['id'] ?>">
                                            <!-- ID del producto en la tabla productos -->
                                            <input type="hidden" name="id_producto" value="<?= $carritos['id_producto'] ?>">
                                            <!-- Cantidad de productos -->
                                            <input type="hidden" name="cantidad" value="<?= $carritos['cantidad'] ?>">
                                            <button class="btn btn-danger" type="submit" name="borrar">X</button>
                                        </td>
                                    </form>

                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <h5 class="m-0 font-weight-bold text-right">Total: $<?= number_format($total, 2) ?></h5>
                    <h5 class="mt-2 text-right"><em>IVA Total: $<?= number_format($total_iva, 2) ?></em></h5>
                </div>
            </div>
        </div>


        <?php
        include_once VISTA_PATH . 'modulos' . DS . 'tienda' . DS . 'agregarproductos.php';
        include_once VISTA_PATH . 'modulos' . DS . 'tienda' . DS . 'actualizarproductos.php';
        include_once VISTA_PATH . 'modulos' . DS . 'visitante' . DS . 'comprarproducto.php';

        // Incluye los archivos de scripts y pie de página
        include_once VISTA_PATH . 'script_and_final.php';

        if (isset($_POST['comprar'])) {
            $instancia->guardarVentaControl();
        }


        ?>