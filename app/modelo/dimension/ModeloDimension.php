<?php
require_once MODELO_PATH . 'conexion.php';

class ModeloDimension extends conexion
{

    public static function agregarDimensionModel($datos)
    {
        $tabla  = 'dimensiones';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO " . $tabla . "
        (nombre, observacion, id_log, foto)
        VALUES ('" . $datos['nombre'] . "','" . $datos['observacion'] . "', '" . $datos['id_log'] . "', '" . $datos['foto'] . "');";
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

    public static function buscarIndicadorNombreModel($nombre)
    {
        $tabla  = 'indicador';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT * FROM " . $tabla . " WHERE activo = 1 AND nombre LIKE '%" . $nombre . "%' ORDER BY id DESC;";
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

    public static function mostrarDimensionModel()
    {
        $tabla  = 'dimensiones';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT * FROM " . $tabla . " WHERE activo = 1 ORDER BY id DESC;";
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

    public static function mostrarLimiteDimensionModel()
    {
        $tabla  = 'dimensiones';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT * FROM " . $tabla . " WHERE activo = 1 ORDER BY FIELD(id, 2,1,3,4,5, 6) LIMIT 20;";
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

    public static function mostrarTodasDimensionModel()
    {
        $tabla  = 'dimensiones';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT * FROM " . $tabla . " WHERE activo = 1 ORDER BY FIELD(id, 2,1,3,4,5, 6) ASC;";
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

    public static function mostrarDimensionesCursoModel($id)
    {
        $tabla  = 'curso_configuracion';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT c.*, d.* FROM " . $tabla . " c
        LEFT JOIN dimensiones d ON d.id = c.id_dimension
        WHERE c.id_curso = :id AND c.activo = 1 GROUP BY d.id ORDER BY FIELD(d.id, 2,1,3,4,5,6) ASC;";
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

    public static function buscarDimensionModel($dato)
    {
        $tabla  = 'dimensiones';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT * FROM " . $tabla . " WHERE activo = 1 AND CONCAT(nombre, ' ', observacion) LIKE '%" . $dato . "%' ORDER BY id DESC;";
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

    public static function inactivarDimensionModel($datos)
    {
        $tabla  = 'dimensiones';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "UPDATE " . $tabla . " SET id_inactivo = '" . $datos['id_log'] . "', fecha_inactivo = NOW() WHERE id = '" . $datos['id'] . "';";
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

    public static function activarDimensionModel($datos)
    {
        $tabla  = 'dimensiones';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "UPDATE " . $tabla . " SET id_activo = '" . $datos['id_log'] . "', fecha_activo = NOW() WHERE id = '" . $datos['id'] . "';";
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

    public static function editarDimensionModel($datos)
    {
        $tabla  = 'dimensiones';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "UPDATE " . $tabla . " SET id_edit = '" . $datos['id_log'] . "', fecha_edit = NOW(), nombre = '" . $datos['nombre'] . "', observacion = '" . $datos['observacion'] . "', foto = '" . $datos['foto'] . "' WHERE id = '" . $datos['id'] . "';";
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

    /*-----------------------------------*/
    public static function agregarIndicadorModel($datos)
    {
        $tabla  = 'indicador';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO " . $tabla . " (nombre, id_dimension, id_log, curso_grupo)
        VALUES (
        '" . $datos['nombre'] . "',
        '" . $datos['id_dimension'] . "',
        '" . $datos['id_log'] . "',
        '" . $datos['grupo'] . "');";
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

    public static function agregarIndicadorPeriodoModel($datos)
    {
        $tabla  = 'indicador';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO $tabla (nombre, id_dimension, id_log, id_periodo, curso_grupo) VALUES (:nombre, :id_dimension, :id_log, :id_periodo, :curso_grupo);";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
            $preparado->bindParam(":id_dimension", $datos["id_dimension"], PDO::PARAM_INT);
            $preparado->bindParam(":id_log", $datos["id_log"], PDO::PARAM_INT);
            $preparado->bindParam(":id_periodo", $datos["periodo"], PDO::PARAM_INT);
            $preparado->bindParam(":curso_grupo", $datos["grupo"], PDO::PARAM_STR);
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

    public static function mostrarLimiteIndicadorModel()
    {
        $tabla  = 'indicador';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
        i.*,
        dm.nombre AS nom_dimension,
        cg.nombre as nom_grupo
        FROM " . $tabla . " i
        LEFT JOIN dimensiones dm ON dm.id = i.id_dimension
        LEFT JOIN curso_grupo cg ON cg.id = i.curso_grupo
        ORDER BY i.id DESC LIMIT 30;";
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

    public static function buscarIndicadoresModel($datos)
    {
        $tabla  = 'indicador';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
        i.*,
        dm.nombre AS nom_dimension,
        cg.nombre as nom_grupo
        FROM " . $tabla . " i
        LEFT JOIN dimensiones dm ON dm.id = i.id_dimension
        LEFT JOIN curso_grupo cg ON cg.id = i.curso_grupo
        WHERE concat(i.nombre) like '%" . $datos['buscar'] . "%'
        " . $datos['dimension'] . "
        ORDER BY abs(i.nombre) ASC;";
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

    public static function mostrarIndicadoresDimensionModel($id, $curso)
    {
        $tabla  = 'indicador';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
        i.*,
        dm.nombre AS nom_dimension
        FROM " . $tabla . " i
        LEFT JOIN dimensiones dm ON dm.id = i.id_dimension
        where i.id_dimension = :id AND i.activo = 1 AND i.curso_grupo IN('0', '" . $curso . "') ORDER BY abs(i.nombre) ASC;";
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

    
    public static function obtenerIndicadoresDimensionesGrupoCursoPeriodoModel($id_grupo, $id_periodo){
        $tabla = "indicador";
        $cnx = conexion::singleton_conexion();
        $cmdsql = "SELECT i.*, d.id AS dimension_id, d.nombre AS dimension_nombre 
                    FROM $tabla i
                    LEFT JOIN dimensiones d ON i.id_dimension = d.id
                    WHERE i.ID_PERIODO = $id_periodo AND i.CURSO_GRUPO = $id_grupo";
        try {
            $preparado = $cnx->preparar($cmdsql);
            if($preparado->execute()){
                return $preparado->fetchAll(PDO::FETCH_ASSOC);
            }else{
                return false;
            }
        }catch(PDOException $e){
            print "Error al obtener los indicadores en el periodo y grupo: " . $e->getMessage();
        }
    }

    public static function indicadorEnConfiguracionModel($id_indicador, $id_grupo, $id_periodo){
        $tabla = "curso_configuracion";
        $cnx = conexion::singleton_conexion();
        $cmdsql = "SELECT 
                    COUNT(DISTINCT cc.id_curso) = 
                    (
                        SELECT COUNT(*) 
                        FROM curso 
                        WHERE curso_grupo = $id_grupo
                        AND activo = 1
                    ) AS indicador_en_todos
                    FROM $tabla cc
                    JOIN curso c ON c.id = cc.id_curso
                    JOIN periodos p ON cc.id_periodo = p.id
                    WHERE cc.id_indicador = $id_indicador AND cc.id_periodo = $id_periodo
                    AND c.curso_grupo = $id_grupo;";

        try{
            $preparado = $cnx->preparar($cmdsql);
            if($preparado->execute()){
                return $preparado->fetchColumn();
            }else{
                return false;
            }
        }catch(PDOException $e){
            print "Error buscando el indicador: " . $e->getMessage();
        }
    }

    public static function editarIndicadorModel($datos)
    {
        $tabla  = 'indicador';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "UPDATE " . $tabla . " SET nombre = :n, id_dimension = :idm, curso_grupo = :cg WHERE id = :id;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':n', $datos['nombre']);
            $preparado->bindParam(':cg', $datos['grupo']);
            $preparado->bindParam(':idm', $datos['dimension']);
            $preparado->bindParam(':id', $datos['id_indicador']);
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

    public static function mostrarGruposModel()
    {
        $tabla  = 'curso_grupo';
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
}
