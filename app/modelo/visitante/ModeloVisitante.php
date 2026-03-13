<?php
require_once MODELO_PATH . 'conexion.php';

class ModeloVisitante extends conexion
{

    public static function Obtenerproductos() {
        $tabla = 'productos';  // Asignamos el nombre de la tabla
        $cnx = conexion::singleton_conexion();  // Usamos el patrón Singleton para la conexión
        $cmdsql = "SELECT * FROM $tabla";  // Consulta SQL

        try {
            $preparado = $cnx->preparar($cmdsql);  // Preparamos la consulta
            if ($preparado->execute()) {  // Ejecutamos la consulta
                return $preparado->fetchAll(PDO::FETCH_ASSOC);  // Devolvemos los resultados
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();  // Capturamos y mostramos errores
        }

        return false;  // Retornamos false si ocurre un error
    }


    
    public static function Ventas($datos_compra) {
        $fecha = date("Y-m-d");
        $hora = date("H:i:a");
        $tabla = 'carrito';  // Asignamos el nombre de la tabla
        $cnx = conexion::singleton_conexion();  // Usamos el patrón Singleton para la conexión
        $cmdsql = "INSERT INTO $tabla (producto, precio, cantidad, metodopago, fecha, hora) 
                   VALUES (:producto, :precio, :cantidad, :metodopago, :fecha, :hora)";
    
        try {
            $preparado = $cnx->preparar($cmdsql);  // Preparamos la consulta
            $preparado->bindParam(':producto', $datos_compra['nombre'], PDO::PARAM_STR);  // Cambié 'producto' por 'nombre'
            $preparado->bindParam(':precio', $datos_compra['precio'], PDO::PARAM_INT);
            $preparado->bindParam(':cantidad', $datos_compra['cantidad'], PDO::PARAM_INT);
            $preparado->bindParam(':metodopago', $datos_compra['metodopago'], PDO::PARAM_STR);
            $preparado->bindParam(':fecha', $fecha, PDO::PARAM_STR);
            $preparado->bindParam(':hora', $hora, PDO::PARAM_STR);
    
            if ($preparado->execute()) {  // Ejecutamos la consulta
                return true;  // Retornamos true si la venta se guarda correctamente
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();  // Capturamos y mostramos errores
        }
    
        return false;  // Retornamos false si ocurre un error
    }
    
    public static function agregarVisitanteModel($datos)
    {
        $tabla = 'registrovisitante';
        $cnx = conexion::singleton_conexion();
    
        $cmdsql = "INSERT INTO $tabla (
            visitante,
            acudiente,
            nombres,
            celular,
            fecha_ingreso,
            horaingreso,
            duracion,
            horasalida,
            valor,
            metodopago
        ) VALUES (
            :visitante,
            :acudiente,
            :nombres,
            :celular,
            :fechaingreso,
            :horaingreso,
            :duracion,
            :horasalida,
            :valor,
            :metodopago
        );";
    
        try {
            $preparado = $cnx->preparar($cmdsql);
    
            // Asignar los valores a los parámetros
            $preparado->bindParam(':visitante', $datos['visitante'], PDO::PARAM_STR);
            $preparado->bindParam(':acudiente', $datos['acudiente'], PDO::PARAM_STR);
            $preparado->bindParam(':nombres', $datos['nombres'], PDO::PARAM_STR);
            $preparado->bindParam(':celular', $datos['celular'], PDO::PARAM_STR);
            $preparado->bindParam(':fechaingreso', $datos['fechaingreso'], PDO::PARAM_STR);
            $preparado->bindParam(':horaingreso', $datos['horaingreso'], PDO::PARAM_STR);
            $preparado->bindParam(':duracion', $datos['duracion'], PDO::PARAM_INT);
            $preparado->bindParam(':horasalida', $datos['horasalida'], PDO::PARAM_STR);
            $preparado->bindParam(':valor', $datos['valor'], PDO::PARAM_STR);
            $preparado->bindParam(':metodopago', $datos['metodopago'], PDO::PARAM_STR);
    

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

    public static function agregarVisitanteCortesiaModel($datos)
    {
        $tabla = 'registrovisitante';
        $cnx = conexion::singleton_conexion();
    
        $cmdsql = "INSERT INTO $tabla (
            visitante,
            acudiente,
            nombres,
            celular,
            fecha_ingreso,
            horaingreso,
            duracion,
            horasalida,
            valor,
            metodopago,
            tipo_cortesia
        ) VALUES (
            :visitante,
            :acudiente,
            :nombres,
            :celular,
            :fechaingreso,
            :horaingreso,
            :duracion,
            :horasalida,
            :valor,
            :metodopagocortesia,
            :tipo_cortesia
        );";
    
        try {
            $preparado = $cnx->preparar($cmdsql);
    
            // Asignar los valores a los parámetros
            $preparado->bindParam(':visitante', $datos['visitante'], PDO::PARAM_STR);
            $preparado->bindParam(':acudiente', $datos['acudiente'], PDO::PARAM_STR);
            $preparado->bindParam(':nombres', $datos['nombres'], PDO::PARAM_STR);
            $preparado->bindParam(':celular', $datos['celular'], PDO::PARAM_STR);
            $preparado->bindParam(':fechaingreso', $datos['fechaingreso'], PDO::PARAM_STR);
            $preparado->bindParam(':horaingreso', $datos['horaingreso'], PDO::PARAM_STR);
            $preparado->bindParam(':duracion', $datos['duracion'], PDO::PARAM_INT);
            $preparado->bindParam(':horasalida', $datos['horasalida'], PDO::PARAM_STR);
            $preparado->bindParam(':valor', $datos['valor'], PDO::PARAM_STR);
            $preparado->bindParam(':metodopagocortesia', $datos['metodopagocortesia'], PDO::PARAM_STR);
            $preparado->bindParam(':tipo_cortesia', $datos['tipocortesia'], PDO::PARAM_STR);
    

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
    

      public static function getVisitantesPorFecha($fecha_inicio)
    {
        $tabla = 'registrovisitante';
        $cnx = conexion::singleton_conexion();

        // Asegúrate de que $fecha_inicio esté en el formato adecuado
        $fecha_inicio = date('Y-m-d', strtotime($fecha_inicio));

        $cmdsql = "SELECT * FROM $tabla WHERE DATE(fecha_ingreso) = :fecha_inicio";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);

            if ($preparado->execute()) {
                $result = $preparado->fetchAll(PDO::FETCH_ASSOC);

                // Mostrar los resultados para depuración
             

                return $result;
            } else {
                echo "Error al ejecutar la consulta.";
                return false;
            }
        } catch (PDOException $e) {
            echo "Error en la consulta SQL: " . $e->getMessage();
            return false;
        }
        $cnx->closed();
        $cnx = null;
    }


    public static function getVisitantesPorFechaOIntervalo($fecha_inicio, $fecha_fin = null)
    {
        $tabla = 'registrovisitante';
        $cnx = conexion::singleton_conexion();
    
        // Si no se proporciona $fecha_fin o es la misma que $fecha_inicio, se busca solo por un día.
        if (is_null($fecha_fin) || $fecha_inicio == $fecha_fin) {
            $fecha_inicio = date('Y-m-d', strtotime($fecha_inicio));
            $cmdsql = "SELECT * FROM $tabla WHERE DATE(fecha_ingreso) = :fecha_inicio";
            try {
                $preparado = $cnx->preparar($cmdsql);
                $preparado->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
            } catch (PDOException $e) {
                echo "Error en la consulta SQL: " . $e->getMessage();
                return false;
            }
        } else {
            // En caso de rango de fechas
            $fecha_inicio = date('Y-m-d', strtotime($fecha_inicio));
            $fecha_fin = date('Y-m-d', strtotime($fecha_fin));
            $cmdsql = "SELECT * FROM $tabla WHERE DATE(fecha_ingreso) BETWEEN :fecha_inicio AND :fecha_fin";
            try {
                $preparado = $cnx->preparar($cmdsql);
                $preparado->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
                $preparado->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
            } catch (PDOException $e) {
                echo "Error en la consulta SQL: " . $e->getMessage();
                return false;
            }
        }
    
        try {
            if ($preparado->execute()) {
                return $preparado->fetchAll(PDO::FETCH_ASSOC);
            } else {
                echo "Error al ejecutar la consulta.";
                return false;
            }
        } catch (PDOException $e) {
            echo "Error en la consulta SQL: " . $e->getMessage();
            return false;
        }
    
        $cnx->closed();
        $cnx = null;
    }
public static function calcularSumaPorMetodoPago($fecha_inicio, $fecha_fin = null)
{
    $visitantes = self::getVisitantesPorFechaOIntervalo($fecha_inicio, $fecha_fin);

    $suma_por_pago = [];
    $total_valor = 0;
    $total_visitantes = 0; // Variable para sumar la cantidad de visitantes

    if ($visitantes) {
        foreach ($visitantes as $visitante) {
            $metodopago = $visitante['metodopago'];
            $tipo_cortesia = $visitante['tipo_cortesia'] ?? "Entrada Normal"; // Asigna "Entrada Normal" si es null
            $valor = $visitante['valor'];
            $cantidad_visitantes = $visitante['visitante']; // Asegúrate de que este campo exista

            $total_valor += $valor;
            $total_visitantes += $cantidad_visitantes; // Suma la cantidad de visitantes

            // Inicializa el método de pago si no existe
            if (!isset($suma_por_pago[$metodopago])) {
                $suma_por_pago[$metodopago] = [];
            }

            // Inicializa el tipo de cortesía si no existe
            if (!isset($suma_por_pago[$metodopago][$tipo_cortesia])) {
                $suma_por_pago[$metodopago][$tipo_cortesia] = [
                    'total' => 0,
                    'cantidad' => 0 // Inicializa contador de visitantes
                ];
            }

            // Suma el valor y la cantidad de visitantes al tipo de cortesía correspondiente
            $suma_por_pago[$metodopago][$tipo_cortesia]['total'] += $valor;
            $suma_por_pago[$metodopago][$tipo_cortesia]['cantidad'] += $cantidad_visitantes;
        }
    }

    return [
        'suma_por_pago' => $suma_por_pago,
        'total_valor' => $total_valor,
        'total_visitantes' => $total_visitantes // Devuelve el total de visitantes
    ];
}


 /*   public static function calcularSumaPorMetodoPago($fecha_inicio)
    {
        $visitantes = self::getVisitantesPorFecha($fecha_inicio);

        $suma_por_pago = [];
        $total_valor = 0;

        if ($visitantes) {
            foreach ($visitantes as $visitante) {
                $metodopago = $visitante['metodopago'];
                $valor = $visitante['valor'];

                $total_valor += $valor;

                if (!isset($suma_por_pago[$metodopago])) {
                    $suma_por_pago[$metodopago] = 0;
                }
                $suma_por_pago[$metodopago] += $valor;
            }
        }

        return [
            'suma_por_pago' => $suma_por_pago,
            'total_valor' => $total_valor
        ];
    }
*/
    public static function mostrarVisitanteModel()
    {
        $tabla  = 'registrovisitante';
        $cnx    = conexion::singleton_conexion();
        
        // Capturar la fecha actual en el formato 'YYYY-MM-DD'
        $hoy = date('Y-m-d');
        
        // Modificar la consulta para filtrar por la fecha de hoy
        $cmdsql = "SELECT * FROM $tabla WHERE fecha_ingreso = :hoy";  // Asegúrate de que el campo 'fecha' es correcto
    
        try {
            $preparado = $cnx->preparar($cmdsql);
            
            // Asignar la variable de la fecha actual al parámetro :hoy
            $preparado->bindParam(':hoy', $hoy, PDO::PARAM_STR);
            
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
