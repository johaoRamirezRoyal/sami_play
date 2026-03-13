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

$instancia_perfil = ControlPerfil::singleton_perfil();

if (isset($_POST['buscar'])) {
	$datos_perfil = $instancia_perfil->buscarPerfilControl($_POST['buscar']);
} else {
	$datos_perfil = $instancia_perfil->mostrarLimitesPerfilesControl();
}
$datos_modulos = $instancia_permiso->mostrarModulosControl();

$permisos = $instancia_permiso->permisosUsuarioControl($perfil_log, 1);
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
						<a href="<?=BASE_URL?>inicio" class="text-play text-decoration-none">
							<i class="fa fa-arrow-left"></i>
						</a>
						&nbsp;
						Permisos
					</h4>
				</div>
				<div class="card-body">
					<form method="POST">
						<div class="row">
							<div class="col-lg-8"></div>
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
									<th scope="col">Perfil</th>
									<th scope="col">Modulos</th>
								</tr>
							</thead>
							<tbody class="buscar">
								<?php
								foreach ($datos_perfil as $perfiles) {
									$id_perfil = $perfiles['id'];
									$nombre    = $perfiles['nombre'];
									$activo    = $perfiles['activo'];
									$modulos   = $perfiles['modulos'];

									$ver = ($activo == 1) ? '' : 'd-none';
									?>
									<tr class="text-center text-uppercase">
										<td><?=$nombre?></td>
										<td><?=$modulos?></td>
										<td>
											<button class="btn btn-play btn-sm" data-tooltip="tooltip" title="Asignar modulos" data-placement="bottom" data-trigger="hover" data-toggle="modal" data-target="#perfil<?=$id_perfil?>">
												<i class="fa fa-plus"></i>
											</button>
										</td>
									</tr>

									<!-- Modal -->
									<div class="modal fade" id="perfil<?=$id_perfil?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
										<div class="modal-dialog" role="document">
											<div class="modal-content modal-lg">
												<div class="modal-header">
													<h5 class="modal-title text-play font-weight-bold" id="exampleModalLabel">Modulos del perfil - (<?=$nombre?>)</h5>
													<a href="<?=BASE_URL?>permisos/index" class="btn btn-sm border-0">
														<i class="fa fa-times"></i>
													</a>
												</div>
												<div class="modal-body">
													<div class="row p-2 listado">
														<?php
														foreach ($datos_modulos as $opcion) {
															$id_opcion  = $opcion['id'];
															$nom_opcion = $opcion['modulo'];

															$opcion_activa = $instancia_permiso->opcionesActivasPerfilControl($id_perfil, $id_opcion);

															$icon  = ($opcion_activa['id'] != '') ? '<i class="fa fa-times float-right"></i>' : '<i class="fa fa-check float-right"></i>';
															$color = ($opcion_activa['id'] != '') ? 'active' : '';
															$class = ($opcion_activa['id'] != '') ? 'inactivar' : 'activar';
															?>
															<div class="col-lg-12 mb-2">
																<div class="list-group">
																	<a href="#" class="list-group-item list-group-item-action <?=$color?> <?=$class?> opcion_<?=$id_opcion?>" id="<?=$id_opcion?>" data-perfil="<?=$id_perfil?>" data-log="<?=$id_log?>">
																		<?=$nom_opcion?>
																		&nbsp;
																		<?=$icon?>
																	</a>
																</div>
															</div>
														<?php }?>
													</div>
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
<?php
include_once VISTA_PATH . 'script_and_final.php';
?>
<script type="text/javascript" src="<?=PUBLIC_PATH?>js/permisos/funcionesPermisos.js"></script>