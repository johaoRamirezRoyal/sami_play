<?php

require_once 'conexion.php';

class IngresoModel extends conexion
{

    public static function verificarUser($nick)
    {
        $cnx = conexion::singleton_conexion();
        $cmd = "SELECT * FROM usuarios WHERE user = '" . $nick . "'";
        try {
            $preparado = $cnx->preparar($cmd);
            if ($preparado->execute()) {
                if ($preparado->rowCount() >= 1) {
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
}
