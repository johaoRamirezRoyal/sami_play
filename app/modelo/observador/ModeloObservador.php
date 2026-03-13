<?php
require_once MODELO_PATH . 'conexion.php';

class ModeloObservador extends conexion
{

    public static function agregarObservacionModel($datos)
    {
        $tabla  = 'observador';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO " . $tabla . " (observador, id_estudiante, id_log) VALUES ('" . $datos['observacion'] . "', '" . $datos['id_estudiante'] . "', '" . $datos['id_log'] . "');";
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

    public static function mostrarObservacionesModel($id)
    {
        $tabla  = 'observador';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT b.*, concat(u.nombre, ' ', u.apellido) as nom_usuario FROM " . $tabla . " b
        LEFT JOIN usuarios u ON u.id_user = b.id_log
        WHERE b.id_estudiante = :id;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id', $id);
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

    public static function comentariosObservadorModel($datos)
    {
        $tabla  = 'observador_comentario';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO " . $tabla . " (id_observador, comentario, id_log) VALUES (:ido, :c, :idl);";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':ido', $datos['id_observador']);
            $preparado->bindParam(':c', $datos['comentario']);
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

    public static function historialComentariosModel($id)
    {
        $tabla  = 'observador_comentario';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT o.*, concat(u.nombre, ' ', u.apellido) as nom_usuario  FROM " . $tabla . " o
        LEFT JOIN usuarios u ON u.id_user = o.id_log
        WHERE o.activo = 1 AND o.id_observador = :id ORDER BY o.id DESC;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id', $id);
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
