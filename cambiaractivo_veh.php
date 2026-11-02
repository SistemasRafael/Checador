<?php
include "connections/config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $veh_id  = $mysqli->real_escape_string($_POST['veh_id']);

    $estado = $mysqli->query("SELECT activo FROM arg_vehiculos WHERE veh_id = '" . $veh_id . "'");
    $estado_v = $estado->fetch_array(MYSQLI_ASSOC);
    $activo = $estado_v['activo'];

    if ($activo == 1) {
        $var = 0;
    } else {
        $var = 1;
    }
    $query = "UPDATE `arg_vehiculos` SET `activo` = '".$var."' WHERE `arg_vehiculos`.`veh_id` = ".$veh_id;

    $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));
    $resultado = $mysqli->query("SELECT veh_id, activo FROM arg_vehiculos WHERE veh_id = '" . $veh_id . "'") or die(mysqli_error($mysqli));
    if ($resultado->num_rows > 0) {
        echo 'Estado actualizado correctamente';
        $html = '';
        while ($row = $resultado->fetch_assoc()) {
            $nombre = ($row['activo']);
            $nomenclatura = $row['veh_id'];
            $html .= 'echo ("<option value=' . $nomenclatura . '>' . $nombre . '</option>")';
        }
    }
}
