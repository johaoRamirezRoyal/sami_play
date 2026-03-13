<?php
date_default_timezone_set('America/Bogota');
require_once CONTROL_PATH . 'Session.php';
$objss = new Session;
$objss->iniciar();
if (!$_SESSION['rol']) {
    $er    = '2';
    $error = base64_encode($er);
    $salir = new Session;
    $salir->iniciar();
    $salir->outsession();
    header('Location:../../login?er=' . $error);
    exit();
}

require_once LIB_PATH . 'tcpdf' . DS . 'tcpdf.php';
require_once CONTROL_PATH . 'tienda' . DS . 'TiendaController.php';
require_once CONTROL_PATH . 'visitante' . DS . 'ControlVisitante.php';

$instancia_tienda = ControlTienda::singleton_tienda();
$instancia_visitante = ControlVisitante::singleton_visitante();

if(isset($_GET['id_venta'])){
    $id_venta = base64_decode($_GET['id_venta']);

    $datos_venta = $instancia_tienda->obtenerInfoVentaControl($id_venta);
    $datos_comprador = $instancia_tienda->obtenerInfoCompradorControl($id_venta);

   // Crear una nueva instancia de TCPDF
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Configuración del documento
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Tu Empresa');
    $pdf->SetTitle('Recibo de Pago');
    $pdf->SetSubject('Recibo de venta');
    $pdf->SetKeywords('TCPDF, PDF, recibo, venta');

    // Eliminar header y footer por defecto
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    // Añadir una página
    $pdf->AddPage();

    $logo_path = PUBLIC_PATH . 'img/logo.png';

    // if (file_exists($logo_path)) {
    //     $pdf->Image($logo_path, 10, 10, 30, 0, 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
    // } else {
    //     $pdf->SetFont('helvetica', 'B', 10);
    //     $pdf->Cell(0, 10, 'Logo no encontrado', 0, 1);
    // }

    // Después de crear la instancia $pdf y antes de generar el contenido
    $html = '<div style="text-align: start;">';
    $html .= '<img src="' . PUBLIC_PATH . 'img/logo.jpg" width="100" />';
    $html .= '</div>';

    // Escribir el HTML en el PDF
    $pdf->writeHTML($html, true, false, true, false, '');

    // Título del recibo
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->Cell(0, 10, 'RECIBO DE PAGO', 0, 1, 'C');
    $pdf->Ln(5);

    // Información de la empresa
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(0, 6, 'Play And Learn', 0, 1, 'C');
    //$pdf->Cell(0, 6, 'NIT: 123456789-0', 0, 1, 'C');
    $pdf->Cell(0, 6, 'Dirección: Cra. 54 #96 43', 0, 1, 'C');
    $pdf->Cell(0, 6, 'Teléfono: 6053225828', 0, 1, 'C');
    $pdf->Ln(10);

    // Información del comprador
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 6, 'INFORMACIÓN DEL COMPRADOR', 0, 1);
    $pdf->SetFont('helvetica', '', 10);

    if (!empty($datos_comprador)) {
        $comprador = $datos_comprador; // Tomamos el primer registro (debería ser el único)
        
        $pdf->Cell(40, 6, 'Nombre:', 0, 0);
        $pdf->Cell(0, 6, $comprador['nombre'], 0, 1);
        
        $pdf->Cell(40, 6, 'Documento:', 0, 0);
        $pdf->Cell(0, 6, $comprador['tipo_doc'] . ' ' . $comprador['numero_documento'], 0, 1);
        
        $pdf->Cell(40, 6, 'Dirección:', 0, 0);
        $pdf->Cell(0, 6, $comprador['direccion'] . ', ' . $comprador['ciudad'], 0, 1);
        
        $pdf->Cell(40, 6, 'Teléfono:', 0, 0);
        $pdf->Cell(0, 6, $comprador['telefono'], 0, 1);
    }

    $pdf->Ln(10);

    // Detalles de la venta
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 6, 'DETALLES DE LA VENTA', 0, 1);
    $pdf->SetFont('helvetica', '', 10);

    if (!empty($datos_venta)) {
        // Encabezados de la tabla
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(70, 6, 'Producto', 1, 0, 'C');
        $pdf->Cell(20, 6, 'Cantidad', 1, 0, 'C');
        $pdf->Cell(30, 6, 'Precio Unit.', 1, 0, 'C');
        $pdf->Cell(30, 6, 'IVA', 1, 0, 'C');
        $pdf->Cell(40, 6, 'Subtotal', 1, 1, 'C');
        
        $pdf->SetFont('helvetica', '', 10);
        
        $total = 0;
        $total_iva = 0;
        
        foreach ($datos_venta as $item) {
            $subtotal = $item['precio'] * $item['cantidad'];
            $iva = $item['iva'] * $item['cantidad'];
            
            $pdf->Cell(70, 6, $item['producto'], 1, 0);
            $pdf->Cell(20, 6, $item['cantidad'], 1, 0, 'C');
            $pdf->Cell(30, 6, number_format($item['precio'], 2, ',', '.'), 1, 0, 'R');
            $pdf->Cell(30, 6, number_format($item['iva'], 2, ',', '.'), 1, 0, 'R');
            $pdf->Cell(40, 6, number_format($subtotal, 2, ',', '.'), 1, 1, 'R');
            
            $total += $subtotal;
            $total_iva += $iva;
        }
        
        // Totales
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(120, 6, 'TOTAL IVA:', 1, 0, 'R');
        $pdf->Cell(40, 6, number_format($total_iva, 2, ',', '.'), 1, 1, 'R');
        
        $pdf->Cell(120, 6, 'TOTAL A PAGAR (IVA incluido):', 1, 0, 'R');
        $pdf->Cell(40, 6, number_format($total, 2, ',', '.'), 1, 1, 'R');
    }

    // Información adicional de la venta
    $pdf->Ln(10);
    $pdf->SetFont('helvetica', '', 10);

    if (!empty($datos_venta)) {
        $primera_venta = $datos_venta[0];
        
        $pdf->Cell(40, 6, 'Fecha:', 0, 0);
        $pdf->Cell(0, 6, $primera_venta['fecha'], 0, 1);
        
        $pdf->cell(40, 6, 'Hora:', 0, 0);
        $pdf->cell(0, 6, $primera_venta['hora'], 0, 1);

        $pdf->Cell(40, 6, 'Método de pago:', 0, 0);
        $pdf->Cell(0, 6, $primera_venta['metodopago'], 0, 1);
        
        $pdf->Cell(40, 6, 'Número de venta:', 0, 0);
        $pdf->Cell(0, 6, $primera_venta['venta_grupal'], 0, 1);
    }

    // Mensaje final
    $pdf->Ln(15);
    $pdf->SetFont('helvetica', 'I', 10);
    $pdf->Cell(0, 6, 'Gracias por su compra!', 0, 1, 'C');
    $pdf->Cell(0, 6, 'Este documento no es válido como factura', 0, 1, 'C');

    // Generar el PDF
    $pdf->Output('recibo_'.$id_venta.'.pdf', 'I');
}