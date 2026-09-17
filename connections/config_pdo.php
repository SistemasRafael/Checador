<?php

session_start();
$mysqli = new mysqli('192.168.20.22', 'danira', 'Danira!', 'arg_registroVisitas');
if ($mysqli->connect_error) {
    die('Error de conexión: ' . $mysqli->connect_error);
$mysqli->set_charset("utf8"); }


?>