<?php
date_default_timezone_set('America/Bogota');
require_once MODELO_PATH . 'observador' . DS . 'ModeloObservador.php';

class ControlObservador
{

    private static $instancia;

    public static function singleton_observador()
    {
        if (!isset(self::$instancia)) {
            $miclase         = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }

    public function mostrarObservacionesControl($id)
    {
        $mostrar = ModeloObservador::mostrarObservacionesModel($id);
        return $mostrar;
    }

     public function historialComentariosControl($id)
    {
        $mostrar = ModeloObservador::historialComentariosModel($id);
        return $mostrar;
    }

    public function agregarObservacionControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            $datos = array(
                'id_estudiante' => $_POST['id_estudiante'],
                'observacion'   => $_POST['observacion'],
                'id_log'        => $_POST['id_log'],
            );

            $guardar = ModeloObservador::agregarObservacionModel($datos);

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

    public function comentariosObservadorControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {
            $datos = array(
                'id_log'        => $_POST['id_log'],
                'id_observador' => $_POST['id_observador'],
                'comentario'    => $_POST['comentario'],
            );

            $guardar = ModeloObservador::comentariosObservadorModel($datos);

            if ($guardar == true) {
                echo '
                <script>
                ohSnap("Guardado correctamente!", {color: "green", "duration": "1000"});
                setTimeout(recargarPagina,1050);

                function recargarPagina(){
                    window.location.replace("historial?estudiante=' . base64_encode($_POST['id_estudiante']) . '");
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
