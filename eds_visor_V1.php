<? 
include "connections/eds_core.php";
	
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
   
?>
<!--<!DOCTYPE html>-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<head>
	<meta charset="UTF-8">
	<title>Intranet Argonaut Gold</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Argonaut Gold">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    
  <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">  
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">  
   <script src="//code.jquery.com/jquery-1.11.0.min.js"></script> 
   
   
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>   
   
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  </head>
  <body>  
  
  <?

//Iniciar sesión de visita
//session_start();

//Definición de constantes
//RH-server
//$EDS_DB_ADDR = "RH-server";
//$conn_info = array("Database"=>"BiostarData_TA", "UID"=>"sa", "PWD"=>"fwSFTlpdNXGCd7Vh");
//print_r ($EDS_DB_DBASE);
//print_r ($conn_info);

$eds_link = sqlsrv_connect($EDS_DB_ADDR, $conn_info);
$unidad_mina = $_GET['mina'];
$_SESSION['unidad'] = $unidad_mina;
$fecha = date("Ymd");
$fecha_ini = $fecha;
$fecha_fin = $fecha;
//print_r ($eds_link);

    
    //$sql = "SELECT  * FROM [BiostarData_TA].[dbo].[marcajes_visor] WHERE unidad_mina = ".$unidad_mina." ORDER BY fecha DESC";
   
    $sql = "EXEC [BiostarData_TA].[dbo].[_proc_marcajes_visor] '".$fecha_ini."','".$fecha_fin."',".$unidad_mina;
    $eds_datos = sqlsrv_query( $eds_link, $sql);

    $row_count = sqlsrv_num_rows( $eds_datos );
    ?>
    <div class="container">    
    <div class="col-md-12 col-lg-12"> 
    <div class="container"> 
       
        <div class="col-md-12 col-lg-12">
         <form method="post" action="eds_visor.php?mina=<?echo $unidad_mina;?>" name="visor" id="visor">  
                <fieldset>                             
                                 <label for="fecha_inicial"><b>Seleccione fecha:</b></label> <br />                      
                             <div class="col-md-3 col-lg-3"> 
                             
                                <input type="date" name="fecha_inicial" class="form-control" id="fecha_inicial" />
                            </div>    
                            <div class="col-md-3 col-lg-3">
                                 <input type="date" name="fecha_final" class="form-control" id="fecha_final" />
                            </div>
                            <div class="col-md-3 col-lg-3">
                                <input type="submit" class="btn btn-info" name="ver" id="ver" value="Ver" readonly />
                            </div>  
                            <!--<div class="col-md-3 col-lg-3">    
                                <input type="botton" class="btn btn-info" name="exportar" id="agregar_herr" onclick="exportar();" value="Exportar" readonly /> 
                            </div>      --!>
                 </fieldset>  
            </form> 
        </div>
                
         </div>
    </div>
       <br />
       <br />
       <br />
       <br />
       <?
      if (isset($_POST['ver'])){
        $fecha_ini = $_POST['fecha_inicial'];
        $fecha_fin = $_POST['fecha_final'];
       
        $sql = "EXEC [BiostarData_TA].[dbo].[_proc_marcajes_visor] '".$fecha_ini."','".$fecha_fin."',".$unidad_mina;
        $eds_datos = sqlsrv_query( $eds_link, $sql);
        $row_count = sqlsrv_num_rows( $eds_datos );
        }
     ?>
         <div class="row">
         <div class="container">         
     <?
    $html_en = "<table class='table table-bordered' id='encabezado'>
                             <thead>
                                 <tr class='table-info'>  
                                    <th scope='col'>Mina</th> 
                                    <th scope='col'>Clave</th>
                                    <th scope='col'>Empleado</th>
                                    <th scope='col'>Checador</th>
                                    <th scope='col'>Fecha</th>
                                    <th scope='col'>Hora</th>
                                  </tr>
                              </thead>
                              <tbody>";
    //for($i = 1; $i<$row_count; $i++){
        
       while( $row = sqlsrv_fetch_array( $eds_datos) ) {
            $i = 1;
            $row = eliminar_acentos($row);
        
                              $html_en .= "<tr>";
                              $html_en .= "<td>".$row['unidad_mina']."</td>";
                              $html_en .= "<td>".$row['num_emp']."</td>";
                              $html_en .= "<td>".$row['empleado']."</td>";
                              $html_en .= "<td>".$row['terminal']."</td>";
                              $html_en .= "<td>".$row['fecha']."</td>";
                              $html_en .= "<td>".$row['hora']."</td>";
                              $html_en .= "</tr>";
                             // $html_en .= "</tbody></table>";
        
            //$html_en .= "<td>".$row['empleado']."</td>";
           // $html_en .= "</tbody></table>";
           //echo $row['empleado'];
              $i = $i+1; 
        }
        $html_en .= "</tbody></table>";
        
        echo $html_en;
    //}       
    
    //  print json_encode($row);
    sqlsrv_close($eds_link);

?>
</div>
</div>
</div>
 </body> 
 <?//}?>