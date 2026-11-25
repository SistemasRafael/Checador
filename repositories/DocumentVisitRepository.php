<?php

class DocumentVisitRepository
{
    private mysqli $mysqli;

    public function __construct(mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
    }

    public function getResponsibleUserInformation(int $trnId): array
    {
        $sql = "SELECT arg_usuarios.nombre AS responsable
                FROM `arg_entradas`                                         
                LEFT JOIN arg_usuarios ON arg_usuarios.u_id = arg_entradas.usuario_id_resp
                WHERE trn_id = ?";

        $stmt = $this->mysqli->prepare($sql);

        if (!$stmt) {
            throw new Exception('Error preparing query: ' . $this->mysqli->error);
        }

        $stmt->bind_param("i", $trnId);

        if (!$stmt->execute()) {
            throw new Exception('Error executing query: ' . $stmt->error);
        }

        $result = $stmt->get_result();

        $empresa = $result->fetch_assoc();

        $stmt->close();

        return $empresa ?: null;
    }

    public function getAttendantInformation(int $trnId): array
    {
        $sql = "SELECT arg_usuarios.nombre AS atiende, dep.departamento
                FROM `arg_entradas`                                   
                LEFT JOIN arg_usuarios ON arg_usuarios.u_id = arg_entradas.usuario_id_atie
                LEFT JOIN arg_usuarios_departamentos AS ude ON ude.u_id = arg_usuarios.u_id
                LEFT JOIN arg_departamentos dep ON dep.dep_id = ude.dep_id
                WHERE trn_id =  ?";

        $stmt = $this->mysqli->prepare($sql);

        if (!$stmt) {
            throw new Exception('Error preparing query: ' . $this->mysqli->error);
        }

        $stmt->bind_param("i", $trnId);

        if (!$stmt->execute()) {
            throw new Exception('Error executing query: ' . $stmt->error);
        }

        $result = $stmt->get_result();

        $empresa = $result->fetch_assoc();

        $stmt->close();

        return $empresa ?: null;
    }

    public function getEntryInformation(int $trnId): array
    {
        $sql = "SELECT folio, DATE_FORMAT(fecha,'%d/%m/%Y') AS fecha, DATE_FORMAT(fecha_inicio,'%d/%m/%Y') AS fecha_inicio
                    ,DATE_FORMAT(fecha_final,'%d/%m/%Y') AS fecha_final, arg_empr_unidades.nombre AS unidad
                    ,arg_usuarios.nombre AS usuario
                    ,arg_entradas.comentario
                FROM `arg_entradas` 
                LEFT JOIN arg_entradas_detalle
                    ON arg_entradas_detalle.trn_id_rel = arg_entradas.trn_id
                LEFT  JOIN arg_empr_unidades
                    ON arg_empr_unidades.unidad_id = arg_entradas.unidad_id
                LEFT JOIN arg_usuarios
                    ON arg_usuarios.u_id = arg_entradas.usuario_id
                WHERE trn_id =  ?";

        $stmt = $this->mysqli->prepare($sql);

        if (!$stmt) {
            throw new Exception('Error preparing query: ' . $this->mysqli->error);
        }

        $stmt->bind_param("i", $trnId);

        if (!$stmt->execute()) {
            throw new Exception('Error executing query: ' . $stmt->error);
        }

        $result = $stmt->get_result();

        $empresa = $result->fetch_assoc();

        $stmt->close();

        return $empresa ?: null;
    }

    public function getCompanyHeader(int $trnId): array
    {
        $sql = "SELECT
                    UPPER(arg_organizaciones.nombre) AS nombre, UPPER(arg_organizaciones.calle) AS calle, arg_organizaciones.num_exterior, arg_entradas.comentario, UPPER(aut.nombre) as usuario_firma, dic.firma
                    ,arg_organizaciones.colonia, arg_ciudades.ciudad, usu.nombre AS usuario, ine.imss AS ine, lic.imss AS licencia, imss.imss AS nss
                FROM `arg_entradas` 
                LEFT JOIN arg_usuarios usu ON usu.u_id = arg_entradas.usuario_id
                LEFT JOIN usuarios_doc ine ON ine.u_id = usu.u_id AND ine.tipo_id = 2
                LEFT JOIN usuarios_doc lic ON lic.u_id = usu.u_id AND lic.tipo_id = 3
                LEFT JOIN usuarios_doc imss ON imss.u_id = usu.u_id AND imss.tipo_id = 1
                LEFT JOIN arg_organizaciones ON usu.org_id = arg_organizaciones.org_id
                LEFT JOIN arg_ciudades ON arg_ciudades.ciudad_id = arg_organizaciones.ciudad_id
                LEFT JOIN arg_usuarios_directivas dic ON dic.valor = arg_entradas.unidad_id AND dic.directiva_id = 2
                LEFT JOIN arg_usuarios aut ON aut.u_id = dic.u_id
                WHERE trn_id = ?";

        $stmt = $this->mysqli->prepare($sql);

        if (!$stmt) {
            throw new Exception('Error preparing query: ' . $this->mysqli->error);
        }

        $stmt->bind_param("i", $trnId);

        if (!$stmt->execute()) {
            throw new Exception('Error executing query: ' . $stmt->error);
        }

        $result = $stmt->get_result();

        $empresa = $result->fetch_assoc();

        $stmt->close();

        return $empresa ?: null;
    }

    public function getVisitInformation(int $trnId): array
    {
        $sql = "SELECT arg_actividad.nombre 
                FROM arg_entradas_actividad 
                LEFT JOIN arg_actividad ON arg_actividad.act_id = arg_entradas_actividad.act_id
                WHERE trn_id_rel = {$trnId}";

        $result = $this->mysqli->query($sql) or die(mysqli_error($this->mysqli));

        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }

    public function getAreasInformation(int $trnId): array
    {
        $sql = "SELECT
                    ear.area_id,
                    ua.area
                FROM `arg_entradas_areas` ear
                LEFT JOIN arg_entradas AS e ON ear.trn_id_rel = e.trn_id
                LEFT JOIN arg_unidades_areas ua ON ear.area_id = ua.area_id AND ua.unidad_id = e.unidad_id
                WHERE trn_id_rel = {$trnId}";

        $result = $this->mysqli->query($sql) or die(mysqli_error($this->mysqli));

        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }

    public function getVisitors(int $trnId): array
    {
        $sql = "SELECT arg_usuarios.nombre AS visitante, usuarios_doc.imss AS ine, imss.imss AS nss
                FROM `arg_entradas_detalle`                                         
                LEFT JOIN arg_usuarios ON arg_entradas_detalle.usuario_id = arg_usuarios.u_id
                LEFT JOIN usuarios_doc ON usuarios_doc.u_id = arg_entradas_detalle.usuario_id AND tipo_id = 2
                LEFT JOIN usuarios_doc imss ON imss.u_id = arg_entradas_detalle.usuario_id AND imss.tipo_id = 1
                WHERE trn_id_rel = {$trnId}";

        $result = $this->mysqli->query($sql) or die(mysqli_error($this->mysqli));

        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }

    public function getVehicles(int $trnId): array
    {
        $sql = "SELECT 
                    lic.imss as licencia, 
                    arg_vehiculos.marca, 
                    arg_vehiculos.modelo,
                    arg_vehiculos.color, 
                    arg_vehiculos.placas, 
                    arg_vehiculos.poliza
                FROM `arg_entradas` 
                LEFT JOIN arg_vehiculos ON arg_vehiculos.veh_id = arg_entradas.veh_id
                LEFT JOIN usuarios_doc lic ON arg_entradas.usuario_id = lic.u_id AND tipo_id = 3
                WHERE trn_id = {$trnId}";

        $result = $this->mysqli->query($sql) or die(mysqli_error($this->mysqli));

        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }
}