<?php
require_once MODELO_PATH . 'conexion.php';

class ModeloPeriodo extends conexion
{

    public static function mostrarLimitePeriodosModel()
    {
        $tabla  = 'periodos';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
        p.*,
        an.anio
        FROM " . $tabla . " p
        LEFT JOIN anio_escolar an ON an.id = p.id_anio
        ORDER BY p.`fecha_inicio` DESC;";
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

    public static function mostrarTodosPeriodosModel()
    {
        $tabla  = 'periodos';
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

    public static function mostrarPeriodosAnioActivoModel()
    {
        $tabla  = 'periodos';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
        p.*,
        an.anio
        FROM " . $tabla . " p
        LEFT JOIN anio_escolar an ON an.id = p.id_anio
        WHERE an.activo = 1 AND p.activo = 1;";
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

    public static function mostrarTodosAniosModel()
    {
        $tabla  = 'anio_escolar';
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

    public static function agregarPeriodoModel($datos)
    {
        $tabla  = 'periodos';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "
        INSERT INTO " . $tabla . " (
        numero,
        id_anio,
        fecha_inicio,
        fecha_fin,
        id_log
        )
        VALUES
        (
        '" . $datos['numero'] . "',
        '" . $datos['anio'] . "',
        '" . $datos['fecha_inicio'] . "',
        '" . $datos['fecha_fin'] . "',
        '" . $datos['id_log'] . "'
        );
        ";
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
}
