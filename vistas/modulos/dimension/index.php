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
require_once CONTROL_PATH . 'dimension' . DS . 'ControlDimension.php';

$instancia = ControlDimension::singleton_dimension();

if (isset($_POST['buscar'])) {
	$datos_dimension = $instancia->buscarDimensionControl($_POST['buscar']);
} else {
	$datos_dimension = $instancia->mostrarLimiteDimensionControl();
}

$permisos = $instancia_permiso->permisosUsuarioControl($perfil_log, 4);
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
						Dimensiones
					</h4>
					<div class="btn-group">
						<button class="btn btn-play btn-sm" type="button" data-toggle="modal" data-target="#agregar_dimension">
							<i class="fa fa-plus"></i>
							&nbsp;
							Agregar Dimension
						</button>
					</div>
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
									<th scope="col">Nombre</th>
									<th scope="col">Rese&ntilde;a</th>
								</tr>
							</thead>
							<tbody class="buscar">
								<?php
								foreach ($datos_dimension as $dimension) {
									$id_dimension  = $dimension['id'];
									$nom_dimension = $dimension['nombre'];
									$observacion   = $dimension['observacion'];
									$activo        = $dimension['activo'];

									$ver_activo   = ($activo == 0) ? '' : 'd-none';
									$ver_inactivo = ($activo == 1) ? '' : 'd-none';

									?>
									<tr class="text-center">
										<td><?=$nom_dimension?></td>
										<td><?=$observacion?></td>
										<td>
											<div class="btn-group">
												<button class="btn btn-play btn-sm" type="button" data-tooltip="tooltip" title="Editar" data-placement="bottom" data-toggle="modal" data-target="#editar_<?=$id_dimension?>">
													<i class="fa fa-edit"></i>
												</button>
												<button class="btn btn-danger btn-sm btni_<?=$id_dimension?> inactivar <?=$ver_inactivo?>" type="button" id="<?=$id_dimension?>" data-tooltip="tooltip" title="Inactivar" data-placement="bottom" data-log="<?=$id_log?>">
													<i class="fa fa-times"></i>
												</button>
												<button class="btn btn-success btn-sm btna_<?=$id_dimension?> activar <?=$ver_activo?>" type="button" id="<?=$id_dimension?>" data-tooltip="tooltip" title="Activar" data-placement="bottom" data-log="<?=$id_log?>">
													<i class="fa fa-check"></i>
												</button>
											</div>
										</td>
									</tr>

									<div class="modal fade" id="editar_<?=$id_dimension?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
										<div class="modal-dialog modal-lg">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title font-weight-bold text-play" id="exampleModalLabel">Editar Dimension No. <?=$id_dimension?></h5>
												</div>
												<div class="modal-body">
													<form method="POST" enctype="multipart/form-data">
														<input type="hidden" name="id_log" value="<?=$id_log?>">
														<input type="hidden" name="id_dimension" value="<?=$id_dimension?>">
														<input type="hidden" name="foto_antigua" value="<?=$dimension['foto']?>">
														<div class="row p-2">
															<div class="col-lg-6 form-group">
																<label class="font-weight-bold">Nombre de Dimension <span class="text-danger">*</span></label>
																<input type="text" class="form-control" name="nombre_edit" value="<?=$nom_dimension?>" required>
															</div>
															<div class="col-lg-6 form-group">
																<label class="font-weight-bold">Foto</label>
																<div class="custom-file pmd-custom-file-filled">
																	<input type="file" class="custom-file-input file_input" name="foto" id="<?=$id_dimension?>" accept=".png, .jpg, .jpeg">
																	<label class="custom-file-label file_label_<?=$id_dimension?>" for="customfilledFile"></label>
																</div>
															</div>
															<div class="col-lg-12 form-group">
																<label class="font-weight-bold">Rese&ntilde;a</label>
																<textarea class="form-control" name="observacion_edit" rows="5"><?=$observacion?></textarea>
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
<?php
include_once VISTA_PATH . 'script_and_final.php';
include_once VISTA_PATH . 'modulos' . DS . 'dimension' . DS . 'agregarDimension.php';

if (isset($_POST['nombre'])) {
	$instancia->agregarDimensionControl();
}

if (isset($_POST['nombre_edit'])) {
	$instancia->editarDimensionControl();
}
?>
<script type="text/javascript" src="<?=PUBLIC_PATH?>js/dimension/funcionesDimension.js"></script>