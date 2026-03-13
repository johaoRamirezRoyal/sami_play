<?php
date_default_timezone_set('America/Bogota');
require_once MODELO_PATH . 'pension' . DS . 'ModeloPension.php';

class ControlPension
{

    private static $instancia;

    public static function singleton_pension()
    {
        if (!isset(self::$instancia)) {
            $miclase         = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }

    public function mostrarMesesPensionControl()
    {
        $mostrar = ModeloPension::mostrarMesesPensionModel();
        return $mostrar;
    }

    public function mostrarLimitePensionControl()
    {
        $mostrar = ModeloPension::mostrarLimitePensionModel();
        return $mostrar;
    }

    public function mostrarPensionesPagasEstudianteControl($id)
    {
        $mostrar = ModeloPension::mostrarPensionesPagasEstudianteModel($id);
        return $mostrar;
    }

    public function agregarPensionControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            $array_mes = array();
            $array_mes = $_POST['mes'];

            $it = new MultipleIterator();
            $it->attachIterator(new ArrayIterator($array_mes));

            foreach ($it as $dato) {

                $datos = array(
                    'id_log'     => $_POST['id_log'],
                    'estudiante' => $_POST['estudiante'],
                    'fecha_pago' => $_POST['fecha_pago'],
                    'mes'        => $dato[0],
                    'anio'       => $_POST['anio'],
                );

                $guardar = ModeloPension::agregarPensionModel($datos);
            }

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
