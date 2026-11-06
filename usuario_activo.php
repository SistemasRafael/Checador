<?php 
include "config/config.php";

$estado = $_POST['estado'];
$id = $_POST['id'];

$query = "UPDATE arg_usuarios SET activo= $estado WHERE u_id = '$id'";

$mysqli->query($query) or die('Error, query failed : ' . $query . mysqli_error($mysqli));
$resultado = $mysqli->query("SELECT u_id, nombre FROM arg_usuarios WHERE u_id = '$id'") or die(mysqli_error($mysqli));

if ($resultado->num_rows > 0) {
    echo 'Se cambió exitosamente.';
}
