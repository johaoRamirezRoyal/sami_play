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

$instancia        = ControlUsuarios::singleton_usuario();
$instancia_perfil = ControlPerfil::singleton_perfil();

$datos_perfil = $instancia_perfil->mostrarPerfilesControl();

if (isset($_POST['buscar'])) {
	$datos_usuarios = $instancia->mostrarUsuariosBuscarControl($_POST['buscar']);
} else {
	$datos_usuarios = $instancia->mostrarUsuariosControl();
}

$permisos = $instancia_permiso->permisosUsuarioControl($perfil_log, 2);
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
						Usuarios
					</h4>
					<div class="btn-group">
						<button class="btn btn-play btn-sm" type="button" data-toggle="modal" data-target="#agregar_usuario">
							<i class="fa fa-plus"></i>
							&nbsp;
							Agregar Usuario
						</button>
					</div>
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
									<th scope="col">Documento</th>
									<th scope="col">Nombre Completo</th>
									<th scope="col">Correo</th>
									<th scope="col">Telefono</th>
									<th scope="col">Usuario</th>
									<th scope="col">Perfil</th>
								</tr>
							</thead>
							<tbody class="buscar">
								<?php
								foreach ($datos_usuarios as $usuario) {
									$id_user      = $usuario['id_user'];
									$documento    = $usuario['documento'];
									$nom_completo = $usuario['nombre'] . ' ' . $usuario['apellido'];
									$correo       = $usuario['correo'];
									$telefono     = $usuario['telefono'];
									$user_nom     = $usuario['user'];
									$perfil       = $usuario['nom_perfil'];
									$activo       = $usuario['activo'];

									$ver_inactivo = ($activo == 1) ? '' : 'd-none';
									$ver_activo   = ($activo == 1) ? 'd-none' : '';

									if ($usuario['perfil'] != 1) {
										?>
										<tr class="text-center user_<?=$id_user?>">
											<td><?=$documento?></td>
											<td><?=$nom_completo?></td>
											<td><?=$correo?></td>
											<td><?=$telefono?></td>
											<td><?=$user_nom?></td>
											<td><?=$perfil?></td>
											<td>
												<div class="btn-group" role="group">
													<button class="btn btn-play btn-sm" data-tooltip="tooltip" title="Editar" data-placement="bottom" data-trigger="hover" data-toggle="modal" data-target="#editar<?=$id_user?>">
														<i class="fa fa-user-edit"></i>
													</button>
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
																					<label class="font-weight-bold">Documento <span class="text-danger">*</span></label>
																					<input type="text" class="form-control numeros" disabled value="<?=$documento?>">
																				</div>
																				<div class="form-group col-lg-12">
																					<label class="font-weight-bold">Nombre <span class="text-danger">*</span></label>
																					<input type="text" class="form-control " required value="<?=$usuario['nombre']?>" name="nombre_edit">
																				</div>
																				<div class="form-group col-lg-12">
																					<label class="font-weight-bold">Apellido</label>
																					<input type="text" class="form-control " value="<?=$usuario['apellido']?>" name="apellido_edit">
																				</div>
																				<div class="form-group col-lg-12">
																					<label class="font-weight-bold">Correo</label>
																					<input type="email" class="form-control " value="<?=$correo?>" name="correo_edit" >
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
																<div class="form-group col-lg-6">
																	<label class="font-weight-bold">Telefono </label>
																	<input type="text" class="form-control numeros" value="<?=$telefono?>" name="telefono_edit">
																</div>
																<div class="form-group col-lg-6">
																	<label class="font-weight-bold">Usuario <span class="text-danger">*</span></label>
																	<input type="text" class="form-control" value="<?=$user_nom?>" disabled>
																</div>
																<div class="col-lg-6 form-group">
																	<label class="font-weight-bold">Perfil <span class="text-danger">*</span></label>
																	<select class="form-control" name="perfil_edit" required>
																		<?php
																		foreach ($datos_perfil as $perfiles) {
																			$id_perfil = $perfiles['id'];
																			$nombre    = $perfiles['nombre'];
																			$activo    = $perfiles['activo'];

																			$selected = ($usuario['perfil'] == $id_perfil) ? 'selected' : '';
																			?>
																			<option value="<?=$id_perfil?>" <?=$selected?>><?=$nombre?></option>
																			<?php
																		}
																		?>
																	</select>
																</div>
																<div class="col-lg-6 form-group">
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
include_once VISTA_PATH . 'modulos' . DS . 'usuarios' . DS . 'agregarUsuario.php';

if (isset($_POST['documento'])) {
	$instancia->agregarUsuarioControl();
}

if (isset($_POST['foto_perfil_ant'])) {
	$instancia->editarUsuarioControl();
}
?>
<script src="<?=PUBLIC_PATH?>js/usuarios/funcionesUsuarios.js"></script>