<?php
include "config/config.php";
include "config/Env.php";
require_once __DIR__ . '/vendor/autoload.php';
ini_set('memory_limit', '512M');
set_time_limit(300);

use Dompdf\Dompdf;
use Dompdf\Options;





if (isset($_GET['unidad_id'])) {
    Env::load(__DIR__ . '/.env');

    $unidad_id = (int) $_GET['unidad_id'];
    $fecha_i   = $_GET['fecha_i'];
    $fecha_f   = $_GET['fecha_f'];
    $org_id    = (int) $_GET['org_id'];

    $datos_texto = obtenerCursosProveedor(
        $mysqli,
        $unidad_id,
        $fecha_i,
        $fecha_f,
        $org_id
    );

    $options = new Options();
    $options->set('isRemoteEnabled', true);

    $dompdf = new Dompdf($options);

    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <style>
            @page {
                margin-top: 120px;
                margin-bottom: 40px;
                margin-left: 20px;
                margin-right: 20px;
            }

            #header {
                position: fixed;
                top: -100px;
                left: 0;
                right: 0;
                height: 100px;
                text-align: center;
            }

            #content {
                margin-top: 20px;
            }
            body {
                font-family: Arial, sans-serif;
                font-size: 10px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            #motivos {
                width: 100%;
            }
            
            h4 {
                margin: 5px 0 0 0;
            }
            
            th {padding: 0 5px;}
            td {padding: 0 5px;}
            table, th, td {border: 2px solid black;}
        </style>
    </head>
    <body>
        <div id="header">
            
            <img width="55" src="'.Env::get('APP_URL').'assets/images/argonaut-logo2.jpg">
            <h4>LISTA DE SOLICITUDES A CURSOS DE INDUCCIÓN COMPLETA</h4><br /><br /><br />
        </div>
 <div id="content">
        <table  id="motivos">
            <thead>
                <tr>
                    <th colspan="5">
                        Usuarios Inscritos
                    </th>
                </tr>
                <tr>
                    <th>FECHA</th>
                    <th>UNIDAD</th>
                    <th>NOMBRE</th>
                    <th>EMPRESA</th>
                    <th>EMAIL</th>
                </tr>
            </thead>
            <tbody>';

    while ($fila = $datos_texto->fetch_assoc()) {

        $html .= '
            <tr>
                <td>'.htmlspecialchars($fila['fecha']).'</td>
                <td>'.htmlspecialchars($fila['unidad_nombre']).'</td>
                <td>'.htmlspecialchars($fila['nombre']).'</td>
                <td>'.htmlspecialchars($fila['organizacion']).'</td>
                <td>'.htmlspecialchars($fila['email']).'</td>
            </tr>
        ';
    }

    $html .= '
            </tbody>

        </table>
</div>
    </body>
    </html>
    ';
    $dompdf->loadHtml($html);

    $dompdf->setPaper('letter');

    $dompdf->render();

    $dompdf->stream(
        'reporte_usuarios.pdf',
        [
            'Attachment' => false
        ]
    );
}

/**
 * Obtiene los cursos completados por proveedores.
 */
function obtenerCursosProveedor($mysqli, $unidad_id, $fecha_i, $fecha_f, $org_id)
{
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
            AND cc.unidad_id = {$unidad_id}
            AND og.org_id = (
                CASE
                    WHEN {$org_id} = 0 THEN og.org_id
                    ELSE {$org_id}
                END
            )
            AND DATE_FORMAT(cu.fecha, '%Y-%m-%d')
                BETWEEN '{$fecha_i}' AND '{$fecha_f}'
        ORDER BY cu.fecha DESC
    ";

    $resultado = $mysqli->query($sql);

    if (!$resultado) {
        die($mysqli->error);
    }

    return $resultado;
}