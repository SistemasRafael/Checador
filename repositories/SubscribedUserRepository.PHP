<?php

class SubscribedUserRepository
{
    private mysqli $mysqli;

    public function __construct(mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
    }
    
    public function getCursosProveedor(
        int $unidadId,
        string $fechaInicio,
        string $fechaFin,
        int $orgId = 0
    ): array {

        $sql = "
            SELECT
                us.nombre AS nombre,
                us.u_id AS u_id,
                us.codigo AS codigo,
                us.email AS email,
                us.org_id AS org_id,
                cu.fecha AS fecha,
                cc.unidad_id AS unidad,
                eu.nombre AS unidad_nombre,
                og.nombre AS organizacion
            FROM arg_usuarios us
            INNER JOIN arg_calendario_usuarios cu
                ON cu.u_id = us.u_id
            INNER JOIN arg_calendario_cursos cc
                ON cc.cal_id = cu.cal_id
            INNER JOIN arg_empr_unidades eu
                ON eu.unidad_id = cc.unidad_id
            INNER JOIN arg_organizaciones og
                ON og.org_id = us.org_id
            WHERE us.division = 'proveedor'
                AND cc.unidad_id = {$unidadId}
                AND og.org_id = (
                    CASE
                        WHEN {$orgId} = 0 THEN og.org_id
                        ELSE {$orgId}
                    END
                )
                AND DATE(cu.fecha)
                    BETWEEN '{$fechaInicio}' AND '{$fechaFin}'
            ORDER BY cu.fecha DESC
        ";

        $result = $this->mysqli->query($sql);

        if (!$result) {
            throw new Exception($this->mysqli->error);
        }

        $cursos = [];

        while ($row = $result->fetch_assoc()) {
            $cursos[] = $row;
        }

        return $cursos;
    }
}