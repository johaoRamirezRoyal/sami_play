<?php
date_default_timezone_set('America/Bogota');
require_once MODELO_PATH . 'calificacion' . DS . 'ModeloCalificacion.php';

class ControlCalificacion
{

    private static $instancia;

    public static function singleton_calificacion()
    {
        if (!isset(self::$instancia)) {
            $miclase         = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }

    public function mostrarBoletinEstudianteControl($curso, $periodo, $estudiante, $anio)
    {
        $mostrar = ModeloCalificacion::mostrarBoletinEstudianteModel($curso, $periodo, $estudiante, $anio);
        return $mostrar;
    }

    public function mostrarDatosBoletinControl($id)
    {
        $mostrar = ModeloCalificacion::mostrarDatosBoletinModel($id);
        return $mostrar;
    }

    public function mostrarBoletinesGeneradosControl($id)
    {
        $mostrar = ModeloCalificacion::mostrarBoletinesGeneradosModel($id);
        return $mostrar;
    }

    public function mostrarDimensionesBoletinControl($id)
    {
        $mostrar = ModeloCalificacion::mostrarDimensionesBoletinModel($id);
        return $mostrar;
    }

    //NUEVO
    public function obtenerNotasConIndicadoresYDimensionesControl($id_boletin)
    {
        return ModeloCalificacion::obtenerNotasConIndicadoresYDimensionesModel($id_boletin);
    }

    public function mostrarIndicadoresDimensionBoletinControl($id, $dimension)
    {
        $mostrar = ModeloCalificacion::mostrarIndicadoresDimensionBoletinModel($id, $dimension);
        return $mostrar;
    }

    public function mostrarNotaGuardadaControl($id_estudiante, $curso, $periodo, $indicador)
    {
        $mostrar = ModeloCalificacion::mostrarNotaGuardadaModel($id_estudiante, $curso, $periodo, $indicador);
        return $mostrar;
    }

    public function calificarEstudianteControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            $datos = array(
                'id_log'        => $_POST['id_log'],
                'id_profesor'   => $_POST['id_log'],
                'id_estudiante' => $_POST['id_estudiante'],
                'curso'         => $_POST['curso'],
                'periodo'       => $_POST['periodo'],
                'observacion'   => $_POST['observacion_general'],
                'ausencia'      => $_POST['ausencias'],
                'edad'          => $_POST['edad'],
            );

            $eliminar_boletin = ModeloCalificacion::eliminarBoletinEstudianteModel($datos);

            if ($eliminar_boletin) {
                $guardar = ModeloCalificacion::calificarEstudianteModel($datos);
            }

            if ($guardar['guardar'] == true) {

                $array_indicador = array();
                $array_indicador = $_POST['id_indicador'];

                $it = new MultipleIterator();
                $it->attachIterator(new ArrayIterator($array_indicador));

                foreach ($it as $dato) {

                    $array_dimension = explode(",", $dato[0]);

                    $datos_notas = array(
                        'id_log'     => $_POST['id_log'],
                        'id_boletin' => $guardar['id'],
                        'dimension'  => $array_dimension[1],
                        'indicador'  => $array_dimension[0],
                        'nota'       => $_POST['nota_' . $array_dimension[0]],
                        'comentario' => $_POST['comentario_' . $array_dimension[1]],
                    );

                    $guardar_notas = ModeloCalificacion::guardarNotasModel($datos_notas);
                }

                if ($guardar_notas == true) {
                    echo '
                    <script>
                    ohSnap("Guardado correctamente!", {color: "green", "duration": "1000"});
                    setTimeout(recargarPagina,1050);

                    function recargarPagina(){
                        window.location.replace("' . $_POST['url'] . '?estudiante=' . base64_encode($_POST['id_estudiante']) . '");
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
    }

    public function generarBoletinControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_boletin']) &&
            !empty($_POST['id_boletin'])
        ) {

            $datos = array(
                'id_boletin' => $_POST['id_boletin'],
                'generar'    => 1,
            );

            $guardar = ModeloCalificacion::generarBoletinModel($datos);

            if ($guardar == true) {
                echo '
                <script>
                ohSnap("Generado correctamente!", {color: "green", "duration": "1000"});
                setTimeout(recargarPagina,1050);

                function recargarPagina(){
                    window.location.replace("index");
                    window.open("' . BASE_URL . 'imprimir/calificacion/boletin?boletin=' . base64_encode($_POST['id_boletin']).'", "_blank");
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
}
