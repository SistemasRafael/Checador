<?
//Iniciar sesión de visita
session_start();

//Definición de constantes
//RH-server
$EDS_DB_ADDR = "172.16.36.12";

$conn_info = array("Database"=>"BiostarData_TA", "UID"=>"bioadmin", "PWD"=>"Axioma$3112$");
$eds_link = sqlsrv_connect($EDS_DB_ADDR, $conn_info);

if ($eds_link == false)
    print 'Erorr [eds_core]: No se pudo conectar al servidor.';
    /*
    $sql = "SELECT empleado FROM [BiostarData_TA].[dbo].[marcajes_visor]";
   // $params = array();
   // $options =  'LC'//array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
  //  $stmt = sqlsrv_query( $conn, $sql , $params, $options );
    $eds_datos = sqlsrv_query( $eds_link, $sql);

    $row_count = sqlsrv_num_rows( $eds_datos );
        while( $row = sqlsrv_fetch_array( $eds_datos) ) {
    
        ?><div class="container"><?
                $html_en = "<table class='table table-bordered' id='encabezado'>
                             <thead>
                                 <tr class='table-info'>   
                                    <th scope='col'>Empresa</th>
                                    <th scope='col'>Usuario</th>
                                    <th scope='col'>Unidad de Mina</th>
                                    <th scope='col'>Desde</th>
                                    <th scope='col'>Hasta</th>
                                    <th scope='col'>Comentario</th>
                                  </tr>
                              </thead>
                              <tbody>";
            
            //$html_en = "<td>".$row['empleado']."</td>";
           // $html_en = "</tbody></table>";
           
        }
        ?></div><?        
    echo $html_en;
    //  print json_encode($row);
    sqlsrv_close($eds_link);
}*/
?>
