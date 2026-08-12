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

    public static function agregarInformacionAdicionalModel($datos)
    {
        $tabla = 'info_adicional_usuarios';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO 
                $tabla (id_user,tipo_documento, fecha_expedicion, fecha_nacimiento, departamento_nacimiento, direccion_vivienda, genero, estrato) 
                VALUES (:id_user, :tipo_doc, :fecha_expedicion, :fecha_nacimiento, :departamento_nacimiento, :direccion, :genero, :estrato)";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id_user', $datos['id_user']);
            $preparado->bindParam(':tipo_doc', $datos['tipo_doc']);
            $preparado->bindParam(':fecha_expedicion', $datos['fecha_expedicion']);
            $preparado->bindParam(':fecha_nacimiento', $datos['fecha_nacimiento']);
            $preparado->bindParam(':departamento_nacimiento', $datos['departamento_nacimiento']);
            $preparado->bindParam(':direccion', $datos['direccion']);
            $preparado->bindParam(':genero', $datos['genero']);
            $preparado->bindParam(':estrato', $datos['estrato']);
            if ($preparado->execute()) {
                $id = $cnx->ultimoIngreso($tabla);
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

    public static function borrarInformacionAdicionalAntiguaModel($id_user, $id_info)
    {
        $tabla = 'info_adicional_usuarios';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "DELETE FROM $tabla WHERE id_user = :id_user AND id <> :id_info";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindValue(':id_user', $id_user);
            $preparado->bindValue(':id_info', $id_info);
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

    public static function agregarDocumentoIdentidadModel($datos)
    {
        $tabla = 'info_adicional_usuarios';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "UPDATE $tabla
                    SET cedula_doc = :cedula_doc
                    WHERE id_user = :id_user AND id = :id;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':cedula_doc', $datos['cedula_doc']);
            $preparado->bindParam(':id_user', $datos['id_user']);
            $preparado->bindParam(':id', $datos['id']);
            if ($preparado->execute()) {
                $respuesta = array('guardar' => true, 'id' => $datos['id']);
                return $respuesta;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
        }
        $cnx->closed();
        $cnx = null;
    }

    public static function mostrarInformacionAdicionalModel($id)
    {
        $tabla = 'info_adicional_usuarios';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "SELECT * FROM $tabla iau 
                    WHERE iau.id_user = :id_user
                    ORDER BY iau.fecha_reg DESC";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindValue(':id_user', $id);
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

    public static function editarNumeroTelefonicoModel($datos)
    {
        $tabla = "usuarios";
        $cnx = conexion::singleton_conexion();
        $cmdsql = "UPDATE $tabla SET telefono = :telefono WHERE id_user = :id_user;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':telefono', $datos['telefono']);
            $preparado->bindParam(':id_user', $datos['id_user']);
            if ($preparado->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
        }
    }

    public static function guardarArchivoFormacionModel($datos)
    {
        $tabla = 'certificado_formacion';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO $tabla (nombre_archivo, id_formacion, id_user) VALUES (:n, :id, :id_log)";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':n', $datos['nombre']);
            $preparado->bindParam(':id', $datos['id_formacion']);
            $preparado->bindParam(':id_log', $datos['id_log']);
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

    public static function eliminarArchivoFormacionModel($id)
    {
        $tabla = 'certificado_formacion';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "DELETE FROM $tabla 
                    WHERE id_formacion = :id";
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

    public static function agregarFormacionPerfilModel($datos)
    {
        $tabla = 'formacion';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO $tabla (id_user, programa, institucion, fecha_grado, fecha_expedicion_certi, duracion, tipo_formacion) 
                    VALUES (:id_user, :programa, :institucion, :fecha_grado, :fecha_expedicion_certi, :duracion, :tipo_formacion)";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id_user', $datos['id_user']);
            $preparado->bindParam(':programa', $datos['programa']);
            $preparado->bindParam(':institucion', $datos['institucion']);
            $preparado->bindParam(':fecha_grado', $datos['fecha_grado']);
            $preparado->bindParam(':fecha_expedicion_certi', $datos['fecha_expedicion_certi']);
            $preparado->bindParam(':duracion', $datos['duracion']);
            $preparado->bindParam(':tipo_formacion', $datos['tipo_formacion']);
            if ($preparado->execute()) {
                $id = $cnx->ultimoIngreso($tabla);
                $respuesta = array('guardar' => true, 'id' => $id);
                return $respuesta;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
        }
        $cnx->closed();
        $cnx = null;
    }

    public static function eliminarFormacionModel($id)
    {
        $tabla = 'formacion';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "DELETE FROM $tabla 
                    WHERE id = :id";
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

    public static function mostrarFormacionesFormalesUsuarioModel($id_user)
    {
        $tabla = 'formacion';
        $cnx = conexion::singleton_conexion();
        $cmdsql = 'SELECT *
                    FROM formacion 
                    where id_user = :id_user 
                    and tipo_formacion = "formal"
                    ORDER BY fecha_grado ASC;';
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id_user', $id_user);
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

    public static function mostrarFormacionesInformalesUsuarioModel($id_user)
    {
        $tabla = 'formacion';
        $cnx = conexion::singleton_conexion();
        $cmdsql = 'SELECT *
                    FROM formacion 
                    where id_user = :id_user 
                    and tipo_formacion = "informal"
                    ORDER BY fecha_expedicion_certi DESC;';
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id_user', $id_user);
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

    public static function mostrarInformacionCertificadoFormacionModel($id_formacion)
    {
        $tabla = "certificado_formacion";
        $cnx = conexion::singleton_conexion();
        $cmdsql = "SELECT * FROM $tabla WHERE id_formacion = :id_formacion;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id_formacion', $id_formacion);
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

    public static function insertarNuevaExperienciaLaboralModel($datos)
    {
        $cnx = conexion::singleton_conexion();
        $sql = 'insert into experiencia_laboral(nombre_empresa, cargo, fecha_ingreso, fecha_retiro, fecha_certificado, id_user)
                values(:nombre_empresa, :cargo, :fecha_ingreso, :fecha_retiro, :fecha_certificado, :id_user);';
        try {
            $preparado = $cnx->preparar($sql);
            $preparado->bindParam(':nombre_empresa', $datos['nombre_empresa']);
            $preparado->bindParam(':cargo', $datos['cargo']);
            $preparado->bindParam(':fecha_ingreso', $datos['fecha_ingreso']);
            $preparado->bindParam(':fecha_retiro', $datos['fecha_retiro']);
            $preparado->bindParam(':id_user', $datos['id_user']);
            $preparado->bindParam(':fecha_certificado', $datos['fecha_certificado']);
            $preparado->setFetchMode(PDO::FETCH_ASSOC);
            if ($preparado->execute()) {
                $id = $cnx->ultimoIngreso('experiencia_laboral');
                $respuesta = array('guardar' => true, 'id' => $id);
                return $respuesta;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            print 'Error!: ' . $e->getMessage();
        }
        $cnx->closed();
        $cnx = null;
    }

    public static function eliminarExperienciaLaboralModel($id)
    {
        $tabla = 'experiencia_laboral';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "DELETE FROM $tabla 
                    WHERE id = :id";
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

    public static function agregarDocumentoExperienciaModel($nombre_doc, $id_experiencia)
    {
        $tabla = 'experiencia_laboral';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "UPDATE $tabla
                    SET certificado_trabajo = :nombre_doc
                    WHERE id = :id_experiencia;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':nombre_doc', $nombre_doc);
            $preparado->bindParam(':id_experiencia', $id_experiencia);
            $preparado->setFetchMode(PDO::FETCH_ASSOC);
            if ($preparado->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            print 'Error!: ' . $e->getMessage();
        }
        $cnx->closed();
        $cnx = null;
    }

    public static function mostrarTodasLasExperienciasLaboralesUserModel($id_user)
    {
        $tabla = 'experiencia_laboral';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "SELECT * FROM $tabla where id_user = :id_user;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id_user', $id_user);
            $preparado->setFetchMode(PDO::FETCH_ASSOC);
            if ($preparado->execute()) {
                return $preparado->fetchAll();
            } else {
                return false;
            }
        } catch (PDOException $e) {
            print 'Error!: ' . $e->getMessage();
        }
        $cnx->closed();
        $cnx = null;
    }

    public static function agregarNuevoDocumentoVariadoModel($datos)
    {
        $tabla = 'documentos_varios';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO $tabla (tipo_doc, id_user, nombre_doc) VALUES (:tipo_doc, :id_user, :nombre_doc);";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':tipo_doc', $datos['tipo_doc']);
            $preparado->bindParam(':id_user', $datos['id_user']);
            $preparado->bindParam(':nombre_doc', $datos['nombre_doc']);
            if ($preparado->execute()) {
                $id = $cnx->ultimoIngreso($tabla);
                $respuesta = array('guardar' => true, 'id' => $id);
                return $respuesta;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            print 'Error!: ' . $e->getMessage();
        }
        $cnx->closed();
        $cnx = null;
    }

    public static function eliminarDocumentoVariosModel($id)
    {
        $tabla = 'documentos_varios';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "DELETE FROM $tabla 
                    WHERE id = :id";
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

    public static function mostrarDocumentosVariosUsuarioModel($id_user)
    {
        $tabla = 'documentos_varios';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "SELECT * FROM $tabla WHERE id_user = :id_user;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id_user', $id_user);
            if ($preparado->execute()) {
                return $preparado->fetchAll();
            } else {
                return false;
            }
        } catch (PDOException $e) {
            print 'Error!: ' . $e->getMessage();
        }
        $cnx->closed();
        $cnx = null;
    }

    public static function agregarProduccionIntelectualModel($datos)
    {
        $tabla = 'produccion_intelectual';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO $tabla 
        (id_user, nombre, tipo_produccion, denominacion, 
        objetivo, descripcion_actividades, duracion, 
        lugar, evidencia_pdf, observacion) VALUES (:id_user, :nombre_produccion, :tipo_produccion, :denominacion_produccion,
        :objetivo_produccion, :descipcion_produccion, :duracion, :lugar, :evidencia_produccion, :observaciones);";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id_user', $datos['id_user']);
            $preparado->bindParam(':nombre_produccion', $datos['nombre_produccion']);
            $preparado->bindParam(':tipo_produccion', $datos['tipo_produccion']);
            $preparado->bindParam(':denominacion_produccion', $datos['denominacion_produccion']);
            $preparado->bindParam(':objetivo_produccion', $datos['objetivo_produccion']);
            $preparado->bindParam(':descipcion_produccion', $datos['descipcion_produccion']);
            $preparado->bindParam(':duracion', $datos['duracion']);
            $preparado->bindParam(':lugar', $datos['lugar']);
            $preparado->bindParam(':evidencia_produccion', $datos['evidencia_produccion']);
            $preparado->bindParam(':observaciones', $datos['observaciones']);
            $preparado->setFetchMode(PDO::FETCH_ASSOC);
            if ($preparado->execute()) {
                $id = $cnx->ultimoIngreso($tabla);
                $rs = array('guardar' => true, 'id' => $id);
                return $rs;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            print 'Error!: ' . $e->getMessage();
        }
        $cnx->closed();
        $cnx = null;
    }

    public static function eliminarProduccionIntelectualModel($id)
    {
        $tabla = 'produccion_intelectual';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "DELETE FROM $tabla 
                    WHERE id = :id";
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

    public static function mostrarProduccionIntelectualUsuarioModel($id_user)
    {
        $tabla = 'produccion_intelectual';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "SELECT * FROM $tabla WHERE id_user = :id_user;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id_user', $id_user);
            $preparado->setFetchMode(PDO::FETCH_ASSOC);
            if ($preparado->execute()) {
                return $preparado->fetchAll();
            } else {
                return false;
            }
        } catch (PDOException $e) {
            print 'Error!: ' . $e->getMessage();
        }
        $cnx->closed();
        $cnx = null;
    }

}
