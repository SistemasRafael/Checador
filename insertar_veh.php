<?php
include '\xampp\htdocs\registro\connections\config.php';

$html   = '';
$placa  = $_POST['placa'];
$marca  = $_POST['marca'];
$modelo = $_POST['modelo'];
$color  = $_POST['color'];
$poliza = $_POST['poliza'];
$org_id = $_SESSION['org_id'];

$id_max_ve = $mysqli->query("SELECT max(veh_id) FROM arg_vehiculos");
        $id_maximo = $id_max_ve->fetch_array(MYSQLI_ASSOC);
    	$id_max_veh = $id_maximo['max(veh_id)'];
        $id_max_veh = $id_max_veh+1;
     
if (isset($placa)){
        

        $query = "INSERT INTO arg_vehiculos (veh_id, placas, marca, modelo, color, poliza, org_id ) ".
                 "VALUES ($id_max_veh, '$placa', '$marca', '$modelo', '$color', '$poliza', $org_id)";
                            
         $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));
         $resultado = $mysqli->query("SELECT veh_id, placas, marca FROM arg_vehiculos ORDER BY veh_id DESC") or die(mysqli_error());
  }      
if ($resultado->num_rows > 0) {
   while ($row = $resultado->fetch_assoc()) {  
        $nombre =($row['placas']);        
        $nomenclatura = $row['veh_id'];
        $html .= 'echo ("<option value='.$nomenclatura.'>'.$nombre.'</option>")';    
    }
}

echo $html;
?>