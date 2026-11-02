<?php
include "connections/config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $u_id_pro  = $mysqli->real_escape_string($_POST['u_id_provee']);

    $estado = $mysqli->query("SELECT administrador FROM arg_usuarios WHERE u_id= '" . $u_id_pro  . "'");
    $estado_v = $estado->fetch_array(MYSQLI_ASSOC);
    $admin = $estado_v['administrador'];

    if ($admin  == 1) {
        $var = 0;
    } 
    else {
        $var = 1;
    }
    $query = "UPDATE `arg_usuarios` SET `administrador` = '".$var."' WHERE `arg_usuarios`.`u_id` = ".$u_id_pro;

    $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));
    $resultado = $mysqli->query("SELECT u_id, administrador FROM arg_usuarios WHERE u_id = '" . $u_id_pro . "'") or die(mysqli_error($mysqli));

    if ($resultado->num_rows > 0) {
        echo 'Se cambió el perfil del proveedor';
        $html = '';

        while ($row = $resultado->fetch_assoc()) {
            $nombre = ($row['administrador']);
            $nomenclatura = $row['u_id'];
            $html .= 'echo ("<option value=' . $nomenclatura . '>' . $nombre . '</option>")';
        }
    }
}
