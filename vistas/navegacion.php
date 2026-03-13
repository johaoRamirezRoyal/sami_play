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

  header('Location:login?er=' . $error);

  exit();

}

include_once VISTA_PATH . 'cabeza.php';

require_once CONTROL_PATH . 'permisos' . DS . 'ControlPermisos.php';

require_once CONTROL_PATH . 'perfil' . DS . 'ControlPerfil.php';



$instancia_perfil  = ControlPerfil::singleton_perfil();

$instancia_permiso = ControlPermisos::singleton_permisos();



$id_log     = $_SESSION['id'];

$perfil_log = $_SESSION['rol'];


$datos_usuario = $instancia_perfil->mostrarDatosPerfilControl($id_log);

$foto_perfil   = (empty($datos_usuario['foto_perfil'])) ? 'img/user.svg' : 'upload/' . $datos_usuario['foto_perfil'];

$nombre_sesion = $_SESSION['nombre_admin'] . ' ' . $_SESSION['apellido'];
//$nombre_sesion = $datos_usuario['nombre'] . ' ' //. $datos_usuario['apellido'];

$id_super_empresa = 1;

?>

<!-- Sidebar -->

<ul class="navbar-nav bg-white sidebar sidebar-dark accordion toggled" id="accordionSidebar">



  <!-- Sidebar - Brand -->

  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?=BASE_URL?>inicio">

    <div class="sidebar-brand-icon">

      <img src="<?=PUBLIC_PATH?>img/logo.png" alt="" class="img-fluid">

    </div>

    <div class="sidebar-brand-text mx-3 text-play mt-3">

    </div>

  </a>



  <!-- Divider -->

  <hr class="sidebar-divider my-0">



  <!-- Nav Item - Dashboard -->

  <li class="nav-item active">

    <a class="nav-link" href="<?=BASE_URL?>inicio">

      <i class="fas fa-home text-play"></i>

      <span class="text-muted">Inicio</span></a>

    </li>



    <!-- Divider -->

    <hr class="sidebar-divider bg-gray">

    <?php

    $permisos = $instancia_permiso->permisosUsuarioControl($perfil_log, 2);

    if ($permisos) {

      ?>

      <li class="nav-item">

        <a class="nav-link collapsed" href="<?=BASE_URL?>usuarios/index" aria-expanded="true" aria-controls="collapseUtilities">

          <i class="fas fa-users text-play"></i>

          <span class="text-muted">Usuarios</span>

        </a>

      </li>

    <?php }

    $permisos = $instancia_permiso->permisosUsuarioControl($perfil_log, 35);

    if ($permisos) {

      ?>

      <li class="nav-item">

        <a class="nav-link collapsed" href="<?=BASE_URL?>inventario/listado" aria-expanded="true" aria-controls="collapseUtilities">

          <i class="fas fa-shapes text-play"></i>

          <span class="text-muted">Listado inventario</span>

        </a>

      </li>

    <?php }

    $permisos = $instancia_permiso->permisosUsuarioControl($perfil_log, 27);

    if ($permisos) {

      ?>

      <li class="nav-item">

        <a class="nav-link collapsed" href="<?=BASE_URL?>inventario/index" aria-expanded="true" aria-controls="collapseUtilities">

          <i class="fas fa-barcode text-play"></i>

          <span class="text-muted">Inventario</span>

        </a>

      </li>

    <?php }

    $permisos = $instancia_permiso->permisosUsuarioControl($perfil_log, 30);

    if ($permisos) {

      ?>

      <li class="nav-item">

        <a class="nav-link collapsed" href="<?=BASE_URL?>reportes/index" aria-expanded="true" aria-controls="collapseUtilities">

          <i class="fas fa-tools text-play"></i>

          <span class="text-muted">Reportes</span>

        </a>

      </li>

    <?php }

    $permisos = $instancia_permiso->permisosUsuarioControl($perfil_log, 5);

    if ($permisos) {

      ?>

      <li class="nav-item">

        <a class="nav-link collapsed" href="<?=BASE_URL?>estudiantes/index" aria-expanded="true" aria-controls="collapseUtilities">

          <i class="fas fa-user-graduate text-play"></i>

          <span class="text-muted">Estudiantes</span>

        </a>

      </li>

    <?php }

    $permisos = $instancia_permiso->permisosUsuarioControl($perfil_log, 3);

    if ($permisos) {

      ?>

      <li class="nav-item">

        <a class="nav-link collapsed" href="<?=BASE_URL?>periodos/index" aria-expanded="true" aria-controls="collapseUtilities">

          <i class="fas fa-hourglass-half text-play"></i>

          <span class="text-muted">Periodos</span>

        </a>

      </li>

    <?php }

    $permisos = $instancia_permiso->permisosUsuarioControl($perfil_log, 4);

    if ($permisos) {

      ?>

      <li class="nav-item">

        <a class="nav-link collapsed" href="<?=BASE_URL?>dimension/index" aria-expanded="true" aria-controls="collapseUtilities">

          <i class="fas fa-cubes text-play"></i>

          <span class="text-muted">Dimensiones</span>

        </a>

      </li>

    <?php }

    $permisos = $instancia_permiso->permisosUsuarioControl($perfil_log, 5);

    if ($permisos) {

      ?>

      <li class="nav-item">

        <a class="nav-link collapsed" href="<?=BASE_URL?>indicador/index" aria-expanded="true" aria-controls="collapseUtilities">

          <i class="fas fa-chart-bar text-play"></i>

          <span class="text-muted">Indicadores</span>

        </a>

      </li>

    <?php }

    $permisos = $instancia_permiso->permisosUsuarioControl($perfil_log, 6);

    if ($permisos) {

      ?>

      <li class="nav-item">

        <a class="nav-link collapsed" href="<?=BASE_URL?>curso/index" aria-expanded="true" aria-controls="collapseUtilities">

          <i class="fas fa-graduation-cap text-play"></i>

          <span class="text-muted">Cursos</span>

        </a>

      </li>

    <?php }

    $permisos = $instancia_permiso->permisosUsuarioControl($perfil_log, 8);

    if ($permisos) {

      ?>

      <li class="nav-item">

        <a class="nav-link collapsed" href="<?=BASE_URL?>calificacion/index" aria-expanded="true" aria-controls="collapseUtilities">

          <i class="fas fa-tasks text-play"></i>

          <span class="text-muted">Calificaciones</span>

        </a>

      </li>

    <?php }

    $permisos = $instancia_permiso->permisosUsuarioControl($perfil_log, 10);

    if ($permisos) {

      ?>

      <li class="nav-item">

        <a class="nav-link collapsed" href="<?=BASE_URL?>boletin/historial?id=<?=base64_encode($id_log)?>" aria-expanded="true" aria-controls="collapseUtilities">

          <i class="fas fa-th-list text-play"></i>

          <span class="text-muted">Boletin</span>

        </a>

      </li>

    <?php }

    $permisos = $instancia_permiso->permisosUsuarioControl($perfil_log, 13);

    if ($permisos) {

      ?>

      <li class="nav-item">

        <a class="nav-link collapsed" href="<?=BASE_URL?>recursos/index" aria-expanded="true" aria-controls="collapseUtilities">

          <i class="fas fa-id-card-alt text-play"></i>

          <span class="text-muted">Gestion Humana</span>

        </a>

      </li>

    <?php }

    ?>

    <!-- Divider -->

    <hr class="sidebar-divider d-none d-md-block bg-gray">



    <!-- Sidebar Toggler (Sidebar) -->

    <div class="text-center d-none d-md-inline">

      <button class="rounded-circle border-0" id="sidebarToggle"></button>

    </div>



  </ul>

  <!-- End of Sidebar -->



  <!-- Content Wrapper -->

  <div id="content-wrapper" class="d-flex flex-column">



    <!-- Main Content -->

    <div id="content">



      <!-- Topbar -->

      <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow-none">





        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">

          <i class="fa fa-bars text-play"></i>

        </button>



        <!-- Topbar Navbar -->

        <ul class="navbar-nav ml-auto">



          <div class="topbar-divider d-none d-sm-block"></div>



          <!-- Nav Item - User Information -->

          <!-- Nav Item - User Information -->

          <li class="nav-item dropdown no-arrow">

            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

              <span class="mr-2 d-lg-inline text-gray-600"><?=$_SESSION['nombre_admin'] . ' ' . $_SESSION['apellido']?></span>

              <div class="circular--perfil">

                <img src="<?=PUBLIC_PATH . $foto_perfil?>">

              </div>

            </a>

            <!-- Dropdown - User Information -->

            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">

              <a class="dropdown-item" href="<?=BASE_URL?>perfil/index">

                <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>

                Perfil

              </a>

              <div class="dropdown-divider"></div>

              <a class="dropdown-item" href="<?=BASE_URL?>salir">

                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>

                Cerrar sesion

              </a>

            </div>

          </li>



        </ul>



      </nav>

      <!-- End of Topbar -->



      <!-- Begin Page Content -->

      <div class="container-fluid">



        <?php

        include_once VISTA_PATH . 'script_and_final.php';

      ?>