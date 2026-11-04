<?php
include "config/config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre  = $mysqli->real_escape_string($_POST['nombre'] ?? '');
    $marca  = $mysqli->real_escape_string($_POST['marca'] ?? '');
    $modelo = $mysqli->real_escape_string($_POST['modelo'] ?? '');
    $serie  = $mysqli->real_escape_string($_POST['serie'] ?? '');
    $empresa = $mysqli->real_escape_string($_POST['empresa'] ?? '');
    $org_id = $mysqli->real_escape_string($_POST['org'] ?? '');
    $org_id = intval($org_id);
    $id_max_ve = $mysqli->query("SELECT max(herr_id) FROM arg_herramientas");
    $id_maximo = $id_max_ve->fetch_array(MYSQLI_ASSOC);
    $id_max_veh = $id_maximo['max(herr_id)'];
    $id_max_veh = $id_max_veh + 1;

    $serie_dup = $mysqli->query("SELECT serie FROM arg_herramientas WHERE serie = '" . $serie . "'");
    $serie_dupli = $serie_dup->fetch_array(MYSQLI_ASSOC);
    $serie_duplic = $serie_dupli['serie'] ?? '';
    $serie_duplicada = $serie_duplic;


    if ($serie == $serie_duplicada) {
        echo 'Error: La serie se encuentra duplicada. Reintente por favor';
    } 
    else {
        $query = "INSERT INTO arg_herramientas (herr_id, org_id, nombre, marca, modelo, serie) " .
            "VALUES ($id_max_veh, '$org_id', '$nombre', '$marca', '$modelo', '$serie')";

        $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));
        $resultado = $mysqli->query("SELECT herr_id, nombre, marca FROM arg_herramientas WHERE org_id = " . $org_id . " ORDER BY herr_id DESC") or die(mysqli_error($mysqli));

        if ($resultado->num_rows > 0) {
            echo 'Se agregó correctamente la herramienta';
            $html = '';

            while ($row = $resultado->fetch_assoc()) {
                $nombre = ($row['nombre']);
                $nomenclatura = $row['herr_id'];
                $html .= 'echo ("<option value=' . $nomenclatura . '>' . $nombre . '</option>")';
            }
        }
    }
}
