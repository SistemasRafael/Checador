<?php
	
//Configuración central de sistema.
include "connections/eds_core.php";
$eds_link = sqlsrv_connect($EDS_DB_ADDR, $conn_info);

    if (isset($_POST['consulta'])) {
        $q = ($_POST['consulta']);
        
        $sql = "SELECT DISTINCT empleado, num_emp AS id FROM [marcajes_busqueda] WHERE empleado LIKE '%".strip_tags($q)."%'";
        $eds_datos = sqlsrv_query( $eds_link, $sql);
     }       
        while( $row = sqlsrv_fetch_array( $eds_datos, SQLSRV_FETCH_ASSOC) ) {
           
            //$html .= '<div><a class="suggest-element" data="'.utf8_encode($row['nombre']).'" id="id'.$row['id'].'">'.utf8_encode($row['nombre']).'</a></div>';
            $html .= '<div>
                        <a class="suggest-element" data="'.utf8_encode($row['empleado']).'" id="id'.$row['id'].'">'.utf8_encode($row['empleado']).'</a>
                      </div>';
          }
       
echo $html;


?>