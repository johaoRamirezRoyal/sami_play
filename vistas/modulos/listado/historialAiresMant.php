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

    header('Location:../login?er=' . $error);

    exit();

}

include_once VISTA_PATH . 'cabeza.php';

include_once VISTA_PATH . 'navegacion.php';

require_once CONTROL_PATH . 'inventario' . DS . 'ControlInventario.php';

require_once CONTROL_PATH . 'hoja_vida' . DS . 'ControlHojaVida.php';



$instancia           = ControlInventario::singleton_inventario();

$instancia_hoja_vida = ControlHojaVida::singleton_hoja_vida();



$permisos = $instancia_permiso->permisosUsuarioControl(12, $perfil_log);



if (!$permisos) {

    include_once VISTA_PATH . 'modulos' . DS . '403.php';

    exit();

}



if (isset($_GET['inventario'])) {



    $id_inventario   = base64_decode($_GET['inventario']);

    $datos_articulo  = $instancia->mostrarDatosArticuloIdControl($id_inventario);

    $datos_historial = $instancia->historialArticuloControl($id_inventario);

    $datos_reporte = $instancia_hoja_vida->mostrarReportesArticuloControl($id_inventario);



    $codigo = $datos_articulo['id'];



    $evidencia = (empty($datos_articulo['evidencia'])) ? 'disabled' : '';



    ?>

    <div class="container-fluid">

        <div class="row">

            <div class="col-lg-12">

                <div class="card shadow-sm mb-4">

                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">

                        <h4 class="m-0 font-weight-bold text-primary">

                            <a href="<?=BASE_URL?>mantenimientos/mantAires" class="text-decoration-none">

                                <i class="fa fa-arrow-left text-primary"></i>

                            </a>

                            &nbsp;

                            Detalle articulo (<?=$datos_articulo['descripcion']?>)

                        </h4>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-lg-4">

                                <div class="form-group">

                                    <label class="font-weight-bold">Nombre del equipo</label>

                                    <div class="input-group mb-3">

                                        <input type="text" class="form-control" maxlength="50" disabled value="<?=$datos_articulo['descripcion']?>">

                                    </div>

                                </div>

                            </div>

                            <div class="col-lg-4">

                                <div class="form-group">

                                    <label class="font-weight-bold">Marca</label>

                                    <div class="input-group mb-3">

                                        <input type="text" class="form-control" maxlength="50" disabled value="<?=$datos_articulo['marca']?>">

                                    </div>

                                </div>

                            </div>

                            <div class="col-lg-4">

                                <div class="form-group">

                                    <label class="font-weight-bold">Modelo</label>

                                    <div class="input-group mb-3">

                                        <input type="text" class="form-control" maxlength="50" disabled value="<?=$datos_articulo['modelo']?>">

                                    </div>

                                </div>

                            </div>

                            <div class="col-lg-4">

                                <div class="form-group">

                                    <label class="font-weight-bold">Codigo</label>

                                    <div class="input-group mb-3">

                                        <input type="text" class="form-control" maxlength="50" disabled value="<?=$codigo?>">

                                    </div>

                                </div>

                            </div>

                            <div class="col-lg-4">

                                <div class="form-group">

                                    <label class="font-weight-bold">Area</label>

                                    <div class="input-group mb-3">

                                        <input type="text" class="form-control" maxlength="50" disabled value="<?=$datos_articulo['area']?>">

                                    </div>

                                </div>

                            </div>

                            <div class="col-lg-4">

                                <div class="form-group">

                                    <label class="font-weight-bold">Usuario responsable</label>

                                    <div class="input-group mb-3">

                                        <input type="text" class="form-control text-uppercase" maxlength="50" disabled value="<?=$datos_articulo['usuario']?>">

                                    </div>

                                </div>

                            </div>

                            <div class="col-lg-4">

                                <div class="form-group">

                                    <label class="font-weight-bold">Fecha asignado</label>

                                    <div class="input-group mb-3">

                                        <input type="text" class="form-control" maxlength="50" disabled value="<?=$datos_articulo['fechareg']?>">

                                    </div>

                                </div>

                            </div>

                            <div class="col-lg-4">

                                <div class="form-group mt-2">

                                    <form method="POST" action="<?=BASE_URL?>imprimir/codigo">

                                        <input type="hidden" value="<?=$codigo?>" name="codigo">

                                        <button type="submit" class="btn btn-primary btn-sm mt-4">

                                            <i class="fas fa-barcode"></i>

                                            &nbsp;

                                            Descargar codigo

                                        </button>

                                        <a href="<?=PUBLIC_PATH?>upload/<?=$datos_articulo['evidencia']?>" class="btn btn-info btn-sm mt-4 ml-2 <?=$evidencia?>" target="_blank">

                                            <i class="fa fa-eye"></i>

                                            &nbsp;

                                            Ver evidencia

                                        </a>

                                    </form>

                                </div>

                            </div>

                        </div>

                        <div class="table-responsive mt-4">

                            <table class="table table-hover border mt-2 table-sm" width="100%" cellspacing="0">

                                <div class="col-lg-12">

                                    <div class="table-responsive">

                                        <table class="table table-hover border" width="100%" cellspacing="0">

                                            <thead>

                                                <tr class="text-center font-weight-bold">

                                                    <th colspan="6">

                                                        Control de mantenimientos

                                                    </th>

                                                </tr>

                                                <tr class="text-center font-weight-bold">

                                                    <th>Observacion</th>

                                                    <th>Reporte</th>

                                                    <th>Fecha</th>

                                                    <th>Respuesta</th>

                                                </tr>

                                            </thead>

                                            <tbody class="buscar text-uppercase">

                                                <?php

                                                foreach ($datos_reporte as $reporte) {

                                                    $id_reporte = $reporte['id'];

                                                    $observacion = $reporte['observacion'];

                                                    $fecha = $reporte['fechareg'];

                                                    $estado = $reporte['estado'];

                                                    $fecha_respuesta = $reporte['fecha_respuesta'];





                                                    $fecha_nueva = $fecha;

                                                    $nuevafecha = strtotime('-1 hour', strtotime($fecha));

                                                    $nuevafecha = date('Y-m-d H:i:s', $nuevafecha);



                                                    $fecha_ver = ($reporte['estado'] == 3) ? $fecha_respuesta : $fecha_nueva;



                                                    $ver = ($reporte['estado'] == 3) ? '' : 'd-none';

                                                    $ver_solucion = ($reporte['estado'] != 3) ? '' : 'd-none';



                                                    if ($fecha_respuesta == '') {

                                                        $respuesta = '';

                                                    } else {

                                                        $fecha_reporte = $instancia_hoja_vida->mostrarFechaReportadoControl($reporte['id_reporte']);

                                                        $datetime1 = new DateTime($fecha_reporte['fechareg']);

                                                        $datetime2 = new DateTime($fecha_respuesta);

                                                        $interval = $datetime1->diff($datetime2);

                                                        $respuesta = $interval->format('%d Dias %h Horas %i Minutos %s Segundos');

                                                    }

                                                    if($estado==3){
                                                        $nombre_estado="Realizado";
                                                    }



                                                    ?>

                                                    <tr class="text-center">

                                                        <td>
                                                            <?= $observacion ?>
                                                        </td>

                                                        <td>
                                                            <?= $nombre_estado ?>
                                                        </td>

                                                        <td>
                                                            <?= $fecha_ver ?>
                                                        </td>

                                                        <td>
                                                            <?= $respuesta ?>
                                                        </td>

                                                        <td>

                                                            <div class="btn-group">

                                                                <a href="<?= BASE_URL ?>imprimir/solucion?reporte=<?= base64_encode($id_reporte) ?>"
                                                                    target="_blank" class="btn btn-primary btn-sm <?= $ver ?>"
                                                                    data-tooltip="tooltip" title="Descargar reporte">

                                                                    <i class="fa fa-download"></i>

                                                                </a>

                                                            </div>

                                                        </td>

                                                    </tr>

                                                    <?php

                                                }

                                                ?>

                                            </tbody>

                                        </table>

                                    </div>

                                </div>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <?php

    include_once VISTA_PATH . 'script_and_final.php';

}

