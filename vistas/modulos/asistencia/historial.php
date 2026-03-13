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
require_once CONTROL_PATH . 'usuarios' . DS . 'ControlUsuarios.php';
require_once CONTROL_PATH . 'asistencia' . DS . 'ControlAsistencia.php';

$instancia         = ControlAsistencia::singleton_asistencia();
$instancia_curso   = ControlCurso::singleton_curso();
$instancia_usuario = ControlUsuarios::singleton_usuario();

$datos_curso = $instancia_curso->mostrarTodosCursosControl();

if (isset($_POST['buscar'])) {

	$curso_id = $_POST['curso'];
	$fecha    = $_POST['fecha'];
	$buscar   = $_POST['buscar'];

	$datos          = array('curso' => $curso_id, 'fecha' => $fecha, 'buscar' => $buscar);
	$datos_usuarios = $instancia->buscarEstudiantesAsistenciaControl($datos);
} else {

	$curso_id = '';
	$fecha    = '';
	$buscar   = '';

	$datos_usuarios = $instancia->ultimasAsistenciasTomadasControl();
}

$permisos = $instancia_permiso->permisosUsuarioControl($perfil_log, 18);
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
						<a href="<?=BASE_URL?>asistencia/curso" class="text-decoration-none text-play">
							<i class="fa fa-arrow-left"></i>
						</a>
						&nbsp;
						Historial Asistencia
					</h4>
				</div>
				<div class="card-body">
					<form method="POST">
						<div class="row">
							<div class="col-lg-4 form-group">
								<select name="curso" class="form-control">
									<option value="" selected>Seleccione un curso...</option>
									<?php
									foreach ($datos_curso as $curso) {
										$id_curso  = $curso['id'];
										$nom_curso = $curso['nombre'];

										$select_curso = ($curso_id == $id_curso) ? 'selected' : '';
										?>
										<option value="<?=$id_curso?>" <?=$select_curso?>><?=$nom_curso?></option>
									<?php }?>
								</select>
							</div>
							<div class="col-lg-4 form-group">
								<input type="date" class="form-control" name="fecha" data-tooltip="tooltip" title="Fecha" data-placement="top" value="<?=$fecha?>">
							</div>
							<div class="form-group col-lg-4">
								<div class="input-group mb-3">
									<input type="text" class="form-control filtro buscar" placeholder="Buscar" name="buscar" aria-describedby="basic-addon2" data-tooltip="tooltip" title="Presiona ENTER para buscar" data-placement="top" data-trigger="focus" value="<?=$buscar?>">
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
					<form method="POST">
						<input type="hidden" name="id_log" value="<?=$id_log?>">
						<input type="hidden" name="url" value="2">
						<div class="table-responsive mt-2">
							<table class="table table-hover border table-sm" width="100%" cellspacing="0">
								<thead>
									<tr class="text-center font-weight-bold">
										<th scope="col">Documento</th>
										<th scope="col">Nombre Completo</th>
										<th scope="col">Curso</th>
										<th scope="col">Genero</th>
										<th scope="col">Fecha Asistencia</th>
										<th scope="col">Asistio</th>
										<th scope="col">No Asistio</th>
									</tr>
								</thead>
								<tbody class="buscar">
									<?php
									foreach ($datos_usuarios as $usuario) {
										$id_user       = $usuario['id_user'];
										$documento     = $usuario['documento'];
										$nom_completo  = $usuario['nombre'] . ' ' . $usuario['apellido'];
										$tipo_doc      = $usuario['tipo_doc'];
										$genero        = $usuario['genero'];
										$user_nom      = $usuario['user'];
										$perfil        = $usuario['nom_perfil'];
										$activo        = $usuario['activo'];
										$nom_curso     = $usuario['nom_curso'];
										$asistencia    = $usuario['asistencia'];
										$id_asistencia = $usuario['id_asistencia'];

										$fecha_asistencia = date('Y-m-d', strtotime($usuario['asistencia_fecha']));
										$fecha_asistencia = (empty($usuario['asistencia_fecha'])) ? '' : $fecha_asistencia;

										$fecha_registrar = $usuario['asistencia_fecha'];

										$ver_inactivo = ($activo == 1) ? '' : 'd-none';
										$ver_activo   = ($activo == 1) ? 'd-none' : '';

										$genero = ($genero == 1) ? 'Masculino' : 'Femenino';

										$ver_si = ($asistencia == 1 || $asistencia == '') ? 'checked' : '';
										$ver_no = ($asistencia == 2) ? 'checked' : '';

										?>
										<input type="hidden" name="id_user[]" value="<?=$id_user?>">
										<input type="hidden" name="id_asistencia[]" value="<?=$id_asistencia?>">
										<input type="hidden" name="fecha_asistencia[]" value="<?=$fecha_registrar?>">
										<tr class="text-center user_<?=$id_user?>">
											<td><?=$documento?></td>
											<td><?=$nom_completo?></td>
											<td><?=$usuario['nom_curso']?></td>
											<td><?=$genero?></td>
											<td><?=$fecha_asistencia?></td>
											<td>
												<input type="radio" class="radio" <?=$ver_si?> name="asistencia_<?=$id_user?>" value="1">
											</td>
											<td>
												<input type="radio" class="radio" <?=$ver_no?> name="asistencia_<?=$id_user?>" value="2">
											</td>
										</tr>
										<?php
									}
									?>
								</tbody>
							</table>
						</div>
						<?php
						if (isset($_POST['buscar'])) {
							?>
							<div class="col-lg-12 form-group text-right mt-2">
								<button class="btn btn-secondary btn-sm" type="submit">
									<i class="fa fa-save"></i>
									&nbsp;
									Guardar
								</button>
							</div>
						<?php }?>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
include_once VISTA_PATH . 'script_and_final.php';

if (isset($_POST['id_log'])) {
	$instancia->tomarAsistenciaControl();
}