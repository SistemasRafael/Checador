<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$mysqli = new mysqli('localhost:3306', 'root', '', 'arg_registroVisitas');

if ($mysqli->connect_error) {
    die('Error de conexión: ' . $mysqli->connect_error);
}
?>