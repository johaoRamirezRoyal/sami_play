<?php
date_default_timezone_set('America/Bogota');
require_once MODELO_PATH . 'asistencia' . DS . 'ModeloAsistenciaCron.php';
require_once CONTROL_PATH . 'numeros.php';

class ControlAsistencia
{

    private static $instancia;

    public static function singleton_asistencia()
    {
        if (!isset(self::$instancia)) {
            $miclase         = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }

    public function mostrarAsistenciaListadoControl()
    {
        $consulta = ModeloAsistenciaCron::comandoSQL();
        $mostrar  = ModeloAsistenciaCron::mostrarAsistenciaListadoModel();
        return $mostrar;
    }

    public function buscarUsuarioAsistenciaControl($buscar)
    {
        $consulta = ModeloAsistenciaCron::comandoSQL();
        $mostrar  = ModeloAsistenciaCron::buscarUsuarioAsistenciaModel($buscar);
        return $mostrar;
    }

    public function mostrarMensajesLimitesControl()
    {
        $consulta = ModeloAsistenciaCron::comandoSQL();
        $mostrar  = ModeloAsistenciaCron::mostrarMensajesLimitesModel();
        return $mostrar;
    }

    public function mensajeDiaAsistenciaControl()
    {
        $consulta = ModeloAsistenciaCron::comandoSQL();
        $mostrar  = ModeloAsistenciaCron::mensajeDiaAsistenciaModel();
        return $mostrar;
    }

    public function mensajesGeneralesLimiteControl()
    {
        $consulta = ModeloAsistenciaCron::comandoSQL();
        $mostrar  = ModeloAsistenciaCron::mensajesGeneralesLimiteModel();
        return $mostrar;
    }

    public function mensajeGeneralActivoControl()
    {
        $consulta = ModeloAsistenciaCron::comandoSQL();
        $mostrar  = ModeloAsistenciaCron::mensajeGeneralActivoModel();
        return $mostrar;
    }

    public function tomarAsistenciaControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            $url = ($_POST['url'] == 1) ? 'curso' : 'historial';

            $array_user = array();
            $array_user = $_POST['id_user'];

            $array_asistencia = array();
            $array_asistencia = $_POST['id_asistencia'];

            $array_fecha = array();
            $array_fecha = $_POST['fecha_asistencia'];

            $it = new MultipleIterator();
            $it->attachIterator(new ArrayIterator($array_user));
            $it->attachIterator(new ArrayIterator($array_asistencia));
            $it->attachIterator(new ArrayIterator($array_fecha));

            foreach ($it as $dato) {

                $id         = $dato[0];
                $estado     = $_POST['asistencia_' . $dato[0]];
                $log        = $_POST['id_log'];
                $fecha      = $dato[2];
                $asistencia = (empty($dato[1])) ? '0' : $dato[1];

                $consulta = ModeloAsistenciaCron::comandoSQL();
                $guardar  = ModeloAsistenciaCron::tomarAsistenciaCursoModel($id, $estado, $log, $fecha, $asistencia);

            }

            if ($guardar == true) {
                echo '
                <script>
                ohSnap("Guardado correctamente!", {color: "green", "duration": "1000"});
                setTimeout(recargarPagina,1050);

                function recargarPagina(){
                    window.location.replace("' . $url . '");
                }
                </script>
                ';
            } else {
                echo '
                <script>
                ohSnap("Ha ocurrido un error", {color: "red"});
                </script>
                ';
            }

        }
    }

    public function buscarEstudiantesAsistenciaControl($datos)
    {
        $curso = (!empty($datos['curso'])) ? ' AND u.curso = ' . $datos['curso'] : '';
        $fecha = (!empty($datos['fecha'])) ? ' AND asi.fechareg LIKE "%' . $datos['fecha'] . '%"' : '';

        $datos = array('curso' => $curso, 'fecha' => $datos['fecha'], 'buscar' => $datos['buscar'], 'fecha_buscar' => $fecha);

        $consulta = ModeloAsistenciaCron::comandoSQL();
        $mostrar  = ModeloAsistenciaCron::buscarEstudiantesAsistenciaModel($datos);
        return $mostrar;
    }

    public function ultimasAsistenciasTomadasControl()
    {
        $consulta = ModeloAsistenciaCron::comandoSQL();
        $mostrar  = ModeloAsistenciaCron::ultimasAsistenciasTomadasModel();
        return $mostrar;
    }

    public function buscarUsuarioAsistenciaGestionControl($datos)
    {

        $perfil           = ($datos['perfil'] == '') ? '' : ' AND u.perfil = ' . $datos['perfil'];
        $fecha_asistencia = ($datos['fecha'] == '') ? '' : ' AND a.fecha_asistencia = "' . $datos['fecha'] . '"';

        $datos = array('buscar' => $datos['buscar'], 'perfil' => $perfil, 'fecha' => $fecha_asistencia);

        $consulta = ModeloAsistenciaCron::comandoSQL();
        $mostrar  = ModeloAsistenciaCron::buscarUsuarioAsistenciaGestionModel($datos);
        return $mostrar;
    }

    public function validarTokenControl($token)
    {
        $consulta = ModeloAsistenciaCron::comandoSQL();
        $mostrar  = ModeloAsistenciaCron::validarTokenModel($token);

        $dia_hoy = date("w");

        if ($mostrar['dia'] == $dia_hoy) {
            $rs = 'ok';
        } else {
            $rs = 'No';
        }

        return $rs;
    }

    public function validarDocumentoControl($documento)
    {
        $consulta = ModeloAsistenciaCron::comandoSQL();
        $mostrar  = ModeloAsistenciaCron::validarDocumentoModel($documento);

        if ($mostrar['id_user'] != '') {
            $datos = array(
                'id_user'   => $mostrar['id_user'],
                'fecha_hoy' => date("Y-m-d"),
                'hora_hoy'  => date("H:i:s"),
            );

            $validar_asistencia_hoy = ModeloAsistenciaCron::validarAsistenciaHoyModel($datos);

            if ($validar_asistencia_hoy['id'] == '') {

                $guardar = ModeloAsistenciaCron::TomarAsistenciaModel($datos);

                if ($guardar == true) {
                    $rs = array('nivel' => 0, 'resultado' => 'ok');
                } else {
                    $rs = array('nivel' => 0, 'resultado' => 'No');
                }
            } else {
                $rs = array('nivel' => 0, 'resultado' => 'tomada');
            }

        } else {
            $rs = array('nivel' => 0, 'resultado' => 'No');
        }

        return $rs;

    }

    public function mensajeAsistenciaControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            $nombre_archivo = '';

            if (isset($_FILES['imagen']['name']) && !empty($_FILES['imagen']['name'])) {

                $nom_arch   = $_FILES['imagen']['name'];
                $ext_arch   = pathinfo($nom_arch, PATHINFO_EXTENSION);
                $fecha_arch = date('YmdHis');

                $nombre_archivo = strtolower(md5($_POST['id_log'] . '_' . $fecha_arch)) . '.' . $ext_arch;

                $carp_destino = PUBLIC_PATH_ARCH . 'upload' . DS;
                $ruta_img     = $carp_destino . $nombre_archivo;

                if ($ext_arch == 'png' || $ext_arch == 'jpeg') {
                    $compressed = compressImage($_FILES['imagen']['tmp_name'], $ruta_img, 70);
                } else {

                    if (is_uploaded_file($_FILES['imagen']['tmp_name'])) {
                        move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_img);
                    }
                }
            }

            $datos = array(
                'id_log'  => $_POST['id_log'],
                'fecha'   => $_POST['fecha_programa'],
                'imagen'  => $nombre_archivo,
                'mensaje' => $_POST['mensaje'],
                'nivel'   => $_POST['nivel'],
                'titulo'  => $_POST['titulo'],
            );

            $guardar = ModeloAsistenciaCron::mensajeAsistenciaModel($datos);

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
                ohSnap("Ha ocurrido un error", {color: "red"});
                </script>
                ';
            }

        }
    }

    public function mensajeGeneralControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            $nombre_archivo = '';

            if (isset($_FILES['imagen']['name']) && !empty($_FILES['imagen']['name'])) {

                $nom_arch   = $_FILES['imagen']['name'];
                $ext_arch   = pathinfo($nom_arch, PATHINFO_EXTENSION);
                $fecha_arch = date('YmdHis');

                $nombre_archivo = strtolower(md5($_POST['id_log'] . '_' . $fecha_arch)) . '.' . $ext_arch;

                $carp_destino = PUBLIC_PATH_ARCH . 'upload' . DS;
                $ruta_img     = $carp_destino . $nombre_archivo;

                if ($ext_arch == 'png' || $ext_arch == 'jpeg') {
                    $compressed = compressImage($_FILES['imagen']['tmp_name'], $ruta_img, 70);
                } else {

                    if (is_uploaded_file($_FILES['imagen']['tmp_name'])) {
                        move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_img);
                    }
                }
            }

            $datos = array(
                'id_log'  => $_POST['id_log'],
                'imagen'  => $nombre_archivo,
                'mensaje' => $_POST['mensaje_general'],
                'titulo'  => $_POST['titulo'],
            );

            $inactivar_mensaje_anterior = ModeloAsistenciaCron::inactivarUltimoMensajeGeneral();

            if ($inactivar_mensaje_anterior) {
                $guardar = ModeloAsistenciaCron::mensajeGeneralModel($datos);
            } else {
                $guardar = ModeloAsistenciaCron::mensajeGeneralModel($datos);
            }

            if ($guardar == true) {
                echo '
                <script>
                ohSnap("Guardado correctamente!", {color: "green", "duration": "1000"});
                setTimeout(recargarPagina,1050);

                function recargarPagina(){
                    window.location.replace("mensaje");
                }
                </script>
                ';
            } else {
                echo '
                <script>
                ohSnap("Ha ocurrido un error", {color: "red"});
                </script>
                ';
            }

        }
    }
}
