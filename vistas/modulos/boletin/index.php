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
require_once CONTROL_PATH . 'curso' . DS . 'ControlCurso.php';
require_once CONTROL_PATH . 'calificacion' . DS . 'ControlCalificacion.php';

$instancia              = ControlUsuarios::singleton_usuario();
$instancia_curso        = ControlCurso::singleton_curso();
$instancia_calificacion = ControlCalificacion::singleton_calificacion();

$datos_cursos = $instancia_curso->mostrarTodosCursosControl();

if (isset($_POST['buscar'])) {
    $filtro = $_POST['filtro'];
    $curso = $_POST['curso'];

    $datos = array(
                'filtro' => $filtro,
                'curso' => $curso,
                );
    
    $datos_usuarios = $instancia->mostrarEstudiantesBuscarControl($datos);
} else {
    $datos_usuarios = $instancia->mostrarEstudiantesControl();
}

$permisos = $instancia_permiso->permisosUsuarioControl($perfil_log, 10);
if (!$permisos) {
    include_once VISTA_PATH . 'modulos' . DS . '403.php';
    die();
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h4 class="m-0 font-weight-bold text-play">
                        <a href="<?= BASE_URL ?>inicio" class="text-decoration-none text-play">
                            <i class="fa fa-arrow-left"></i>
                        </a>
                        &nbsp;
                        Boletines
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="row">
                            <div class="col-lg-4"></div>
                            <div class="col-lg-4 form-group">
                                <select name="curso" class="form-control">
                                    <option value="" selected>Seleccione un curso...</option>
                                    <?php
                                    foreach ($datos_cursos as $curso) {
                                        $id_curso  = $curso['id'];
                                        $nom_curso = $curso['nombre'];
                                    ?>
                                        <option value="<?= $id_curso ?>"><?= $nom_curso ?></option>
                                        
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-lg-4">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control filtro buscar" placeholder="Buscar" name="filtro" aria-describedby="basic-addon2" data-tooltip="tooltip" title="Presiona ENTER para buscar" data-placement="top" data-trigger="focus">
                                    <div class="input-group-append">
                                        <button class="btn btn-play btn-sm" type="submit" name="buscar">
                                            <i class="fa fa-search"></i>
                                            &nbsp;
                                            Buscar
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
                                    <th scope="col">Tipo Documento</th>
                                    <th scope="col">Documento</th>
                                    <th scope="col">Nombre Completo</th>
                                    <th scope="col">Genero</th>
                                    <th scope="col">Curso</th>
                                </tr>
                            </thead>
                            <tbody class="buscar">
                                <?php
                                foreach ($datos_usuarios as $usuario) {
                                    $id_user      = $usuario['id_user'];
                                    $documento    = $usuario['documento'];
                                    $nom_completo = $usuario['nombre'] . ' ' . $usuario['apellido'];
                                    $tipo_doc     = $usuario['tipo_doc'];
                                    $genero       = $usuario['genero'];
                                    $user_nom     = $usuario['user'];
                                    $perfil       = $usuario['nom_perfil'];
                                    $activo       = $usuario['activo'];
                                    $nom_curso    = $usuario['nom_curso'];

                                    $ver_inactivo = ($activo == 1) ? '' : 'd-none';
                                    $ver_activo   = ($activo == 1) ? 'd-none' : '';

                                    $genero = ($genero == 1) ? 'Masculino' : 'Femenino';

                                    $datos_curso = $instancia_curso->mostrarInformacionCursoControl($usuario['curso']);

                                    $boletin_estudiante = $instancia_calificacion->mostrarBoletinEstudianteControl($datos_curso['id'], $datos_curso['periodo_actual'], $id_user, $datos_curso['id_anio']);

                                    $ver_boletin   = (!empty($boletin_estudiante['id']) && $boletin_estudiante['generado'] == 1) ? '' : 'd-none';
                                    $ver_calificar = ($boletin_estudiante['generado'] == 0) ? '' : 'd-none';

                                ?>
                                    <tr class="text-center user_<?= $id_user ?>">
                                        <td><?= $tipo_doc ?></td>
                                        <td><?= $documento ?></td>
                                        <td><?= $nom_completo ?></td>
                                        <td><?= $genero ?></td>
                                        <td><?= $nom_curso ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?= BASE_URL ?>imprimir/calificacion/boletin?boletin=<?= base64_encode($boletin_estudiante['id']) ?>" class="btn btn-primary btn-sm <?= $ver_boletin ?>" data-tooltip="tooltip" title="Ver Boletin" data-placement="bottom" target="_blank">
                                                    <i class="fa fa-file-pdf"></i>
                                                </a>
                                                <a href="<?= BASE_URL ?>boletin/calificar?estudiante=<?= base64_encode($id_user) ?>" class="btn btn-play btn-sm <?= $ver_calificar ?>" data-tooltip="tooltip" title="Calificar" data-placement="bottom">
                                                    <i class="fas fa-clipboard-list"></i>
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
            </div>
        </div>
    </div>
</div>
<?php
include_once VISTA_PATH . 'script_and_final.php';
