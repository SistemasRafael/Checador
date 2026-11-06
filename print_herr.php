<?php
include "config/config.php";
include "config/Env.php";
require_once __DIR__ . '/vendor/autoload.php';
ini_set('memory_limit', '512M');
set_time_limit(300);

use Dompdf\Dompdf;
use Dompdf\Options;

if (isset($_GET['trn_id'])){
    Env::load(__DIR__ . '/.env');

    $options = new Options();
    $options->set('isRemoteEnabled', true);

    $dompdf = new Dompdf($options);

    $dompdf->loadHtml(CreateHtml($mysqli));
    $dompdf->setPaper('letter');
    $dompdf->render();

    if (ob_get_length()) {
        ob_end_clean();
    }

    $dompdf->stream(
        'reporte.pdf',
        ['Attachment' => false]
    );
}

function CreateHtml(mysqli $mysqli) {
    $trn_id_rel = $_GET['trn_id'];
    $empresa_encab = $mysqli->query("SELECT
                                        arg_organizaciones.nombre, usu.nombre AS usuario
                                    FROM `arg_entradas` 
                                    LEFT JOIN arg_usuarios usu
                                        ON usu.u_id = arg_entradas.usuario_id                                            
                                    LEFT JOIN arg_organizaciones
                                        ON usu.org_id = arg_organizaciones.org_id
                                    WHERE trn_id = ".$trn_id_rel) or die(mysqli_error($mysqli));
    $empresa = $empresa_encab ->fetch_array(MYSQLI_ASSOC);

    $datos_e = $mysqli->query("SELECT folio, DATE_FORMAT(fecha,'%d/%m/%Y') AS fecha, DATE_FORMAT(fecha_inicio,'%d/%m/%Y') AS fecha_inicio
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
                                WHERE trn_id = ".$trn_id_rel) or die(mysqli_error($mysqli));
    $entrada = $datos_e->fetch_assoc();
    
    $herramientas = $mysqli->query("SELECT arg_entradas_herramientas.cantidad, arg_herramientas.nombre, arg_herramientas.marca, arg_herramientas.serie
                                        FROM `arg_entradas_herramientas` 
                                        LEFT JOIN arg_herramientas
                                            ON arg_herramientas.herr_id = arg_entradas_herramientas.herr_id
                                        WHERE trn_id_rel = ".$trn_id_rel) or die(mysqli_error($mysqli));

    $html = "
    <!DOCTYPE html>
    <html>
        <head>
            <meta charset='UTF-8'>
            <style>
                html, body, table { 
                    font-family: helvetica; 
                    font-size: 100%; 
                }

                h1 {
                    color: #003366;
                }

                table, th, td {
                    border: 1px solid #000;
                }

                th {
                    background-color: #f2f2f2;
                }

                th, td {
                    padding: 8px;
                }

                .backgroud-color {
                    background-color: gray;
                }

                @page {
                    margin-top: 20px;
                    margin-bottom: 120px;
                    margin-left: 20px;
                    margin-right: 20px;
                }

                #footer {
                    position: fixed;
                    bottom: -60px;
                    left: 0;
                    right: 0;
                    height: 80px;
                }

                #header1{
                    width:100%;
                    margin-bottom:5px;
                }
            </style>
        </head>
        <body>
            <div id='header1'>
                <table width='100%' style='border:0;border-collapse:collapse;'>
                    <tr>
                        <td width='25%' 
                            style='border:0;
                                    text-align:left;
                                    vertical-align:middle;'>
                            <img src='".Env::get('APP_URL')."assets/images/argonaut-logo2.jpg' width='120' height='80'>
                        </td>
                        <td width='80%'
                            style='border:0;
                                text-align:center;
                                vertical-align:bottom;
                                padding-bottom:10px;
                                font-size:18px;'>
                            RESGUARDO PROVISIONAL
                        </td>
                    </tr>
                </table>
            </div>
            <div id='header'>
                <table id='encabezado' width='95%'  style='border:0.1px solid'>                                      
                        <thead>
                            <tr>            
                            <th scope='col' width='50%'  style='border:0.1px solid' align='left' bgcolor='white'>Nombre de la Empresa: ".$empresa['nombre']."</th>
                        </tr>                                
                    </thead>
                </table>
                <table id='encabezado' width='95%'  style='border:0.1px solid'>                                      
                    <thead>                                 
                        <tr>     
                            <th scope='col' width='90%' style='border:0.1px solid' align='left' bgcolor='white'>Nombre de quien elabora el resguardo: ".$empresa['usuario']."</th>
                        </tr>
                    </thead>
                </table>
                <table id='encabezado' width='95%'  style='border:0.1px solid'>                                      
                    <thead>                                 
                        <tr>     
                            <th scope='col' style='border:0.1px solid' align='left' bgcolor='white'>Fecha de Ingreso: ".$entrada['fecha_inicio']."</th>
                            <th scope='col' style='border:0.1px solid' align='left' bgcolor='white'>Fecha de Salida: ".$entrada['fecha_final']."</th>
                        </tr>
                    </thead>
                </table>
                <table id='encab' width='95%'  style='border:0.1px solid'>                                      
                    <thead>
                        <tr>            
                            <th scope='col' align='center' class='backgroud-color'>Descripción de los Materiales</th>
                        </tr>
                    </thead>
                </table>
                <table id='veh' width='95%'  style='border:0.1px solid'>                                      
                    <thead>
                        <tr>            
                            <th scope='col' width='5%'  align='left' class='backgroud-color'>No</th>
                            <th scope='col' width='10%' align='left' class='backgroud-color'>Cantidad</th>
                            <th scope='col' width='20%' align='left' class='backgroud-color'>Marca</th>
                            <th scope='col' width='30%' align='left' class='backgroud-color'>Serie</th>                                    
                            <th scope='col' width='40%' align='left' class='backgroud-color'>Descripción</th>
                        </tr>
                    </thead>
                </table>";
        $i=0;                     
        $html .= "<table id='herrami' width='95%' style='border:1px'>
                    <tbody>";                    
                        while ($fila_her = $herramientas->fetch_assoc()) {
                            $i++;
                            $html .="<tr>
                                        <td width='5%'  style='border:1px solid; font-size:14px' align='left'>".$i."</td>
                                        <td width='10%' style='border:1px solid; font-size:14px' align='left'>".$fila_her['cantidad']."</td>
                                        <td width='20%' style='border:1px solid; font-size:14px' align='left'>".$fila_her['marca']."</td>
                                        <td width='30%' style='border:1px solid; font-size:14px' align='left'>".$fila_her['serie']."</td>
                                        <td width='40%' style='border:1px solid; font-size:14px' align='left'>".$fila_her['nombre']."</td>
                                    </tr>"; 
                        }
        $html .="   </tbody>
                </table>
            </div>
            <div id='footer'>
                <table id='pie_p' width='95%' >                                      
                    <thead>
                        <tr>            
                            <th scope='col' width='30%' style='border:0.1px solid' align='left' bgcolor='white'>Nombre y firma de quien autoriza el ingreso</th>
                            <th scope='col' width='70%' style='border:0.1px solid' align='left' bgcolor='white'></th>
                        </tr>
                    </thead>
                </table>
                <table id='pie_p' width='95%'>                                      
                    <thead>
                        <tr>            
                            <th scope='col' width='30%' style='border:0.1px solid' align='left' bgcolor='white'>Nombre y firma de quien autoriza la salida</th>
                            <th scope='col' width='70%' style='border:0.1px solid' align='left' bgcolor='white'></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </body>
    </html>";

    return $html;
}
?>
