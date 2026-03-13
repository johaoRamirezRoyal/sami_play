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
require_once CONTROL_PATH . 'calificacion' . DS . 'ControlCalificacion.php';

$instancia = ControlCalificacion::singleton_calificacion();

if (isset($_GET['boletin'])) {
    $id_boletin = base64_decode($_GET['boletin']);

    $datos_boletin     = $instancia->mostrarDatosBoletinControl($id_boletin);
    $datos_dimensiones = $instancia->obtenerNotasConIndicadoresYDimensionesControl($id_boletin);

    //$datos_notas = $instancia->obtenerNotasConIndicadoresYDimensionesControl($id_boletin);

    //echo $id_boletin;
    //exit ();

    class MYPDF extends TCPDF
    {

        public function setData($logo)
        {
            $this->logo = $logo;
        }

        public function Cabecera($datos)
        {
            $cabecera = '
            <table border="1" cellpadding="3">
            <tr style="text-align: center;">
            <td rowspan="3" style="width: 20%;"><img src="' . PUBLIC_PATH . 'img/logo.png" width="100"></td>
            <td rowspan="3" style="width: 50%; background-color: rgb(204,204,204); font-weight:bold;">
            CENTRO DE DESARROLLO Y ESTIMULACIÓN PLAY AND LEARN
            <br>
            INFORME VALORATIVO
            <br>
            ' . $datos['nom_periodo'] . ' TRIMESTRE
            </td>
            <td style="width: 30%; background-color: rgb(204,204,204);"><span style="font-weight:bold">A&ntilde;o Lectivo: </span>' . $datos['anio_boletin'] . '</td>
            </tr>
            <tr>
            <td style="width: 30%; background-color: rgb(204,204,204);"><span style="font-weight:bold">Nivel: </span>' . $datos['nom_curso'] . '</td>
            </tr>
            <tr>
            <td style="width: 30%; background-color: rgb(204,204,204);"><span style="font-weight:bold">Ausencias: </span>' . $datos['ausencia'] . '</td>
            </tr>
            <tr>
            <td colspan="2" style="text-align: left;"><span style="font-weight:bold;">Nombre del Estudiante: </span>' . $datos['nom_estudiante'] . '</td>
            <td style="text-align: center;"><span style="font-weight:bold;">Fecha: </span>' . date('d-m-Y', strtotime($datos['fecha_fin'])) . '</td>
            </tr>
            </table>
            <br>
            <br>
            <table border="1" cellpadding="3">
                <tr>
                    <th style="text-align: left; background-color: rgb(204, 204, 204);">LA: Logro alcanzado</th>
                    <th style="text-align: left; background-color: rgb(204, 204, 204);">LD: Logro en desarrollo</th>
                    <th style="text-align: left; background-color: rgb(204, 204, 204);">NA: Necesita apoyo</th>
                </tr>
            </table>
            <br>            
            ';

            $this->Ln(2);
            $this->SetFont(PDF_FONT_NAME_MAIN_CENTURY, '', 10);
            $this->Cell(10);
            $this->writeHTMLCell(170, 0, '', '', $cabecera, '', 1, 0, true, 'C', true);
        }

        public function Header() {}

        public function Footer()
        {
            $this->SetY(-15);
            $this->SetFillColor(127);
            $this->SetTextColor(127);
            $this->SetFont(PDF_FONT_NAME_MAIN_CENTURY, 'I', 10);
            $this->Cell(0, 10, 'Pagina ' . $this->PageNo(), 0, 0, 'C');
        }
    }

    $pdf = new MYPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Colegio Real');
    $pdf->SetTitle('Boletin');
    $pdf->SetSubject('Boletin');
    $pdf->SetKeywords('Boletin');
    $pdf->AddPage();

    $titulo = '
<table cellpadding="5" border="0" style="width: 100%; text-align: center;">
    <tr>
        <td colspan="2" style="text-align:center;">
            <img src="' . PUBLIC_PATH . 'img/portada2.png" style="width: 400px; height: auto; margin-top: 20px; margin-bottom: 20px;">
        </td>
    </tr>

    <tr>
        <td colspan="2" style="font-size: 1.6em; font-weight:bold; color:rgb(0, 0, 0); text-align:center; color: green;">
            ' . $datos_boletin['nom_estudiante'] . '
        </td>
    </tr>

    <tr><td colspan="2" style="height: 20px;"></td></tr>

    <tr>
        <td style="font-size: 1.2em; text-align:left; font-weight:bold; border: 2px solid green; color: green;">
            Trimestre: <span style="font-weight:normal;"> ' . $datos_boletin['nom_periodo'] . '</span>
        </td>
        <td style="font-size: 1.2em; text-align:left; font-weight:bold; border: 2px solid green; color: green;">
            Año Escolar: <span style="font-weight:normal;"> ' . $datos_boletin['anio_boletin'] . '</span>
        </td>
    </tr>

    <tr>
        <td style="font-size: 1.2em; text-align:left; font-weight:bold; border-left: 2px solid green; border-right: 2px solid green; border-bottom: 2px solid green; border-top: 0; color: green;">
            Nivel: <span style="font-weight:normal;"> ' . $datos_boletin['nom_curso'] . '</span>
        </td>
        <td style="font-size: 1.2em; text-align:left; font-weight:bold; border-left: 2px solid green; border-right: 2px solid green; border-top: 0; color: green;">
            Edad: <span style="font-weight:normal;"> ' . $datos_boletin['edad'] . '</span>
        </td>
    </tr>

    <tr>
        <td style="font-size: 1.2em; text-align:left; font-weight:bold; border: 2px solid green; color: green;">
            Fecha: <span style="font-weight:normal;"> ' . date('d-m-Y', strtotime($datos_boletin['fecha_fin'])) . '</span>
        </td>
        <td style="border-right: 2px solid green; border-bottom: 2px solid green;"></td>
    </tr>
</table>
';

    $pdf->Ln(10);
    $pdf->SetFont(PDF_FONT_NAME_MAIN_CENTURY, '', 10);
    $pdf->Cell(10);
    $pdf->writeHTMLCell(170, 0, '', '', $titulo, '', 1, 0, true, 'C', true);

    $pdf->Ln(60);
    $pdf->cabecera($datos_boletin);

    //$pdf->Ln(8);

    $cont = 0;

    foreach ($datos_dimensiones as $id_dimension => $dimension) {
        $imagen_dimension = empty($dimension['foto'])
            ? '<i>Sin imagen</i>'
            : '<img src="' . PUBLIC_PATH . 'upload/' . $dimension['foto'] . '" style="width:80px;">';

        $notas_dimension = '
        <table cellpadding="5" border="1">
            <tr>
                <!-- <td>' . $imagen_dimension . '</td> -->
            <td colspan="3" style="text-align: left;">
                <b>' . $dimension['nombre'] . ':</b> ' . $dimension['observacion'] . '
            </td>
            </tr>
            <tr>
                <td style="width: 84%; background-color: rgb(204, 204, 204);"><span style="font-weight:bold;">INDICADOR</span></td>
                <td style="width: 5.3%; background-color: rgb(204, 204, 204); text-align: center;"><span style="font-weight:bold;">LA</span></td>
                <td style="width: 5.3%; background-color: rgb(204, 204, 204); text-align: center;"><span style="font-weight:bold;">LD</span></td>
                <td style="width: 5.3%; background-color: rgb(204, 204, 204); text-align: center;"><span style="font-weight:bold;">NA</span></td>
            </tr>
        ';

        foreach ($dimension['indicadores'] as $indicador) {
            // Reemplaza el valor de la nota con "NA", "LD" o "LA"
            //$nota_formateada = ($indicador['nota'] == 1) ? 'LA' : (($indicador['nota'] == 2) ? 'LD' : 'NA');

            switch ($indicador['nota']) {
                case 1:
                    $nota_formateada = '<td style="width: 5.3%;"><span style="font-weight:bold;">X</span></td>
                                        <td style="width: 5.3%;"><span style="font-weight:bold;"></span></td>
                                        <td style="width: 5.3%;"><span style="font-weight:bold;"></span></td>';
                    break;
                case 2:
                    $nota_formateada = '<td style="width: 5.3%;"><span style="font-weight:bold;"></span></td>
                                        <td style="width: 5.3%;"><span style="font-weight:bold;">X</span></td>
                                        <td style="width: 5.3%;"><span style="font-weight:bold;"></span></td>';
                    break;
                default:
                    $nota_formateada = '<td style="width: 5.3%;"><span style="font-weight:bold;"></span></td>
                                        <td style="width: 5.3%;"><span style="font-weight:bold;"></span></td>
                                        <td style="width: 5.3%;"><span style="font-weight:bold;">X</span></td>';
                    break;
            }

            $notas_dimension .= '
                <tr style="background-color: rgb(255, 255, 255);">
                    <td style="width: 84%; text-align: left;" align="left">' . $indicador['nombre_indicador'] . '</td>'
                . $nota_formateada . '
                </tr>';
        }


        $notas_dimension .= '</table>';

        //$pdf->Ln(8);
        $pdf->SetFont(PDF_FONT_NAME_MAIN_CENTURY, '', 9);
        $pdf->Cell(10);
        $pdf->writeHTMLCell(170, 0, '', '', $notas_dimension, '', 1, 0, true, 'C', true);
    }





    $observacion = '
    <table border="1"  cellpadding="5">
    <tr style="text-align: justify;">
    <td><span style="font-weight:bold;">OBSERVACIONES GENERALES: </span>' . $datos_boletin['observacion'] . '</td>
    </tr>
    </table>
    ';

    //$pdf->Ln(20);
    $pdf->SetFont(PDF_FONT_NAME_MAIN_CENTURY, '', 10);
    $pdf->Cell(10);
    $pdf->writeHTMLCell(170, 0, '', '', $observacion, '', 1, 0, true, 'C', true);
    //<img src="' . PUBLIC_PATH . 'img/firma_coord.png" width="200">
    // ==== FIRMAS ====
    $firmas = '
<table border="0" cellpadding="5">
<tr style="text-align: center;">
    <td>
        <br><br>
        <img src="' . PUBLIC_PATH . 'img/Firmanat.png" width="155"><br>
        ________________________________<br>
        <span style="font-weight:bold;">COORDINADORA ACADÉMICA</span>
    </td>
    <td>
        <br><br><br><br><br><br><br><br><br><br>
        ________________________________<br>
        <span style="font-weight:bold;">FIRMA DE LA PROFESORA</span>
    </td>
</tr>
</table>
';

    $pdf->Ln(5);
    $pdf->SetFont(PDF_FONT_NAME_MAIN_CENTURY, '', 10);
    $pdf->Cell(10);
    $pdf->writeHTMLCell(170, 0, '', '', $firmas, '', 1, 0, true, 'C', true);

    $pdf->Output('Boletin_' . $datos_boletin['nom_estudiante'] . '_periodo_' . $datos_boletin['nom_periodo'] . '.pdf', 'I');
}
