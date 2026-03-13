<?php
require_once MODELO_PATH . 'conexion.php';

class ModeloCalificacion extends conexion
{

    public static function calificarEstudianteModel($datos)
    {
        $tabla  = 'boletin';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO " . $tabla . " (
            id_estudiante,
            curso,
            periodo,
            id_profesor,
            id_log,
            ausencia,
            edad,
            observacion)
        VALUES (
            '" . $datos['id_estudiante'] . "',
            '" . $datos['curso'] . "',
            '" . $datos['periodo'] . "',
            '" . $datos['id_profesor'] . "',
            '" . $datos['id_log'] . "',
            '" . $datos['ausencia'] . "',
            '" . $datos['edad'] . "',
            '" . $datos['observacion'] . "'); ";
        try {
            $preparado = $cnx->preparar($cmdsql);
            if ($preparado->execute()) {
                $id   = $cnx->ultimoIngreso($tabla);
                $rstl = array('guardar' => true, 'id' => $id);
                return $rstl;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
        }
        $cnx->closed();
        $cnx = null;
    }

    public static function eliminarBoletinEstudianteModel($datos)
    {
        $tabla  = 'boletin';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "DELETE FROM notas WHERE id_boletin IN(SELECT b.id FROM boletin b WHERE b.id_estudiante = " . $datos['id_estudiante'] . " AND b.curso = " . $datos['curso'] . " AND b.periodo = " . $datos['periodo'] . ");
            DELETE FROM boletin WHERE id_estudiante = " . $datos['id_estudiante'] . " AND curso = " . $datos['curso'] . " AND periodo = " . $datos['periodo'] . ";";
        try {
            $preparado = $cnx->preparar($cmdsql);
            if ($preparado->execute()) {
                $id   = $cnx->ultimoIngreso($tabla);
                $rstl = array('guardar' => true, 'id' => $id);
                return $rstl;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
        }
        $cnx->closed();
        $cnx = null;
    }

    public static function guardarNotasModel($datos)
    {
        $tabla  = 'notas';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO " . $tabla . " (
                id_boletin,
                dimension,
                indicador,
                nota,
                comentario,
                id_log
                )
            VALUES
            (
                '" . $datos['id_boletin'] . "',
                '" . $datos['dimension'] . "',
                '" . $datos['indicador'] . "',
                '" . $datos['nota'] . "',
                '" . $datos['comentario'] . "',
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

    public static function mostrarBoletinEstudianteModel($curso, $periodo, $estudiante, $anio)
    {
        $tabla  = 'boletin';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT b.* FROM boletin b
                LEFT JOIN periodos p ON p.id = b.periodo
                LEFT JOIN  usuarios u on u.id_user = b.id_estudiante
                WHERE b.id_estudiante = " . $estudiante . " AND b.curso = " . $curso . " AND b.periodo = " . $periodo . " AND p.id_anio = " . $anio . " and b.activo = 1 and u.activo=1 order by b.id desc limit 1;";
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

    public static function mostrarDatosBoletinModel($id)
    {
        $tabla  = 'boletin';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
                b.edad,
                b.ausencia,
                b.observacion,
                c.nombre AS nom_curso,
                an.anio AS anio_boletin,
                p.numero AS nom_periodo,
                CONCAT(u.nombre, ' ', u.apellido) AS nom_estudiante,
                b.fechareg,
                p.fecha_fin
                FROM " . $tabla . " b
                LEFT JOIN usuarios u ON u.id_user = b.id_estudiante
                LEFT JOIN curso c ON c.id = b.curso
                LEFT JOIN periodos p ON p.id = b.periodo
                LEFT JOIN anio_escolar an ON an.id = p.id_anio
                WHERE b.id = :id;";
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

    public static function mostrarDimensionesBoletinModel($id)
    {
        $tabla = 'notas';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "SELECT
                dm.id,
                dm.nombre,
                dm.observacion,
                GROUP_CONCAT(n.indicador SEPARATOR ', ') AS indicadores,
                GROUP_CONCAT(n.nota SEPARATOR ', ') AS notas,
                n.comentario,
                dm.foto
            FROM " . $tabla . " n
            LEFT JOIN dimensiones dm ON dm.id = n.dimension
            WHERE n.id_boletin = :id
            GROUP BY dm.id, dm.nombre, dm.observacion, n.comentario, dm.foto
            ORDER BY FIELD(n.dimension, 2,1,3,4,5,6)";
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
    

// NUEVO
public static function obtenerNotasConIndicadoresYDimensionesModel($id_boletin)
{
    $tabla_notas = 'notas';
    $tabla_indicadores = 'indicador';
    $tabla_dimensiones = 'dimensiones';
    $cnx = conexion::singleton_conexion();

    $cmdsql = "
        SELECT 
            dm.id AS id_dimension,
            dm.nombre AS nombre_dimension,
            dm.observacion AS observacion_dimension,
            dm.foto AS foto_dimension,
            id.nombre AS nombre_indicador,
            n.nota AS nota,
            n.comentario AS comentario
        FROM $tabla_notas n
        INNER JOIN $tabla_indicadores id ON id.id = n.indicador AND id.activo = 1
        LEFT JOIN $tabla_dimensiones dm ON dm.id = n.dimension
        WHERE n.id_boletin = :id_boletin
        ORDER BY FIELD(dm.id, 2, 1, 3, 5, 4, 6), id.id;"; //dm.id, id.id;
    try {
        $preparado = $cnx->preparar($cmdsql);
        $preparado->bindParam(':id_boletin', $id_boletin, PDO::PARAM_INT);
        if ($preparado->execute()) {
            $datos = $preparado->fetchAll(PDO::FETCH_ASSOC);

            // Agrupar indicadores por dimensión
            $resultados = [];
            foreach ($datos as $dato) {
                $id_dimension = $dato['id_dimension'];
                if (!isset($resultados[$id_dimension])) {
                    $resultados[$id_dimension] = [
                        'nombre' => $dato['nombre_dimension'],
                        'observacion' => $dato['observacion_dimension'],
                        'foto' => $dato['foto_dimension'],
                        'indicadores' => [],
                    ];
                }
                $resultados[$id_dimension]['indicadores'][] = [
                    'nombre_indicador' => $dato['nombre_indicador'],
                    'nota' => $dato['nota'],
                    'comentario' => $dato['comentario'],
                ];
            }

            return $resultados;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage();
    }
    $cnx->closed();
    $cnx = null;
}
    
    

    

    public static function mostrarIndicadoresDimensionBoletinModel($id, $dimension)
    {
        $tabla  = 'notas';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
                id.nombre,
                n.nota
                FROM " . $tabla . " n
                LEFT JOIN indicador id ON id.id = n.indicador
                WHERE n.dimension = :idm AND n.id_boletin = :id;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id', $id);
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

    public static function mostrarNotaGuardadaModel($id_estudiante, $curso, $periodo, $indicador)
    {
        $tabla  = 'notas';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
                n.*,
                (select ob.comentario from notas ob where ob.id = n.id group by ob.dimension) as comentario_ob
                FROM notas n
                LEFT JOIN boletin b ON b.id = n.id_boletin
                WHERE b.id_estudiante = " . $id_estudiante . " AND b.curso = " . $curso . " AND b.periodo = " . $periodo . " AND n.indicador = " . $indicador . ";";
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

    public static function mostrarBoletinesGeneradosModel($id)
    {
        $tabla  = 'boletin';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
                b.*,
                c.nombre AS nom_curso,
                p.numero AS nom_periodo,
                an.anio,
                CONCAT(u.nombre, ' ', u.apellido) AS nom_profesor
                FROM boletin b
                LEFT JOIN curso c ON c.id = b.curso
                LEFT JOIN periodos p ON p.id = b.periodo
                LEFT JOIN anio_escolar an ON an.id = p.id_anio
                LEFT JOIN usuarios u ON u.id_user = b.id_profesor
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

    public static function generarBoletinModel($datos)
    {
        $tabla  = 'boletin';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "UPDATE " . $tabla . " SET generado = :g WHERE id = :id;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id', $datos['id_boletin']);
            $preparado->bindParam(':g', $datos['generar']);
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
