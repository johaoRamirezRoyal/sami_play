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
require_once CONTROL_PATH . 'dimension' . DS . 'ControlDimension.php';
require_once CONTROL_PATH . 'curso' . DS . 'ControlCurso.php';
require_once CONTROL_PATH . 'calificacion' . DS . 'ControlCalificacion.php';

$instancia           = ControlCalificacion::singleton_calificacion();
$instancia_usuario   = ControlUsuarios::singleton_usuario();
$instancia_dimension = ControlDimension::singleton_dimension();
$instancia_curso     = ControlCurso::singleton_curso();

$permisos = $instancia_permiso->permisosUsuarioControl($perfil_log, 15);
if (!$permisos) {
	include_once VISTA_PATH . 'modulos' . DS . '403.php';
	die();
}

if (isset($_GET['estudiante'])) {

	$id_estudiante = base64_decode($_GET['estudiante']);

	$datos_estudiante    = $instancia_usuario->mostrarDatosEstudiantesControl($id_estudiante);
	$datos_dimensiones   = $instancia_dimension->mostrarDimensionesCursoControl($datos_estudiante['curso']);
	$datos_periodo_curso = $instancia_curso->mostrarPeriodosConfiguradosCursoControl($datos_estudiante['curso']);
	$datos_asistencia    = $instancia_usuario->cantidadAsistenciaControl($id_estudiante, $datos_periodo_curso['fecha_inicio'], $datos_periodo_curso['fecha_fin']);
	$datos_curso         = $instancia_curso->mostrarInformacionCursoControl($datos_estudiante['curso']);
	$datos_boletin       = $instancia->mostrarBoletinEstudianteControl($datos_estudiante['curso'], $datos_curso['periodo_actual'], $id_estudiante, $datos_curso['id_anio']);
	$datos_periodo       = $instancia_curso->mostrarPeriodosCursoControl($datos_estudiante['curso']);

	$ausencias = (empty($datos_boletin['ausencia'])) ? $datos_asistencia['cantidad'] : $datos_boletin['ausencia'];
	$genero    = ($datos_estudiante['genero'] == 1) ? 'Masculino' : 'Femenino';

	?>
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
				<div class="card shadow-sm mb-4">
					<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
						<h4 class="m-0 font-weight-bold text-play">
							<a href="<?=BASE_URL?>boletin/index" class="text-decoration-none text-play">
								<i class="fa fa-arrow-left"></i>
							</a>
							&nbsp;
							Boletin - Estudiante (<?=$datos_estudiante['nombre'] . ' ' . $datos_estudiante['apellido']?>)
						</h4>
					</div>
					<div class="card-body">
						<form method="POST">
							<input type="hidden" name="id_log" value="<?=$id_log?>">
							<input type="hidden" name="id_estudiante" value="<?=$datos_estudiante['id_user']?>">
							<input type="hidden" name="curso" value="<?=$datos_estudiante['curso']?>">
							<input type="hidden" name="url" value="calificar">
							<div class="row p-2">
								<div class="col-lg-4 form-group">
									<div class="circular--portrait">
										<img src="<?=PUBLIC_PATH?>upload/<?=$datos_estudiante['foto_perfil']?>" alt="">
									</div>
								</div>
								<div class="col-lg-8 form-group">
									<div class="row">
										<div class="col-lg-6 form-group">
											<label class="font-weight-bold">Tipo de Documento</label>
											<input type="text" class="form-control" disabled value="<?=$datos_estudiante['tipo_doc']?>">
										</div>
										<div class="col-lg-6 form-group">
											<label class="font-weight-bold">Documento</label>
											<input type="text" class="form-control" disabled value="<?=$datos_estudiante['documento']?>">
										</div>
										<div class="col-lg-6 form-group">
											<label class="font-weight-bold">Nombre Completo</label>
											<input type="text" class="form-control" disabled value="<?=$datos_estudiante['nombre'] . ' ' . $datos_estudiante['apellido']?>">
										</div>
										<div class="col-lg-6 form-group">
											<label class="font-weight-bold">Curso</label>
											<input type="text" class="form-control" disabled value="<?=$datos_estudiante['nom_curso']?>">
										</div>
										<div class="col-lg-6 form-group">
											<label class="font-weight-bold">Genero</label>
											<input type="text" class="form-control" disabled value="<?=$genero?>">
										</div>
										<div class="col-lg-6 form-group">
											<label class="font-weight-bold">Periodo a calificar <span class="text-danger">*</span></label>
											<select name="periodo" class="form-control" required>
												<option value="">Seleccione una opcion...</option>
												<?php
												foreach ($datos_periodo as $periodo) {
													$id_periodo  = $periodo['id'];
													$nom_periodo = $periodo['numero'] . ' - ' . $periodo['anio'];

													$selected_periodo = ($datos_curso['periodo_actual'] == $id_periodo) ? 'selected' : '';
													?>
													<option value="<?=$id_periodo?>" <?=$selected_periodo?>><?=$nom_periodo?></option>
												<?php }?>
											</select>
										</div>
										<div class="col-lg-6 form-group">
											<label class="font-weight-bold">Edad <span class="text-danger">*</span></label>
											<input type="text" name="edad" class="form-control" value="<?=$datos_boletin['edad']?>" required>
										</div>
										<div class="col-lg-6 form-group">
											<label class="font-weight-bold">Ausencias <span class="text-danger">*</span></label>
											<input type="text" name="ausencias" class="form-control numeros" value="<?=$ausencias?>" required>
										</div>
									</div>
								</div>
								<div class="col-lg-12 form-group text-left mt-4">
									<h6 class="text-danger font-weight-bold">Convenciones:</h6>
									<ul class="ml-4">
										<li>A: Logro Alcanzado</li>
										<li>P: Logro en Progreso</li>
									</ul>
								</div>
							</div>
							<div class="table-responsive">
								<table class="table table-hover border table-sm" width="100%" cellspacing="0">
									<thead>
										<tr class="text-center font-weight-bold">
											<th scope="col" colspan="3">TABLA DE CALIFICACIONES</th>
										</tr>
									</thead>
									<?php
									foreach ($datos_dimensiones as $dimension) {
										$id_dimension  = $dimension['id'];
										$nom_dimension = $dimension['nombre'];
										$resena        = $dimension['observacion'];

										$datos_indicadores = $instancia_curso->mostrarIndicadoresCursoPeriodoControl($datos_estudiante['curso'], $datos_curso['periodo_actual'], $id_dimension);
										?>
										<thead>
											<tr class="text-center font-weight-bold">
												<th scope="col" colspan="3"><?=$nom_dimension?>: <span class="font-weight-normal"><?=$resena?></span></th>
											</tr>
											<tr class="text-center font-weight-bold">
												<th scope="col">Indicadores de desempe&ntilde;o</th>
												<th scope="col">A</th>
												<th scope="col">P</th>
											</tr>
										</thead>
										<tbody class="buscar">
											<?php
											foreach ($datos_indicadores as $indicador) {
												$id_indicador  = $indicador['id_indicador'];
												$nom_indicador = $indicador['nombre'];

												$notas_guardadas = $instancia->mostrarNotaGuardadaControl($datos_estudiante['id_user'], $datos_estudiante['curso'], $datos_curso['periodo_actual'], $id_indicador);

												$check_1 = (!empty($notas_guardadas['indicador']) && $notas_guardadas['nota'] == 1) ? 'checked' : '';
												$check_2 = (!empty($notas_guardadas['indicador']) && $notas_guardadas['nota'] == 2) ? 'checked' : '';

												if ($nom_indicador != '') {
													?>
													<input type="hidden" name="id_indicador[]" value="<?=$id_indicador?>,<?=$id_dimension?>">
													<tr class="text-left">
														<td><?=$nom_indicador?></td>
														<td>
															<div class="form-check">
																<input class="form-check-input" type="radio" name="nota_<?=$id_indicador?>" value="1" <?=$check_1?>>
															</div>
														</td>
														<td>
															<div class="form-check">
																<input class="form-check-input" type="radio" name="nota_<?=$id_indicador?>" value="2" <?=$check_2?>>
															</div>
														</td>
													</tr>
												<?php }}?>
												<tr>
													<td colspan="3">
														<div class="col-lg-12 form-group">
															<label class="font-weight-bold">Comentarios Profesora</label>
															<textarea class="form-control" rows="5" name="comentario_<?=$id_dimension?>"><?=$notas_guardadas['comentario_ob']?></textarea>
														</div>
													</td>
												</tr>
											</tbody>
										<?php }?>
									</table>
								</div>
								<div class="row">
									<div class="col-lg-12 form-group">
										<label class="font-weight-bold">Observaciones Generales</label>
										<textarea name="observacion_general" class="form-control" rows="5"><?=$datos_boletin['observacion']?></textarea>
									</div>
									<div class="col-lg-12 form-group mt-4 text-right">
										<button class="btn btn-play btn-sm" type="submit">
											<i class="fa fa-save"></i>
											&nbsp;
											Guardar Avance
										</button>
									</div>
								</div>
							</form>
							<?php
							$permisos = $instancia_permiso->permisosUsuarioControl($perfil_log, 15);
							if ($permisos) {
								if (!empty($datos_boletin['id'])) {
									?>
									<form method="POST">
										<input type="hidden" name="id_boletin" value="<?=$datos_boletin['id']?>">
										<div class="row" style="margin-top:-4%;">
											<div class="col-lg-6 form-group text-left">
												<button class="btn btn-primary btn-sm" type="submit">
													<i class="fa fa-file-pdf"></i>
													&nbsp;
													Generar Boletin
												</button>
											</div>
										</div>
									</form>
								<?php }}?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
			include_once VISTA_PATH . 'script_and_final.php';

			if (isset($_POST['id_log'])) {
				$instancia->calificarEstudianteControl();
			}

			if (isset($_POST['id_boletin'])) {
				$instancia->generarBoletinControl();
			}
		}
