<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    ini_set('memory_limit', '512M');
    set_time_limit(300);
    
    $mysqli = new mysqli('127.0.0.1','rafael.flores', 'R4Fa3L-Fl0r3s$07', 'arg_registrovisitas');
    
    if ($mysqli->connect_error) {
        die('Error de conexión: ' . $mysqli->connect_error);
    }
?>