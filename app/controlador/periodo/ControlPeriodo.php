<?php
date_default_timezone_set('America/Bogota');
require_once MODELO_PATH . 'periodo' . DS . 'ModeloPeriodo.php';
require_once CONTROL_PATH . 'hash.php';

class ControlPeriodo
{

    private static $instancia;

    public static function singleton_periodo()
    {
        if (!isset(self::$instancia)) {
            $miclase         = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }

    public function mostrarLimitePeriodosControl()
    {
        $mostrar = ModeloPeriodo::mostrarLimitePeriodosModel();
        return $mostrar;
    }

    public function mostrarTodosPeriodosControl()
    {
        $mostrar = ModeloPeriodo::mostrarTodosPeriodosModel();
        return $mostrar;
    }

    public function mostrarPeriodosAnioActivoControl()
    {
        $mostrar = ModeloPeriodo::mostrarPeriodosAnioActivoModel();
        return $mostrar;
    }

    public function mostrarTodosAniosControl()
    {
        $mostrar = ModeloPeriodo::mostrarTodosAniosModel();
        return $mostrar;
    }

    public function agregarPeriodoControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            $datos = array(
                'id_log'       => $_POST['id_log'],
                'numero'       => $_POST['numero'],
                'anio'         => $_POST['anio'],
                'fecha_inicio' => $_POST['fecha_inicio'],
                'fecha_fin'    => $_POST['fecha_fin'],
            );

            $guardar = ModeloPeriodo::agregarPeriodoModel($datos);

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

}
