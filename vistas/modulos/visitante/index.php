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
require_once CONTROL_PATH . 'visitante' . DS . 'ControlVisitante.php';
require_once CONTROL_PATH . 'usuarios' . DS . 'ControlUsuarios.php';

$horaactual = date("H:i:s");

// Convertir las horas a DateTime
$horaactual_dt = new DateTime($horaactual);

$instancia = ControlVisitante::singleton_visitante();


$datos_visitante = $instancia->mostrarVisitanteControl();
//$datos_meses       = $instancia->mostrarMesesPensionControl();

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
						Asistencia
					</h4>
					<div class="btn-group">

					</div>
					<div class="btn-group">
               
						<button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#agregar_cortesia">
							<i class="fa fa-plus"></i>
							&nbsp;
							Cortesia
						</button>
						<button type="button" class="btn btn-play btn-sm" data-toggle="modal" data-target="#agregar_registro">
							<i class="fa fa-plus"></i>
							&nbsp;
							Asistencia
						</button>
					</div>
				</div>
				<div class="card-body">
					<form method="POST">
						<div class="row">
						
						
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
            <th scope="col">Cantidad</th>
			<th scope="col">Visitantes</th>
            <th scope="col">Acudiente</th>
			<th scope="col">Evento</th>
            <th scope="col">Celular</th>
            <th scope="col">Ingreso</th>
            <th scope="col">Duración</th>
            <th scope="col">Salida</th>
        <!--     <th scope="col">Cambio</th>  Nueva columna para acciones -->
        </tr>
    </thead>
    <tbody class="buscar">
        <?php
        foreach ($datos_visitante as $pension) {
            $id = $pension['id'];
            $visitante = $pension['visitante'];
			$nombres = $pension['nombres'];
            $acudiente = $pension['acudiente'];
            $celular = $pension['celular'];
            $fechaingreso = $pension['fecha_ingreso'];
            $horaingreso = $pension['horaingreso'];
            $duracion = $pension['duracion'];
            $horasalida = $pension['horasalida'];
			$tipo_cortesia =$pension['tipo_cortesia'];


            // Convertir la hora de salida a DateTime
            $horasalida_dt = new DateTime($horasalida);
			if (empty($tipo_cortesia)) {
				$tipo_cortesia = 'Normal';
			}
            ?>
            <tr class="text-center">
                <td><?=$visitante?></td>
				<td><?=$nombres?></td>
                <td><?=$acudiente?></td>
				<td style="background-color: <?= ($tipo_cortesia == 'Actividad') ? 'lightblue' : 'white'; ?>">
    <?= $tipo_cortesia ?>
</td>

                <td><?=$celular?></td>
                <td><?=$horaingreso?></td>
                <td>
                    <?php
                    if ($duracion == 1) {
                        echo "30 min";
                    } elseif ($duracion == 2) {
                        echo "60 min";
                    } else {
                        echo $duracion . " Hora(s)";
                    }
                    ?>
                </td>
                <td>
                    <?php
                    if ($horasalida_dt < $horaactual_dt) {
                        ?>
                        <span class="badge badge-danger">
                            <?= $horasalida ?>
                        </span>
                        <?php
                    } else {
                        ?>
                        <span class="badge badge-success">
                            <?= $horasalida ?>
                        </span>
                        <?php
                    }
                    ?>
                </td>
                <td>
                    <!-- Botón de edición 
                    <a href="<?= BASE_URL ?>visitante/EditarRegistro.php?id=<?= $id ?>" class="btn btn-warning btn-sm">
                        <i class="fa fa-edit"></i>
                        Editar
                    </a>-->
                </td>
            </tr>
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
include_once VISTA_PATH . 'modulos' . DS . 'visitante' . DS . 'agregarRegistro.php';
include_once VISTA_PATH . 'modulos' . DS . 'visitante' . DS . 'cortesia.php';
include_once VISTA_PATH . 'modulos' . DS . 'visitante' . DS . 'comprarproducto.php';

// Verificar si se presionó el botón de "Agregar Registro"
if (isset($_POST['submit_registro'])) {
    $instancia->agregarVisitanteControl();
}

// Verificar si se presionó el botón de "Agregar Cortesía"
if (isset($_POST['submit_cortesia'])) {
    $instancia->agregarVisitanteCortesiaControl();
}

if (isset($_POST['comprar'])) {
    $instancia->guardarVentaControl();
}
?>