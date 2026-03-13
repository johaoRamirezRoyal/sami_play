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
require_once CONTROL_PATH . 'curso' . DS . 'ControlCurso.php';

$instancia = ControlCurso::singleton_curso();

if (isset($_POST['buscar'])) {

} else {
	$datos_curso = $instancia->mostrarLimiteCursosControl();
}

$permisos = $instancia_permiso->permisosUsuarioControl($perfil_log, 6);
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
						<a href="<?=BASE_URL?>inicio" class="text-decoration-none text-play">
							<i class="fa fa-arrow-left"></i>
						</a>
						&nbsp;
						Cursos
					</h4>
					<div class="btn-group">
						<button class="btn btn-play btn-sm" type="button" data-toggle="modal" data-target="#agregar_curso">
							<i class="fa fa-plus"></i>
							&nbsp;
							Agregar Curso
						</button>
					</div>
				</div>
				<div class="card-body">
					<form method="POST">
						<div class="row">
							<div class="col-lg-8 form-group"></div>
							<div class="form-group col-lg-4">
								<div class="input-group mb-3">
									<input type="text" class="form-control filtro" placeholder="Buscar" name="buscar" aria-describedby="basic-addon2" data-tooltip="tooltip" title="Presiona ENTER para buscar" data-placement="top" data-trigger="focus">
									<div class="input-group-append">
										<button class="btn btn-play btn-sm" type="submit">
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
									<th scope="col">Nombre</th>
									<th scope="col">Director de grupo</th>
									<th scope="col">Numero de estudiantes</th>
								</tr>
							</thead>
							<tbody class="buscar">
								<?php
								foreach ($datos_curso as $curso) {
									$id_curso             = $curso['id'];
									$nom_curso            = $curso['nombre'];
									$nom_profesor         = $curso['nom_profesor'];
									$cantidad_estudiantes = $curso['cantidad_estudiantes'];
									?>
									<tr class="text-center">
										<td><?=$nom_curso?></td>
										<td><?=$nom_profesor?></td>
										<td><?=$cantidad_estudiantes?></td>
										<td>
											<div class="btn-group">
												<a class="btn btn-info btn-sm" href="<?=BASE_URL?>curso/detalles?codigo=<?=base64_encode($id_curso)?>" data-tootlip="tootlip" title="Ver detalles" data-placement="bottom">
													<i class="fa fa-eye"></i>
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
include_once VISTA_PATH . 'modulos' . DS . 'curso' . DS . 'agregarCurso.php';

if (isset($_POST['nombre'])) {
	$instancia->agregarCursoControl();
}
?>