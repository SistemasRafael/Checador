<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    ini_set('memory_limit', '512M');
    set_time_limit(300);
    
    $mysqli = new mysqli('localhost:3306', 'root', '', 'arg_registroVisitas');
    
    if ($mysqli->connect_error) {
        die('Error de conexión: ' . $mysqli->connect_error);
    }
?>