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

$instancia = ControlPerfil::singleton_perfil();

$datos = $instancia->mostrarDatosPerfilControl($id_log);
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12">
			<div class="card shadow-sm mb-4">
				<div class="card-header py-3">
					<h4 class="m-0 font-weight-bold text-play">Perfil</h4>
				</div>
				<div class="card-body">
					<form action="" method="POST" id="form_enviar" enctype="multipart/form-data">
						<input type="hidden" value="<?=$datos['id_user']?>" name="id_user">
						<input type="hidden" value="<?=$datos['pass']?>" name="pass_old">
						<input type="hidden" value="<?=$datos['documento']?>" name="documento">
						<input type="hidden" value="<?=$datos['foto_perfil']?>" name="foto_perfil_ant">
						<input type="hidden" value="<?=BASE_URL?>perfil/index" name="url">
						<div class="row">
							<div class="col-lg-12 form-group">
								<div class="row">
									<div class="col-lg-4 form-group mt-4">
										<div class="circular--portrait">
											<img src="<?=PUBLIC_PATH . $foto_perfil?>" alt="">
										</div>
									</div>
									<div class="col-lg-8 form-group mt-2">
										<div class="row">
											<div class="col-lg-12 form-group">
												<label class="font-weight-bold">Numero de documento <span class="text-danger">*</span></label>
												<input type="text" class="form-control" value="<?=$datos['documento']?>" disabled>
											</div>
											<div class="col-lg-6">
												<div class="form-group">
													<label class="font-weight-bold">Nombre <span class="text-danger">*</span></label>
													<input type="text" class="form-control letras" value="<?=$datos['nombre']?>" name="nombre" required>
												</div>
											</div>
											<div class="col-lg-6">
												<div class="form-group">
													<label class="font-weight-bold">Apellido <span class="text-danger">*</span></label>
													<input type="text" class="form-control letras" value="<?=$datos['apellido']?>" name="apellido" required>
												</div>
											</div>
											<div class="col-lg-6">
												<div class="form-group">
													<label class="font-weight-bold">Correo <span class="text-danger">*</span></label>
													<input type="email" class="form-control" value="<?=$datos['correo']?>" name="correo" required>
												</div>
											</div>
											<div class="col-lg-6">
												<div class="form-group">
													<label class="font-weight-bold">Telefono</label>
													<input type="text" class="form-control numeros" value="<?=$datos['telefono']?>" name="telefono">
												</div>
											</div>
											<div class="col-lg-6">
												<div class="form-group">
													<label class="font-weight-bold">Usuario <span class="text-danger">*</span></label>
													<input type="text" class="form-control" value="<?=$datos['user']?>" name="usuario" readonly>
												</div>
											</div>
											<div class="col-lg-12 form-group">
												<label class="font-weight-bold">Foto perfil <span class="text-danger">*</span></label>
												<div class="custom-file pmd-custom-file-filled">
													<input type="file" class="custom-file-input file_input" name="foto" required accept=".png, .jpg, .jpeg">
													<label class="custom-file-label file_label" for="customfilledFile"></label>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-12 text-center mb-4">
								<hr>
								<h4 class="text-play font-weight-bold">Cambiar Contrase&ntilde;a</h4>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label class="font-weight-bold">Nueva Contrase&ntilde;a</label>
									<input type="password" class="form-control" name="password" id="password">
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label class="font-weight-bold">Nueva Confirmar Contrase&ntilde;a</label>
									<input type="password" class="form-control" name="conf_password" id="conf_password">
								</div>
							</div>
						</div>
						<div class="form-group mt-4 float-right">
							<button type="submit" class="btn btn-play btn-sm" id="enviar_perfil">
								<i class="fa fa-save"></i>
								&nbsp;
								Guardar Cambios
							</button>
							<input type="hidden" name="perfil" value="<?=$datos['perfil']?>">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
include_once VISTA_PATH . 'script_and_final.php';
if (isset($_POST['documento'])) {
	$instancia->editarPerfilControl();
}
?>
<script src="<?=PUBLIC_PATH?>js/validaciones.js"></script>