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

                $eliminar_indicadores = ModeloCurso::inactivarIndicadoresCursoModel($_POST['id_curso']);

                if ($eliminar_indicadores == true) {

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
                        );

                        $guardar = ModeloCurso::configuracionCursoModel($datos);
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
}
