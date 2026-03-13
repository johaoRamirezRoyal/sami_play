<?php
require_once MODELO_PATH . 'conexion.php';

class ModeloAsistenciaCron extends conexion
{
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

    public static function mostrarAsistenciaListadoModel()
    {
        $tabla  = 'asistencia_gestion';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
        u.documento,
        CONCAT(u.nombre, ' ', u.apellido) AS nom_user,
        (SELECT nombre FROM perfiles WHERE id = u.perfil) AS perfil,
        a.hora_asistencia, a.fecha_asistencia
        FROM asistencia_gestion a
        INNER JOIN usuarios u ON u.id_user = a.id_user ORDER BY u.nombre ASC LIMIT 25;";
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

    public static function buscarUsuarioAsistenciaGestionModel($datos)
    {
        $tabla  = 'asistencia_gestion';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = " SELECT
        u.documento,
        CONCAT(u.nombre, ' ', u.apellido) AS nom_user,
        (SELECT nombre FROM perfiles WHERE id = u.perfil) AS perfil,
        a.hora_asistencia, a.fecha_asistencia
        FROM asistencia_gestion a
        INNER JOIN usuarios u ON a.id_user = u.id_user
        WHERE CONCAT(u.nombre, ' ', u.apellido, ' ', u.documento) like '%" . $datos['buscar'] . "%'
        " . $datos['perfil'] . "
        " . $datos['fecha'] . "
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

    public static function validarTokenModel($token)
    {
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT * FROM dias_qr WHERE token = '" . $token . "';";
        try {
            $preparado = $cnx->preparar($cmdsql);
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

    public static function validarDocumentoModel($documento)
    {
        $cnx = conexion::singleton_conexion();
        $cmd = "SELECT * FROM usuarios WHERE documento = '" . $documento . "' AND perfil NOT IN(1);";
        try {
            $preparado = $cnx->preparar($cmd);
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

    public static function TomarAsistenciaModel($datos)
    {
        $cnx = conexion::singleton_conexion();
        $cmd = "INSERT INTO asistencia_gestion (
            id_user,
            fecha_asistencia,
            hora_asistencia)
        VALUES(
            :idu,
            :fA,
            :hA);
        ";
        try {
            $preparado = $cnx->preparar($cmd);
            $preparado->bindParam(':idu', $datos['id_user']);
            $preparado->bindParam(':fA', $datos['fecha_hoy']);
            $preparado->bindParam(':hA', $datos['hora_hoy']);
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

    public static function validarAsistenciaHoyModel($datos)
    {
        $cnx = conexion::singleton_conexion();
        $cmd = "SELECT * FROM asistencia_gestion WHERE id_user = :idu AND fecha_asistencia = '" . $datos['fecha_hoy'] . "' ORDER BY id DESC LIMIT 1";
        try {
            $preparado = $cnx->preparar($cmd);
            $preparado->bindParam(':idu', $datos['id_user']);
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

    public static function mensajeAsistenciaModel($datos)
    {
        $tabla = 'asistencia_mensaje';
        $cnx   = conexion::singleton_conexion();
        $cmd   = "INSERT INTO " . $tabla . " (fecha, imagen, mensaje, id_log, nivel, titulo) VALUES ('" . $datos['fecha'] . "', '" . $datos['imagen'] . "', '" . $datos['mensaje'] . "', '" . $datos['id_log'] . "', '" . $datos['nivel'] . "', '" . $datos['titulo'] . "');";
        try {
            $preparado = $cnx->preparar($cmd);
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

    public static function mostrarMensajesLimitesModel()
    {
        $tabla = 'asistencia_mensaje';
        $cnx   = conexion::singleton_conexion();
        $cmd   = "SELECT
            ast.*
            FROM asistencia_mensaje ast
            ORDER BY ast.fecha DESC LIMIT 25;";
        try {
            $preparado = $cnx->preparar($cmd);
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

    public static function mensajeDiaAsistenciaModel()
    {
        $tabla = 'asistencia_mensaje';
        $cnx   = conexion::singleton_conexion();
        $cmd   = "SELECT
            ast.*
            FROM asistencia_mensaje ast
            WHERE fecha = '" . date('Y-m-d') . "';";
        try {
            $preparado = $cnx->preparar($cmd);
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

    public static function mensajeGeneralModel($datos)
    {
        $tabla = 'asistencia_mensaje_general';
        $cnx   = conexion::singleton_conexion();
        $cmd   = "INSERT INTO " . $tabla . " (titulo, imagen, mensaje, id_log) VALUES (:t, :img, :msj, :idl);";
        try {
            $preparado = $cnx->preparar($cmd);
            $preparado->bindParam(':t', $datos['titulo']);
            $preparado->bindParam(':img', $datos['imagen']);
            $preparado->bindParam(':msj', $datos['mensaje']);
            $preparado->bindParam(':idl', $datos['id_log']);
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

    public static function inactivarUltimoMensajeGeneral()
    {
        $tabla = 'asistencia_mensaje_general';
        $cnx   = conexion::singleton_conexion();
        $cmd   = "UPDATE " . $tabla . " SET activo = 0 ORDER BY id DESC LIMIT 1;";
        try {
            $preparado = $cnx->preparar($cmd);
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

    public static function mensajesGeneralesLimiteModel()
    {
        $tabla = 'asistencia_mensaje_general';
        $cnx   = conexion::singleton_conexion();
        $cmd   = "SELECT * FROM " . $tabla . " ORDER BY id DESC LIMIT 20;";
        try {
            $preparado = $cnx->preparar($cmd);
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

    public static function mensajeGeneralActivoModel()
    {
        $tabla = 'asistencia_mensaje_general';
        $cnx   = conexion::singleton_conexion();
        $cmd   = "SELECT * FROM " . $tabla . " WHERE activo =1;";
        try {
            $preparado = $cnx->preparar($cmd);
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

    public static function tomarAsistenciaCursoModel($id, $estado, $log, $fecha, $asistencia)
    {
        $tabla = 'asistencia_curso';
        $cnx   = conexion::singleton_conexion();
        $cmd   = "DELETE FROM asistencia_curso WHERE id_user = " . $id . " AND fechareg LIKE '%" . date('Y-m-d', strtotime($fecha)) . "%';
            INSERT INTO " . $tabla . " (id_user, asistencia, id_log, fechareg) VALUES ('" . $id . "', '" . $estado . "', '" . $log . "', '" . $fecha . "');";
        try {
            $preparado = $cnx->preparar($cmd);
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

    public static function buscarEstudiantesAsistenciaModel($datos)
    {
        $tabla = 'usuarios';
        $cnx   = conexion::singleton_conexion();
        $cmd   = "
                SELECT
                u.*,
                p.nombre AS nom_perfil,
                c.nombre AS nom_curso,
                asi.id AS id_asistencia,
                (SELECT a.asistencia FROM asistencia_curso a WHERE a.id_user = u.id_user AND a.activo = 1 AND a.fechareg = asi.fechareg ORDER BY a.id DESC LIMIT 1) AS asistencia,
                (SELECT a.fechareg FROM asistencia_curso a WHERE a.id_user = u.id_user AND a.activo = 1 AND a.fechareg = asi.fechareg ORDER BY a.id DESC LIMIT 1) AS asistencia_fecha
                FROM usuarios u
                LEFT JOIN perfiles p ON p.id = u.perfil
                LEFT JOIN curso c ON c.id = u.curso
                LEFT JOIN asistencia_curso asi ON asi.id_user = u.id_user
                WHERE u.perfil = 3 AND
                CONCAT(u.nombre, ' ' , u.apellido, ' ', u.documento, ' ', c.nombre) LIKE '%" . $datos['buscar'] . "%'
                " . $datos['curso'] . "
                " . $datos['fecha_buscar'] . "
                AND asi.activo = 1 ORDER BY u.nombre ASC";
        try {
            $preparado = $cnx->preparar($cmd);
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

    public static function ultimasAsistenciasTomadasModel()
    {
        $tabla = 'usuarios';
        $cnx   = conexion::singleton_conexion();
        $cmd   = "SELECT
                u.*,
                p.nombre AS nom_perfil,
                c.nombre AS nom_curso,
                asi.id AS id_asistencia,
                (SELECT a.asistencia FROM asistencia_curso a WHERE a.id_user = u.id_user AND a.activo = 1 AND a.fechareg = asi.fechareg ORDER BY a.id DESC LIMIT 1) AS asistencia,
                (SELECT a.fechareg FROM asistencia_curso a WHERE a.id_user = u.id_user AND a.activo = 1 AND a.fechareg = asi.fechareg ORDER BY a.id DESC LIMIT 1) AS asistencia_fecha
                FROM usuarios u
                LEFT JOIN perfiles p ON p.id = u.perfil
                LEFT JOIN curso c ON c.id = u.curso
                LEFT JOIN asistencia_curso asi ON asi.id_user = u.id_user
                WHERE u.perfil = 3 AND asi.fechareg LIKE '%" . date('Y-m-d') . "%' AND asi.activo = 1 ORDER BY u.nombre ASC LIMIT 25;";
        try {
            $preparado = $cnx->preparar($cmd);
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
}
