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

require_once CONTROL_PATH . 'periodo' . DS . 'ControlPeriodo.php';
require_once CONTROL_PATH . 'curso' . DS . 'ControlCurso.php';
require_once CONTROL_PATH . 'dimension' . DS . 'ControlDimension.php';

$instancia_periodo = ControlPeriodo::singleton_periodo();
$instancia_cursos = ControlCurso::singleton_curso();
$instancia_dimensiones = ControlDimension::singleton_dimension();

$periodos = $instancia_periodo->mostrarTodosPeriodosControl();
$cursos = $instancia_cursos->mostrarGruposCursosControl();

if (isset($_POST['filtro'])) {
    $id_grupo = $_POST['grupo_curso'];
    $id_periodo = $_POST['periodo'];

    $indicadores_dimensiones = $instancia_dimensiones->obtenerIndicadoresDimensionesGrupoCursoPeriodoControl($id_grupo, $id_periodo);
    $dimensiones = [];

    foreach ($indicadores_dimensiones as $item) {
        $id_dimension = $item['dimension_id'];

        $dimensiones[$id_dimension]['nombre'] = $item['dimension_nombre'];
        $dimensiones[$id_dimension]['indicadores'][] = $item;
    }
}

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h4 class="m-0 font-weight-bold text-play">
                        <a href="<?= BASE_URL ?>indicador/index" class="text-decoration-none text-play">
                            <i class="fa fa-arrow-left"></i>
                        </a>
                        &nbsp;
                        Administrar Indicadores
                    </h4>
                </div>
                <div class="card-body">
                    <div class="col-lg-12">
                        <form method="post">
                            <div class="row">

                                <div class="col-lg-3">
                                    <select name="periodo" id="periodo" class="form-control" required>
                                        <option value="" disabled selected>Seleccione un periodo</option>
                                        <?php
                                        foreach ($periodos as $p) {
                                            $id = $p['id'];
                                            $numero = $p['numero'];
                                            $anio = $p['anio']
                                        ?>
                                            <option value="<?= $id ?>" <?= (isset($_POST['periodo']) && $_POST['periodo'] == $id ? 'selected' : '') ?>>Periodo <?= $numero ?> (<?= $anio ?>)</option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="col-lg-3">
                                    <select name="grupo_curso" id="grupo_curso" class="form-control" required>
                                        <option value="" disabled selected>Seleccione un grupo</option>
                                        <?php
                                        foreach ($cursos as $c) {
                                            $id = $c['id'];
                                            $numero = $c['nombre'];
                                        ?>
                                            <option value="<?= $id ?>" <?= (isset($_POST['grupo_curso']) && $_POST['grupo_curso'] == $id ? 'selected' : '') ?>>Grupo: <?= $numero ?></option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="col-lg-6 d-flex justify-content-end">
                                    <button class="btn btn-play btn-sm" name="filtro" type="submit">
                                        Filtrar
                                    </button>
                                </div>

                            </div>
                        </form>

                        <?php
                        if (isset($_POST['filtro'])) {
                        ?>
                            <form method="POST">
                                <input type="hidden" name="grupo_curso" value="<?= $id_grupo ?>">
                                <input type="hidden" name="id_periodo" value="<?= $id_periodo ?>">
                                <input type="hidden" name="id_log" value="<?= $id_log ?>">
                                <input type="hidden" name="activo" value="1">
                                <div class="table-responsive mt-4">
                                    <div class="mb-3">
                                        <button type="button" id="toggleIndicadores" class="btn btn-primary btn-sm">
                                            Seleccionar todos
                                        </button>

                                        <span class="ml-3 badge badge-info">
                                            Activos: <span id="contadorIndicadores">0</span>
                                        </span>
                                    </div>
                                    <table class="table table-bordered table-hover table-sm">

                                        <?php foreach ($dimensiones as $dimension) { ?>

                                            <thead class="thead-light">
                                                <tr class="text-center font-weight-bold">
                                                    <th colspan="2"><?= $dimension['nombre'] ?></th>
                                                </tr>
                                                <tr>
                                                    <th width="90%">Indicador</th>
                                                    <th width="10%">Activo</th>
                                                </tr>
                                            </thead>

                                            <tbody>

                                                <?php foreach ($dimension['indicadores'] as $indicador) {
                                                    $id_indicador = $indicador['id'];
                                                    $id_dimension = $indicador['id_dimension'];
                                                    $estado = $instancia_dimensiones->indicadorEnConfiguracionControl($id_indicador, $id_grupo, $id_periodo);
                                                    $checked = ($estado == 1) ? 'checked' : '';
                                                ?>

                                                    <tr>
                                                        <td><?= $indicador['nombre'] ?></td>

                                                        <td class="text-center">
                                                            <div class="custom-control custom-switch">
                                                                <input
                                                                    type="checkbox"
                                                                    class="custom-control-input indicador-check"
                                                                    id="indicador<?= $indicador['id'] ?>"
                                                                    name="indicador[]"
                                                                    value="<?= $id_indicador ?>, <?= $id_dimension ?>"
                                                                    <?= $checked ?>>

                                                                <label class="custom-control-label" for="indicador<?= $indicador['id'] ?>"></label>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                <?php } ?>

                                            </tbody>

                                        <?php } ?>

                                    </table>
                                </div>
                                <div class="col-lg-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-info btn-md" name="generar">
                                        Agregar indicadores
                                    </button>
                                </div>
                            </form>
                        <?php
                        } else { ?>
                            <div class="table-responsive mt-4">
                                <table class="table table-bordered table-hover table-sm">
                                    <thead class="thead-light">
                                        <tr class="text-center font-weight-bold">
                                            <th colspan="2">Dimensión</th>
                                        </tr>
                                        <tr class="text-center">
                                            <th width="90%">Indicador</th>
                                            <th width="10%">Activo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="2" class="text-center text-muted">
                                                Debe seleccionar el grupo del curso y el periodo para configurar los indicadores
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {

        function actualizarContador() {
            let total = $(".indicador-check").length;
            let checked = $(".indicador-check:checked").length;

            $("#contadorIndicadores").text(checked);

            $("#toggleIndicadores")
                .text(total === checked ? "Deseleccionar todos" : "Seleccionar todos")
                .toggleClass("btn-danger", total === checked)
                .toggleClass("btn-primary", total !== checked);
        }

        $("#toggleIndicadores").click(function() {
            let total = $(".indicador-check").length;
            let checked = $(".indicador-check:checked").length;

            $(".indicador-check").prop("checked", total !== checked).trigger("change");
        });

        $(".indicador-check").on("change", actualizarContador);

        actualizarContador();

    });
</script>

<?php
if (isset($_POST['generar'])) {
    $instancia_cursos->agregarIndicadoresConfiguracionCursoControl();
}
