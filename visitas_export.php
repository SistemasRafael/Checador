<?php

/**
 * Informe visor de visitas
 * Danira Romero Maldonado * 
 * ----------------------------------------
 * 
 **/
 include "connections/config.php";  
 //20250227
 //include "vendor/autoload.php";
 /*
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;*/
 
 //require_once 'vendor/phpoffice/src/PhpSpreadsheet/src/Bootstrap.php';
 

include 'vendor/phpoffice/phpSpreadsheet/src/PhpSpreadshhet/Spreadsheet';
include 'vendor/phpoffice/phpSpreadsheet/src/PhpSpreadshhet/Writer/Xlsx';

 
/*use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;*/

/*use phpoffice\phpspreadsheet\src\PhpSpreadsheet\Spreadsheet;
use phpoffice\phpspreadsheet\src\PhpSpreadsheet\Writer\Xlsx;*/

//$fecha_ini = $_GET['fecha_inicial'];
//$fecha_fin = $_GET['fecha_final'];

$u_id = $_GET['u_id'];
//echo $u_id;

/*$hoy = $_GET['hoy'];
$fechafinal = $_GET['fecha_final'];
$empleado = $_GET['empleado'];*/
$unidad_mina_sel = $_GET['unidad'];

//echo $unidad_mina_sel;


$unidad = $mysqli->query("SELECT nombre
                           FROM `arg_empr_unidades` 
                           WHERE unidad_id = " . $unidad_mina_sel) or die(mysqli_error());
$unidad_sele = $unidad->fetch_assoc();

$mina_nom = $unidad_sele['nombre'];

//echo $mina_nom;

/*
if ($empleado == '') {
    $empleado = '';
}*/

// set syles

//$spreadsheet = new Spreadsheet();
//$spreadsheet ->getDefaultStyle()->getFont()->setName('Arial');
//$spreadsheet ->getDefaultStyle()->getFont()->setSize(10);
$mina_nom = 'LC';

//mysqli_multi_query($mysqli, "CALL visor_visitas (" . $u_id . ",'" . $hoy . "','" . $fechafinal . "'," . $empleado . ", " . $unidad_mina_sel . ", 0" . ")") or die(mysqli_error($mysqli));

$objSheet = $spreadsheet->getActiveSheet();
$objSheet->setTitle('Visitas');

// Se agregan los titulos del reporte
/*$objSheet->mergeCells('A1:K1');
$objSheet->getStyle('A1:K1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);*/
//$objSheet->setCellValue('A1','Unidad de Mina ' . $mina_nom);

/*$objSheet->setCellValue('A2','Empresa');
$objSheet->setCellValue('B2','Proveedor');
$objSheet->setCellValue('C2','Mina');
$objSheet->setCellValue('D2','Folio');
$objSheet->setCellValue('E2','Atiende');
$objSheet->setCellValue('F2','Fecha Inicio');
$objSheet->setCellValue('G2','Fecha Final');
$objSheet->setCellValue('H2','Estado');
$objSheet->setCellValue('I2','Iniciado');
$objSheet->setCellValue('J2','Finalizado');
$objSheet->setCellValue('K2','Duracion');*/

/*
//Se inserta el detalle de la consulta al informe
$i = 3;
if ($result = mysqli_store_result($mysqli)) {
    while ($row = mysqli_fetch_assoc($result)) {
        $objSheet->setCellValue('A' . $i,$row['empresa']);
        $objSheet->setCellValue('B' . $i,$row['nombre']);
        $objSheet->setCellValue('C' . $i,$row['mina']);
        $objSheet->setCellValue('D' . $i,$row['folio']);
        $objSheet->setCellValue('E' . $i,$row['atiende']);
        $objSheet->setCellValue('F' . $i,$row['fecha_inicio']);
        $objSheet->setCellValue('G' . $i,$row['fecha_final']);
        $objSheet->setCellValue('H' . $i,$row['estado']);
        $objSheet->setCellValue('I' . $i,$row['iniciado']);
        $objSheet->setCellValue('J' . $i,$row['finalizado']);
        $objSheet->setCellValue('K' . $i,$row['duracion_visita']);
        $i = $i + 1;
    }
}*/
//Ajuste de columnas a tama�o del texto contenido

/*for ($col = 'A'; $col != 'L'; $col++) {
    $objSheet->getColumnDimension($col)->setAutoSize(true);
}
*/

//$spreadsheet->getActiveSheet(0)->freezePaneByColumnAndRow(3, 3);

/*header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');*/
//header('Content-Disposition: attachment;filename="'.$unidad_mina.' Checador.xlsx"');
/*header('Content-Disposition: attachment;filename="Visitas de proveedores.xlsx"');
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');*/

//$writer = new Xlsx($spreadsheet);

//$fileName = 'hola.csv';

/*header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Pesaje Muestra.xlsx"');*/
//header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');

//$writer->save('php://output');
//exit;
