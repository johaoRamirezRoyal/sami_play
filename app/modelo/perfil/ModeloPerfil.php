<?php
require_once MODELO_PATH . 'conexion.php';

class ModeloPerfil extends conexion
{

    public static function mostrarDatosPerfilModel($datos)
    {
        $tabla  = 'usuarios';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT *, (SELECT p.nombre from perfiles p where p.id = perfil) as nom_perfil FROM " . $tabla . " where id_user = :i";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindValue(':i', $datos, PDO::PARAM_INT);
            if ($preparado->execute()) {
                if ($preparado->rowCount() == 1) {
                    return $preparado->fetch();
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
        }
        $cnx->closed();
        $cnx = null;
    }

    public static function mostrarPerfilesModel()
    {
        $tabla  = 'perfiles';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
        p.*,
        (SELECT COUNT(pr.id) FROM permisos pr WHERE pr.id_perfil = p.id AND pr.activo = 1) AS modulos
        FROM " . $tabla . " p WHERE p.id NOT IN(1) AND p.activo = 1 ORDER BY p.nombre ASC;";
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
        $cnx->closed();
        $cnx = null;
    }

    public static function mostrarLimitesPerfilesModel()
    {
        $tabla  = 'perfiles';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
        p.*,
        (SELECT COUNT(pr.id) FROM permisos pr WHERE pr.id_perfil = p.id AND pr.activo = 1) AS modulos
        FROM " . $tabla . " p ORDER BY p.nombre ASC limit 20;";
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
        $cnx->closed();
        $cnx = null;
    }

    public static function buscarPerfilModel($buscar)
    {
        $tabla  = 'perfiles';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
        p.*,
        (SELECT COUNT(pr.id) FROM permisos pr WHERE pr.id_perfil = p.id AND pr.activo = 1) AS modulos
        FROM " . $tabla . " p WHERE p.nombre LIKE '%" . $buscar . "%' ORDER BY p.nombre ASC;";
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
        $cnx->closed();
        $cnx = null;
    }

    public static function editarPerfilModel($datos)
    {
        $tabla = 'usuarios';
        $cnx   = conexion::singleton_conexion();
        $sql   = "UPDATE " . $tabla . " SET documento = :d,nombre = :n,apellido = :a,correo = :c,telefono = :t,user = :u,pass = :p,perfil = :r, foto_perfil = :fp, user_log = :id WHERE id_user = :id";
        try {
            $preparado = $cnx->preparar($sql);
            $preparado->bindParam(':d', $datos['documento']);
            $preparado->bindParam(':n', $datos['nombre']);
            $preparado->bindParam(':a', $datos['apellido']);
            $preparado->bindParam(':c', $datos['correo']);
            $preparado->bindParam(':t', $datos['telefono']);
            $preparado->bindParam(':u', $datos['usuario']);
            $preparado->bindParam(':p', $datos['pass']);
            $preparado->bindParam(':r', $datos['perfil']);
            $preparado->bindParam(':fp', $datos['foto_perfil']);
            $preparado->bindValue(':id', $datos['id_user']);
            if ($preparado->execute()) {
                $id        = $cnx->ultimoIngreso($tabla);
                $resultado = array('guardar' => true, 'id' => $id);
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

}
