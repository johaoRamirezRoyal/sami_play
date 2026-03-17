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
require_once CONTROL_PATH . 'dimension' . DS . 'ControlDimension.php';
require_once CONTROL_PATH . 'periodo' . DS . 'ControlPeriodo.php';
require_once CONTROL_PATH . 'usuarios' . DS . 'ControlUsuarios.php';

$instancia_usuario   = ControlUsuarios::singleton_usuario();
$instancia_periodo   = ControlPeriodo::singleton_periodo();
$instancia_dimension = ControlDimension::singleton_dimension();
$instancia           = ControlCurso::singleton_curso();

$permisos = $instancia_permiso->permisosUsuarioControl($perfil_log, 6);
if (!$permisos) {
	include_once VISTA_PATH . 'modulos' . DS . '403.php';
	die();
}

if (isset($_GET['codigo'])) {

	$id_curso = base64_decode($_GET['codigo']);

	$datos_curso       = $instancia->mostrarInformacionCursoControl($id_curso);
	$datos_dimensiones = $instancia_dimension->mostrarTodasDimensionControl();
	$datos_periodo     = $instancia_periodo->mostrarPeriodosAnioActivoControl();
	$datos_profesor    = $instancia_usuario->mostrarTodosUsuariosControl();
	$datos_estudiantes = $instancia_usuario->mostrarEstudiantesCursoControl($id_curso);

	$periodo_configuracion = $instancia->mostrarPeriodoCursoControl($id_curso);

	$datos_general_indicadores = $instancia->mostrarDimensionesIndicadoresInformacionControl($datos_curso['periodo_actual'], $id_curso);

	$indicadores_por_dimension = [];

	foreach ($datos_general_indicadores as $item) {
		$id_dimension = $item['id_dimension'];

		$indicadores_por_dimension[$id_dimension]['nombre_dimension'] = $item['nombre_dimension'];
		$indicadores_por_dimension[$id_dimension]['indicadores'][] = $item;
	}

	//var_dump($datos_curso['periodo_actual']);

?>
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
				<div class="card shadow-sm mb-4">
					<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
						<h4 class="m-0 font-weight-bold text-play">
							<a href="<?= BASE_URL ?>curso/index" class="text-decoration-none text-play">
								<i class="fa fa-arrow-left"></i>
							</a>
							&nbsp;
							Curso - <?= $datos_curso['nombre'] ?>
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
										<a class="nav-link" id="home-tab" data-toggle="tab" href="#indicador" role="tab" aria-controls="home" aria-selected="true">Indicadores</a>
									</li>
									<li class="nav-item" role="presentation">
										<a class="nav-link" id="contact-tab" data-toggle="tab" href="#estudiantes" role="tab" aria-controls="contact" aria-selected="false">Estudiantes</a>
									</li>
								</ul>
							</div>
							<div class="col-lg-12 form-group">
								<div class="tab-content">
									<!--------------------------->
									<div class="tab-pane fade show active" id="informacion" role="tabpanel" aria-labelledby="profile-tab">
										<form method="POST">
											<input type="hidden" name="id_log" value="<?= $id_log ?>">
											<input type="hidden" name="id_curso" value="<?= $id_curso ?>">
											<div class="row mt-4 p-2">
												<div class="col-lg-4 form-group">
													<label class="font-weight-bold">Nombre <span class="text-danger">*</span></label>
													<input type="text" class="form-control" name="nom_curso" value="<?= $datos_curso['nombre'] ?>" required>
												</div>
												<div class="col-lg-4 form-group">
													<label class="font-weight-bold">Director de grupo <span class="text-danger">*</span></label>
													<select name="profesor" class="form-control" required>
														<option value="">Seleccione una opcion...</option>
														<?php
														foreach ($datos_profesor as $profesor) {
															$id_user         = $profesor['id_user'];
															$nombre_completo = $profesor['nombre'] . ' ' . $profesor['apellido'];

															$selected_profesor = ($id_user == $datos_curso['id_profesor']) ? 'selected' : '';

															if ($profesor['perfil'] == 4) {
														?>
																<option value="<?= $id_user ?>" <?= $selected_profesor ?>><?= $nombre_completo ?></option>
														<?php
															}
														}
														?>
													</select>
												</div>
												<div class="col-lg-4 form-group">
													<label class="font-weight-bold">Periodo Escolar <span class="text-danger">*</span></label>
													<select name="periodo_actual" class="form-control" required>
														<option value="" selected>Seleccione una opcion...</option>
														<?php
														foreach ($datos_periodo as $periodo) {
															$id_periodo  = $periodo['id'];
															$nom_periodo = $periodo['numero'] . ' - ' . $periodo['anio'];

															$selected_periodo = ($datos_curso['periodo_actual'] == $id_periodo) ? 'selected' : '';
														?>
															<option value="<?= $id_periodo ?>" <?= $selected_periodo ?>><?= $nom_periodo ?></option>
														<?php } ?>
													</select>
												</div>
												<div class="col-lg-4 form-group">
													<label class="font-weight-bold">Numero de estudiantes</label>
													<input type="text" class="form-control" disabled value="<?= $datos_curso['cantidad_estudiante'] ?>">
												</div>
												<div class="col-lg-12 form-group text-right mt-2">
													<button class="btn btn-play btn-sm" type="submit">
														<i class="fa fa-edit"></i>
														&nbsp;
														Editar informacion
													</button>
												</div>
											</div>
										</form>
									</div>
									<!--------------------------->
									<div class="tab-pane fade" id="indicador" role="tabpanel" aria-labelledby="home-tab">
										<form method="POST">
											<input type="hidden" name="id_log" value="<?= $id_log ?>">
											<input type="hidden" name="id_curso" value="<?= $id_curso ?>">
											<div class="row p-2 mt-4">
												<div class="col-lg-4 form-group">
													<label class="font-weight-bold">Nombre</label>
													<input type="text" class="form-control" value="<?= $datos_curso['nombre'] ?>" disabled>
												</div>
												<div class="col-lg-4 form-group">
													<label class="font-weight-bold">Codigo Curso</label>
													<input type="text" class="form-control" value="<?= $datos_curso['id'] ?>" disabled>
												</div>
												<div class="col-lg-4 form-group">
													<label class="font-weight-bold">Periodo Escolar <span class="text-danger">*</span></label>
													<select name="periodo" class="form-control" required>
														<option value="" selected>Seleccione una opcion...</option>
														<?php
														foreach ($datos_periodo as $periodo) {
															$id_periodo  = $periodo['id'];
															$nom_periodo = $periodo['numero'] . ' - ' . $periodo['anio'];

															$selected_periodo = ($periodo_configuracion['id_periodo'] == $id_periodo) ? 'selected' : '';
														?>
															<option value="<?= $id_periodo ?>" <?= $selected_periodo ?>><?= $nom_periodo ?></option>
														<?php } ?>
													</select>
												</div>
												<div class="col-lg-12 form-group mt-2 text-right">
													<button class="btn btn-secondary btn-sm unselect" type="button">
														<i class="far fa-check-circle"></i>
														&nbsp;
														Desmarcar todo
													</button>
												</div>
											</div>
											<div class="table-responsive mt-4">
												<table class="table table-hover table-sm" width="100%" cellspacing="0">

													<?php foreach ($indicadores_por_dimension as $dimension): ?>

														<thead>
															<tr class="text-center font-weight-bold">
																<th colspan="2"><?= $dimension['nombre_dimension'] ?></th>
															</tr>
														</thead>

														<tbody class="border">

															<?php foreach ($dimension['indicadores'] as $indicador):

																$id_indicador = $indicador['id_indicador'];
																$nom_indicador = $indicador['nombre_indicador'];
																$activo = $indicador['activo'];

																$checked = ($activo == 1) ? 'checked' : '';

															?>

																<tr>
																	<td><?= $nom_indicador ?></td>
																	<td>
																		<div class="custom-control custom-switch">
																			<input type="checkbox"
																				class="custom-control-input check"
																				value="<?= $id_indicador ?>,<?= $indicador['id_dimension'] ?>"
																				name="indicador[]"
																				id="indicador<?= $id_indicador ?>"
																				<?= $checked ?>>

																			<label class="custom-control-label" for="indicador<?= $id_indicador ?>"></label>
																		</div>
																	</td>
																</tr>

															<?php endforeach; ?>

														</tbody>

													<?php endforeach; ?>

												</table>
											</div>
											<div class="row">
												<div class="col-lg-12 form-group mt-4 text-right">
													<button class="btn btn-play btn-sm" type="submit">
														<i class="fa fa-save"></i>
														&nbsp;
														Guardar Configuracion
													</button>
												</div>
											</div>
										</form>
									</div>
									<!--------------------------->
									<div class="tab-pane fade" id="estudiantes" role="tabpanel" aria-labelledby="contact-tab">
										<div class="table-responsive mt-4">
											<table class="table table-hover border table-sm" width="100%" cellspacing="0">
												<thead>
													<tr class="text-center font-weight-bold">
														<th scope="col">Tipo documento</th>
														<th scope="col">Documento</th>
														<th scope="col">Nombre Completo</th>
													</tr>
												</thead>
												<tbody class="buscar">
													<?php
													foreach ($datos_estudiantes as $estudiante) {
														$id_estudiante  = $estudiante['id_user'];
														$nom_completo   = $estudiante['nombre'] . ' ' . $estudiante['apellido'];
														$tipo_documento = $estudiante['tipo_doc'];
														$documento      = $estudiante['documento'];
														$correo         = $estudiante['correo'];
														$telefono       = $estudiante['telefono'];
													?>
														<tr class="text-center">
															<td><?= $tipo_documento ?></td>
															<td><?= $documento ?></td>
															<td><?= $nom_completo ?></td>
														</tr>
													<?php } ?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
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

	if (isset($_POST['periodo'])) {
		$instancia->configuracionCursoControl();
	}

	if (isset($_POST['nom_curso'])) {
		$instancia->editarCursoControl();
	}
}
?>
<script src="<?= PUBLIC_PATH ?>js/boletin/funcionesBoletin.js"></script>