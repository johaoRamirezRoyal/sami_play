<?php
date_default_timezone_set('America/Bogota');
require_once MODELO_PATH . 'curso' . DS . 'ModeloCurso.php';

class ControlCurso
{

    private static $instancia;

    public static function singleton_curso()
    {
        if (!isset(self::$instancia)) {
            $miclase         = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }

    public function mostrarLimiteCursosControl()
    {
        $mostrar = ModeloCurso::mostrarLimiteCursosModel();
        return $mostrar;
    }

    public function mostrarTodosCursosControl()
    {
        $mostrar = ModeloCurso::mostrarTodosCursosModel();
        return $mostrar;
    }

    public function mostrarGruposCursosControl()
    {
        return ModeloCurso::mostrarGruposCursosModel();
    }

    public function mostrarDatosCursoProfesorControl($id)
    {
        $mostrar = ModeloCurso::mostrarDatosCursoProfesorModel($id);
        return $mostrar;
    }

    public function mostrarPeriodosConfiguradosCursoControl($id)
    {
        $mostrar = ModeloCurso::mostrarPeriodosConfiguradosCursoModel($id);
        return $mostrar;
    }

    public function obtenerCursosDeGrupoCursoControl($id_grupo)
    {
        return ModeloCurso::obtenerCursosDeGrupoCursoModel($id_grupo);
    }

    public function mostrarPeriodosCursoControl($id)
    {
        $mostrar = ModeloCurso::mostrarPeriodosCursoModel($id);
        return $mostrar;
    }

    public function mostrarInformacionCursoControl($id)
    {
        $mostrar = ModeloCurso::mostrarInformacionCursoModel($id);
        return $mostrar;
    }

    public function mostrarPeriodoCursoControl($id)
    {
        $mostrar = ModeloCurso::mostrarPeriodoCursoModel($id);
        return $mostrar;
    }

    public function mostrarIndicadorMarcadoCursoControl($id_curso, $indicador)
    {
        $mostrar = ModeloCurso::mostrarIndicadorMarcadoCursoModel($id_curso, $indicador);
        return $mostrar;
    }

    public function mostrarDimensionesIndicadoresInformacionControl($id_periodo, $id_curso)
    {
        return ModeloCurso::mostrarDimensionesIndicadoresInformacionModel($id_periodo, $id_curso);
    }

    public function mostrarIndicadoresCursoPeriodoControl($id_curso, $periodo, $dimension)
    {
        $mostrar = ModeloCurso::mostrarIndicadoresCursoPeriodoModel($id_curso, $periodo, $dimension);
        return $mostrar;
    }

    public function agregarCursoControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            $datos = array(
                'id_log' => $_POST['id_log'],
                'nombre' => $_POST['nombre'],
            );

            $guardar = ModeloCurso::agregarCursoModel($datos);

            if ($guardar == true) {
                echo '
                <script>
                ohSnap("Guardado correctamente!", {color: "green", "duration": "1000"});
                setTimeout(recargarPagina,1050);

                function recargarPagina(){
                    window.location.replace("index");
                }
                </script>
                ';
            } else {
                echo '
                <script>
                ohSnap("Ha ocurrido un error!", {color: "red"});
                </script>
                ';
            }
        }
    }

    public function editarCursoControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            $datos = array(
                'id_log'         => $_POST['id_log'],
                'id_curso'       => $_POST['id_curso'],
                'nombre'         => $_POST['nom_curso'],
                'id_profesor'    => $_POST['profesor'],
                'periodo_actual' => $_POST['periodo_actual'],
            );

            $guardar = ModeloCurso::editarCursoModel($datos);

            if ($guardar == true) {
                echo '
                <script>
                ohSnap("Guardado correctamente!", {color: "green", "duration": "1000"});
                setTimeout(recargarPagina,1050);

                function recargarPagina(){
                    window.location.replace("detalles?codigo=' . base64_encode($_POST['id_curso']) . '");
                }
                </script>
                ';
            } else {
                echo '
                <script>
                ohSnap("Ha ocurrido un error!", {color: "red"});
                </script>
                ';
            }
        }
    }

    public function configuracionCursoControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            if (!empty($_POST['indicador'])) {

                $desactivar_indicadores = ModeloCurso::inactivarIndicadoresCursoModel($_POST['id_curso']);

                if ($desactivar_indicadores == false) {
                    echo 'No se pudo hacer la desactivación de los indicadores';
                }

                //$eliminar_indicadores = ModeloCurso::eliminarIndicadoresConfiguracionModel($_POST['id_curso'], $_POST['periodo']);

                if ($desactivar_indicadores == true) {

                    $array_indicador = array();
                    $array_indicador = $_POST['indicador'];

                    $it = new MultipleIterator();
                    $it->attachIterator(new ArrayIterator($array_indicador));

                    foreach ($it as $dato) {

                        $array_dimension = explode(",", $dato[0]);

                        $datos = array(
                            'id_log'       => $_POST['id_log'],
                            'id_curso'     => $_POST['id_curso'],
                            'id_dimension' => $array_dimension[1],
                            'id_periodo'   => $_POST['periodo'],
                            'id_indicador' => $array_dimension[0],
                            'activo'       => 1
                        );

                        $guardar = ModeloCurso::actualizarEstadoDeIndicadoresModel($datos);
                        if ($guardar == false) {
                            echo 'No se pudo actualizar el estado del indicador con ID: ' . $datos['id_indicador'] .'!';
                            return;
                        }
                    }

                    if ($guardar == true) {
                        echo '
                        <script>
                        ohSnap("Guardado correctamente!", {color: "green", "duration": "1000"});
                        setTimeout(recargarPagina,1050);

                        function recargarPagina(){
                            window.location.replace("detalles?codigo=' . base64_encode($_POST['id_curso']) . '");
                        }
                        </script>
                        ';
                    } else {
                        echo '
                        <script>
                        ohSnap("Ha ocurrido un error!", {color: "red"});
                        </script>
                        ';
                    }
                }
            } else {
                echo '
                <script>
                ohSnap("Seleccione al menos 1 indicador!", {color: "red"});
                </script>
                ';
            }
        }
    }

    public function agregarIndicadoresConfiguracionCursoControl()
    {
        if (isset($_POST['generar'])) {

            if (empty($_POST['indicador'])) {
                echo "Debe seleccionar al menos un indicador";
                return;
            }

            $grupo_curso = $_POST['grupo_curso'];
            $id_periodo = $_POST['id_periodo'];
            $id_log = $_POST['id_log'];
            $activo = $_POST['activo'];
            $indicadores = $_POST['indicador']; // Array con indicadores y dimensiones!

            $cursos = ModeloCurso::obtenerCursosDeGrupoCursoModel($grupo_curso);

            if ($cursos == 0) {
                echo "No hay cursos disponibles";
                return;
            }

            foreach ($cursos as $curso) {
                $id_curso = $curso['id'];
                $eliminar_registros = ModeloCurso::eliminarIndicadoresConfiguracionModel($id_curso, $id_periodo);

                if ($eliminar_registros == false) {
                    echo 'No se pudieron eliminar los registros existentes';
                    return;
                }

                foreach ($indicadores as $valor) {
                    list($id_indicador, $id_dimension) = explode(',', $valor);

                    $datos = array(
                        'id_curso' => $id_curso,
                        'id_periodo' => $id_periodo,
                        'id_dimension' => $id_dimension,
                        'id_indicador' => $id_indicador,
                        'id_log' => $id_log,
                        'activo' => $activo
                    );

                    $generar = ModeloCurso::agregarIndicadoresConfiguracionCursoModelo($datos);

                    if ($generar == false) {
                        echo json_encode(array('error' => 'Error al agregar el indicador', 'datos' => $datos));
                        return;
                    }
                }

                echo '
                        <script>
                            ohSnap("Guardado correctamente!", {color: "green", "duration": "1000"});
                            setTimeout(recargarPagina,1050);

                            function recargarPagina(){
                                window.location.replace("administrar");
                            }
                        </script>
                        ';
            }
        }
    }
}
