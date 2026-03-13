<?php

date_default_timezone_set('America/Bogota');

require_once MODELO_PATH . 'permisos' . DS . 'ModeloPermisos.php';



class ControlPermisos

{



    private static $instancia;



    public static function singleton_permisos()

    {

        if (!isset(self::$instancia)) {

            $miclase         = __CLASS__;

            self::$instancia = new $miclase;

        }

        return self::$instancia;

    }



    public function permisosUsuarioControl($perfil, $opcion)

    {

        $datos = ModeloPermisos::permisosUsuarioModel($perfil, $opcion);

        $rs    = ($datos['id'] != '') ? true : false;

        return $rs;

    }

    public function permisosUsuarioControlTramites($id_opcion, $perfil)

    {

        $mostrar = ModeloPermisos::permisosUsuarioModelTramites($id_opcion, $perfil);

        return $mostrar;

    }



    public function mostrarModulosControl()

    {

        $mostrar = ModeloPermisos::mostrarModulosModel();

        return $mostrar;

    }



    public function opcionesActivasPerfilControl($perfil, $opcion)

    {

        $mostrar = ModeloPermisos::opcionesActivasPerfilModel($perfil, $opcion);

        return $mostrar;

    }



    public function inactivarPermisoControl()

    {

        if (

            $_SERVER['REQUEST_METHOD'] == 'POST' &&

            isset($_POST['id_opcion']) &&

            !empty($_POST['id_opcion'])

        ) {

            $datos = array(

                'id_opcion' => $_POST['id_opcion'],

                'id_perfil' => $_POST['id_perfil'],

                'id_log'    => $_POST['id_log'],

            );



            $inactivar = ModeloPermisos::inactivarPermisoModel($datos);

            $rs        = ($inactivar == true) ? 'ok' : 'no';

            return $rs;

        }

    }



    public function activarPermisoControl()

    {

        if (

            $_SERVER['REQUEST_METHOD'] == 'POST' &&

            isset($_POST['id_opcion']) &&

            !empty($_POST['id_opcion'])

        ) {

            $datos = array(

                'id_opcion' => $_POST['id_opcion'],

                'id_perfil' => $_POST['id_perfil'],

                'id_log'    => $_POST['id_log'],

            );



            $inactivar = ModeloPermisos::inactivarPermisoModel($datos);

            $activar   = ModeloPermisos::activarPermisoModel($datos);

            $rs        = ($activar == true) ? 'ok' : 'no';

            return $rs;

        }

    }

}

