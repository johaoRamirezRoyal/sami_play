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
require_once CONTROL_PATH . 'usuarios' . DS . 'ControlUsuarios.php';
require_once CONTROL_PATH . 'calificacion' . DS . 'ControlCalificacion.php';
require_once CONTROL_PATH . 'perfil' . DS . 'ControlPerfil.php';
require_once CONTROL_PATH . 'pension' . DS . 'ControlPension.php';

$instancia              = ControlUsuarios::singleton_usuario();
$instancia_perfil       = ControlPerfil::singleton_perfil();
$instancia_pension      = ControlPension::singleton_pension();
$instancia_calificacion = ControlCalificacion::singleton_calificacion();

$permisos = $instancia_permiso->permisosUsuarioControl($perfil_log, 16);
if (!$permisos) {
    include_once VISTA_PATH . 'modulos' . DS . '403.php';
    die();
}

if (isset($_GET['id'])) {

    $id_estudiante = base64_decode($_GET['id']);

    $datos_estudiante = $instancia->mostrarDatosEstudiantesControl($id_estudiante);
    $datos_pension    = $instancia_pension->mostrarPensionesPagasEstudianteControl($id_estudiante);
    $datos_boletin    = $instancia_calificacion->mostrarBoletinesGeneradosControl($id_estudiante);

    ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h4 class="m-0 font-weight-bold text-play">
                            <a href="<?=BASE_URL?>inicio" class="text-decoration-none text-play">
                                <i class="fa fa-arrow-left"></i>
                            </a>
                            &nbsp;
                            Historial de boletines
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                        </div>
                        <?php
                        if (!empty($datos_estudiante['ultima_anio_pension'])) {
                            if ($datos_estudiante['ultima_mes_pension'] == date('n') && $datos_estudiante['ultima_anio_pension'] == date('Y')) {
                                ?>
                                <div class="table-responsive mt-4">
                                    <table class="table table-hover border table-sm" width="100%" cellspacing="0">
                                        <thead>
                                            <tr class="text-center font-weight-bold">
                                                <th scope="col" colspan="6">BOLETINES GENERADOS</th>
                                            </tr>
                                            <tr class="text-center font-weight-bold">
                                                <th scope="col">Profesor</th>
                                                <th scope="col">Curso</th>
                                                <th scope="col">A&ntilde;o</th>
                                                <th scope="col">Periodo</th>
                                            </tr>
                                        </thead>
                                        <tbody class="buscar">
                                            <?php
                                            foreach ($datos_boletin as $boletin) {
                                                $id_boletin   = $boletin['id'];
                                                $nom_profesor = $boletin['nom_profesor'];
                                                $nom_curso    = $boletin['nom_curso'];
                                                $anio         = $boletin['anio'];
                                                $periodo      = $boletin['nom_periodo'];
                                                ?>
                                                <tr class="text-center">
                                                    <td><?=$nom_profesor?></td>
                                                    <td><?=$nom_curso?></td>
                                                    <td><?=$anio?></td>
                                                    <td><?=$periodo?></td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <a href="<?=BASE_URL?>imprimir/calificacion/boletin?boletin=<?=base64_encode($id_boletin)?>" class="btn btn-primary btn-sm" data-tooltip="tooltip" title="Ver Boletin" data-placement="bottom" target="_blank">
                                                                <i class="fa fa-file-pdf"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php }?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php } else {
                                ?>
                                <div class="row mt-4">
                                    <div class="col-lg-3 form-group"></div>
                                    <div class="col-lg-6 form-group">
                                        <div class="card shadow-sm bg-danger border-0">
                                            <div class="card-body">
                                                <h3 class="text-center text-white font-weight-bold">Pension Atrasada</h3>
                                                <p class="text-white text-center">Favor cancelar las pensiones atrasadas para poder visualizar los boletines generados.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    include_once VISTA_PATH . 'script_and_final.php';
}
