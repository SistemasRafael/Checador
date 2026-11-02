<?php
include "connections/config.php";

$nombre = $_POST['nombre'];
$email = $_POST['email'];
$id = $_POST['id'];
$query = "UPDATE arg_usuarios SET nombre = '".$nombre."', email = '".$email."' WHERE u_id=".$id;
$mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));