<?php
include "Utilerias/Response.php";

class VisitantesRepositorio
{
    private mysqli $mysqli;

    public function __construct(mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
    }

    public function guardar(
        int $visita_id,
        string $nombre,
        string $ine,
        string $nss,
        string $archivoIne,
        string $archivoNss
    ) {

        $stmt = $this->mysqli->prepare("
            INSERT INTO visitantes
            (
                visita_id,
                nombre_completo,
                ine,
                nss,
                archivo_ine,
                archivo_nss
            )
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "isssss",
            $visita_id,
            $nombre,
            $ine,
            $nss,
            $archivoIne,
            $archivoNss
        );

        if (!$stmt->execute()) {
            return Response::error($stmt->error);
        }

        return Response::success(
            "Registro guardado",
            ["id" => $this->mysqli->insert_id]
        );
    }

    public function eliminar(int $visitante_id)
    {
        $stmt = $this->mysqli->prepare("
            DELETE FROM visitantes
            WHERE visitante_id = ?
        ");

        $stmt->bind_param(
            "i",
            $visitante_id
        );

        if (!$stmt->execute()) {
            return Response::error($stmt->error);
        }

        if ($stmt->affected_rows === 0) {
            return Response::error(
                "No se encontró el visitante con ID {$visitante_id}"
            );
        }

        return Response::success(
            "Visitante eliminado correctamente"
        );
    }

}