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
require_once CONTROL_PATH . 'observador' . DS . 'ControlObservador.php';

$instancia            = ControlUsuarios::singleton_usuario();
$instancia_curso      = ControlCurso::singleton_curso();
$instancia_observador = ControlObservador::singleton_observador();

$permisos = $instancia_permiso->permisosUsuarioControl($perfil_log, 14);
if (!$permisos) {
	include_once VISTA_PATH . 'modulos' . DS . '403.php';
	die();
}

if (isset($_GET['estudiante'])) {

	$id_estudiante = base64_decode($_GET['estudiante']);

	$datos_estudiante = $instancia->mostrarDatosEstudiantesControl($id_estudiante);
	$datos_observador = $instancia_observador->mostrarObservacionesControl($id_estudiante);
}
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12">
			<div class="card shadow-sm mb-4">
				<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
					<h4 class="m-0 font-weight-bold text-play">
						<a href="<?=BASE_URL?>observador/index" class="text-decoration-none text-play">
							<i class="fa fa-arrow-left"></i>
						</a>
						&nbsp;
						Historial de Observador - (<?=$datos_estudiante['nombre']?> <?=$datos_estudiante['apellido']?>)
					</h4>
				</div>
				<div class="card-body">
					<div class="row p-2 mt-2">
						<div class="col-lg-12 form-group">
							<div class="row">
								<div class="col-lg-4 form-group mt-4">
									<div class="circular--portrait">
										<img src="<?=PUBLIC_PATH?>upload/<?=$datos_estudiante['foto_perfil']?>" alt="">
									</div>
								</div>
								<div class="col-lg-8 form-group mt-2">
									<div class="row">
										<div class="col-lg-12 form-group">
											<label class="font-weight-bold">Numero de documento <span class="text-danger">*</span></label>
											<input type="text" class="form-control" value="<?=$datos_estudiante['documento']?>" disabled>
										</div>
										<div class="col-lg-6">
											<div class="form-group">
												<label class="font-weight-bold">Nombre <span class="text-danger">*</span></label>
												<input type="text" class="form-control letras" value="<?=$datos_estudiante['nombre']?>" disabled>
											</div>
										</div>
										<div class="col-lg-6">
											<div class="form-group">
												<label class="font-weight-bold">Apellido <span class="text-danger">*</span></label>
												<input type="text" class="form-control letras" value="<?=$datos_estudiante['apellido']?>" disabled>
											</div>
										</div>
										<div class="col-lg-6">
											<div class="form-group">
												<label class="font-weight-bold">Correo</label>
												<input type="email" class="form-control" value="<?=$datos_estudiante['correo']?>" disabled>
											</div>
										</div>
										<div class="col-lg-6">
											<div class="form-group">
												<label class="font-weight-bold">Telefono</label>
												<input type="text" class="form-control numeros" value="<?=$datos_estudiante['telefono']?>" disabled>
											</div>
										</div>
										<div class="col-lg-6">
											<div class="form-group">
												<label class="font-weight-bold">Curso</label>
												<input type="text" class="form-control" value="<?=$datos_estudiante['nom_curso']?>" disabled>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="table-responsive mt-2">
								<table class="table table-hover border table-sm" width="100%" cellspacing="0">
									<thead>
										<tr class="text-center font-weight-bold">
											<th scope="col" colspan="2">Historial de observador</th>
										</tr>
										<tr class="text-center font-weight-bold">
											<th scope="col">Observacion</th>
										</tr>
									</thead>
									<tbody class="buscar">
										<?php
										foreach ($datos_observador as $observador) {
											$id_observador = $observador['id'];
											$observacion   = $observador['observador'];

											$datos_comentarios = $instancia_observador->historialComentariosControl($id_observador);
											?>
											<tr class="text-left">
												<td style="width:90%;"><?=$observacion?></td>
												<td>
													<div class="btn-group">
														<button class="btn btn-play btn-sm" type="button" data-tooltip="tooltip" title="Historial de comentarios" data-placement="bottom" data-toggle="modal" data-target="#historial_<?=$id_observador?>">
															<i class="fas fa-history"></i>
														</button>
														<button class="btn btn-info btn-sm" type="button" data-tooltip="tooltip" title="Agregar comentario" data-placement="bottom" data-toggle="modal" data-target="#coment_<?=$id_observador?>">
															<i class="fa fa-comment"></i>
														</button>
													</div>
												</td>
											</tr>


											<div class="modal fade" id="historial_<?=$id_observador?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
												<div class="modal-dialog modal-lg" role="document">
													<div class="modal-content">
														<div class="modal-header">
															<h4 class="modal-title font-weight-bold text-play" id="exampleModalLabel">Historial de comentarios</h4>
															<button class="btn btn-sm" type="button" data-dismiss="modal">
																<i class="fa fa-times"></i>
															</button>
														</div>
														<div class="modal-body">
															<div class="row p-2">
																<div class="col-lg-12 form-group">
																	<div class="card">
																		<div class="card-header text-left">
																			<div class="row">
																				<div class="col-lg-6 form-group">
																					<h5 class="text-play font-weight-bold"><?=$observador['nom_usuario']?> - (Observacion General)</h5>
																				</div>
																				<div class="col-lg-6 form-group text-right">
																					<h6 class="text-play font-weight-bold"><?=$observador['fechareg']?></h6>
																				</div>
																			</div>
																		</div>
																		<div class="card-body">
																			<textarea class="form-control" rows="5" disabled><?=$observacion?></textarea>
																		</div>
																	</div>
																</div>
																<?php
																foreach ($datos_comentarios as $historial) {
																	$comentario = $historial['comentario'];
																	?>
																	<div class="col-lg-12 form-group">
																		<div class="card">
																			<div class="card-header text-left">
																				<div class="row">
																					<div class="col-lg-6 form-group">
																						<h5 class="text-play font-weight-bold"><?=$historial['nom_usuario']?></h5>
																					</div>
																					<div class="col-lg-6 form-group text-right">
																						<h6 class="text-play font-weight-bold"><?=$historial['fechareg']?></h6>
																					</div>
																				</div>
																			</div>
																			<div class="card-body">
																				<p><?=$comentario?></p>
																			</div>
																		</div>
																	</div>
																	<?php
																}
																?>
															</div>
														</div>
													</div>
												</div>
											</div>

											<div class="modal fade" id="coment_<?=$id_observador?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
												<div class="modal-dialog modal-lg" role="document">
													<div class="modal-content">
														<div class="modal-header">
															<h4 class="modal-title font-weight-bold text-play" id="exampleModalLabel">Comentario</h4>
														</div>
														<div class="modal-body">
															<form method="POST">
																<input type="hidden" name="id_log" value="<?=$id_log?>">
																<input type="hidden" name="id_observador" value="<?=$id_observador?>">
																<input type="hidden" name="id_estudiante" value="<?=$id_estudiante?>">
																<div class="row p-2">
																	<div class="col-lg-12 form-group">
																		<label class="font-weight-bold">Observacion</label>
																		<textarea disabled class="form-control" rows="5"><?=$observacion?></textarea>
																	</div>
																	<div class="col-lg-12 form-group">
																		<label class="font-weight-bold">Comentario <span class="text-danger">*</span></label>
																		<textarea name="comentario" rows="5" class="form-control"></textarea>
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


										<?php }?>
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
<?php
include_once VISTA_PATH . 'script_and_final.php';

if (isset($_POST['id_observador'])) {
	$instancia_observador->comentariosObservadorControl();
}
?>