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

require_once LIB_PATH . 'PhpSpreadsheet' . DS . 'vendor' . DS . 'autoload.php';
require_once CONTROL_PATH . 'asistencia' . DS . 'ControlAsistencia.php';

$instancia = ControlAsistencia::singleton_asistencia();

if (isset($_GET['buscar'])) {
    $datos          = array('buscar' => $_GET['buscar'], 'perfil' => $_GET['perfil'], 'fecha' => $_GET['fecha']);
    $datos_usuarios = $instancia->buscarUsuarioAsistenciaGestionControl($datos);
}

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$spreadsheet = new Spreadsheet();

$spreadsheet->getProperties()
->setTitle('Reporte de asistencia')
->setDescription('Este documento fue generado por el sistema');

$sheet = $spreadsheet->setActiveSheetIndex(0);

$estilos_cabecera = [
    'font'      => [
        'bold' => true,
    ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    ],
];

$estilos_datos = [
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    ],
];

$sheet->getStyle('A1:F1')->applyFromArray($estilos_cabecera);
$sheet->getStyle('A:F')->applyFromArray($estilos_datos);

foreach (range('A', 'F') as $column) {
    $sheet->getColumnDimension($column)->setAutoSize(true);
}

$sheet->setCellValue('A1', 'DOCUMENTO')
->setCellValue('B1', 'NOMBRE COMPLETO')
->setCellValue('C1', 'GRUPO')
->setCellValue('D1', 'PERFIL')
->setCellValue('E1', 'FECHA DE ASISTENCIA')
->setCellValue('F1', 'HORA DE ASISTENCIA');

$cont = 2;

foreach ($datos_usuarios as $usuario) {

    $documento        = $usuario['documento'];
    $nombre_completo  = $usuario['nom_user'];
    $perfil           = $usuario['perfil'];
    $fecha_asistencia = $usuario['fecha_asistencia'];
    $hora             = $usuario['hora_asistencia'];
    $nom_grupo        = $usuario['nom_grupo'];

    $sheet->setCellValue('A' . $cont, $documento)
    ->setCellValue('B' . $cont, $nombre_completo)
    ->setCellValue('C' . $cont, $nom_grupo)
    ->setCellValue('D' . $cont, $perfil)
    ->setCellValue('E' . $cont, $fecha_asistencia)
    ->setCellValue('F' . $cont, $hora);

    $cont++;
}

$spreadsheet->getActiveSheet()->setTitle('Hoja 1');

$fileName = "Reporte_asistencia_" . date('Y-m-d') . ".xlsx";
$writer   = new Xlsx($spreadsheet);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . urlencode($fileName) . '"');
$writer->save('php://output');
