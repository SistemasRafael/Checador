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
  
  
  <script>
    function actualiza($fecha_ini, $fecha_fin, $mina, $empleado)
        {
            var fecha_ini = $fecha_ini;
            var fecha_fin = $fecha_fin; 
            var mina = $mina;
            var empleado = $empleado;
            var total_ingresos = $total_ingresos
            
            var cadena = fecha_ini+'&fecha_fin='+fecha_fin+'&mina='+mina+'&empleado'+$empleado;
            var print_d = '<?php echo "app.php?fecha_ini="?>'+cadena; 
            
            //window.location.href = print_d;
            window.location.href = print_d;
        }
        
    function expo($fecha_ini, $fecha_fin, $unidad_mina, $empleado)
        {
            var fecha_ini = $fecha_ini;
            var fecha_fin = $fecha_fin; 
            var mina = $unidad_mina;
            var empleado = $empleado
            //alert(fecha_fin);                   
            
            var cadena = fecha_ini+'&fecha_fin='+fecha_fin+'&mina='+mina+'&empleado='+empleado;
            var print_d = '<?php echo "eds_visor_export.php?fecha_ini="?>'+cadena;  
            //alert (print_d);              
                    window.location.href = print_d;
            }
  
  </script>
 
  </head>
  <body>  
  
  <?

$eds_link = sqlsrv_connect($EDS_DB_ADDR, $conn_info);
$unidad_mina = $_GET['mina'];
$fecha = date("Ymd");
$fecha_ini = $_GET['fecha_ini'];
$fecha_fin = $_GET['fecha_fin'];
    if ($fecha_ini == '')
        $fecha_ini = $fecha;
    
    if ($fecha_fin == '')
        $fecha_fin = $fecha;
        
$empleado = $_GET['empleado'];
if ($empleado == '')
        $empleado .= '';
        
//echo $empleado;

    $sql = "EXEC [BiostarData_TA].[dbo].[_proc_marcajes_visorV2] '".$fecha_ini."','".$fecha_fin."',".$unidad_mina.", '".$empleado."'";
    $eds_datos = sqlsrv_query( $eds_link, $sql);

    $row_count = sqlsrv_num_rows( $eds_datos );
    
    $sql = "EXEC [BiostarData_TA].[dbo].[_proc_marcajes_totales] 1, '".$fecha_ini."','".$fecha_fin."',".$unidad_mina;
    $eds_datos_tot = sqlsrv_query( $eds_link, $sql);
    $row_t = sqlsrv_fetch_array( $eds_datos_tot);
    $total_ingresos = $row_t['total'];
    
    $sql_r = "EXEC [BiostarData_TA].[dbo].[_proc_marcajes_totales] 3, '".$fecha_ini."','".$fecha_fin."',".$unidad_mina;
    $eds_datos_reg = sqlsrv_query( $eds_link, $sql_r);
    $row_r = sqlsrv_fetch_array( $eds_datos_reg);
    $total_global = $row_r['total'];
    
     ?>
    <div class="container">    
    <div class="col-md-12 col-lg-12"> 
    
        <div class="container">            
            <div class="row">
                <div class="container"> 
                     <div class="col-md-6 col-lg-6">
                        <label for="fec"><b>Seleccione fecha:</b></label> 
                      </div>	
                     <div class="col-md-3 col-lg-3">
                        <label for="fec"><b>Buscar empleado:</b></label> <br />  <br /> 
               	    </div>	
                </div>
            </div>
        
        <div class="row">
        <div class="col-md-12 col-lg-12">
        
         <form method="post" action="app.php?fecha_ini=<?echo $fecha_ini."&fecha_fin=".$fecha_fin."&mina=".$unidad_mina."&empleado=".$empleado."";?>" name="visor" id="visor">  
                <fieldset>                                                
                             <div class="col-md-2 col-lg-2">  
                                <input type="date" name="fecha_ini" class="form-control" id="fecha_ini" />
                            </div>    
                            <div class="col-md-2 col-lg-2">
                                 <input type="date" name="fecha_fin" class="form-control" id="fecha_fin" />
                            </div>
                            
                            <div class="col-md-1 col-lg-1">
                                <input type="submit" class="btn btn-info" name="ver" id="ver" value="Ver" readonly />
                            </div>    
                            
                              <div id="content" class="col-md-7 col-lg-7">
                                <section class="principal">
                                
                                	<div class="formulario" >
                                    <div id="content" class="col-md-7 col-lg-7"> 
                                     
                                  <!--!  <h6>Nombre del empleado:</h6>--!>
                                   
                                		<label for="caja_busqueda"></label>
                                		<input class="search_query form-control" type="text" name="caja_busqueda" id="caja_busqueda" autocomplete="off" placeholder="Buscar..."></input>
                                           <br />
                                	</div>	
                                    </div>                       
                                	<div class="col-md-7 col-lg-7" id="datos"></div>
                                    
                                </section>
                            </div>                               
                 </fieldset>  
            </form> 
            </div>
        </div>  
      </div> 
       <br />
       <?
      if (isset($_POST['ver'])){
        $fecha_ini = $_POST['fecha_ini'];//date('Ymd', strtotime($_POST['fecha_ini']));
        $fecha_fin = $_POST['fecha_fin'];//date('Ymd', strtotime($_POST['fecha_fin']));
        $empleado = $_POST['caja_busqueda'];
       // echo $empleado;
       // echo $fecha_ini;
       // echo $fecha_fin;
        
        $sql = "EXEC [BiostarData_TA].[dbo].[_proc_marcajes_visorV2] '".$fecha_ini."','".$fecha_fin."',".$unidad_mina.", '".$empleado."'";
        $eds_datos = sqlsrv_query( $eds_link, $sql);
        $row_count = sqlsrv_num_rows( $eds_datos );
        
        //Totales marcador
        $sql = "EXEC [BiostarData_TA].[dbo].[_proc_marcajes_totales] 1, '".$fecha_ini."','".$fecha_fin."',".$unidad_mina;
        $eds_datos_tot = sqlsrv_query( $eds_link, $sql);
        $row_t = sqlsrv_fetch_array( $eds_datos_tot);
        $total_ingresos = $row_t['total'];
        
        $sql_r = "EXEC [BiostarData_TA].[dbo].[_proc_marcajes_totales] 3, '".$fecha_ini."','".$fecha_fin."',".$unidad_mina;
        $eds_datos_reg = sqlsrv_query( $eds_link, $sql_r);
        $row_r = sqlsrv_fetch_array( $eds_datos_reg);
        $total_global = $row_r['total'];
        
           echo "<script> actualiza('$fecha_ini','$fecha_fin', $unidad_mina, '$empleado'); </script> ";        
        }
      
     ?>
         <div class="row">
            <div class="col-md-12 col-lg-12">
                <div class="container-fluid">
                        <div class="col-md-6 col-lg-6">
                            <h5><b>Consulta del: <?echo $fecha_ini.' al '.$fecha_fin;?></b></h5>
                        </div>
                        <div class="col-md-2 col-lg-2">
                                <input type="submit" class="btn btn-info" name="export" id="export" value="Exportar" onclick= "expo(<?echo "'".$fecha_ini."', '".$fecha_fin."',".$unidad_mina.",'".$empleado."'"?>);" readonly />
                         </div>
                         <br/> 
                         
                        <div class="col-xl-2 col-sm-2 col-md-2 col-ld-2 ">
                              <div class="card text-white text-lg-center bg-success o-hidden h-70">
                                <div class="card-body">
                                  <div class="card-body-icon medium">
                                    <i class="fa fa-sign-in fa-1x"></i>
                                  </div>
                                   </div>
                                   <div class="card-footer">                                     
                                    <a> <?echo ' INGRESOS: '.$total_ingresos;?></a>
                                  </div>                               
                                </div>
                              </div>  
                              
                              <div class="col-xl-2 col-sm-2 col-md-2 col-ld-2 ">
                              <div class="card text-white text-lg-center bg-secondary o-hidden h-80">
                                <div class="card-body">
                                  <div class="card-body-icon medium">
                                    <i class="fa fa-user-o center fa-1x"></i>
                                  </div>
                                </div>
                                 <div class="card-footer">   
                                    <a> <?echo 'TOTAL: '.$total_global;?></a> 
                                </div>
                              </div>  
                             </div>
                       </div>     
              </div>
         </div>      
         <br /> 
     <?
    $html_en = "<table class='table table-bordered' style='font-size:12px'  id='encabezado'>
                             <thead>
                                 <tr class='table-info'>  
                                    <th scope='col'>Mina</th> 
                                    <th scope='col'>Clave</th>
                                    <th scope='col'>Empleado</th>
                                    <th scope='col'>Departamento</th>
                                    <th scope='col'>Tipo</th>
                                    <th scope='col'>Checador</th>
                                    <th scope='col'>Fecha</th>
                                    <th scope='col'>HOra</th>
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
                              $html_en .= "<td>".$row['departamento']."</td>";
                              $html_en .= "<td>".$row['tipo_empleado']."</td>";
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
 </body>
          
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/main.js"></script>  
 <?//}?>