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

require_once CONTROL_PATH . 'asistencia' . DS . 'ControlAsistencia.php';



$permisos = $instancia_permiso->permisosUsuarioControl($perfil_log, 13);

if (!$permisos) {

    include_once VISTA_PATH . 'modulos' . DS . '403.php';

    exit();

}



?>

<div class="container-fluid">

    <div class="row">

        <div class="col-lg-12">

            <div class="card shadow-sm mb-4">

                <div class="card-header py-3">

                    <h4 class="m-0 font-weight-bold text-play">

                        <a href="<?=BASE_URL?>inicio" class="text-decoration-none">

                            <i class="fa fa-arrow-left text-play"></i>

                        </a>

                        &nbsp;

                        Gestión humana y Calidad

                    </h4>

                </div>

                <div class="card-body">

                    <div class="row">

                        <?php

                        $permisos = $instancia_permiso->permisosUsuarioControl($perfil_log, 11);

                        if ($permisos) {

                            ?>

                            <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>asistencia/index">

                                <div class="card border-left-danger shadow-sm h-100 py-2">

                                    <div class="card-body">

                                        <div class="row no-gutters align-items-center">

                                            <div class="col mr-2">

                                                <div class="h5 mb-0 font-weight-bold text-gray-800">Asistencia</div>

                                            </div>

                                            <div class="col-auto">

                                                <i class="fas fa-list fa-2x text-danger"></i>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </a>

                        <?php }

                        $permisos = $instancia_permiso->permisosUsuarioControl($perfil_log, 12);

                        if ($permisos) {

                            ?>

                            <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>recursos/news/index">

                                <div class="card border-left-primary shadow-sm h-100 py-2">

                                    <div class="card-body">

                                        <div class="row no-gutters align-items-center">

                                            <div class="col mr-2">

                                                <div class="h5 mb-0 font-weight-bold text-gray-800">NEWS Play</div>

                                            </div>

                                            <div class="col-auto">

                                                <i class="far fa-newspaper fa-2x text-primary"></i>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </a>

                        <?php }

                        $permisos = $instancia_permiso->permisosUsuarioControlTramites(76,$perfil_log);
                        
                        if ($permisos) {
                        
                            ?>
                        
                            <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>recursos/tramites/index">
                        
                                <div class="card border-left-pink shadow-sm h-100 py-2">
                        
                                    <div class="card-body">
                        
                                        <div class="row no-gutters align-items-center">
                        
                                            <div class="col mr-2">
                        
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">Tramites y Servicios</div>
                        
                                            </div>
                        
                                            <div class="col-auto">
                        
                                                <i class="fas fa-certificate fa-2x text-pink"></i>
                        
                                            </div>
                        
                                        </div>
                        
                                    </div>
                        
                                </div>
                        
                            </a>
                        
                        <?php }

                        $permisos = $instancia_permiso->permisosUsuarioControl(1, $perfil_log);

                        if ($permisos) {

                            ?>

                            <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>recursos/permisos/index">

                                <div class="card border-left-warning shadow-sm h-100 py-2">

                                    <div class="card-body">

                                        <div class="row no-gutters align-items-center">

                                            <div class="col mr-2">

                                                <div class="h5 mb-0 font-weight-bold text-gray-800">Permisos/Licencias</div>

                                            </div>

                                            <div class="col-auto">

                                                <i class="fas fa-user-clock fa-2x text-warning"></i>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </a>

                        <?php }

                        ?>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <?php

    include_once VISTA_PATH . 'script_and_final.php';

?>