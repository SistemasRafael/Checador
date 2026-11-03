<?php
include "config/config.php";

$pass = md5($_POST['pass']);
$id = $_POST['id'];
$query = "UPDATE arg_usuarios SET clave='".$pass."' WHERE u_id=".$id;
$mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));