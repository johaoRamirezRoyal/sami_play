<?php
date_default_timezone_set('America/Bogota');
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', '..' . DS . '..' . DS . '..');
require_once '..' . DS . '..' . DS . '..' . DS . 'confi' . DS . 'Config.php';
require_once CONTROL_PATH . 'asistencia' . DS . 'ControlAsistencia.php';

$instancia       = ControlAsistencia::singleton_asistencia();
$token           = $instancia->validarDocumentoControl($_POST['id']);
$mensaje_dia     = $instancia->mensajeDiaAsistenciaControl();
$mensaje_general = $instancia->mensajeGeneralActivoControl();

$imagen_general = ($mensaje_general['imagen'] == '') ? 'img/news.jpg' : 'upload/' . $mensaje_general['imagen'];

if ($token['resultado'] == 'ok' || $token['resultado'] == 'tomada') {
	?>
	<div class="container-fluid mt-4">
		<div class="row">
			<div class="col-lg-2"></div>
			<div class="col-lg-8">
				<div class="card shadow-lg mb-4">
					<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
						<h4 class="m-0 font-weight-bold text-play">
							NEWS Play
						</h4>
					</div>
					<div class="card-body">
						<div class="row p-2">
							<div class="col-lg-12 form-group">
								<div class="card shadow">
									<div class="card-header">
										<h5 class="text-play font-weight-bold">Mensaje del dia</h5>
									</div>
									<div class="card-body">
										<div class="row">
											<div class="col-lg-6 form-group text-center">
												<img src="<?=PUBLIC_PATH?><?=$imagen_general?>" class="img-fluid" style="width: 80%;" alt="">
											</div>
											<div class="col-lg-6 form-group">
												<h5 class="font-weight-bold"><?=$mensaje_general['titulo']?></h5>
												<h6><?=$mensaje_general['mensaje']?></h6>
											</div>
										</div>
									</div>
								</div>
							</div>
							<?php
							if (count($mensaje_dia) > 0) {
								foreach ($mensaje_dia as $mensaje) {
									$titulo       = $mensaje['titulo'];
									$cont_mensaje = $mensaje['mensaje'];
									$imagen       = ($mensaje['imagen'] == '') ? 'img/news.jpg' : 'upload/' . $mensaje['imagen'];
									$nivel        = $mensaje['nivel'];

									$ver_mensaje = ($nivel == $token['nivel']) ? '' : 'd-none';
									$ver_mensaje = ($nivel == 0) ? '' : $ver_mensaje;
									?>
									<div class="col-lg-6 form-group <?=$ver_mensaje?>">
										<div class="card shadow-sm">
											<div class="card-header text-center">
												<h5 class="font-weight-bold text-play"><?=$titulo?></h5>
											</div>
											<div class="card-body text-center">
												<img src="<?=PUBLIC_PATH?><?=$imagen?>" class="img-fluid" style="width: 60%;" alt="">
											</div>
											<div class="card-footer">
												<h6 class=""><?=$cont_mensaje?></h6>
											</div>
										</div>
									</div>
									<?php
								}
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
}
