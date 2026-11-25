<?php
include "config/config.php";

$u_id= $_POST['u_id'];
$query = "UPDATE arg_usuarios SET clave = '".MD5("Aa99999@")."' WHERE u_id=".$u_id;
$mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));
echo "Se cambió la contraseña correctamente.";