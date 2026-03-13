<?php
date_default_timezone_set('America/Bogota');
require_once MODELO_PATH . 'visitante' . DS . 'ModeloVisitante.php';
require_once MODELO_PATH . 'tienda' . DS . 'Inventario.php';

class ControlVisitante
{

    private static $instancia;

    public static function singleton_visitante()
    {
        if (!isset(self::$instancia)) {
            $miclase         = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }
    
    public function mostrarVisitanteControl()

    {


        $mostrar = ModeloVisitante::mostrarVisitanteModel();


        return $mostrar;


    }

    public function mostrarProductosControl() {
        $productos = ModeloVisitante::Obtenerproductos();  
        
        return $productos;  
    }

 /* public function mostrarVisitantePorFecha($fecha)
{
    // Verificar la fecha proporcionada
   

    // Obtener los visitantes por fecha
    $visitantes = ModeloVisitante::getVisitantesPorFecha($fecha);

    // Verificar si hay resultados
    if (!$visitantes || empty($visitantes)) {
        echo "No se encontraron registros para la fecha: $fecha";
        return;
    }

    // Calcular suma por método de pago
    $suma_por_pago = [];
    $total_valor = 0;

    foreach ($visitantes as $visitante) {
        $metodopago = $visitante['metodopago'];
        $valor = $visitante['valor'];

        $total_valor += $valor;

        if (!isset($suma_por_pago[$metodopago])) {
            $suma_por_pago[$metodopago] = 0;
        }
        $suma_por_pago[$metodopago] += $valor;
    }

    // Retornar los datos a la vista
    return [
        'suma_por_pago' => $suma_por_pago,
        'total_valor' => $total_valor
    ];
 }*/

 

 public function obtenerPrecioProducto($nombre_producto) {
    $cnx = conexion::singleton_conexion();
    $cmdsql = "SELECT precio FROM productos WHERE nombre = :nombre";
    
    try {
        $preparado = $cnx->preparar($cmdsql);
        $preparado->bindParam(':nombre', $nombre_producto, PDO::PARAM_STR);
        $preparado->execute();
        $resultado = $preparado->fetch(PDO::FETCH_ASSOC);

        return $resultado['precio'] ?? null;  // Retornamos el precio o null si no se encuentra
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage();
    }
}

 public function mostrarVisitantePorFecha($fecha_inicio, $fecha_fin = null)
 {
     $fecha_inicio = $_POST['fecha_inicio'];
 $fecha_fin = isset($_POST['fecha_fin']) && !empty($_POST['fecha_fin']) ? $_POST['fecha_fin'] : null;
     // Llamar al modelo para calcular la suma por método de pago, ya sea para un solo día o un rango
     $resultados = ModeloVisitante::calcularSumaPorMetodoPago($fecha_inicio, $fecha_fin);
 
     // Verificar si hay resultados
     if ($resultados) {
         return [
             'suma_por_pago' => $resultados['suma_por_pago'],
             'total_valor' => $resultados['total_valor']
         ];
     } else {
         return [
             'suma_por_pago' => [],
             'total_valor' => 0
         ];
     }
 }

 public function guardarVentaControl() {
        
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_log']) && !empty($_POST['id_log'])) {
        // Preparar los datos para enviarlos al modelo
        $datos_compra = array(
            'id_log'       => $_POST['id_log'],
            'nombre'      => $_POST['nombre'],
            'precio'        => $_POST['precio'],
            'cantidad'        => $_POST['cantidad'],
            'metodopago'   => $_POST['metodopago']
        );
        
        
    $resultado = ModeloVisitante::Ventas($datos_compra);      // Llamamos al método del modelo estáticamente

    if ($resultado) {
        echo '
        <script>
        ohSnap("Agregado al Carrito!", {color: "green", "duration": "5000"});
        setTimeout(recargarPagina, 1050);
        
        function recargarPagina(){
            window.location.replace("index");
        }
        </script>';
    } else {
        echo '
        <script>
        ohSnap("Ha ocurrido un error!", {color: "red", "duration": "5000"});
        </script>';
    }
    return $resultado;  // Retornamos el resultado de la operación
}
}



public function agregarVisitanteControl()
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_log']) && !empty($_POST['id_log'])) {
        // Preparar los datos para enviarlos al modelo
        $datos = array(
            'id_log'       => $_POST['id_log'],
            'visitante'    => $_POST['visitante'],
            'acudiente'    => $_POST['acudiente'],
            'nombres'    => $_POST['nombres'],
            'celular'      => $_POST['celular'],
            'fechaingreso' => $_POST['fechaingreso'],
            'horaingreso'  => $_POST['horaingreso'],
            'duracion'     => $_POST['duracion'],
            'horasalida'   => $_POST['horasalida'],
            'valor'        => $_POST['valor'],
            'metodopago'   => $_POST['metodopago']
        );


        // Llamar al modelo para guardar los datos
        $guardar = ModeloVisitante::agregarVisitanteModel($datos);

        // Verificar si el guardado fue exitoso
        if ($guardar) {
            echo '
            <script>
            ohSnap("Guardado correctamente!", {color: "green", "duration": "5000"});
            setTimeout(recargarPagina, 1050);
            
            function recargarPagina(){
                window.location.replace("index");
            }
            </script>';
        } else {
            echo '
            <script>
            ohSnap("Ha ocurrido un error!", {color: "red", "duration": "5000"});
            </script>';
        }
    }
}




public function agregarVisitanteCortesiaControl()
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_log']) && !empty($_POST['id_log'])) {
        // Preparar los datos para enviarlos al modelo
        $datos = array(
            'id_log'       => $_POST['id_log'],
            'visitante'    => $_POST['visitante'],
            'acudiente'    => $_POST['acudiente'],
            'nombres'      => $_POST['nombres'],
            'celular'      => $_POST['celular'],
            'fechaingreso' => $_POST['fechaingreso'],
            'horaingreso'  => $_POST['horaingreso'],
            'duracion'     => $_POST['duracion'],
            'horasalida'   => $_POST['horasalida'],
            'valor'        => $_POST['valor'],
            'metodopagocortesia'   => $_POST['metodopagocortesia'],
            'tipocortesia' => $_POST['tipocortesia']

        );


        // Llamar al modelo para guardar los datos
        $guardar = ModeloVisitante::agregarVisitanteCortesiaModel($datos);

        // Verificar si el guardado fue exitoso
        if ($guardar) {
            echo '
            <script>
            ohSnap("Guardado correctamente!", {color: "green", "duration": "5000"});
            setTimeout(recargarPagina, 1050);
            
            function recargarPagina(){
                window.location.replace("index");
            }
            </script>';
        } else {
            echo '
            <script>
            ohSnap("Ha ocurrido un error!", {color: "red", "duration": "5000"});
            </script>';
        }
    }
}
    
}
