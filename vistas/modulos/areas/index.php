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
require_once CONTROL_PATH . 'areas' . DS . 'ControlAreas.php';

$instancia = ControlAreas::singleton_areas();

$datos_areas = $instancia->mostrarAreasControl($id_super_empresa);

$permisos = $instancia_permiso->permisosUsuarioControl($perfil_log, 28);

if (!$permisos) {
    include_once VISTA_PATH . 'modulos' . DS . '403.php';
    exit();
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h4 class="m-0 font-weight-bold text-primary">
                        <a href="<?=BASE_URL?>inicio" class="text-decoration-none">
                            <i class="fa fa-arrow-left text-primary"></i>
                        </a>
                        &nbsp;
                        Areas
                    </h4>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(17px, 19px, 0px);">
                            <div class="dropdown-header">Acciones:</div>
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#agregar_area">Agregar area</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8 form-inline">
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control filtro" placeholder="Buscar">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text rounded-right" id="basic-addon1">
                                            <i class="fa fa-search"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive mt-2">
                        <table class="table table-hover border table-sm" width="100%" cellspacing="0">
                            <thead>
                                <tr class="text-center font-weight-bold">
                                    <th scope="col">#</th>
                                    <th scope="col">Nombre</th>
                                </tr>
                            </thead>
                            <tbody class="buscar text-uppercase">
                                <?php
                                foreach ($datos_areas as $areas) {
                                    $id_area = $areas['id'];
                                    $nombre  = $areas['nombre'];
                                    $estado  = $areas['activo'];

                                    $ver_activo   = 'd-none';
                                    $ver_inactivo = 'd-none';

                                    if ($estado == 1) {
                                        $ver_activo   = 'd-none';
                                        $ver_inactivo = '';
                                    } else {
                                        $ver_activo   = '';
                                        $ver_inactivo = 'd-none';
                                    }
                                    ?>
                                    <tr class="text-center">
                                        <td><?=$id_area?></td>
                                        <td><?=$nombre?></td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" class="btn btn-primary btn-sm" data-tooltip="tooltip" data-placement="bottom" title="Editar area" data-toggle="modal" data-target="#editar_area<?=$id_area?>">
                                                    <i class="fa fa-edit"></i>
                                                </button>

                                                <button class="btn btn-success btn-sm activar_area <?=$ver_activo?>" id="activar_<?=$id_area?>" data-id="<?=$id_area?>" data-tooltip="tooltip" data-placement="bottom" title="Activar area" data-trigger="hover">
                                                    <i class="fa fa-check"></i>
                                                </button>
                                                <button class="btn btn-danger btn-sm inactivar_area <?=$ver_inactivo?>" id="inactivar_<?=$id_area?>" data-id="<?=$id_area?>" data-tooltip="tooltip" data-placement="bottom" title="Inactivar area" data-trigger="hover">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>


                                    <!-- Editar Area -->
                                    <div class="modal fade" id="editar_area<?=$id_area?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-md" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title text-primary font-weight-bold" id="exampleModalLabel">Editar Area</h5>
                                                </div>
                                                <form method="POST">
                                                    <input type="hidden" name="id_area" value="<?=$id_area?>">
                                                    <div class="modal-body border-0">
                                                        <div class="form-group">
                                                            <label class="font-weight-bold">Nombre</label>
                                                            <input type="text" class="form-control" required name="nom_edit" maxlength="50" minlength="1" value="<?=$nombre?>">
                                                        </div>

                                                    </div>
                                                    <div class="modal-footer border-0">
                                                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Cerrar</button>
                                                        <button type="submit" class="btn btn-success btn-sm">
                                                            <i class="fa fa-edit"></i>
                                                            &nbsp;
                                                            Guardar Cambios
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                <?php }?>
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
include_once VISTA_PATH . 'modulos' . DS . 'areas' . DS . 'agregarArea.php';

if (isset($_POST['nombre'])) {
    $instancia->guradarAreaControl();
}

if (isset($_POST['nom_edit'])) {
    $instancia->editarAreaControl();
}
?>
<script type="text/javascript" src="<?=PUBLIC_PATH?>js/areas/funcionesArea.js"></script>