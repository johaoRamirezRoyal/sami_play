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
require_once CONTROL_PATH . 'perfil' . DS . 'ControlPerfil.php';
require_once CONTROL_PATH . 'pension' . DS . 'ControlPension.php';
require_once CONTROL_PATH . 'calificacion' . DS . 'ControlCalificacion.php';
require_once CONTROL_PATH . 'observador' . DS . 'ControlObservador.php';

$instancia              = ControlUsuarios::singleton_usuario();
$instancia_perfil       = ControlPerfil::singleton_perfil();
$instancia_pension      = ControlPension::singleton_pension();
$instancia_calificacion = ControlCalificacion::singleton_calificacion();
$instancia_observador   = ControlObservador::singleton_observador();

$permisos = $instancia_permiso->permisosUsuarioControl($perfil_log, 7);
if (!$permisos) {
    include_once VISTA_PATH . 'modulos' . DS . '403.php';
    die();
}

if (isset($_GET['id'])) {
    $id_estudiante = base64_decode($_GET['id']);

    $datos_estudiante = $instancia->mostrarDatosEstudiantesControl($id_estudiante);
    $datos_pension    = $instancia_pension->mostrarPensionesPagasEstudianteControl($id_estudiante);
    $datos_boletin    = $instancia_calificacion->mostrarBoletinesGeneradosControl($id_estudiante);
    $datos_observador = $instancia_observador->mostrarObservacionesControl($id_estudiante);

    ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h4 class="m-0 font-weight-bold text-play">
                            <a href="<?=BASE_URL?>estudiantes/index" class="text-decoration-none text-play">
                                <i class="fa fa-arrow-left"></i>
                            </a>
                            &nbsp;
                            Hoja de Vida Estudiante - (<?=$datos_estudiante['nombre']?> <?=$datos_estudiante['apellido']?>)
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#informacion" role="tab" aria-controls="profile" aria-selected="false">Informacion</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="home-tab" data-toggle="tab" href="#pension" role="tab" aria-controls="home" aria-selected="true">Pension</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="contact-tab" data-toggle="tab" href="#observador" role="tab" aria-controls="contact" aria-selected="false">Observador</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="contact-tab" data-toggle="tab" href="#boletines" role="tab" aria-controls="contact" aria-selected="false">Boletines</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-12 form-group">
                            <div class="tab-content">
                                <!--------------------------->
                                <div class="tab-pane fade show active" id="informacion" role="tabpanel" aria-labelledby="profile-tab">
                                    <form action="" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" value="<?=$id_estudiante?>" name="id_user">
                                        <input type="hidden" value="<?=$id_log?>" name="id_log">
                                        <input type="hidden" value="<?=$datos_estudiante['foto_perfil']?>" name="foto_perfil_ant">
                                        <input type="hidden" value="<?=$datos_estudiante['pass']?>" name="pass_old">
                                        <input type="hidden" value="" name="password">
                                        <input type="hidden" value="" name="conf_password">
                                        <input type="hidden" value="<?=$datos_estudiante['user']?>" name="usuario">
                                        <input type="hidden" value="<?=$datos_estudiante['perfil']?>" name="perfil">
                                        <input type="hidden" value="<?=$datos_estudiante['documento']?>" name="documento">
                                        <input type="hidden" value="<?=BASE_URL?>estudiantes/hojaVida?id=<?=base64_encode($id_estudiante)?>" name="url">
                                        <div class="row p-2 mt-2">
                                            <div class="col-lg-12 form-group">
                                                <div class="row">
                                                    <div class="col-lg-4 form-group mt-4">
                                                        <div class="circular--portrait">
                                                            <img src="<?=PUBLIC_PATH?>upload/<?=$datos_estudiante['foto_perfil']?>" alt="">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-8 form-group mt-2">
                                                        <div class="row">
                                                            <div class="col-lg-12 form-group">
                                                                <label class="font-weight-bold">Numero de documento <span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" value="<?=$datos_estudiante['documento']?>" disabled>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    <label class="font-weight-bold">Nombre <span class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control letras" value="<?=$datos_estudiante['nombre']?>" name="nombre" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    <label class="font-weight-bold">Apellido <span class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control letras" value="<?=$datos_estudiante['apellido']?>" name="apellido" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    <label class="font-weight-bold">Correo</label>
                                                                    <input type="email" class="form-control" value="<?=$datos_estudiante['correo']?>" name="correo">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    <label class="font-weight-bold">Telefono</label>
                                                                    <input type="text" class="form-control numeros" value="<?=$datos_estudiante['telefono']?>" name="telefono">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-12 form-group">
                                                                <label class="font-weight-bold">Foto perfil</label>
                                                                <div class="custom-file pmd-custom-file-filled">
                                                                    <input type="file" class="custom-file-input file_input" name="foto" accept=".png, .jpg, .jpeg">
                                                                    <label class="custom-file-label file_label" for="customfilledFile"></label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group mt-2 float-right">
                                            <button type="submit" class="btn btn-play btn-sm">
                                                <i class="fa fa-save"></i>
                                                &nbsp;
                                                Guardar Cambios
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <!--------------------------->
                                <div class="tab-pane fade show" id="pension" role="tabpanel" aria-labelledby="profile-tab">
                                    <div class="table-responsive mt-4">
                                        <table class="table table-hover border table-sm" width="100%" cellspacing="0">
                                            <thead>
                                                <tr class="text-center font-weight-bold">
                                                    <th scope="col" colspan="3">PENSIONES PAGADAS</th>
                                                </tr>
                                                <tr class="text-center font-weight-bold">
                                                    <th scope="col">A&ntilde;o Pago</th>
                                                    <th scope="col">Mes Pago</th>
                                                    <th scope="col">Fecha Pago</th>
                                                </tr>
                                            </thead>
                                            <tbody class="buscar">
                                                <?php
                                                foreach ($datos_pension as $pension) {
                                                    $id_pension   = $pension['id'];
                                                    $documento    = $pension['documento'];
                                                    $nom_completo = $pension['nom_estudiante'];
                                                    $anio_pago    = $pension['anio'];
                                                    $mes_pago     = $pension['mes_pago'];
                                                    $fecha_pago   = $pension['fecha_pago'];
                                                    ?>
                                                    <tr class="text-center">
                                                        <td><?=$anio_pago?></td>
                                                        <td><?=$mes_pago?></td>
                                                        <td><?=$fecha_pago?></td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!--------------------------->
                                <div class="tab-pane fade show" id="observador" role="tabpanel" aria-labelledby="profile-tab">
                                    <div class="table-responsive mt-2">
                                        <table class="table table-hover border table-sm" width="100%" cellspacing="0">
                                            <thead>
                                                <tr class="text-center font-weight-bold">
                                                    <th scope="col" colspan="2">Historial de observador</th>
                                                </tr>
                                                <tr class="text-center font-weight-bold">
                                                    <th scope="col">Observacion</th>
                                                </tr>
                                            </thead>
                                            <tbody class="buscar">
                                                <?php
                                                foreach ($datos_observador as $observador) {
                                                    $id_observador = $observador['id'];
                                                    $observacion   = $observador['observador'];

                                                    $datos_comentarios = $instancia_observador->historialComentariosControl($id_observador);
                                                    ?>
                                                    <tr class="text-left">
                                                        <td style="width:90%;"><?=$observacion?></td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <button class="btn btn-play btn-sm" type="button" data-tooltip="tooltip" title="Historial de comentarios" data-placement="bottom" data-toggle="modal" data-target="#historial_<?=$id_observador?>">
                                                                    <i class="fas fa-history"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>


                                                    <div class="modal fade" id="historial_<?=$id_observador?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title font-weight-bold text-play" id="exampleModalLabel">Historial de comentarios</h4>
                                                                    <button class="btn btn-sm" type="button" data-dismiss="modal">
                                                                        <i class="fa fa-times"></i>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="row p-2">
                                                                        <div class="col-lg-12 form-group">
                                                                            <div class="card">
                                                                                <div class="card-header text-left">
                                                                                    <h5 class="font-weight-bold text-play">
                                                                                        <?=$observador['nom_usuario']?> - (OBSERVACION INICIAL)
                                                                                    </h5>
                                                                                </div>
                                                                                <div class="card-body">
                                                                                    <textarea class="form-control" rows="5" disabled><?=$observacion?></textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <?php
                                                                        foreach ($datos_comentarios as $historial) {
                                                                            $comentario = $historial['comentario'];
                                                                            ?>
                                                                            <div class="col-lg-12 form-group">
                                                                                <div class="card">
                                                                                    <div class="card-header text-left">
                                                                                        <h5 class="font-weight-bold text-play">
                                                                                            <?=$historial['nom_usuario']?>
                                                                                        </h5>
                                                                                    </div>
                                                                                    <div class="card-body">
                                                                                        <p><?=$comentario?></p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                <?php }?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!--------------------------->
                                <div class="tab-pane fade show" id="boletines" role="tabpanel" aria-labelledby="profile-tab">
                                    <?php
/*if (!empty($datos_estudiante['ultima_anio_pension'])) {
    if ($datos_estudiante['ultima_mes_pension'] == date('n') && $datos_estudiante['ultima_anio_pension'] == date('Y')) {*/
        if (!empty($id_log)) {
            if (!empty($id_log)) {
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
        } else {
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
        ?>
    </div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<?php
include_once VISTA_PATH . 'script_and_final.php';

if (isset($_POST['documento'])) {
    $instancia_perfil->editarPerfilControl();
}
}
?>