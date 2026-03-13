<?php
require_once MODELO_PATH . 'conexion.php';

class ModeloPension extends conexion
{

    public static function mostrarMesesPensionModel()
    {
        $tabla  = 'meses';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT * FROM " . $tabla . " WHERE activo = 1;";
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

    public static function agregarPensionModel($datos)
    {
        $tabla  = 'pension';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO " . $tabla . " (
        id_estudiante,
        anio,
        mes,
        fecha_pago,
        id_log)
        VALUES
        (
        '" . $datos['estudiante'] . "',
        '" . $datos['anio'] . "',
        '" . $datos['mes'] . "',
        '" . $datos['fecha_pago'] . "',
        '" . $datos['id_log'] . "');";
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

    public static function mostrarLimitePensionModel()
    {
        $tabla  = 'pension';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
        p.*,
        u.documento,
        CONCAT(u.nombre, ' ', u.apellido) AS nom_estudiante,
        m.nombre AS mes_pago
        FROM pension p
        LEFT JOIN usuarios u ON u.id_user = p.id_estudiante
        LEFT JOIN meses m ON m.id = p.mes
        ORDER BY p.fecha_pago DESC LIMIT 30;";
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

    public static function mostrarPensionesPagasEstudianteModel($id)
    {
        $tabla  = 'pension';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
        p.*,
        u.documento,
        CONCAT(u.nombre, ' ', u.apellido) AS nom_estudiante,
        m.nombre AS mes_pago
        FROM pension p
        LEFT JOIN usuarios u ON u.id_user = p.id_estudiante
        LEFT JOIN meses m ON m.id = p.mes
        WHERE p.id_estudiante = :id
        ORDER BY p.fecha_pago DESC;";
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
