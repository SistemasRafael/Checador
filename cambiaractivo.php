<?php
include "config/config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $herr_id  = $mysqli->real_escape_string($_POST['herr_id']);

    $estado = $mysqli->query("SELECT activo FROM arg_herramientas WHERE herr_id = '" . $herr_id . "'");
    $estado_v = $estado->fetch_array(MYSQLI_ASSOC);
    $activo = $estado_v['activo'];

    if ($activo == 1) {
        $var = 0;
    } 
    else {
        $var = 1;
    }
    $query = "UPDATE `arg_herramientas` SET `activo` = '".$var."' WHERE `arg_herramientas`.`herr_id` = ".$herr_id;

    $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));
    $resultado = $mysqli->query("SELECT herr_id, activo FROM arg_herramientas WHERE herr_id = '" . $herr_id . "'") or die(mysqli_error($mysqli));
    if ($resultado->num_rows > 0) {
        echo 'Se cambió correctamente el estado de la herramienta';
        $html = '';
        
        while ($row = $resultado->fetch_assoc()) {
            $nombre = ($row['activo']);
            $nomenclatura = $row['herr_id'];
            $html .= 'echo ("<option value=' . $nomenclatura . '>' . $nombre . '</option>")';
        }
    }
}
