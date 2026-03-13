<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12">
			<div class="card shadow-sm mb-4">
				<div class="card-header py-3">
					<h4 class="m-0 font-weight-bold text-play">Inicio</h4>
				</div>
				<div class="card-body">
					<div class="row">
						<?php
						$permisos = $instancia_permiso->permisosUsuarioControl($perfil_log, 2);
						if ($permisos) {
						?>
							<a class="col-md-3 mb-4 text-decoration-none" href="<?= BASE_URL ?>usuarios/index">
								<div class="card border-left-success shadow-sm h-100 py-2">
									<div class="card-body">
										<div class="row no-gutters align-items-center">
											<div class="col mr-2">
												<div class="h5 mb-0 font-weight-bold text-gray-800">Usuarios</div>
											</div>
											<div class="col-auto">
												<i class="fas fa-users fa-2x text-success"></i>
											</div>
										</div>
									</div>
								</div>
							</a>
						<?php }
						$permisos = $instancia_permiso->permisosUsuarioControl($perfil_log, 7);
						if ($permisos) {
						?>
							<a class="col-md-3 mb-4 text-decoration-none" href="<?= BASE_URL ?>estudiantes/index">
								<div class="card border-left-blue shadow-sm h-100 py-2">
									<div class="card-body">
										<div class="row no-gutters align-items-center">
											<div class="col mr-2">
												<div class="h5 mb-0 font-weight-bold text-gray-800">Estudiantes</div>
											</div>
											<div class="col-auto">
												<i class="fas fa-user-graduate fa-2x text-blue"></i>
											</div>
										</div>
									</div>
								</div>
							</a>
						<?php }
						$permisos = $instancia_permiso->permisosUsuarioControl($perfil_log, 3);
						if ($permisos) {
						?>
							<a class="col-md-3 mb-4 text-decoration-none" href="<?= BASE_URL ?>periodos/index">
								<div class="card border-left-danger shadow-sm h-100 py-2">
									<div class="card-body">
										<div class="row no-gutters align-items-center">
											<div class="col mr-2">
												<div class="h5 mb-0 font-weight-bold text-gray-800">Periodos</div>
											</div>
											<div class="col-auto">
												<i class="fas fa-hourglass-half fa-2x text-danger"></i>
											</div>
										</div>
									</div>
								</div>
							</a>
						<?php }
						$permisos = $instancia_permiso->permisosUsuarioControl($perfil_log, 4);
						if ($permisos) {
						?>
							<a class="col-md-3 mb-4 text-decoration-none" href="<?= BASE_URL ?>dimension/index">
								<div class="card border-left-purple shadow-sm h-100 py-2">
									<div class="card-body">
										<div class="row no-gutters align-items-center">
											<div class="col mr-2">
												<div class="h5 mb-0 font-weight-bold text-gray-800">Dimensiones</div>
											</div>
											<div class="col-auto">
												<i class="fas fa-cubes fa-2x text-purple"></i>
											</div>
										</div>
									</div>
								</div>
							</a>
						<?php }
						$permisos = $instancia_permiso->permisosUsuarioControl($perfil_log, 5);
						if ($permisos) {
						?>
							<a class="col-md-3 mb-4 text-decoration-none" href="<?= BASE_URL ?>indicador/index">
								<div class="card border-left-orange shadow-sm h-100 py-2">
									<div class="card-body">
										<div class="row no-gutters align-items-center">
											<div class="col mr-2">
												<div class="h5 mb-0 font-weight-bold text-gray-800">Indicadores</div>
											</div>
											<div class="col-auto">
												<i class="fas fa-chart-bar  fa-2x text-orange"></i>
											</div>
										</div>
									</div>
								</div>
							</a>
						<?php }
						$permisos = $instancia_permiso->permisosUsuarioControl($perfil_log, 6);
						if ($permisos) {
						?>
							<a class="col-md-3 mb-4 text-decoration-none" href="<?= BASE_URL ?>curso/index">
								<div class="card border-left-green-dark shadow-sm h-100 py-2">
									<div class="card-body">
										<div class="row no-gutters align-items-center">
											<div class="col mr-2">
												<div class="h5 mb-0 font-weight-bold text-gray-800">Cursos</div>
											</div>
											<div class="col-auto">
												<i class="fas fa-graduation-cap  fa-2x text-green-dark"></i>
											</div>
										</div>
									</div>
								</div>
							</a>
						<?php }
						$permisos = $instancia_permiso->permisosUsuarioControl($perfil_log, 8);
						if ($permisos) {
						?>
							<a class="col-md-3 mb-4 text-decoration-none" href="<?= BASE_URL ?>calificacion/index">
								<div class="card border-left-info shadow-sm h-100 py-2">
									<div class="card-body">
										<div class="row no-gutters align-items-center">
											<div class="col mr-2">
												<div class="h5 mb-0 font-weight-bold text-gray-800">Calificaciones</div>
											</div>
											<div class="col-auto">
												<i class="fas fa-tasks fa-2x text-info"></i>
											</div>
										</div>
									</div>
								</div>
							</a>
						<?php }
						$permisos = $instancia_permiso->permisosUsuarioControl($perfil_log, 17);
						if ($permisos) {
						?>
							<a class="col-md-3 mb-4 text-decoration-none" href="<?= BASE_URL ?>asistencia/curso">
								<div class="card border-left-pink-white shadow-sm h-100 py-2">
									<div class="card-body">
										<div class="row no-gutters align-items-center">
											<div class="col mr-2">
												<div class="h5 mb-0 font-weight-bold text-gray-800">Asistencia Curso</div>
											</div>
											<div class="col-auto">
												<i class="fas fa-list-ol fa-2x text-pink-white"></i>
											</div>
										</div>
									</div>
								</div>
							</a>
						<?php }
						$permisos = $instancia_permiso->permisosUsuarioControl($perfil_log, 18);
						if ($permisos) {
						?>
							<a class="col-md-3 mb-4 text-decoration-none" href="<?= BASE_URL ?>asistencia/historial">
								<div class="card border-left-green shadow-sm h-100 py-2">
									<div class="card-body">
										<div class="row no-gutters align-items-center">
											<div class="col mr-2">
												<div class="h5 mb-0 font-weight-bold text-gray-800">Asistencia Historial</div>
											</div>
											<div class="col-auto">
												<i class="fas fa-list-ol fa-2x text-green"></i>
											</div>
										</div>
									</div>
								</div>
							</a>
						<?php }
						$permisos = $instancia_permiso->permisosUsuarioControl($perfil_log, 14);
						if ($permisos) {
						?>
							<a class="col-md-3 mb-4 text-decoration-none" href="<?= BASE_URL ?>observador/index">
								<div class="card border-left-pink shadow-sm h-100 py-2">
									<div class="card-body">
										<div class="row no-gutters align-items-center">
											<div class="col mr-2">
												<div class="h5 mb-0 font-weight-bold text-gray-800">Observador</div>
											</div>
											<div class="col-auto">
												<i class="fas fa-clipboard-list fa-2x text-pink"></i>
											</div>
										</div>
									</div>
								</div>
							</a>
						<?php }
						$permisos = $instancia_permiso->permisosUsuarioControl($perfil_log, 9);
						if ($permisos) {
						?>
							<a class="col-md-3 mb-4 text-decoration-none" href="<?= BASE_URL ?>pension/index">
								<div class="card border-left-warning shadow-sm h-100 py-2">
									<div class="card-body">
										<div class="row no-gutters align-items-center">
											<div class="col mr-2">
												<div class="h5 mb-0 font-weight-bold text-gray-800">Pension</div>
											</div>
											<div class="col-auto">
												<i class="fas fa-wallet fa-2x text-warning"></i>
											</div>
										</div>
									</div>
								</div>
							</a>
						<?php }
						$permisos = $instancia_permiso->permisosUsuarioControl($perfil_log, 10);
						if ($permisos) {
						?>
							<a class="col-md-3 mb-4 text-decoration-none" href="<?= BASE_URL ?>boletin/index">
								<div class="card border-left-purple shadow-sm h-100 py-2">
									<div class="card-body">
										<div class="row no-gutters align-items-center">
											<div class="col mr-2">
												<div class="h5 mb-0 font-weight-bold text-gray-800">Boletines</div>
											</div>
											<div class="col-auto">
												<i class="fas fa-th-list fa-2x text-purple"></i>
											</div>
										</div>
									</div>
								</div>
							</a>
						<?php }
						$permisos = $instancia_permiso->permisosUsuarioControl($perfil_log, 16);
						if ($permisos) {
						?>
							<a class="col-md-3 mb-4 text-decoration-none" href="<?= BASE_URL ?>boletin/historial?id=<?= base64_encode($id_log) ?>">
								<div class="card border-left-green-force shadow-sm h-100 py-2">
									<div class="card-body">
										<div class="row no-gutters align-items-center">
											<div class="col mr-2">
												<div class="h5 mb-0 font-weight-bold text-gray-800">Boletines Historial</div>
											</div>
											<div class="col-auto">
												<i class="fas fa-history fa-2x text-green-force"></i>
											</div>
										</div>
									</div>
								</div>
							</a>
						<?php }
						$permisos = $instancia_permiso->permisosUsuarioControl($perfil_log, 19);

						if ($permisos) {

						?>

							<a class="col-md-3 mb-4 text-decoration-none" href="<?= BASE_URL ?>tienda/index">

								<div class="card border-left-pink shadow-sm h-100 py-2">

									<div class="card-body">

										<div class="row no-gutters align-items-center">

											<div class="col mr-2">

												<div class="h5 mb-0 font-weight-bold text-gray-800">Tienda</div>

											</div>

											<div class="col-auto">

												<i class="fa fa-shopping-bag fa-2x text-pink"></i>

											</div>

										</div>

									</div>

								</div>

							</a>
						<?php }

						$permisos = $instancia_permiso->permisosUsuarioControl($perfil_log, 27);

						if ($permisos) {

						?>

							<a class="col-md-3 mb-4 text-decoration-none" href="<?= BASE_URL ?>inventario/index">

								<div class="card border-left-dark shadow-sm h-100 py-2">

									<div class="card-body">

										<div class="row no-gutters align-items-center">

											<div class="col mr-2">

												<div class="h5 mb-0 font-weight-bold text-gray-800">Inventario</div>

											</div>

											<div class="col-auto">

												<i class="fas fa-barcode fa-2x text-dark"></i>

											</div>

										</div>

									</div>

								</div>

							</a>

						<?php }
						$permisos = $instancia_permiso->permisosUsuarioControl($perfil_log, 33);

						if ($permisos) {

						?>

							<a class="col-md-3 mb-4 text-decoration-none" href="<?= BASE_URL ?>listado/index">

								<div class="card border-left-blue shadow-sm h-100 py-2">

									<div class="card-body">

										<div class="row no-gutters align-items-center">

											<div class="col mr-2">

												<div class="h5 mb-0 font-weight-bold text-gray-800">Listado</div>

											</div>

											<div class="col-auto">

												<i class="fas fa-list-ol fa-2x text-blue"></i>

											</div>

										</div>

									</div>

								</div>

							</a>

						<?php }

						$permisos = $instancia_permiso->permisosUsuarioControl($perfil_log, 31);

						if ($permisos) {

							?>

							<a class="col-md-3 mb-4 text-decoration-none" href="<?= BASE_URL ?>areas/reasignar">

								<div class="card border-left-orange shadow-sm h-100 py-2">

									<div class="card-body">

										<div class="row no-gutters align-items-center">

											<div class="col mr-2">

												<div class="h5 mb-0 font-weight-bold text-gray-800">Re-asignar area</div>

											</div>

											<div class="col-auto">

												<i class="fas fa-sync-alt fa-2x text-orange"></i>

											</div>

										</div>

									</div>

								</div>

							</a>

						<?php }


						$permisos = $instancia_permiso->permisosUsuarioControl($perfil_log, 13);
						if ($permisos) {
						?>
							<a class="col-md-3 mb-4 text-decoration-none" href="<?= BASE_URL ?>recursos/index">
								<div class="card border-left-semi-green shadow-sm h-100 py-2">
									<div class="card-body">
										<div class="row no-gutters align-items-center">
											<div class="col mr-2">
												<div class="h5 mb-0 font-weight-bold text-gray-800">Gestión humana</div>
											</div>
											<div class="col-auto">
												<i class="fas fa-id-card-alt fa-2x text-semi-green"></i>
											</div>
										</div>
									</div>
								</div>
							</a>
						<?php }
						$permisos = $instancia_permiso->permisosUsuarioControl($perfil_log, 1);
						if ($permisos) {
						?>
							<a class="col-md-3 mb-4 text-decoration-none" href="<?= BASE_URL ?>permisos/index">
								<div class="card border-left-secondary shadow-sm h-100 py-2">
									<div class="card-body">
										<div class="row no-gutters align-items-center">
											<div class="col mr-2">
												<div class="h5 mb-0 font-weight-bold text-gray-800">Permisos</div>
											</div>
											<div class="col-auto">
												<i class="fas fa-unlock-alt fa-2x text-secondary"></i>
											</div>
										</div>
									</div>
								</div>
							</a>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>