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
require_once CONTROL_PATH . 'perfil' . DS . 'ControlPerfil.php';
require_once CONTROL_PATH . 'usuarios' . DS . 'ControlUsuarios.php';
require_once CONTROL_PATH . 'curso' . DS . 'ControlCurso.php';

$instancia        = ControlUsuarios::singleton_usuario();
$instancia_perfil = ControlPerfil::singleton_perfil();
$instancia_curso  = ControlCurso::singleton_curso();

$datos_curso  = $instancia_curso->mostrarTodosCursosControl();
$datos_perfil = $instancia_perfil->mostrarPerfilesControl();

$datos_curso_docente = $instancia_curso->mostrarDatosCursoProfesorControl($id_log);

if (isset($_POST['buscar'])) {
	$datos_usuarios = $instancia->mostrarEstudiantesBuscarControl($_POST['buscar']);
} else if (!empty($datos_curso_docente['id'])) {
	$datos_usuarios = $instancia->mostrarEstudiantesCursoControl($datos_curso_docente['id']);
} else {
	$datos_usuarios = $instancia->mostrarEstudiantesControl();
}

$permisos = $instancia_permiso->permisosUsuarioControl($perfil_log, 7);
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
						Estudiantes
					</h4>			
					<?php 
					if($perfil_log == 1 || $perfil_log == 2){
					 ?>	
					<div class="btn-group">
						<button class="btn btn-play btn-sm" type="button" data-toggle="modal" data-target="#agregar_usuario">
							<i class="fa fa-plus"></i>
							&nbsp;
							Agregar Estudiante
						</button>
					</div>
				<?php } ?>
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

									?>
									<tr class="text-center user_<?=$id_user?>">
										<td><?=$tipo_doc?></td>
										<td><?=$documento?></td>
										<td><?=$nom_completo?></td>
										<td><?=$genero?></td>
										<td><?=$nom_curso?></td>
										<td>
											<div class="btn-group" role="group">
												<button class="btn btn-play btn-sm" data-tooltip="tooltip" title="Editar" data-placement="bottom" data-trigger="hover" data-toggle="modal" data-target="#editar<?=$id_user?>">
													<i class="fa fa-user-edit"></i>
												</button>
												<a href="<?=BASE_URL?>estudiantes/hojaVida?id=<?=base64_encode($id_user)?>" class="btn btn-info btn-sm" data-tooltip="tooltip" title="Hoja de Vida" data-placement="bottom">
													<i class="fa fa-eye"></i>
												</a>
												<button class="btn btn-danger btn-sm inactivar btni_<?=$id_user?> <?=$ver_inactivo?>" type="button" id="<?=$id_user?>" data-tooltip="tooltip" title="Inactivar Usuario" data-placement="bottom">
													<i class="fa fa-times"></i>
												</button>
												<button class="btn btn-success btn-sm activar btna_<?=$id_user?> <?=$ver_activo?>" type="button" id="<?=$id_user?>" data-tooltip="tooltip" title="Activar Usuario" data-placement="bottom">
													<i class="fa fa-check"></i>
												</button>
											</div>
										</td>
									</tr>


									<!-- Modal -->
									<div class="modal fade" id="editar<?=$id_user?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
										<div class="modal-dialog modal-lg" role="document">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title font-weight-bold text-play">Editar Usuario</h5>
												</div>
												<div class="modal-body">
													<form method="POST" enctype="multipart/form-data">
														<input type="hidden" name="id_user" value="<?=$id_user?>">
														<input type="hidden" name="id_log" value="<?=$id_log?>">
														<input type="hidden" name="foto_perfil_ant" value="<?=$usuario['foto_perfil']?>">
														<div class="row p-2">
															<div class="col-lg-12 form-group" style="margin-bottom: -2%;">
																<div class="row">
																	<div class="col-lg-6 form-group">
																		<div class="circular--portrait">										<img src="<?=PUBLIC_PATH?>upload/<?=$usuario['foto_perfil']?>" alt="">
																		</div>
																	</div>
																	<div class="col-lg-6 form-group">
																		<div class="row">
																			<div class="col-lg-12 form-group">
																				<label class="font-weight-bold">Tipo de Documento <span class="text-danger">*</span></label>
																				<select name="tipo_doc" class="form-control" required>
																					<?php
																					$select_tipo1 = ($tipo_doc == 'T.I') ? 'selected' : '';
																					$select_tipo2 = ($tipo_doc == 'C.C') ? 'selected' : '';
																					$select_tipo3 = ($tipo_doc == 'R.C') ? 'selected' : '';
																					?>
																					<option value="T.I" <?=$select_tipo1?>>T.I</option>
																					<option value="C.C" <?=$select_tipo2?>>C.C</option>
																					<option value="R.C" <?=$select_tipo3?>>R.C</option>
																				</select>
																			</div>
																			<div class="col-lg-12 form-group">
																				<label class="font-weight-bold">Documento <span class="text-danger">*</span></label>
																				<input type="text" class="form-control numeros" disabled value="<?=$documento?>">
																			</div>
																			<div class="form-group col-lg-12">
																				<label class="font-weight-bold">Nombre <span class="text-danger">*</span></label>
																				<input type="text" class="form-control " required value="<?=$usuario['nombre']?>" name="nombre">
																			</div>
																			<div class="form-group col-lg-12">
																				<label class="font-weight-bold">Apellido</label>
																				<input type="text" class="form-control " value="<?=$usuario['apellido']?>" name="apellido">
																			</div>
																		</div>
																	</div>
																</div>
															</div>
															<div class="col-lg-6 form-group">
																<label class="font-weight-bold">Curso <span class="text-danger">*</span></label>
																<select class="form-control" name="curso" required>
																	<option value="" selected>Seleccione una opcion...</option>
																	<?php
																	foreach ($datos_curso as $curso) {
																		$id_curso  = $curso['id'];
																		$nom_curso = $curso['nombre'];

																		$selected_curso = ($usuario['curso'] == $id_curso) ? 'selected' : '';
																		?>
																		<option value="<?=$id_curso?>" <?=$selected_curso?>><?=$nom_curso?></option>
																		<?php
																	}
																	?>
																</select>
															</div>
															<div class="col-lg-6 form-group">
																<label class="font-weight-bold">Genero <span class="text-danger">*</span></label>
																<select name="genero" class="form-control" required>
																	<?php
																	$select_genero1 = ($genero == 1) ? 'selected' : '';
																	$select_genero2 = ($genero == 2) ? 'selected' : '';
																	?>
																	<option value="1" <?=$select_genero1?>>Masculino</option>
																	<option value="2" <?=$select_genero2?>>Femenino</option>
																</select>
															</div>
															<div class="col-lg-12 form-group">
																<label class="font-weight-bold">Foto perfil</label>
																<div class="custom-file pmd-custom-file-filled">
																	<input type="file" class="custom-file-input file_input" id="<?=$id_user?>" name="foto" accept=".png, .jpg, .jpeg">
																	<label class="custom-file-label file_label_<?=$id_user?>" for="customfilledFile"></label>
																</div>
															</div>
															<div class="col-lg-6 form-group text-left mt-4">
																<button class="btn btn-secondary btn-sm restablecer" id="<?=$id_user?>" type="button" data-toggle="popover" title="Restablecer Contraseña" data-placement="left" data-trigger="hover" data-content="La nuea contraseña sera play123@">
																	<i class="fas fa-sync-alt"></i>
																	&nbsp;
																	Restablecer Contrase&ntilde;a
																</button>
															</div>
															<div class="form-group col-lg-6 text-right mt-4">
																<button class="btn btn-danger btn-sm" data-dismiss="modal">
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
include_once VISTA_PATH . 'modulos' . DS . 'estudiantes' . DS . 'agregarEstudiante.php';

if (isset($_POST['documento'])) {
	$instancia->agregarEstudianteControl();
}

if (isset($_POST['foto_perfil_ant'])) {
	$instancia->editarEstudianteControl();
}
?>
<script src="<?=PUBLIC_PATH?>js/usuarios/funcionesUsuarios.js"></script>