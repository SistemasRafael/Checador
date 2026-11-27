<?php
namespace App\Repositories;

use App\Utilities\Response;
use App\Models\Visit;
use mysqli;

class VisitRepository
{
    private mysqli $mysqli;

    public function __construct(mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
    }

    public function add(Visit $visit) {

        $stmt = $this->mysqli->prepare("
            INSERT INTO arg_visita_grupal
            (
                nombre_completo,
                folio_visita,
                ine,
                nss,
                archivo_ine,
                archivo_nss
            )
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "sissss",
            $visit->nombre_completo,
            $visit->folio_visita,
            $visit->ine,
            $visit->nss,
            $visit->archivo_ine,
            $visit->archivo_nss
        );

        if (!$stmt->execute()) {
            return Response::error($stmt->error);
        }

        return Response::success(
            "Registro guardado",
            ["id" => $this->mysqli->insert_id]
        );
    }

    public function delete(int $visita_grupal_id)
    {
        $stmt = $this->mysqli->prepare("
            DELETE FROM arg_visita_grupal
            WHERE visita_grupal_id = ?
        ");

        $stmt->bind_param(
            "i",
            $visita_grupal_id
        );

        if (!$stmt->execute()) {
            return Response::error($stmt->error);
        }

        if ($stmt->affected_rows === 0) {
            return Response::error(
                "No se encontró el visitante con ID {$visita_grupal_id}"
            );
        }

        return Response::success(
            "Visitante eliminado correctamente"
        );
    }

}