<?php

require_once MODELO_PATH . 'conexion.php';



class ModeloUsuarios extends conexion

{

    public static function mostrarTodosUsuariosInventarioModel()

    {

        $tabla  = 'usuarios';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT

        CONCAT(UPPER(u.nombre), ' ', UPPER(u.apellido)) AS nom_user,

        u.*,

        (SELECT p.nombre FROM perfiles p WHERE p.id = u.perfil) AS nom_perfil,

        n.nombre AS nom_nivel,

        c.nombre AS nom_curso

        FROM usuarios u

        LEFT JOIN nivel n ON n.id = u.id_nivel

        LEFT JOIN curso c ON c.id = u.id_curso

        WHERE u.perfil NOT IN(1,6,17,16) AND u.estado = 'activo' ORDER BY u.nombre ASC;";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->setFetchMode(PDO::FETCH_ASSOC);

            if ($preparado->execute()) {

                return $preparado->fetchAll();

            } else {

                return false;

            }

        } catch (PDOException $e) {

            print "Error!: " . $e->getMessage();

        }

        //$cnx->closed();

        $cnx = null;

    }

    public static function comandoSQL()

    {

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SET SQL_BIG_SELECTS=1";

        try {

            $preparado = $cnx->preparar($cmdsql);

            if ($preparado->execute()) {

                return true;

            } else {

                return false;

            }

        } catch (PDOException $e) {

            print "Error!: " . $e->getMessage();

        }

        $cnx->closed();

        $cnx = null;

    }



    public static function mostrarUsuariosModel()

    {

        $tabla  = 'usuarios';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT

        u.*,

        p.nombre AS nom_perfil

        FROM " . $tabla . " u

        LEFT JOIN perfiles p ON p.id = u.perfil

        WHERE perfil NOT IN(1,3)

        LIMIT 20;";

        try {

            $preparado = $cnx->preparar($cmdsql);

            if ($preparado->execute()) {

                return $preparado->fetchAll();

            } else {

                return false;

            }

        } catch (PDOException $e) {

            print "Error!: " . $e->getMessage();

        }

        $cnx->closed();

        $cnx = null;

    }



    public static function mostrarTodosUsuariosModel()

    {

        $tabla  = 'usuarios';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT

        u.*,
        
        concat(u.nombre, ' ', u.apellido) AS nom_user,

        p.nombre AS nom_perfil

        FROM " . $tabla . " u

        LEFT JOIN perfiles p ON p.id = u.perfil

        WHERE perfil NOT IN(1,3) AND u.activo = 1;";

        try {

            $preparado = $cnx->preparar($cmdsql);

            if ($preparado->execute()) {

                return $preparado->fetchAll();

            } else {

                return false;

            }

        } catch (PDOException $e) {

            print "Error!: " . $e->getMessage();

        }

        $cnx->closed();

        $cnx = null;

    }



    public static function mostrarTodosEstudiantesModel()

    {

        $tabla  = 'usuarios';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT

        u.*,

        p.nombre AS nom_perfil,

        c.nombre AS nom_curso

        FROM " . $tabla . " u

        LEFT JOIN perfiles p ON p.id = u.perfil

        LEFT JOIN curso c ON c.id = u.curso

        WHERE u.perfil = 3 AND u.activo = 1

        ORDER BY u.nombre ASC;";

        try {

            $preparado = $cnx->preparar($cmdsql);

            if ($preparado->execute()) {

                return $preparado->fetchAll();

            } else {

                return false;

            }

        } catch (PDOException $e) {

            print "Error!: " . $e->getMessage();

        }

        $cnx->closed();

        $cnx = null;

    }


    public static function validarFirmaModel($id)
    {
        $tabla  = 'firmas';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE * FROM " . $tabla . " WHERE id_user = :id AND activo = 1 ORDER BY id DESC LIMIT 1;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id', $id);
            $preparado->setFetchMode(PDO::FETCH_ASSOC);
            if ($preparado->execute()) {
                return $preparado->fetch();
            } else {
                return false;
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
        }
        $cnx->closed();
        $cnx = null;
    }



    public static function mostrarEstudiantesModel()

    {

        $tabla  = 'usuarios';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT

        u.*,

        p.nombre AS nom_perfil,

        c.nombre AS nom_curso

        FROM " . $tabla . " u

        LEFT JOIN perfiles p ON p.id = u.perfil

        LEFT JOIN curso c ON c.id = u.curso

        WHERE perfil = 3

        ORDER BY u.nombre ASC LIMIT 20;";

        try {

            $preparado = $cnx->preparar($cmdsql);

            if ($preparado->execute()) {

                return $preparado->fetchAll();

            } else {

                return false;

            }

        } catch (PDOException $e) {

            print "Error!: " . $e->getMessage();

        }

        $cnx->closed();

        $cnx = null;

    }



    public static function mostrarDatosEstudiantesModel($id)

    {

        $tabla  = 'usuarios';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT

        u.*,

        p.nombre AS nom_perfil,

        c.nombre AS nom_curso,

        (select p.anio from pension p where p.id_estudiante = u.id_user order by p.id desc limit 1) as ultima_anio_pension,

        (select p.mes from pension p where p.id_estudiante = u.id_user order by p.id desc limit 1) as ultima_mes_pension

        FROM " . $tabla . " u

        LEFT JOIN perfiles p ON p.id = u.perfil

        LEFT JOIN curso c ON c.id = u.curso

        WHERE u.id_user = :id;";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindParam(':id', $id);

            if ($preparado->execute()) {

                return $preparado->fetch();

            } else {

                return false;

            }

        } catch (PDOException $e) {

            print "Error!: " . $e->getMessage();

        }

        $cnx->closed();

        $cnx = null;

    }



    public static function mostrarEstudiantesCursoModel($curso)

    {

        $tabla  = 'usuarios';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT

        u.*,

        p.nombre AS nom_perfil,

        c.nombre AS nom_curso,

        (SELECT a.id FROM asistencia_curso a WHERE a.id_user = u.id_user AND a.activo = 1 AND a.fechareg LIKE '%2023-02-06%' ORDER BY a.id DESC LIMIT 1) AS id_asistencia,

        (SELECT a.asistencia FROM asistencia_curso a WHERE a.id_user = u.id_user AND a.activo = 1 AND a.fechareg LIKE '%" . date('Y-m-d') . "%' ORDER BY a.id DESC LIMIT 1) AS asistencia,

            (SELECT a.fechareg FROM asistencia_curso a WHERE a.id_user = u.id_user AND a.activo = 1 AND a.fechareg LIKE '%" . date('Y-m-d') . "%' ORDER BY a.id DESC LIMIT 1) AS asistencia_fecha

            FROM " . $tabla . " u

            LEFT JOIN perfiles p ON p.id = u.perfil

            LEFT JOIN curso c ON c.id = u.curso

            WHERE u.perfil = 3 AND u.activo = 1 AND u.curso = " . $curso . " ORDER BY u.nombre ASC;";

        try {

            $preparado = $cnx->preparar($cmdsql);

            if ($preparado->execute()) {

                return $preparado->fetchAll();

            } else {

                return false;

            }

        } catch (PDOException $e) {

            print "Error!: " . $e->getMessage();

        }

        $cnx->closed();

        $cnx = null;

    }



    public static function mostrarUsuariosBuscarModel($buscar)

    {

        $tabla  = 'usuarios';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT

            u.*,

            p.nombre AS nom_perfil

            FROM " . $tabla . " u

            LEFT JOIN perfiles p ON p.id = u.perfil

            WHERE CONCAT(u.nombre, ' ' , u.apellido, ' ', u.documento) LIKE '%" . $buscar . "%' AND perfil NOT IN(1,3);";

        try {

            $preparado = $cnx->preparar($cmdsql);

            if ($preparado->execute()) {

                return $preparado->fetchAll();

            } else {

                return false;

            }

        } catch (PDOException $e) {

            print "Error!: " . $e->getMessage();

        }

        $cnx->closed();

        $cnx = null;

    }



    public static function mostrarEstudiantesBuscarModel($buscar)

    {

        $tabla  = 'usuarios';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT

            u.*,

            p.nombre AS nom_perfil,

            c.nombre as nom_curso,

            (SELECT a.id FROM asistencia_curso a WHERE a.id_user = u.id_user AND a.activo = 1 AND a.fechareg LIKE '%2023-02-06%' ORDER BY a.id DESC LIMIT 1) AS id_asistencia,

            (SELECT a.asistencia FROM asistencia_curso a WHERE a.id_user = u.id_user AND a.activo = 1 AND a.fechareg LIKE '%" . date('Y-m-d') . "%' ORDER BY a.id DESC LIMIT 1) AS asistencia,

                (SELECT a.fechareg FROM asistencia_curso a WHERE a.id_user = u.id_user AND a.activo = 1 AND a.fechareg LIKE '%" . date('Y-m-d') . "%' ORDER BY a.id DESC LIMIT 1) AS asistencia_fecha

                FROM " . $tabla . " u

                LEFT JOIN perfiles p ON p.id = u.perfil

                LEFT JOIN curso c ON c.id = u.curso

                WHERE perfil = 3 and u.activo=1 

                ". $buscar['filtro'] . "

                ". $buscar["curso"] . "

                ORDER BY u.nombre ASC;";

        try {

            $preparado = $cnx->preparar($cmdsql);

            if ($preparado->execute()) {

                return $preparado->fetchAll();

            } else {

                return false;

            }

        } catch (PDOException $e) {

            print "Error!: " . $e->getMessage();

        }

        $cnx->closed();

        $cnx = null;

    }



    public static function agregarUsuarioModel($datos)

    {

        $tabla = 'usuarios';

        $cnx   = conexion::singleton_conexion();

        $sql   = "INSERT INTO " . $tabla . " (documento, nombre, apellido, correo, telefono, perfil, user, pass, user_log, foto_perfil) VALUES (:d, :n, :a, :c, :t, :p, :us, :pass, :idl, :fp);";

        try {

            $preparado = $cnx->preparar($sql);

            $preparado->bindParam(':d', $datos['documento']);

            $preparado->bindParam(':n', $datos['nombre']);

            $preparado->bindParam(':a', $datos['apellido']);

            $preparado->bindParam(':c', $datos['correo']);

            $preparado->bindParam(':t', $datos['telefono']);

            $preparado->bindParam(':p', $datos['perfil']);

            $preparado->bindParam(':us', $datos['user']);

            $preparado->bindParam(':pass', $datos['pass']);

            $preparado->bindParam(':idl', $datos['id_log']);

            $preparado->bindParam(':fp', $datos['foto_perfil']);

            if ($preparado->execute()) {

                $id        = $cnx->ultimoIngreso($tabla);

                $resultado = array('id' => $id, 'guardar' => true);

                return $resultado;

            } else {

                return false;

            }

        } catch (PDOException $e) {

            print "Error!: " . $e->getMessage();

        }

        $cnx->closed();

        $cnx = null;

    }



    public static function agregarEstudianteModel($datos)

    {

        $tabla = 'usuarios';

        $cnx   = conexion::singleton_conexion();

        $sql   = "INSERT INTO " . $tabla . " (tipo_doc, documento, nombre, apellido, genero, perfil, user, pass, user_log, foto_perfil, curso) VALUES (:td, :d, :n, :ap, :gn, :pr, :us, :pass, :idl, :fp, :cu);";

        try {

            $preparado = $cnx->preparar($sql);

            $preparado->bindParam(':td', $datos['tipo_doc']);

            $preparado->bindParam(':d', $datos['documento']);

            $preparado->bindParam(':n', $datos['nombre']);

            $preparado->bindParam(':ap', $datos['apellido']);

            $preparado->bindParam(':gn', $datos['genero']);

            $preparado->bindParam(':pr', $datos['perfil']);

            $preparado->bindParam(':us', $datos['user']);

            $preparado->bindParam(':pass', $datos['pass']);

            $preparado->bindParam(':idl', $datos['id_log']);

            $preparado->bindParam(':fp', $datos['foto_perfil']);

            $preparado->bindParam(':cu', $datos['curso']);

            if ($preparado->execute()) {

                $id        = $cnx->ultimoIngreso($tabla);

                $resultado = array('id' => $id, 'guardar' => true);

                return $resultado;

            } else {

                return false;

            }

        } catch (PDOException $e) {

            print "Error!: " . $e->getMessage();

        }

        $cnx->closed();

        $cnx = null;

    }



    public static function editarUsuarioModel($datos)

    {

        $tabla = 'usuarios';

        $cnx   = conexion::singleton_conexion();

        $sql   = "UPDATE " . $tabla . " SET nombre = :n, apellido = :a, correo = :c, telefono = :t, perfil = :r, user_log = :idl, foto_perfil = :fp WHERE id_user = :id";

        try {

            $preparado = $cnx->preparar($sql);

            $preparado->bindValue(':id', $datos['id_user']);

            $preparado->bindParam(':idl', $datos['id_log']);

            $preparado->bindParam(':n', $datos['nombre']);

            $preparado->bindParam(':a', $datos['apellido']);

            $preparado->bindParam(':c', $datos['correo']);

            $preparado->bindParam(':t', $datos['telefono']);

            $preparado->bindParam(':r', $datos['perfil']);

            $preparado->bindParam(':fp', $datos['foto_perfil']);

            if ($preparado->execute()) {

                return true;

            } else {

                return false;

            }

        } catch (PDOException $e) {

            print "Error!: " . $e->getMessage();

        }

        $cnx->closed();

        $cnx = null;

    }



    public static function editarEstudianteModel($datos)

    {

        $tabla = 'usuarios';

        $cnx   = conexion::singleton_conexion();

        $sql   = "UPDATE " . $tabla . " SET tipo_doc = :td, nombre = :n, apellido = :a, genero = :gn, user_log = :idl, foto_perfil = :fp, curso = :cu WHERE id_user = :id";

        try {

            $preparado = $cnx->preparar($sql);

            $preparado->bindValue(':id', $datos['id_user']);

            $preparado->bindParam(':idl', $datos['id_log']);

            $preparado->bindParam(':td', $datos['tipo_doc']);

            $preparado->bindParam(':n', $datos['nombre']);

            $preparado->bindParam(':a', $datos['apellido']);

            $preparado->bindParam(':gn', $datos['genero']);

            $preparado->bindParam(':fp', $datos['foto_perfil']);

            $preparado->bindParam(':cu', $datos['curso']);

            if ($preparado->execute()) {

                return true;

            } else {

                return false;

            }

        } catch (PDOException $e) {

            print "Error!: " . $e->getMessage();

        }

        $cnx->closed();

        $cnx = null;

    }



    public static function inactivarUsuarioModel($id)

    {

        $tabla = 'usuarios';

        $cnx   = conexion::singleton_conexion();

        $sql   = "UPDATE " . $tabla . " SET activo = 0, fecha_inactivo = NOW() WHERE id_user = :id";

        try {

            $preparado = $cnx->preparar($sql);

            $preparado->bindParam(':id', $id);

            if ($preparado->execute()) {

                return true;

            } else {

                return false;

            }

        } catch (PDOException $e) {

            print "Error!: " . $e->getMessage();

        }

        $cnx->closed();

        $cnx = null;

    }



    public static function activarUsuarioModel($id)

    {

        $tabla = 'usuarios';

        $cnx   = conexion::singleton_conexion();

        $sql   = "UPDATE " . $tabla . " SET activo = 1, fecha_activo = NOW() WHERE id_user = :id";

        try {

            $preparado = $cnx->preparar($sql);

            $preparado->bindParam(':id', $id);

            if ($preparado->execute()) {

                return true;

            } else {

                return false;

            }

        } catch (PDOException $e) {

            print "Error!: " . $e->getMessage();

        }

        $cnx->closed();

        $cnx = null;

    }



    public static function consultarDocumentoModel($id)

    {

        $tabla = 'usuarios';

        $cnx   = conexion::singleton_conexion();

        $sql   = "SELECT * FROM " . $tabla . " WHERE user = '" . $id . "' ORDER BY id_user DESC LIMIT 1;";

        try {

            $preparado = $cnx->preparar($sql);

            if ($preparado->execute()) {

                return $preparado->fetch();

            } else {

                return false;

            }

        } catch (PDOException $e) {

            print "Error!: " . $e->getMessage();

        }

        $cnx->closed();

        $cnx = null;

    }



    public static function passwordNewModel($id, $pass)

    {

        $tabla = 'usuarios';

        $cnx   = conexion::singleton_conexion();

        $sql   = "UPDATE " . $tabla . " SET pass = :p WHERE id_user = :id;";

        try {

            $preparado = $cnx->preparar($sql);

            $preparado->bindParam(':id', $id);

            $preparado->bindParam(':p', $pass);

            if ($preparado->execute()) {

                return true;

            } else {

                return false;

            }

        } catch (PDOException $e) {

            print "Error!: " . $e->getMessage();

        }

        $cnx->closed();

        $cnx = null;

    }



    public static function cantidadAsistenciaModel($id, $fecha_incio, $fecha_fin)

    {

        $tabla = 'asistencia_curso';

        $cnx   = conexion::singleton_conexion();

        $sql   = "SELECT COUNT(id) as cantidad FROM asistencia_curso WHERE fechareg >= '" . $fecha_incio . " 00:00:00' AND fechareg <= '" . $fecha_fin . " 23:59:59' AND id_user = " . $id . " AND asistencia = 2 AND activo = 1;";

        try {

            $preparado = $cnx->preparar($sql);

            $preparado->bindParam(':id', $id);

            $preparado->bindParam(':p', $pass);

            if ($preparado->execute()) {

                return $preparado->fetch();

            } else {

                return false;

            }

        } catch (PDOException $e) {

            print "Error!: " . $e->getMessage();

        }

        $cnx->closed();

        $cnx = null;

    }

}

