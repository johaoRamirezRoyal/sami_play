<?php
require_once MODELO_PATH . 'conexion.php';

class ModeloCurso extends conexion
{

    public static function agregarCursoModel($datos)
    {
        $tabla  = 'curso';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO " . $tabla . " (nombre, id_log) VALUES ('" . $datos['nombre'] . "', '" . $datos['id_log'] . "');";
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

    public static function editarCursoModel($datos)
    {
        $tabla  = 'curso';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "UPDATE " . $tabla . " SET nombre = '" . $datos['nombre'] . "', id_edit = '" . $datos['id_log'] . "', fecha_edit = NOW(), id_profesor = '" . $datos['id_profesor'] . "', periodo_actual = '" . $datos['periodo_actual'] . "' WHERE id = '" . $datos['id_curso'] . "';";
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

    public static function mostrarLimiteCursosModel()
    {
        $tabla  = 'curso';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
            c.*,
            CONCAT(u.nombre, ' ', u.apellido) AS nom_profesor,
            (SELECT COUNT(u.id_user) FROM usuarios u WHERE u.curso = c.id AND u.perfil = 3 AND u.activo = 1) AS cantidad_estudiantes
            FROM curso c
            LEFT JOIN usuarios u ON u.id_user = c.id_profesor
            where c.activo=1
            ORDER BY c.nombre ASC LIMIT 20;";
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

    public static function mostrarTodosCursosModel()
    {
        $tabla  = 'curso';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
            c.*,
            CONCAT(u.nombre, ' ', u.apellido) AS nom_profesor
            FROM curso c
            LEFT JOIN usuarios u ON u.id_user = c.id_profesor
            WHERE c.activo = 1
            ORDER BY c.nombre ASC;";
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

    public static function mostrarDatosCursoProfesorModel($id)
    {
        $tabla  = 'curso';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
            c.*,
            CONCAT(u.nombre, ' ', u.apellido) AS nom_profesor,
            p.id_anio
            FROM curso c
            LEFT JOIN usuarios u ON u.id_user = c.id_profesor
            left join periodos p on p.id = c.periodo_actual
            WHERE c.activo = 1 AND c.id_profesor = :id;";
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

    public static function mostrarPeriodosConfiguradosCursoModel($id)
    {
        $tabla  = 'curso_configuracion';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
            p.*,
            an.anio
            FROM " . $tabla . " c
            LEFT JOIN dimensiones d ON d.id = c.id_dimension
            LEFT JOIN periodos p ON p.id = c.id_periodo
            left join anio_escolar an on an.id = p.id_anio
            WHERE c.id_curso = :id AND c.activo = 1
            GROUP BY c.id_periodo;";
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

    public static function mostrarPeriodosCursoModel($id)
    {
        $tabla  = 'curso_configuracion';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
            p.*,
            an.anio
            FROM " . $tabla . " c
            LEFT JOIN dimensiones d ON d.id = c.id_dimension
            LEFT JOIN periodos p ON p.id = c.id_periodo
            left join anio_escolar an on an.id = p.id_anio
            WHERE c.id_curso = :id
            GROUP BY c.id_periodo;";
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

    public static function mostrarInformacionCursoModel($id)
    {
        $tabla  = 'curso';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
            c.*,
            p.id_anio,
            (SELECT COUNT(u.id_user) FROM usuarios u WHERE u.activo = 1 AND u.perfil = 3 AND u.curso = c.id) cantidad_estudiante
            FROM curso c
            left join periodos p on p.id = c.periodo_actual
            WHERE c.id = :id;";
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

    public static function inactivarIndicadoresCursoModel($id)
    {
        $tabla  = 'curso_configuracion';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "UPDATE " . $tabla . " SET activo = 0 WHERE id_curso = :id;";
        try {
            $preparado = $cnx->preparar($cmdsql);
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

    public static function configuracionCursoModel($datos)
    {
        $tabla  = 'curso_configuracion';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO " . $tabla . " (
                id_curso,
                id_periodo,
                id_dimension,
                id_indicador,
                id_log
                )
            VALUES
            (
                '" . $datos['id_curso'] . "',
                '" . $datos['id_periodo'] . "',
                '" . $datos['id_dimension'] . "',
                '" . $datos['id_indicador'] . "',
                '" . $datos['id_log'] . "');
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

    public static function mostrarPeriodoCursoModel($id)
    {
        $tabla  = 'curso_configuracion';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT * FROM " . $tabla . " WHERE id_curso = " . $id . " AND activo = 1 GROUP BY id_periodo ORDER BY id DESC;";
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

    public static function mostrarIndicadorMarcadoCursoModel($id_curso, $indicador)
    {
        $tabla  = 'curso_configuracion';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT * FROM curso_configuracion WHERE id_curso = " . $id_curso . " AND activo = 1 AND id_indicador = " . $indicador . " ORDER BY id DESC LIMIT 1;";
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

    public static function mostrarIndicadoresCursoPeriodoModel($id_curso, $periodo, $dimension)
    {
        $tabla  = 'curso_configuracion';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
                c.id_indicador,
                id.nombre,
                id.id_dimension as id_dimension_indicador
                FROM " . $tabla . " c
                LEFT JOIN indicador id ON id.id = c.id_indicador
                WHERE c.id_curso = :idc AND c.id_periodo = :idp AND c.activo = 1 AND c.id_dimension = :idm ORDER BY id.id ASC;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':idc', $id_curso);
            $preparado->bindParam(':idp', $periodo);
            $preparado->bindParam(':idm', $dimension);
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
