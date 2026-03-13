<?php
date_default_timezone_set('America/Bogota');
require_once MODELO_PATH . 'perfil' . DS . 'ModeloPerfil.php';
require_once CONTROL_PATH . 'hash.php';

class ControlPerfil
{

    private static $instancia;

    public static function singleton_perfil()
    {
        if (!isset(self::$instancia)) {
            $miclase         = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }

    public function mostrarDatosPerfilControl($id)
    {
        $datos = ModeloPerfil::mostrarDatosPerfilModel($id);
        return $datos;
    }

    public function mostrarPerfilesControl()
    {
        $datos = ModeloPerfil::mostrarPerfilesModel();
        return $datos;
    }

    public function mostrarLimitesPerfilesControl()
    {
        $datos = ModeloPerfil::mostrarLimitesPerfilesModel();
        return $datos;
    }

    public function buscarPerfilControl($buscar)
    {
        $datos = ModeloPerfil::buscarPerfilModel($buscar);
        return $datos;
    }

    public function editarPerfilControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_user']) &&
            !empty($_POST['id_user'])
        ) {

            $pass      = $_POST['password'];
            $conf_pass = $_POST['conf_password'];

            $clavecifrada = ($pass == $conf_pass && $pass != "" && $conf_pass != "") ? Hash::hashpass($conf_pass) : $_POST['pass_old'];

            $nombre_archivo = $_POST['foto_perfil_ant'];

            if (isset($_FILES['foto']['name']) && !empty($_FILES['foto']['name'])) {

                $nom_arch   = $_FILES['foto']['name'];
                $ext_arch   = pathinfo($nom_arch, PATHINFO_EXTENSION);
                $fecha_arch = date('YmdHis');

                $nombre_archivo = strtolower(md5($_POST['id_log'] . '_' . $_POST['nombre'] . $fecha_arch)) . '.' . $ext_arch;

                $carp_destino = PUBLIC_PATH_ARCH . 'upload' . DS;
                $ruta_img     = $carp_destino . $nombre_archivo;

                if (is_uploaded_file($_FILES['foto']['tmp_name'])) {
                    move_uploaded_file($_FILES['foto']['tmp_name'], $ruta_img);
                }
            }

            $datos = array(
                'id_user'     => $_POST['id_user'],
                'documento'   => $_POST['documento'],
                'nombre'      => $_POST['nombre'],
                'apellido'    => $_POST['apellido'],
                'correo'      => $_POST['correo'],
                'telefono'    => $_POST['telefono'],
                'usuario'     => $_POST['usuario'],
                'pass'        => $clavecifrada,
                'perfil'      => $_POST['perfil'],
                'foto_perfil' => $nombre_archivo,
            );

            $guardar = ModeloPerfil::editarPerfilModel($datos);

            if ($guardar['guardar'] == true) {
                echo '
                <script>
                ohSnap("Modificado correctamente!", {color: "green", "duration": "1000"});
                setTimeout(recargarPagina,1050);

                function recargarPagina(){
                    window.location.replace("' . $_POST['url'] . '");
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
        } else {
            echo '
            <script>
            ohSnap("Ha ocurrido un error!", {color: "red"});
            </script>
            ';
        }
    }

}
