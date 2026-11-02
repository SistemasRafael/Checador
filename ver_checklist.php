<?php 
include "connections/config.php";

$veh_id = $_POST['veh_id'];
$examenes_query = $mysqli->query("SELECT * FROM arg_vehiculos_checklist WHERE veh_id = " . $veh_id . "") or die(mysqli_error($mysqli));
$row = $examenes_query->fetch_assoc();

$carroceria  = $row['estado_carroceria'];
$espejos  = $row['estado_espejos'];
$parabrisas = $row['estado_parabrisas'];
$limpiaparabrisas  = $row['estado_limpiaparabrisas'];
$triangulo = $row['triangulos_seguridad'];
$cintas = $row['cintas_reflectivas'];
$llanta = $row['llanta_repuesto_gato_cruceta'];
$eslingas  = $row['eslingas'];
$cable = $row['cables_para_corriente'];
$limpieza = $row['limpieza'];

$datos = $carroceria . ";" . $espejos . ";" . $parabrisas . ";" . $limpiaparabrisas . ";" . $triangulo . ";" . $cintas . ";" . 
$llanta . ";" . $eslingas . ";" . $cable . ";" . $limpieza;

echo $datos;
