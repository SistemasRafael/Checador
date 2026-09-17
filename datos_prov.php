<?php
include '\xampp\htdocs\registro\connections\config.php';

//$html = '';
$u_id_p = $_POST['prov_id'];
//echo $veh_id;
if (isset($u_id_p)){
         $resultado_imss = $mysqli->query("SELECT imss FROM usuarios_doc WHERE tipo_id = 1 AND u_id=".$u_id_p) or die(mysqli_error());
         $resultado_ine = $mysqli->query("SELECT imss FROM usuarios_doc WHERE tipo_id = 2 AND u_id=".$u_id_p) or die(mysqli_error());
  }      
        
if ($resultado_imss->num_rows > 0) {
$html.="<table class='tabla_datos' id=".$u_id_p.">";

    	while ($fila = $resultado_imss->fetch_assoc()) {
    	   $ine = $resultado_ine->fetch_assoc();
 	   
    		$html.="
                        <a >".$fila['imss'].'&nbsp &nbsp &nbsp &nbsp &nbsp'.$ine['imss']."</a>
    					                 
    				  ";

    	}
    	$html.="</table>";
}
echo $html;
?>