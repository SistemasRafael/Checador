<?php
include '\xampp\htdocs\registro\connections\config.php';

$html = '';
$veh_id = $_POST['veh_id'];
//echo $veh_id;
if (isset($veh_id)){
         $resultado = $mysqli->query("SELECT veh_id, placas, marca, modelo, poliza, color FROM arg_vehiculos WHERE veh_id=".$veh_id) or die(mysqli_error());
  }      
        
if ($resultado->num_rows > 0) {
$html.="<table class='tabla_datos'>
    			<thead>
    				<tr id='titulo'>
    					
    					<td width=15%>Marca</td>
    					<td width=15%>Modelo</td>
                        <td width=15%>Color</td>
                        <td width=15%>Poliza</td>
    				</tr>

    			</thead>
    			

    	<tbody>";

    	while ($fila = $resultado->fetch_assoc()) {
 	   
    		$html.="<tr>
                        <td width=20%> <a >".$fila['marca']."</a></td>
    					<td width=20%> <a >".$fila['modelo']."</a></td>
                        <td width=20%> <a >".$fila['color']."</a></td>
                        <td width=20%> <a >".$fila['poliza']."</a></td>
    				  </tr>";

    	}
    	$html.="</tbody></table>";
}
echo $html;
?>