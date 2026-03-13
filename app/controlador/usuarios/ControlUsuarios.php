<?php

date_default_timezone_set('America/Bogota');

require_once MODELO_PATH . 'usuarios' . DS . 'ModeloUsuarios.php';

require_once CONTROL_PATH . 'hash.php';

require_once CONTROL_PATH . 'numeros.php';



class ControlUsuarios

{


    public function mostrarTodosUsuariosInventarioControl()

    {

        $consulta = ModeloUsuarios::comandoSQL();

        $mostrar  = ModeloUsuarios::mostrarTodosUsuariosInventarioModel();

        return $mostrar;

    }



    private static $instancia;



    public static function singleton_usuario()

    {

        if (!isset(self::$instancia)) {

            $miclase         = __CLASS__;

            self::$instancia = new $miclase;

        }

        return self::$instancia;

    }



    public function mostrarUsuariosControl()

    {

        $mostrar = ModeloUsuarios::mostrarUsuariosModel();

        return $mostrar;

    }



    public function mostrarTodosUsuariosControl()

    {

        $mostrar = ModeloUsuarios::mostrarTodosUsuariosModel();

        return $mostrar;

    }

    //Cambio reportes
    public function validarFirmaIdControl($id)
    {
        $consulta = ModeloUsuarios::comandoSQL();
        $mostrar  = ModeloUsuarios::validarFirmaModel($id);
        return $mostrar;
    }




    public function mostrarTodosEstudiantesControl()

    {

        $mostrar = ModeloUsuarios::mostrarTodosEstudiantesModel();

        return $mostrar;

    }



    public function mostrarEstudiantesControl()

    {

        $mostrar = ModeloUsuarios::mostrarEstudiantesModel();

        return $mostrar;

    }



    public function mostrarDatosEstudiantesControl($id)

    {

        $mostrar = ModeloUsuarios::mostrarDatosEstudiantesModel($id);

        return $mostrar;

    }



    public function mostrarEstudiantesCursoControl($curso)

    {

        $mostrar = ModeloUsuarios::mostrarEstudiantesCursoModel($curso);

        return $mostrar;

    }



    public function mostrarUsuariosBuscarControl($buscar)

    {

        $mostrar = ModeloUsuarios::mostrarUsuariosBuscarModel($buscar);

        return $mostrar;

    }



    public function mostrarEstudiantesBuscarControl($datos)

    {
        $filtro = (isset($datos['filtro']) && $datos['filtro'] != '') ? " AND CONCAT(u.nombre, ' ' , u.apellido, ' ', u.documento, ' ', c.nombre) LIKE '%" . $datos['filtro'] . "%' " : "";
        $curso = (isset($datos['curso']) && $datos['curso'] != null) ? " AND c.id = " . $datos['curso'] : '';

        $datos_filtrado = array('filtro' => $filtro, 'curso' => $curso);
        $mostrar = ModeloUsuarios::mostrarEstudiantesBuscarModel($datos_filtrado);

        return $mostrar;

    }



    public function cantidadAsistenciaControl($id, $fecha_inicio, $fecha_fin)

    {

        $mostrar = ModeloUsuarios::cantidadAsistenciaModel($id, $fecha_inicio, $fecha_fin);

        return $mostrar;

    }



    public function agregarUsuarioControl()

    {

        if (

            $_SERVER['REQUEST_METHOD'] == 'POST' &&

            isset($_POST['id_log']) &&

            !empty($_POST['id_log'])

        ) {



            $pass           = Hash::hashpass('123456789');

            $nombre_archivo = '';



            if (isset($_FILES['foto']['name']) && !empty($_FILES['foto']['name'])) {

                $nombre_archivo = guardarArchivo($_FILES['foto']);

            }



            $datos = array(

                'id_log'      => $_POST['id_log'],

                'documento'   => $_POST['documento'],

                'nombre'      => $_POST['nombre'],

                'apellido'    => $_POST['apellido'],

                'correo'      => $_POST['correo'],

                'telefono'    => $_POST['telefono'],

                'perfil'      => $_POST['perfil'],

                'user'        => $_POST['user'],

                'pass'        => $pass,

                'foto_perfil' => $nombre_archivo,

            );



            $guardar = ModeloUsuarios::agregarUsuarioModel($datos);



            if ($guardar['guardar'] == true) {

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



    public function editarUsuarioControl()

    {

        if (

            $_SERVER['REQUEST_METHOD'] == 'POST' &&

            isset($_POST['id_log']) &&

            !empty($_POST['id_log'])

        ) {



            $nombre_archivo = $_POST['foto_perfil_ant'];



            if (isset($_FILES['foto']['name']) && !empty($_FILES['foto']['name'])) {

                $eliminar = eliminarArchivo($nombre_archivo);

                if ($eliminar == true) {

                    $nombre_archivo = guardarArchivo($_FILES['foto']);

                }

            }



            $datos = array(

                'id_user'     => $_POST['id_user'],

                'id_log'      => $_POST['id_log'],

                'nombre'      => $_POST['nombre_edit'],

                'apellido'    => $_POST['apellido_edit'],

                'correo'      => $_POST['correo_edit'],

                'telefono'    => $_POST['telefono_edit'],

                'perfil'      => $_POST['perfil_edit'],

                'foto_perfil' => $nombre_archivo,

            );



            $guardar = ModeloUsuarios::editarUsuarioModel($datos);



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

                ohSnap("Ha ocurrido un error!", {color: "red"});

                </script>

                ';

            }

        }

    }



    public function passwordNewControl()

    {

        if (

            $_SERVER['REQUEST_METHOD'] == 'POST' &&

            isset($_POST['id']) &&

            !empty($_POST['id'])

        ) {

            $pass      = Hash::hashpass('play123@');

            $inactivar = ModeloUsuarios::passwordNewModel($_POST['id'], $pass);

            $mensaje   = ($inactivar == true) ? 'ok' : 'no';

            return $mensaje;

        }

    }



    public function inactivarUsuarioControl()

    {

        if (

            $_SERVER['REQUEST_METHOD'] == 'POST' &&

            isset($_POST['id']) &&

            !empty($_POST['id'])

        ) {

            $inactivar = ModeloUsuarios::inactivarUsuarioModel($_POST['id']);

            $mensaje   = ($inactivar == true) ? 'ok' : 'no';

            return $mensaje;

        }

    }



    public function activarUsuarioControl()

    {

        if (

            $_SERVER['REQUEST_METHOD'] == 'POST' &&

            isset($_POST['id']) &&

            !empty($_POST['id'])

        ) {

            $activar = ModeloUsuarios::activarUsuarioModel($_POST['id']);

            $mensaje = ($activar == true) ? 'ok' : 'no';

            return $mensaje;

        }

    }



    public function consultarDocumentoControl()

    {

        if (

            $_SERVER['REQUEST_METHOD'] == 'POST' &&

            isset($_POST['id']) &&

            !empty($_POST['id'])

        ) {

            $datos   = ModeloUsuarios::consultarDocumentoModel($_POST['id']);

            $mensaje = ($datos['id_user'] == '') ? 'ok' : 'no';

            return $mensaje;

        }

    }



    /*--------------------------------------*/

    public function agregarEstudianteControl()

    {

        if (

            $_SERVER['REQUEST_METHOD'] == 'POST' &&

            isset($_POST['id_log']) &&

            !empty($_POST['id_log'])

        ) {



            $pass           = Hash::hashpass($_POST['documento']);

            $nombre_archivo = '';



            if (isset($_FILES['foto']['name']) && !empty($_FILES['foto']['name'])) {



                $nom_arch   = $_FILES['foto']['name'];

                $ext_arch   = pathinfo($nom_arch, PATHINFO_EXTENSION);

                $fecha_arch = date('YmdHis');



                $nombre_archivo = strtolower(md5($_POST['documento'] . '_' . $_POST['nombre'] . $fecha_arch)) . '.' . $ext_arch;



                $carp_destino = PUBLIC_PATH_ARCH . 'upload' . DS;

                $ruta_img     = $carp_destino . $nombre_archivo;



                if (is_uploaded_file($_FILES['foto']['tmp_name'])) {

                    move_uploaded_file($_FILES['foto']['tmp_name'], $ruta_img);

                }

            }



            $datos = array(

                'id_log'      => $_POST['id_log'],

                'tipo_doc'    => $_POST['tipo_doc'],

                'documento'   => $_POST['documento'],

                'nombre'      => $_POST['nombre'],

                'apellido'    => $_POST['apellido'],

                'genero'      => $_POST['genero'],

                'perfil'      => 3,

                'user'        => $_POST['documento'],

                'pass'        => $pass,

                'foto_perfil' => $nombre_archivo,

                'curso'       => $_POST['curso'],

            );



            $guardar = ModeloUsuarios::agregarEstudianteModel($datos);



            if ($guardar['guardar'] == true) {

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



    public function editarEstudianteControl()

    {

        if (

            $_SERVER['REQUEST_METHOD'] == 'POST' &&

            isset($_POST['id_log']) &&

            !empty($_POST['id_log'])

        ) {



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

                'id_log'      => $_POST['id_log'],

                'id_user'     => $_POST['id_user'],

                'nombre'      => $_POST['nombre'],

                'apellido'    => $_POST['apellido'],

                'tipo_doc'    => $_POST['tipo_doc'],

                'genero'      => $_POST['genero'],

                'foto_perfil' => $nombre_archivo,

                'curso'       => $_POST['curso'],

            );



            $guardar = ModeloUsuarios::editarEstudianteModel($datos);



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

                ohSnap("Ha ocurrido un error!", {color: "red"});

                </script>

                ';

            }

        }

    }

}

