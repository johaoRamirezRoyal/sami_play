<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once MODELO_PATH . 'tienda' . DS . 'Inventario.php';

class ControlTienda
{
    private static $instancia;

    public static function singleton_tienda()
    {
        if (!isset(self::$instancia)) {
            $miclase         = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }

    //seccion Carrito    

    const IVA = 0.19;

    public static function ObtenerCarritoControl()
    {
        $carrito = Inventario::ObtenerCarritoModel();
        // var_dump($carrito);
        return $carrito;
    }

    public function cobrarCarritoControl()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['producto']) && !empty($_POST['producto'])) {

            // Si 'producto' es un array, significa que hay múltiples productos
            $ventagrupal = Inventario::generarNumeroVentaGrupal();



            if (is_array($_POST['producto'])) {
                $productos_carrito = count($_POST['producto']);
                $resultado = true; // Variable para saber si todas las inserciones fueron exitosas
                $fecha = date("Y-m-d");
                $hora = date("H:i:s");

                for ($i = 0; $i < $productos_carrito; $i++) {
                    $datos_compra = array(
                        'producto'     => $_POST['producto'][$i] ?? null,
                        'precio'     => $_POST['precio'][$i] ?? null,
                        'cantidad'   => $_POST['cantidad'][$i] ?? null,
                        'metodopago' => $_POST['metodopago'] ?? null, // Asumimos que el método de pago es único 
                        'fecha' => $fecha,
                        'hora' => $hora,
                        'ventagrupal' => $ventagrupal,
                        'iva' => $_POST['iva'][$i] ?? null,
                    );

                    $resultado = Inventario::cobrar($datos_compra); // && $resultado;
                }


                if ($resultado['guardado'] == true) {

                    $datos_usuario = array(
                        'id_user' => $_POST['id_user'] ?? null,
                        'venta_grupal' => $ventagrupal,
                        'cedula_comprador' => $_POST['cedula_comprador_registrada'] ?? null,
                        'nombre' => $_POST['nombre_usuario'] ?? null,
                        'ciudad' => $_POST['ciudad'] ?? null,
                        'direccion' => $_POST['direccion'] ?? null,
                        'telefono' => $_POST['telefono'] ?? null,
                        'tipo_doc' => $_POST['tipo_documento'] ?? null,
                    );

                    $guardado_info_user = Inventario::guardarUsuarioVentaModel($datos_usuario);

                    if ($guardado_info_user == false) {
                        echo 'Error al guardar el usuario';
                        die();
                    }

                    Inventario::limpiarCarritoModel();

                    echo '
                    <script>
                    ohSnap("Compra realizada con éxito!", {color: "green", "duration": "5000"});
                    setTimeout(recargarPagina, 1050);
                    
                    function recargarPagina(){
                        window.location.replace("'. BASE_URL . 'imprimir/ventas/reciboPago?id_venta=' . base64_encode($ventagrupal) . '");
                    }
                    </script>';
                } else {
                    echo '
                    <script>
                    ohSnap("Ha ocurrido un error en la compra!", {color: "red", "duration": "5000"});
                    </script>';
                }

                return $resultado;
            } else {
                echo '
                <script>
                ohSnap("El comprador no tiene ningún producto en el carrito de compra!", {color: "red", "duration": "5000"});
                </script>
                ';
            }
        }
    }


    public function traerInfoCompradorControl($cedula)
    {
        $info = Inventario::obtenerInfoCompradorModel($cedula);
        return $info;
    }


    public static function LimpiarCarritoControl()
    {
        $limpiar = Inventario::limpiarCarritoModel();
        if ($limpiar) {
            echo '
            <script>
            ohSnap("Carrito vaciado correctamente!", {color: "blue", "duration": "5000"});
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

    public static function AgregarElementoCarritoControl()
    {
        if (isset($_POST['carrito'])) {
            // Recibir los datos enviados desde el formulario
            $datos = [
                'id_prod' => $_POST['id'],
                'producto' => $_POST['nombre_producto'],
                'precio' => $_POST['precio_total_producto'], // Precio total calculado
                'cantidad' => $_POST['cantidad_producto'],
                'iva' => $_POST['iva']
            ];

            // Datos para actualizar el inventario
            $datos2 = [
                'id' => $_POST['id'],
                'cantidad' => $_POST['cantidad_producto']
            ];


            // Llamar al modelo para guardar los datos en el carrito
            $guardarinventario = Inventario::agregarElementosCarritoModel($datos);

            if ($guardarinventario) {
                // Llamar al modelo para actualizar el stock del producto
                $actualizarstock = Inventario::actualizarStock($datos2);

                if ($actualizarstock) {
                    echo '<script>
                        ohSnap("Artículo agregado correctamente al carrito!", {color: "green", "duration": "5000"});
                        setTimeout(recargarPagina, 1050);
    
                        function recargarPagina(){
                            window.location.replace("index");
                        }
                        </script>';
                } else {
                    echo '<script>ohSnap("Error al actualizar el stock del producto!", {color: "red", "duration": "5000"});</script>';
                }
            } else {
                echo '<script>ohSnap("Ha ocurrido un error al agregar el artículo al carrito!", {color: "red", "duration": "5000"});</script>';
            }
        }
    }



    public static function BorrarCarrito()
    {
        if (isset($_POST['borrar'])) {
            // Recuperar los datos del formulario
            $id_carrito = $_POST['id_carrito'];  // ID en la tabla carrito
            $id_producto = $_POST['id_producto']; // ID en la tabla productos
            $cantidad = $_POST['cantidad'];      // Cantidad del producto

            // Actualizar el stock sumando la cantidad eliminada al stock existente
            $actualizarStock = Inventario::actualizarStock2($id_producto, $cantidad);

            if ($actualizarStock) {
                // Eliminar el producto del carrito
                Inventario::BorrarCarritoModel($id_carrito);

                echo '
                <script>
                    window.location.replace("index");
                </script>
                ';
            } else {
                echo "Error al actualizar el stock.";
            }
        }
    }



    public static function SumarCarrito()
    {
        if (isset($_POST['sumar'])) {
            $id = $_POST['id_producto']; // Recuperar el ID del producto
            $valor_unidad = $_POST['precio']; // Recuperar el valor de la unidad

            // Llamar al modelo para sumar la cantidad y el precio
            $sumar = Inventario::SumarCarritoModel($id, $valor_unidad);

            echo '
            <script>
                function recargarPagina(){
                    window.location.replace("index");
                }
                recargarPagina(); // Llamamos la función de recarga
            </script>
            ';
        }
    }


    public static function RestarCarrito()
    {
        if (isset($_POST['restar'])) {
            $id = $_POST['id_producto']; // Recuperar el ID del producto
            $valor_unidad = $_POST['precio']; // Recuperar el valor de la unidad

            // Llamar al modelo para restar la cantidad y el precio
            $restar = Inventario::RestarCarritoModel($id, $valor_unidad);

            echo '
            <script>
                function recargarPagina(){
                    window.location.replace("index");
                }
                recargarPagina(); // Llamamos la función de recarga
            </script>
            ';
        }
    }


    public function agregarArticuloControl()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nombre']) && !empty($_POST['nombre'])) {
            $variaciones = $_POST['variaciones'] ?? [null]; // Si no hay, al menos 1 sin variación
            $resultados = [];

            foreach ($variaciones as $variacion) {
                $datos = array(
                    'nombre' => $_POST['nombre'],
                    'precio' => $_POST['precio'],
                    'cantidad'  => $_POST['cantidad'],
                    'iva' => $_POST['iva'],
                    'categoria' => $_POST['categoria'], //cambio
                    'variacion' => !empty($variacion) ? $variacion : null
                );

                if ($datos['iva'] == '0') {
                    $datos['iva'] = 0;
                } else {
                    $datos['iva'] = $datos['precio'] * self::IVA;
                }

                $guardado = Inventario::agregarArticuloModel($datos);
                $resultados[] = $guardado;
            }

            // Verificamos si todos se guardaron
            if (!in_array(false, $resultados)) {
                echo '
                <script>
                ohSnap("Todos los artículos se guardaron correctamente!", {color: "green", "duration": "5000"});
                setTimeout(recargarPagina, 1050);
                function recargarPagina(){
                    window.location.replace("index");
                }
                </script>';
            } else {
                echo '
                <script>
                ohSnap("Uno o más artículos no se guardaron correctamente.", {color: "red", "duration": "5000"});
                </script>';
            }
        }
    }



    public static function procesarFormularioActualizacion()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_articulo']) && !empty($_POST['id_articulo'])) {
            // Obtener los valores del formulario
            $datos = array(
                'idProducto' => $_POST['id_articulo'],
                'nombre' => $_POST['nombre'],
                'precio' => $_POST['precio'],
                'cantidad' => $_POST['cantidad'], // Cantidad actual (deshabilitada en el frontend)
                'cantidadNueva' => $_POST['cantidad_nueva'], // Cantidad para agregar
                'iva' => $_POST['precio'] * self::IVA
            );
            // Asegúrate de que el campo cantidad nueva no esté vacío y sea un número

            // Llamamos al método para actualizar el producto
            $resultadoo = Inventario::actualizarProducto($datos);

            if ($resultadoo) {
                echo '
                    <script>
                    ohSnap("Artículo eliminado correctamente!", {color: "green", "duration": "5000"});
                    setTimeout(recargarPagina, 1050);
        
                    function recargarPagina(){
                        window.location.replace("index");
                    }
                    </script>';
            } else {
                echo '
                    <script>
                    ohSnap("Ha ocurrido un error al eliminar el artículo!", {color: "red", "duration": "5000"});
                    </script>';
            }
        }
    }



    public static function eliminarArticuloControl()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_articulo']) && !empty($_POST['id_articulo'])) {
            // Preparar los datos para enviarlos al modelo
            $idProducto = $_POST['id_articulo'];

            // Llamar al modelo para eliminar el producto
            $eliminarArticulo = Inventario::eliminarProductoPorId($idProducto);

            // Verificar si la eliminación fue exitosa
            if ($eliminarArticulo) {
                echo '
                <script>
                ohSnap("Artículo deshabilitado correctamente!", {color: "green", "duration": "5000"});
                setTimeout(recargarPagina, 1050);
    
                function recargarPagina(){
                    window.location.replace("index");
                }
                </script>';
            } else {
                echo '
                <script>
                ohSnap("Ha ocurrido un error al eliminar el artículo!", {color: "red", "duration": "5000"});
                </script>';
            }
        }
    }



    public function filtrarVentasPorFechaControl()
    {
        if (isset($_POST['fecha']) && !empty($_POST['fecha'])) {
            $fecha = $_POST['fecha'];
            $ventas = Inventario::filtrarVentasPorFechaModel($fecha);

            $totalVentas = 0;
            $totalVentasMedias = 0;
            $metodoPagoSuma = [];
            $metodoPagoMedias = [];

            foreach ($ventas as $venta) {
                $metodoPago = $venta['metodopago'];

                // Filtrar ventas de "medias" (no las incluimos en la suma general)
                if (strpos(strtolower($venta['productos']), 'medias') !== false) {
                    // Sumar solo en el filtro de "medias"
                    $totalVentasMedias += $venta['total_precio'];

                    // Agrupar por método de pago para "medias"
                    if (!isset($metodoPagoMedias[$metodoPago])) {
                        $metodoPagoMedias[$metodoPago] = 0;
                    }
                    $metodoPagoMedias[$metodoPago] += $venta['total_precio'];
                } else {
                    // Sumar solo las ventas que no son de "medias" en el total general
                    $totalVentas += $venta['total_precio'];

                    // Agrupar por método de pago (excluyendo las "medias")
                    if (!isset($metodoPagoSuma[$metodoPago])) {
                        $metodoPagoSuma[$metodoPago] = 0;
                    }
                    $metodoPagoSuma[$metodoPago] += $venta['total_precio'];
                }
            }

            return [
                'ventas' => $ventas,
                'totalVentas' => $totalVentas,
                'metodoPagoSuma' => $metodoPagoSuma,
                'totalVentasMedias' => $totalVentasMedias,   // Devuelve el total de medias
                'metodoPagoMedias' => $metodoPagoMedias      // Devuelve los métodos de pago de medias
            ];
        } else {
            return [
                'ventas' => [],
                'totalVentas' => 0,
                'metodoPagoSuma' => [],
                'totalVentasMedias' => 0,  // Inicializa
                'metodoPagoMedias' => []   // Inicializa
            ];
        }
    }




    // Mostrar todos los artículos
    public function mostrarArticulosControl()
    {
        $articulos = Inventario::obtenerArticulosModel();
        return $articulos;
    }

    public function obtenerDetallesVenta($ventaId)
    {
        $venta = $this->modelo->obtenerVentaPorId($ventaId);
        return $venta;
    }

    public function mostrarHistorialVentasControl()
    {
        $fecha = isset($_POST['fecha']) ? $_POST['fecha'] : date('Y-m-d');
        $ventas = Inventario::obtenerVentasModel();
        return $ventas;
        echo '<script>
        window.location.href = "index.php#tabla-compras";
    </script>';
    }
    // Buscar artículos por término de búsqueda
    public function buscarArticulosControl($termino)
    {
        $articulos = Inventario::buscarArticulosModel($termino);
        return $articulos;
    }


    // Agregar un nuevo artículo


    // Agregar stock a un artículo existente
    public function agregarStockControl()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_articulo']) && !empty($_POST['id_articulo'])) {
            // Preparar los datos para enviarlos al modelo
            $id_articulo = $_POST['id_articulo'];
            $cantidad = $_POST['cantidad'];

            // Llamar al modelo para agregar stock
            $guardar1 = Inventario::agregarStockModel($id_articulo, $cantidad);

            // Verificar si el stock fue actualizado exitosamente
            if ($guardar1) {
                echo '
                <script>
                ohSnap("Stock actualizado correctamente!", {color: "green", "duration": "5000"});
                setTimeout(recargarPagina, 1050);
                
                function recargarPagina(){
                    window.location.replace("index");
                }
                </script>';
            } else {
                echo '
                <script>
                ohSnap("Ha ocurrido un error al actualizar el stock!", {color: "red", "duration": "5000"});
                </script>';
            }
        }
    }

    public function obtenerInfoCompradorControl($venta_grupal)
    {
        $info = Inventario::obtenerInformacionCompradorModel($venta_grupal);
        return $info;
    }

    public function obtenerInfoVentaControl($venta_grupal)
    {
        $info = Inventario::obtenerInformacionVentaModel($venta_grupal);
        return $info;
    }

    public function obtenerArticulosFiltradosControl($filtro)
    {
        $articulos = Inventario::obtenerArticulosFiltradosModel($filtro);
        return $articulos;
    }

    public function mostrarCategoriasControl(){
        $categorias = Inventario::mostrarCategoriasModel();
        return $categorias;
    }

    public function traerProductosCategoriasControl($id_categoria){
        $productos = Inventario::traerProductosCategoriasModel($id_categoria); //Nuevo
        return $productos;
    }
}
