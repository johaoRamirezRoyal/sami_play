<?php

require_once MODELO_PATH . 'conexion.php';



class ModeloPermisos extends conexion

{



    public static function permisosUsuarioModel($perfil, $opcion)

    {

        $tabla  = 'permisos';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT SQL_NO_CACHE * FROM " . $tabla . " a WHERE a.id_perfil = :p AND a.id_opcion = :o AND a.activo = 1 ORDER BY a.id DESC LIMIT 1;";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindParam(':p', $perfil);

            $preparado->bindParam(':o', $opcion);

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

    public static function permisosUsuarioModelTramites($id_opcion, $perfil)

    {

        $tabla  = 'cron_permisos';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT SQL_NO_CACHE * FROM cron_permisos WHERE id_opcion = :io AND id_perfil = :p AND activo = 1 ORDER BY id DESC LIMIT 1;";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindParam(':io', $id_opcion);

            $preparado->bindParam(':p', $perfil);

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
    



    public static function mostrarModulosModel()

    {

        $tabla  = 'opcion';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT

        SQL_NO_CACHE m.*,

        IF(md.id = 1, m.nombre, CONCAT(md.nombre, ' - ', m.nombre)) AS modulo

        FROM " . $tabla . " m

        LEFT JOIN modulos md ON md.id = m.id_modulo

        WHERE m.activo = 1 ORDER BY modulo ASC;";

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



    public static function opcionesActivasPerfilModel($perfil, $opcion)

    {

        $tabla  = 'permisos';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT SQL_NO_CACHE * FROM " . $tabla . " WHERE id_perfil = :p AND id_opcion = :o AND activo = 1 LIMIT 1;";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindParam(':p', $perfil);

            $preparado->bindParam(':o', $opcion);

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



    public static function inactivarPermisoModel($datos)

    {

        $tabla  = 'permisos';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "DELETE FROM " . $tabla . " WHERE id_perfil = :idp AND id_opcion = :o;";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindParam(':idp', $datos['id_perfil']);

            $preparado->bindParam(':o', $datos['id_opcion']);

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



    public static function activarPermisoModel($datos)

    {

        $tabla  = 'permisos';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "INSERT INTO " . $tabla . " (id_perfil, id_opcion, user_log) VALUES (:idp, :o, :idl);";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindParam(':idp', $datos['id_perfil']);

            $preparado->bindParam(':o', $datos['id_opcion']);

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

}

