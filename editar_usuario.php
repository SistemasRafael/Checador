<?php
include "config/config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $mysqli->real_escape_string($_POST['uidedit']);
    $nombre  = $mysqli->real_escape_string($_POST['nombreedit']);
    $email   = $mysqli->real_escape_string($_POST['emailedit']);
    $mina    = $mysqli->real_escape_string($_POST['mina_seleccionada']);
    
    
    $query = "UPDATE arg_usuarios SET nombre = '$nombre', email = '$email' WHERE u_id = ".$id;

    $mysqli->query($query) or die('Error, query failed : ' . $query . mysqli_error($mysqli));
    
    $query = "UPDATE arg_usuarios_directivas SET valor = '".$mina."' WHERE directiva_id = 1 AND u_id = ".$id;
    
    $mysqli->query($query) or die('Error, query failed : ' . $query . mysqli_error($mysqli));
    
    $resultado = $mysqli->query("SELECT u_id, nombre FROM arg_usuarios WHERE u_id = ".$id) or die(mysqli_error($mysqli));

    if ($resultado->num_rows > 0) {
        echo 'Se editó correctamente el usuario';
    }
}
