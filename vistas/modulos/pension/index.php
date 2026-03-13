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
require_once CONTROL_PATH . 'pension' . DS . 'ControlPension.php';
require_once CONTROL_PATH . 'curso' . DS . 'ControlCurso.php';
require_once CONTROL_PATH . 'usuarios' . DS . 'ControlUsuarios.php';

$instancia         = ControlPension::singleton_pension();
$instancia_curso   = ControlCurso::singleton_curso();
$instancia_usuario = ControlUsuarios::singleton_usuario();

$datos_meses       = $instancia->mostrarMesesPensionControl();
$datos_curso       = $instancia_curso->mostrarTodosCursosControl();
$datos_estudiantes = $instancia_usuario->mostrarTodosEstudiantesControl();

if (isset($_POST['curso'])) {

} else {
	$datos_pension = $instancia->mostrarLimitePensionControl();
}

$permisos = $instancia_permiso->permisosUsuarioControl($perfil_log, 9);
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
						Pension
					</h4>
					<div class="btn-group">
						<button type="button" class="btn btn-play btn-sm" data-toggle="modal" data-target="#agregar_pago">
							<i class="fa fa-plus"></i>
							&nbsp;
							Agregar Pago
						</button>
					</div>
				</div>
				<div class="card-body">
					<form method="POST">
						<div class="row">
							<div class="col-lg-4 form-group">
								<select class="form-control" name="mes">
									<option value="" selected>Seleccione un mes...</option>
									<?php
									foreach ($datos_meses as $mes) {
										$id_mes  = $mes['id'];
										$nom_mes = $mes['nombre'];
										?>
										<option value="<?=$id_mes?>"><?=$nom_mes?></option>
									<?php }?>
								</select>
							</div>
							<div class="col-lg-4 form-group">
								<select class="form-control" name="curso">
									<option value="" selected>Seleccione un curso...</option>
									<?php
									foreach ($datos_curso as $curso) {
										$id_curso  = $curso['id'];
										$nom_curso = $curso['nombre'];
										?>
										<option value="<?=$id_curso?>"><?=$nom_curso?></option>
									<?php }?>
								</select>
							</div>
							<div class="form-group col-lg-4">
								<div class="input-group mb-3">
									<input type="text" class="form-control filtro buscar" placeholder="Buscar" name="buscar" aria-describedby="basic-addon2" data-tooltip="tooltip" title="Presiona ENTER para buscar" data-placement="top" data-trigger="focus">
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
									<th scope="col">Documento</th>
									<th scope="col">Nombre Completo</th>
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
										<td><?=$documento?></td>
										<td><?=$nom_completo?></td>
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
			</div>
		</div>
	</div>
</div>
<?php
include_once VISTA_PATH . 'script_and_final.php';
include_once VISTA_PATH . 'modulos' . DS . 'pension' . DS . 'agregarPago.php';

if (isset($_POST['id_log'])) {
	$instancia->agregarPensionControl();
}
?>