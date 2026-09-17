<?
/**
 * Informe para exportar desde Bd Checadores SQL
 * Danira Romero Maldonado * 
 * ----------------------------------------
 * Checador
 **/

include "connections/eds_core.php";
include '\xampp\htdocs\__pro\argonaut\common\phpExcel\Classes\PHPExcel.php';
include '\xampp\htdocs\__pro\argonaut\common\phpExcel\Classes\IOFactory.php';
include '\xampp\htdocs\__pro\argonaut\common\PHPExcel\Classes\Writer\Excel5.php';

$eds_link = sqlsrv_connect($EDS_DB_ADDR, $conn_info);

function eliminar_acentos($cadena){
		
		//Reemplazamos la A y a
		$cadena = str_replace(
		array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'),
		array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),
		$cadena
		);
 
		//Reemplazamos la E y e
		$cadena = str_replace(
		array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'),
		array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
		$cadena );
 
		//Reemplazamos la I y i
		$cadena = str_replace(
		array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
		array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
		$cadena );
 
		//Reemplazamos la O y o
		$cadena = str_replace(
		array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
		array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
		$cadena );
 
		//Reemplazamos la U y u
		$cadena = str_replace(
		array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),
		array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
		$cadena );
 
		//Reemplazamos la N, n, C y c
		$cadena = str_replace(
		array('Ñ', 'ñ', 'Ç', 'ç'),
		array('N', 'n', 'C', 'c'),
		$cadena
		);
		
		return $cadena;
	}

$fecha_ini = $_GET['fecha_ini'];
$fecha_fin = $_GET['fecha_fin'];
$mina = $_GET['mina'];
//echo $unidad_mina;
if ($mina == "LC")
    $mina_nom = "LA COLORADA";
    else{
        if ($mina == "EC")
            $mina_nom = 'EL CASTILLO';
        else
            $mina_nom = 'SAN AGUSTIN';   
    }

//$mina = 'este';

$objPHPExcel = new PHPExcel;
 // set syles
$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
$objPHPExcel->getDefaultStyle()->getFont()->setSize(10);
$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);

$sql = "EXEC [BiostarData_TA].[dbo].[_proc_marcajes_visor] '".$fecha_ini."','".$fecha_fin."',".$mina;
$eds_datos = sqlsrv_query( $eds_link, $sql);

$row_count = sqlsrv_num_rows( $eds_datos );

$objSheet = $objPHPExcel->getActiveSheet();
$objSheet->setTitle('Checador '.$mina_nom);

 // Se agregan los titulos del reporte
 $objSheet->mergeCells('A1:E1');
 $objSheet->getStyle  ('A1:E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
 $objSheet->getCell('A1')->setValue('Unidad de Mina '.$mina); 
 $objSheet->getCell('A2')->setValue('Clave');
 $objSheet->getCell('B2')->setValue('Empleado');
 $objSheet->getCell('C2')->setValue('Checador');
 $objSheet->getCell('D2')->setValue('Fecha');
 $objSheet->getCell('E2')->setValue('Hora');
 
 //Se inserta el detalle de la consulta al informe
 $i = 3;
  while( $row = sqlsrv_fetch_array( $eds_datos) ) { 
    $row = eliminar_acentos($row);
    //$objSheet->getCell('A'.$i)->setValue($row['unidad_mina']);
    $objSheet->getCell('A'.$i)->setValue($row['num_emp']);
    $objSheet->getCell('B'.$i)->setValue($row['empleado']);
    $objSheet->getCell('C'.$i)->setValue($row['terminal']);
    $objSheet->getCell('D'.$i)->setValue($row['fecha']);
    $objSheet->getCell('E'.$i)->setValue($row['hora']);
 $i=$i+1; 
 }
 //Ajuste de columnas a tamaño del texto contenido
 for ($col = 'A'; $col != 'J'; $col++) { 
      $objSheet->getColumnDimension($col)->setAutoSize(true);         
    }
 
 $objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(3,3);
             
 header('Content-Type: application/vnd.ms-excel');
 //header('Content-Disposition: attachment;filename="'.$unidad_mina.' Checador.xlsx"');
 header('Content-Disposition: attachment;filename="Checador '.$mina_nom.'.xlsx"');
 header('Cache-Control: max-age=0');
 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
 $objWriter->save('php://output');
 exit;
//Desconectarse al servicio de datos.
//include '\xampp\htcore\scripts\\' . $db_srv . '\user_disconnect.php';
?>