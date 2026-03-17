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
require_once CONTROL_PATH . 'periodo' . DS . 'ControlPeriodo.php';

$instancia = ControlDimension::singleton_dimension();
$instancia_periodo   = ControlPeriodo::singleton_periodo();

$datos_dimension = $instancia->mostrarTodasDimensionControl();
$datos_grupo     = $instancia->mostrarGruposControl();
$datos_periodo     = $instancia_periodo->mostrarPeriodosAnioActivoControl();

if (isset($_POST['buscar'])) {

	$datos           = array('buscar' => $_POST['buscar'], 'dimension' => $_POST['dimension']);
	$datos_indicador = $instancia->buscarIndicadoresControl($datos);
} else {
	$datos_indicador = $instancia->mostrarLimiteIndicadorControl();
}

$permisos = $instancia_permiso->permisosUsuarioControl($perfil_log, 5);
if (!$permisos) {
	include_once VISTA_PATH . 'modulos' . DS . '403.php';
	die();
}
?>

<style>
	.custom-file-input-wrapper {
		position: relative;
		display: flex;
		align-items: center;
	}

	.custom-file-input-wrapper input[type="file"] {
		display: none;
	}

	.custom-file-label-btn {
		background: #007bff;
		color: #fff;
		padding: 8px 15px;
		border-radius: 5px;
		cursor: pointer;
		margin-right: 10px;
		transition: 0.3s;
	}

	.custom-file-label-btn:hover {
		background: #0056b3;
	}

	.file-name {
		font-size: 14px;
		color: #555;
	}
</style>

<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12">
			<div class="card shadow-sm mb-4">
				<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
					<h4 class="m-0 font-weight-bold text-play">
						<a href="<?= BASE_URL ?>inicio" class="text-decoration-none text-play">
							<i class="fa fa-arrow-left"></i>
						</a>
						&nbsp;
						Indicadores
					</h4>
					<div class="btn-group">
						<button class="btn btn-play btn-sm" type="button" data-toggle="modal" data-target="#agregar_indicador">
							<i class="fa fa-plus"></i>
							&nbsp;
							Agregar Indicador
						</button>
						<a href="<?= BASE_URL ?>indicador/administrar" class="btn btn-info btn-sm">
							<i class="fa fa-suitcase"></i>
							Administrar Indicadores
						</a>
						<button class="btn btn-success btn-sm" type="button" data-toggle="modal" data-target="#agregar_indicador_csv">
							<i class="fa fa-upload"></i>
							&nbsp;
							Importar Excel de indicadores
						</button>
					</div>
				</div>
				<div class="card-body">
					<form method="POST">
						<div class="row">
							<div class="col-lg-4 form-group"></div>
							<div class="col-lg-4 form-group">
								<select class="form-control" name="dimension">
									<option value="" select>Seleccione una dimension...</option>
									<?php
									foreach ($datos_dimension as $dimension) {
										$id_dimension  = $dimension['id'];
										$nom_dimension = $dimension['nombre'];
									?>
										<option value="<?= $id_dimension ?>"><?= $nom_dimension ?></option>
									<?php } ?>
								</select>
							</div>
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
									<th scope="col">Inidicador</th>
									<th scope="col">Grupo</th>
									<th scope="col">Dimension</th>
								</tr>
							</thead>
							<tbody class="buscar">
								<?php
								foreach ($datos_indicador as $indicador) {
									$id_indicador  = $indicador['id'];
									$nom_indicador = $indicador['nombre'];
									$nom_dimension = $indicador['nom_dimension'];
									$nom_grupo     = $indicador['nom_grupo'];
								?>
									<tr class="text-center">
										<td class="text-left"><?= $nom_indicador ?></td>
										<td><?= $nom_grupo ?></td>
										<td><?= $nom_dimension ?></td>
										<td>
											<div class="btn-group">
												<button class="btn btn-play btn-sm" type="button" data-toggle="modal" data-target="#editar_<?= $id_indicador ?>" data-tooltip="tooltip" title="Editar" data-placement="bottom">
													<i class="fa fa-edit"></i>
												</button>
											</div>
										</td>
									</tr>

									<div class="modal fade" id="editar_<?= $id_indicador ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
										<div class="modal-dialog modal-lg" role="document">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title font-weight-bold text-play" id="exampleModalLabel">Editar Indicador</h5>
												</div>
												<div class="modal-body">
													<form method="POST">
														<input type="hidden" name="id_log" value="<?= $id_log ?>">
														<input type="hidden" name="id_indicador" value="<?= $id_indicador ?>">
														<div class="row p-2">
															<div class="col-lg-6 form-group">
																<label class="font-weight-bold">Nombre <span class="text-danger">*</span></label>
																<input type="text" class="form-control" name="nombre" value="<?= $nom_indicador ?>" required>
															</div>
															<div class="col-lg-6 form-group">
																<label class="font-weight-bold">Dimension <span class="text-danger">*</span></label>
																<select name="dimension" class="form-control" required>
																	<option value="" select>Seleccione una opcion...</option>
																	<?php
																	foreach ($datos_dimension as $dimension) {
																		$id_dimension_sel = $dimension['id'];
																		$nom_dimension    = $dimension['nombre'];

																		$selec = ($id_dimension == $id_dimension_sel) ? 'selected' : '';
																	?>
																		<option value="<?= $id_dimension_sel ?>" <?= $selec ?>><?= $nom_dimension ?></option>
																	<?php
																	}
																	?>
																</select>
															</div>
															<div class="col-lg-6 form-group">
																<label class="font-weight-bold">Grupo <span class="text-danger">*</span></label>
																<select name="grupo" required class="form-control">
																	<option value="">Selecciona una opcion...</option>
																	<?php
																	foreach ($datos_grupo as $grupo) {
																		$id_grupo  = $grupo['id'];
																		$nom_grupo = $grupo['nombre'];

																		$selec = ($id_grupo == $indicador['curso_grupo']) ? 'selected' : '';
																	?>
																		<option value="<?= $id_grupo ?>" <?= $selec ?>><?= $nom_grupo ?></option>
																	<?php
																	}
																	?>
																</select>
															</div>
															<div class="col-lg-12 form-group text-right mt-2">
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
								<?php } ?>
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
include_once VISTA_PATH . 'modulos' . DS . 'indicador' . DS . 'modal' . DS . 'agregarIndicador.php';
include_once VISTA_PATH . 'modulos' . DS . 'indicador' . DS . 'modal' . DS . 'indicador_csv.php';

if (isset($_POST['nom_indicador'])) {
	$instancia->agregarIndicadorControl();
}

if (isset($_POST['id_indicador'])) {
	$instancia->editarIndicadorControl();
}

if(isset($_POST['subir_csv'])){
	$instancia->importarIndicadoresExcelControl();
}
?>