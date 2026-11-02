<?php
header('Content-Type: application/json');
include "connections/config.php";
include "Repositorios/VisitantesRepositorio.php";

$action = $_POST['action'] ?? '';
$response = [];

switch ($action) {
    case 'guardar':
        $visitante = new VisitantesRepositorio($mysqli);

        try {
            $response = $visitante->guardar(
                $_POST['visita_id'],
                $_POST['nombre'],
                $_POST['ine'],
                $_POST['nss'],
                $_POST['archivoIne'],
                $_POST['archivoNss']
            );
        } 
        catch (Exception $e) {
            $response = Response::error($e->getMessage());
        }

        break;

    case 'eliminar':
        $visitante = new VisitantesRepositorio($mysqli);

        try {
            $response = $visitante->eliminar($_POST['visitante_id']);
        } 
        catch (Exception $e) {
            $response = Response::error($e->getMessage());
        }

        break;
}

echo json_encode($response);
?>