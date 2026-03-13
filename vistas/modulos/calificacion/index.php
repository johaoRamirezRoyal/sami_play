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
require_once CONTROL_PATH . 'calificacion' . DS . 'ControlCalificacion.php';
require_once CONTROL_PATH . 'observador' . DS . 'ControlObservador.php';

$instancia            = ControlCalificacion::singleton_calificacion();
$instancia_curso      = ControlCurso::singleton_curso();
$instancia_usuario    = ControlUsuarios::singleton_usuario();
$instancia_observador = ControlObservador::singleton_observador();

$datos_curso = $instancia_curso->mostrarDatosCursoProfesorControl($id_log);

if (isset($_POST['buscar'])) {
	$datos_usuarios = $instancia_usuario->mostrarEstudiantesBuscarControl($_POST['buscar']);
} else if (!empty($datos_curso['id'])) {
	$datos_usuarios = $instancia_usuario->mostrarEstudiantesCursoControl($datos_curso['id']);
} else {
	$datos_usuarios = array();
}

$permisos = $instancia_permiso->permisosUsuarioControl($perfil_log, 8);
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
						Calificaciones - <?=$datos_curso['nombre']?>
					</h4>
				</div>
				<div class="card-body">
					<form method="POST">
						<div class="row">
							<div class="col-lg-8"></div>
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

									$boletin_estudiante = $instancia->mostrarBoletinEstudianteControl($datos_curso['id'], $datos_curso['periodo_actual'], $id_user, $datos_curso['id_anio']);

									$ver_boletin = (!empty($boletin_estudiante['id']) && $boletin_estudiante['generado'] == 1) ? '' : 'd-none';
									$ver_calificar = ($boletin_estudiante['generado'] == 0) ? '' : 'd-none';

									if ($usuario['curso'] == $datos_curso['id']) {

										?>
										<tr class="text-center user_<?=$id_user?>">
											<td><?=$tipo_doc?></td>
											<td><?=$documento?></td>
											<td><?=$nom_completo?></td>
											<td><?=$genero?></td>
											<td><?=$nom_curso?></td>
											<td>
												<div class="btn-group" role="group">
													<a href="<?=BASE_URL?>imprimir/calificacion/boletin?boletin=<?=base64_encode($boletin_estudiante['id'])?>" class="btn btn-primary btn-sm <?=$ver_boletin?>" data-tooltip="tooltip" title="Ver Boletin" data-placement="bottom" target="_blank">
														<i class="fa fa-file-pdf"></i>
													</a>
													<a href="<?=BASE_URL?>calificacion/notas?estudiante=<?=base64_encode($id_user)?>" class="btn btn-play btn-sm <?=$ver_calificar?>" data-tooltip="tooltip" title="Calificar" data-placement="bottom">
														<i class="fas fa-clipboard-list"></i>
													</a>
													<button type="button" class="btn btn-info btn-sm" data-tooltip="tooltip" title="Observador" data-placement="bottom" data-toggle="modal" data-target="#observador_<?=$id_user?>">
														<i class="fas fa-pencil-ruler"></i>
													</button>
												</div>
											</td>
										</tr>


										<div class="modal fade" id="observador_<?=$id_user?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
											<div class="modal-dialog modal-lg" role="document">
												<div class="modal-content">
													<div class="modal-header">
														<h5 class="modal-title font-weight-bold text-play" id="exampleModalLabel">Observador - (<?=$nom_completo?>)</h5>
													</div>
													<div class="modal-body">
														<form method="POST">
															<input type="hidden" name="id_estudiante" value="<?=$id_user?>">
															<input type="hidden" name="id_log" value="<?=$id_log?>">
															<div class="row p-2">
																<div class="col-lg-6 form-group">
																	<label class="font-weight-bold">Estudiante</label>
																	<input type="text" class="form-control" disabled value="<?=$nom_completo?>">
																</div>
																<div class="col-lg-6 form-group">
																	<label class="font-weight-bold">Curso</label>
																	<input type="text" class="form-control" disabled value="<?=$nom_curso?>">
																</div>
																<div class="col-lg-12 form-group">
																	<label class="font-weight-bold">Observacion</label>
																	<textarea class="form-control" name="observacion" rows="5"></textarea>
																</div>
																<div class="col-lg-12 form-group mt-2 text-right">
																	<button class="btn btn-danger btn-sm" type="button" data-dismiss="modal">
																		<i class="fa fa-times"></i>
																		&nbsp;
																		Cancelar
																	</button>
																	<button class="btn btn-play btn-sm" type="submit">
																		<i class="fa fa-save"></i>
																		&nbsp;
																		Guardar
																	</button>
																</div>
															</div>
														</form>
													</div>
												</div>
											</div>
										</div>
										<?php
									}
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

if (isset($_POST['id_log'])) {
	$instancia_observador->agregarObservacionControl();
}
?>