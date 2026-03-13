<?php
date_default_timezone_set('America/Bogota');
require_once MODELO_PATH . 'dimension' . DS . 'ModeloDimension.php';
require_once CONTROL_PATH . 'numeros.php';

class ControlDimension
{

    private static $instancia;

    public static function singleton_dimension()
    {
        if (!isset(self::$instancia)) {
            $miclase         = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }

    public function mostrarLimiteDimensionControl()
    {
        $mostrar = ModeloDimension::mostrarLimiteDimensionModel();
        return $mostrar;
    }

    public function mostrarTodasDimensionControl()
    {
        $mostrar = ModeloDimension::mostrarTodasDimensionModel();
        return $mostrar;
    }

    public function mostrarGruposControl()
    {
        $mostrar = ModeloDimension::mostrarGruposModel();
        return $mostrar;
    }

    public function mostrarDimensionesCursoControl($id)
    {
        $mostrar = ModeloDimension::mostrarDimensionesCursoModel($id);
        return $mostrar;
    }

    public function buscarDimensionControl($datos)
    {
        $mostrar = ModeloDimension::buscarDimensionModel($datos);
        return $mostrar;
    }

    public function agregarDimensionControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            $nombre_archivo = '';

            if (isset($_FILES['foto']['name']) && !empty($_FILES['foto']['name'])) {
                $nombre_archivo = guardarArchivo($_FILES['foto']);
            }

            $datos = array(
                'id_log'      => $_POST['id_log'],
                'nombre'      => $_POST['nombre'],
                'observacion' => $_POST['observacion'],
                'foto'        => $nombre_archivo,
            );

            $guardar = ModeloDimension::agregarDimensionModel($datos);

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

    public function editarDimensionControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            $nombre_archivo = $_POST['foto_antigua'];

            if (isset($_FILES['foto']['name']) && !empty($_FILES['foto']['name'])) {
                $eliminar = eliminarArchivo($nombre_archivo);
                if ($eliminar == true) {
                    $nombre_archivo = guardarArchivo($_FILES['foto']);
                }
            }

            $datos = array(
                'id_log'      => $_POST['id_log'],
                'id'          => $_POST['id_dimension'],
                'nombre'      => $_POST['nombre_edit'],
                'observacion' => $_POST['observacion_edit'],
                'foto'        => $nombre_archivo,
            );

            $guardar = ModeloDimension::editarDimensionModel($datos);

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

    public function inactivarDimensionControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id']) &&
            !empty($_POST['id'])
        ) {

            $datos = array(
                'id_log' => $_POST['log'],
                'id'     => $_POST['id'],
            );

            $inactivar = ModeloDimension::inactivarDimensionModel($datos);
            $result    = ($inactivar == true) ? 'ok' : 'no';
            return $result;
        }
    }

    public function activarDimensionControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id']) &&
            !empty($_POST['id'])
        ) {

            $datos = array(
                'id_log' => $_POST['log'],
                'id'     => $_POST['id'],
            );

            $activar = ModeloDimension::activarDimensionModel($datos);
            $result  = ($activar == true) ? 'ok' : 'no';
            return $result;
        }
    }

    /*-------------------INDICADORES--------------------*/

    public function mostrarLimiteIndicadorControl()
    {
        $mostrar = ModeloDimension::mostrarLimiteIndicadorModel();
        return $mostrar;
    }

    public function mostrarIndicadoresDimensionControl($id, $curso)
    {
        $mostrar = ModeloDimension::mostrarIndicadoresDimensionModel($id, $curso);
        return $mostrar;
    }

    public function buscarIndicadoresControl($datos)
    {
        $dimension = (!empty($datos['dimension'])) ? ' AND dm.id = ' . $datos['dimension'] : '';

        $datos = array('dimension' => $dimension, 'buscar' => $datos['buscar']);

        $mostrar = ModeloDimension::buscarIndicadoresModel($datos);
        return $mostrar;
    }

    public function agregarIndicadorControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            $nombre = ModeloDimension::buscarIndicadorNombreModel($_POST['nom_indicador']);

            if (empty($nombre['id'])) {

                $datos = array(
                    'id_log'       => $_POST['id_log'],
                    'nombre'       => $_POST['nom_indicador'],
                    'id_dimension' => $_POST['dimension'],
                    'grupo'        => $_POST['grupo'],
                );

                $guardar = ModeloDimension::agregarIndicadorModel($datos);

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
                    ohSnap("Error al guardar!", {color: "red"});
                    </script>
                    ';
                }
            } else {
                echo '
                <script>
                ohSnap("Indicador Duplicado!", {color: "red"});
                </script>
                ';
            }

        }
    }

    public function editarIndicadorControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {
            $datos = array(
                'id_log'       => $_POST['id_log'],
                'id_indicador' => $_POST['id_indicador'],
                'nombre'       => $_POST['nombre'],
                'dimension'    => $_POST['dimension'],
                'grupo'        => $_POST['grupo'],
            );

            $guardar = ModeloDimension::editarIndicadorModel($datos);

            if ($guardar == true) {
                echo '
                <script>
                ohSnap("Modificado correctamente!", {color: "green", "duration": "1000"});
                setTimeout(recargarPagina,1050);

                function recargarPagina(){
                    window.location.replace("index");
                }
                </script>
                ';
            } else {
                echo '
                <script>
                ohSnap("Error al modificar!", {color: "red"});
                </script>
                ';
            }
        }
    }
}
