<?php
require_once MODELO_PATH . 'conexion.php';

class Inventario extends conexion
{
    private static $instancia;

    public static function singleton_inventario()
    {
        if (!self::$instancia) {
            self::$instancia = new Inventario();
        }
        return self::$instancia;
    }


    public static function actualizarProducto($datos)
    {
        $tabla = 'productos';
        $cnx = conexion::singleton_conexion();

        // Consulta para actualizar el producto
        $cmdsql = "UPDATE $tabla 
                   SET nombre = :nombre, 
                       precio = :precio, 
                       cantidad = cantidad + :cantidadNueva,
                       iva = :iva
                   WHERE id = :idProducto";

        try {
            $preparado = $cnx->preparar($cmdsql);

            // Vincular los parámetros
            $preparado->bindParam(':idProducto', $datos['idProducto'], PDO::PARAM_INT);
            $preparado->bindParam(':nombre', $datos['nombre'], PDO::PARAM_STR);
            $preparado->bindParam(':precio', $datos['precio'], PDO::PARAM_STR);
            $preparado->bindParam(':cantidadNueva', $datos['cantidadNueva'], PDO::PARAM_INT);
            $preparado->bindParam(':iva', $datos['iva'], PDO::PARAM_INT);

            // Ejecutar la consulta
            if ($preparado->execute()) {
                return true; // Retornar true si se ejecutó correctamente
            } else {
                return false; // Retornar false si falló la ejecución
            }
        } catch (PDOException $e) {
            // Mostrar el error si ocurre
            print "Error!: " . $e->getMessage();
            return false;
        }

        // Cerrar la conexión
        $cnx->closed();
        $cnx = null;
    }


    public static function agregarArticuloModel($datos)
    {
        $tabla = 'productos';
        $cnx = conexion::singleton_conexion();

        // Consulta para insertar en la tabla productos
        $cmdsql = "INSERT INTO $tabla (
            nombre,
            precio,
            cantidad,
            iva,
            variaciones,
            categoria,
            estado
        ) VALUES (
            :nombre,
            :precio,
            :cantidad,
            :iva,
            :variaciones,
            :categoria,
            :estado
        );";

        try {
            // Preparar la consulta para productos
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':nombre', $datos['nombre'], PDO::PARAM_STR);
            $preparado->bindParam(':precio', $datos['precio'], PDO::PARAM_STR);
            $preparado->bindParam(':cantidad', $datos['cantidad'], PDO::PARAM_INT);
            $preparado->bindParam(':iva', $datos['iva'], PDO::PARAM_INT);
            $preparado->bindParam(':variaciones', $datos['variacion'], PDO::PARAM_STR);
            $preparado->bindParam(':categoria', $datos['categoria'], PDO::PARAM_STR); //cambio
            $estado = 1; // El valor que se quiere insertar
            $preparado->bindParam(':estado', $estado, PDO::PARAM_INT);

            // Ejecutar el primer insert
            if ($preparado->execute()) {
                // Obtener el nombre del producto
                $nombre_producto = $datos['nombre'];
                $precio = $datos['precio'];
                // Consulta para insertar en la tabla historial
                $tabla_historial = 'historial'; // Nombre de la tabla de historial
                $cmdsql_historial = "INSERT INTO $tabla_historial (
                    accion,
                    fecha
                ) VALUES (
                    :descripcion,
                    :fecha
                );";

                // Preparar el insert en la tabla historial
                $preparado_historial = $cnx->preparar($cmdsql_historial);
                $descripcion = "Se agregó el producto: " . $nombre_producto . "con el precio " . "$" . $precio;
                $fecha = date("Y-m-d");
                $preparado_historial->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
                $preparado_historial->bindParam(':fecha', $fecha, PDO::PARAM_STR);


                // Ejecutar el segundo insert
                if ($preparado_historial->execute()) {
                    return true; // Retornar true si ambos inserts fueron exitosos
                } else {
                    return false; // Retornar false si el segundo insert falla
                }
            } else {
                return false; // Retornar false si el primer insert falla
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
            return false;
        }

        $cnx->closed();
        $cnx = null;
    }

    public static function filtrarVentasPorFechaModel($fecha)
    {
        $tabla = 'ventas';
        $cnx = conexion::singleton_conexion();

        $cmdsql = "SELECT 
            venta_grupal, 
            GROUP_CONCAT(CONCAT(producto, ' (', cantidad, ')') SEPARATOR ', ') AS productos, 
            SUM(precio) AS total_precio, 
            metodopago, 
            DATE(fecha) AS fecha, 
            hora
        FROM 
            $tabla
        WHERE 
            DATE(fecha) = :fecha
        GROUP BY 
            venta_grupal;";

        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':fecha', $fecha, PDO::PARAM_STR);

            if ($preparado->execute()) {
                $resultados = $preparado->fetchAll(PDO::FETCH_ASSOC);

                return $resultados;
            } else {
                throw new Exception("No se pudieron ejecutar los resultados.");
            }
        } catch (PDOException $e) {
            throw new Exception("Error en la consulta: " . $e->getMessage());
        }
    }

    public static function eliminarProductoPorId($idProducto)
    {
        $tabla = 'productos';
        $cnx = conexion::singleton_conexion(); // Obtener la conexión singleton

        $cmdsql = "UPDATE $tabla SET estado = 0 WHERE id = :id";  // Consulta para eliminar el producto
        try {
            $preparado = $cnx->preparar($cmdsql); // Preparar la consulta
            $preparado->bindParam(':id', $idProducto, PDO::PARAM_INT); // Vincular el parámetro

            if ($preparado->execute()) {
                // Si se ejecuta correctamente, retornamos true
                return true;
            } else {
                // Si falla la ejecución, retornamos false
                return false;
            }
        } catch (PDOException $e) {
            // Capturamos el error y lo mostramos
            print "Error!: " . $e->getMessage();
            return false;
        }

        $cnx->closed(); // Cerrar la conexión
        $cnx = null; // Liberar la conexión
    }

    public static function obtenerVentasModel()
    {
        $tabla = 'ventas';
        $cnx = conexion::singleton_conexion();

        // Obtener la fecha de hoy
        $fecha_hoy = date('Y-m-d');

        // Modificar la consulta para incluir la condición de la fecha
        $cmdsql = "SELECT 
        venta_grupal, 
        GROUP_CONCAT(CONCAT(producto, ' (', cantidad, ')') SEPARATOR ', ') AS productos, 
        SUM(precio) AS total_precio, 
        metodopago, 
        fecha, 
        hora
    FROM 
        ventas
    WHERE 
        fecha = :fecha_hoy
    GROUP BY 
        venta_grupal;";

        try {
            $preparado = $cnx->preparar($cmdsql);

            // Pasar la fecha como parámetro a la consulta preparada
            $preparado->bindParam(':fecha_hoy', $fecha_hoy, PDO::PARAM_STR);

            if ($preparado->execute()) {
                return $preparado->fetchAll(PDO::FETCH_ASSOC);
            } else {
                return false;
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
            return false;
        }

        $cnx->closed();
        $cnx = null;
    }


    // Buscar artículos en el inventario

    // Obtener todos los artículos del inventario
    public static function obtenerArticulosModel()
    {
        $tabla = 'productos';
        $cnx = conexion::singleton_conexion();

        $cmdsql = "SELECT * FROM $tabla WHERE estado = 1";
        try {
            $preparado = $cnx->preparar($cmdsql);

            if ($preparado->execute()) {
                return $preparado->fetchAll(PDO::FETCH_ASSOC);
            } else {
                return false;
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
            return false;
        }

        $cnx->closed();
        $cnx = null;
    }

    public static function obtenerArticulosFiltradosModel($filtro)
    {
        $tabla = 'productos';
        $cnx = conexion::singleton_conexion();

        $cmdsql = "SELECT * FROM $tabla WHERE estado = 1 AND nombre LIKE :filtro";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $filtro = '%' . $filtro . '%'; //Agregar el % al principio y al final del filtro
            $preparado->bindParam(':filtro', $filtro, PDO::PARAM_STR);
            if ($preparado->execute()) {
                return $preparado->fetchAll(PDO::FETCH_ASSOC);
            } else {
                return false;
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
            return false;
        }

        $cnx->closed();
        $cnx = null;
    }

    public static function obtenerArticulosAdminModel()
    {
        $tabla = 'productos';
        $cnx = conexion::singleton_conexion();

        $cmdsql = "SELECT * FROM $tabla";
        try {
            $preparado = $cnx->preparar($cmdsql);

            if ($preparado->execute()) {
                return $preparado->fetchAll(PDO::FETCH_ASSOC);
            } else {
                return false;
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
            return false;
        }

        $cnx->closed();
        $cnx = null;
    }

    public static function generarNumeroVentaGrupal()
    {
        $numeroVentaGrupal = random_int(0, 999999); // Genera un número entre 100000 y 999999

        // Verifica que el número no se repita en la base de datos
        $cnx = conexion::singleton_conexion();
        $cmdsql = "SELECT COUNT(*) FROM ventas WHERE venta_grupal = :venta_grupal";

        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':venta_grupal', $numeroVentaGrupal, PDO::PARAM_INT);
            $preparado->execute();
            $existe = $preparado->fetchColumn();

            // Si ya existe, vuelve a generar el número
            if ($existe > 0) {
                return self::generarNumeroVentaGrupal(); // Llama de nuevo a la función si ya existe
            } else {
                return $numeroVentaGrupal; // Si no existe, devuelve el número generado
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
        }

        return false;
    }

    //seccion carrito

    public static function agregarElementosCarritoModel($datos)
    {
        $tabla = 'carrito';
        $cnx = conexion::singleton_conexion();

        // Consulta para insertar en la tabla carrito
        $cmdsql = "INSERT INTO $tabla (
        id_producto,
        producto,
        valor_unidad,
        precio,
        iva,
        cantidad
    ) VALUES (
        :id_producto,
        :producto,
        :valor_unidad,
        :precio,
        :iva,
        :cantidad
    );";

        try {
            // Preparar la consulta
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id_producto', $datos['id_prod'], PDO::PARAM_STR);
            $preparado->bindParam(':producto', $datos['producto'], PDO::PARAM_STR);
            $preparado->bindParam(':valor_unidad', $datos['precio'], PDO::PARAM_STR); // Suponiendo que el precio por unidad es el mismo valor
            $preparado->bindParam(':precio', $datos['precio'], PDO::PARAM_STR);
            $preparado->bindParam(':iva', $datos['iva'], PDO::PARAM_INT);
            $preparado->bindParam(':cantidad', $datos['cantidad'], PDO::PARAM_INT);
            // Ejecutar el insert
            if ($preparado->execute()) {
                return true; // Insert exitoso
            } else {
                return false; // Error en el insert
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
            return false;
        }

        $cnx->closed();
        $cnx = null;
    }

    public static function cobrar($datos_compra)
    {
        $tabla = 'ventas';

        // Conexión a la base de datos
        $cnx = conexion::singleton_conexion();

        $cmdsql = "INSERT INTO $tabla (producto, precio, cantidad, iva, metodopago, fecha, hora, venta_grupal) 
               VALUES (:producto, :precio, :cantidad, :iva, :metodopago, :fecha, :hora, :venta_grupal)";

        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':producto', $datos_compra['producto'], PDO::PARAM_STR);
            $preparado->bindParam(':precio', $datos_compra['precio'], PDO::PARAM_STR); // Cambié a PARAM_STR si el precio es decimal
            $preparado->bindParam(':cantidad', $datos_compra['cantidad'], PDO::PARAM_INT);
            $preparado->bindParam(':metodopago', $datos_compra['metodopago'], PDO::PARAM_STR);
            $preparado->bindParam(':fecha', $datos_compra['fecha'], PDO::PARAM_STR); // Usa 'fecha' directamente
            $preparado->bindParam(':hora', $datos_compra['hora'], PDO::PARAM_STR);   // Usa 'hora' directamente
            $preparado->bindParam(':venta_grupal', $datos_compra['ventagrupal'], PDO::PARAM_INT);
            $preparado->bindParam(':iva', $datos_compra['iva'], PDO::PARAM_INT);

            if ($preparado->execute()) {
                $id = $cnx->ultimoIngreso($tabla);
                $rs = array('id' => $id, 'guardado' => true);
                return $rs;
            } else {
                $rs = array('id' => null, 'guardado' => false);
                return $rs;
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
        }

        return false;
    }

    public static function obtenerInfoCompradorModel($cedula)
    {
        $tabla = 'usuarios';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "SELECT * FROM $tabla u WHERE u.documento = $cedula;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            if ($preparado->execute()) {
                return $preparado->fetch(PDO::FETCH_ASSOC);
            } else {
                return false;
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
            return false;
        }

        $cnx->closed();
        $cnx = null;
    }

    public static function guardarUsuarioVentaModel($datos)
    {
        $tabla = 'info_comprador';
        $cnx = conexion::singleton_conexion();

        $cmdsql = "INSERT INTO $tabla (id_user, nombre, numero_documento, ciudad, direccion, id_venta_grupal, telefono, tipo_doc) 
               VALUES (:id_user, :nombre, :numero_documento, :ciudad, :direccion, :id_venta_grupal, :telefono, :tipo_doc)";

        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id_user', $datos['id_user'], PDO::PARAM_STR);
            $preparado->bindParam(':nombre', $datos['nombre'], PDO::PARAM_STR);
            $preparado->bindParam(':numero_documento', $datos['cedula_comprador'], PDO::PARAM_STR);
            $preparado->bindParam(':ciudad', $datos['ciudad'], PDO::PARAM_STR);
            $preparado->bindParam(':direccion', $datos['direccion'], PDO::PARAM_STR);
            $preparado->bindParam(':id_venta_grupal', $datos['venta_grupal'], PDO::PARAM_INT);
            $preparado->bindParam(':telefono', $datos['telefono'], PDO::PARAM_STR);
            $preparado->bindParam(':tipo_doc', $datos['tipo_doc'], PDO::PARAM_STR);

            if ($preparado->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
            return false;
        }

        $cnx->closed();
        $cnx = null;
    }

    public static function actualizarStock($datos2)
    {
        $tabla = 'productos';
        $cnx = conexion::singleton_conexion();

        // Aquí restamos la cantidad comprada al stock existente
        $cmdsql = "UPDATE $tabla SET cantidad = cantidad - :cantidad WHERE id = :id";

        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id', $datos2['id'], PDO::PARAM_INT);
            $preparado->bindParam(':cantidad', $datos2['cantidad'], PDO::PARAM_INT);

            if ($preparado->execute()) {
                return true;
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
        }

        return false;
    }

    public static function actualizarStock2($id_prod, $cantidad)
    {
        $tabla = 'productos';
        $cnx = conexion::singleton_conexion();

        $cmdsql = "UPDATE $tabla SET cantidad = cantidad + :cantidad WHERE id = :id";

        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id', $id_prod, PDO::PARAM_INT);
            $preparado->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);

            if ($preparado->execute()) {
                return true;
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
        }

        return false;
    }



    public static function BorrarCarritoModel($id)
    {
        $tabla = 'carrito';
        $cnx = conexion::singleton_conexion();

        // Consulta para eliminar el ítem del carrito
        $cmdsql = "DELETE FROM $tabla WHERE id = :id";

        try {
            // Preparar la consulta
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id', $id, PDO::PARAM_INT);

            // Ejecutar la consulta
            return $preparado->execute(); // Retornar true si se ejecuta correctamente
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
            return false;
        }

        $cnx = null; // Cerrar la conexión
    }


    public static function SumarCarritoModel($id, $valor_unidad)
    {
        $tabla = 'carrito';
        $cnx = conexion::singleton_conexion();

        // Consulta para aumentar la cantidad y actualizar el precio
        $cmdsql = "UPDATE $tabla 
               SET cantidad = cantidad + 1, 
                   precio = precio + :valor_unidad 
               WHERE id = :id";

        try {
            // Preparar la consulta
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id', $id, PDO::PARAM_INT);
            $preparado->bindParam(':valor_unidad', $valor_unidad, PDO::PARAM_STR);

            // Ejecutar la consulta
            return $preparado->execute(); // Retornar true si se ejecuta correctamente
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
            return false;
        }

        $cnx->closed();
        $cnx = null;
    }

    public static function RestarCarritoModel($id, $valor_unidad)
    {
        $tabla = 'carrito';
        $cnx = conexion::singleton_conexion();

        // Consulta para restar la cantidad y actualizar el precio, asegurando que la cantidad no sea menor a 1
        $cmdsql = "UPDATE $tabla 
               SET cantidad = cantidad - 1, 
                   precio = precio - :valor_unidad 
               WHERE id = :id";

        try {
            // Preparar la consulta
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id', $id, PDO::PARAM_INT);
            $preparado->bindParam(':valor_unidad', $valor_unidad, PDO::PARAM_STR);

            // Ejecutar la consulta
            return $preparado->execute(); // Retornar true si se ejecuta correctamente
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
            return false;
        }

        $cnx->closed();
        $cnx = null;
    }



    public static function obtenerCarritoModel()
    {
        $tabla = 'carrito';
        $cnx = conexion::singleton_conexion();

        $cmdsql = "SELECT * FROM $tabla";
        try {
            $preparado = $cnx->preparar($cmdsql);

            if ($preparado->execute()) {
                $result = $preparado->fetchAll(PDO::FETCH_ASSOC);
                //var_dump($result); // Verifica el resultado de la consulta
                return $result;
            } else {
                echo "Error en la ejecución de la consulta.";
                return false;
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
            return false;
        }

        $cnx->closed();
        $cnx = null;
    }

    public static function limpiarCarritoModel()
    {
        $tabla = 'carrito';
        $cnx = conexion::singleton_conexion();

        $cmdsql = "DELETE FROM $tabla";
        try {
            $preparado = $cnx->preparar($cmdsql);

            if ($preparado->execute()) {
                $result = $preparado->fetchAll(PDO::FETCH_ASSOC);
                //var_dump($result); // Verifica el resultado de la consulta
                return true;
            } else {
                echo "Error en la ejecución de la consulta.";
                return false;
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
            return false;
        }

        $cnx->closed();
        $cnx = null;
    }

    public static function obtenerInformacionCompradorModel($venta_grupal)
    {
        $tabla = 'info_comprador';
        $cnx = conexion::singleton_conexion();

        $cmdsql = "SELECT * FROM $tabla WHERE id_venta_grupal = :id_venta_grupal";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id_venta_grupal', $venta_grupal, PDO::PARAM_INT);

            if ($preparado->execute()) {
                return $preparado->fetch(PDO::FETCH_ASSOC);
            } else {
                return false;
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
            return false;
        }

        $cnx->closed();
        $cnx = null;
    }

    public static function obtenerInformacionVentaModel($venta_grupal)
    {
        $tabla = 'ventas';
        $cnx = conexion::singleton_conexion();

        $cmdsql = "SELECT * FROM $tabla WHERE venta_grupal = :venta_grupal";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':venta_grupal', $venta_grupal, PDO::PARAM_INT);

            if ($preparado->execute()) {
                return $preparado->fetchAll(PDO::FETCH_ASSOC);
            } else {
                return false;
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
            return false;
        }

        $cnx->closed();
        $cnx = null;
    }

    public static function mostrarCategoriasModel(){
        $tabla = 'categoria_productos';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "SELECT * FROM $tabla";
        try {
            $preparado = $cnx->preparar($cmdsql);
            if ($preparado->execute()) {
                return $preparado->fetchAll(PDO::FETCH_ASSOC);
            } else {
                return false;
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
            return false;
        }

        $cnx->closed();
        $cnx = null;
    }

    public static function traerProductosCategoriasModel($id_categoria){
        $tabla = "productos";
        $cnx = conexion::singleton_conexion();
        $cmdsql = "SELECT * FROM $tabla WHERE categoria = $id_categoria";
        try {
            $preparado = $cnx->preparar($cmdsql);
            if ($preparado->execute()) {
                return $preparado->fetchAll(PDO::FETCH_ASSOC);
            } else {
                return false;
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
            return false;
        }

        $cnx->closed();
    }
}
