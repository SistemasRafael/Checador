<?php

class ToolRepository
{
    private mysqli $mysqli;

    public function __construct(mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
    }

    public function getEmpresa(int $trnId): array
    {
        $sql = "SELECT
                    arg_organizaciones.nombre,
                    usu.nombre AS usuario
                FROM arg_entradas
                LEFT JOIN arg_usuarios usu
                    ON usu.u_id = arg_entradas.usuario_id
                LEFT JOIN arg_organizaciones
                    ON usu.org_id = arg_organizaciones.org_id
                WHERE trn_id = {$trnId}";

        $result = $this->mysqli->query($sql)
            or die(mysqli_error($this->mysqli));

        return $result->fetch_assoc() ?: [];
    }

    public function getEntrada(int $trnId): array
    {
        $sql = "SELECT
                    folio,
                    DATE_FORMAT(fecha,'%d/%m/%Y') AS fecha,
                    DATE_FORMAT(fecha_inicio,'%d/%m/%Y') AS fecha_inicio,
                    DATE_FORMAT(fecha_final,'%d/%m/%Y') AS fecha_final,
                    arg_empr_unidades.nombre AS unidad,
                    arg_usuarios.nombre AS usuario,
                    arg_entradas.comentario
                FROM arg_entradas
                LEFT JOIN arg_entradas_detalle
                    ON arg_entradas_detalle.trn_id_rel = arg_entradas.trn_id
                LEFT JOIN arg_empr_unidades
                    ON arg_empr_unidades.unidad_id = arg_entradas.unidad_id
                LEFT JOIN arg_usuarios
                    ON arg_usuarios.u_id = arg_entradas.usuario_id
                WHERE trn_id = {$trnId}";

        $result = $this->mysqli->query($sql)
            or die(mysqli_error($this->mysqli));

        return $result->fetch_assoc() ?: [];
    }

    public function getHerramientas(int $trnId): array
    {
        $sql = "SELECT
                    arg_entradas_herramientas.cantidad,
                    arg_herramientas.nombre,
                    arg_herramientas.marca,
                    arg_herramientas.serie
                FROM arg_entradas_herramientas
                LEFT JOIN arg_herramientas
                    ON arg_herramientas.herr_id = arg_entradas_herramientas.herr_id
                WHERE trn_id_rel = {$trnId}";

        $result = $this->mysqli->query($sql)
            or die(mysqli_error($this->mysqli));

        $herramientas = [];

        while ($row = $result->fetch_assoc()) {
            $herramientas[] = $row;
        }

        return $herramientas;
    }
}