<?php
include "config/config.php";

$html = '';
$usuario_actu = $_POST['usuario_actualiza'];

$empresa_upd = $mysqli->query("SELECT 
                                   u.org_id 
                                  ,u.nombre
                                  ,og.nombre AS empresa
                                  ,now() AS fecha_hora
                               FROM
                                  arg_usuarios AS u
                                  LEFT JOIN arg_organizaciones AS og
                                    ON u.org_id = og.org_id
                               WHERE u.u_id = ".$usuario_actu) or die(mysqli_error($mysqli));             
$org_id_upda = $empresa_upd->fetch_assoc();
$org_id_update = $org_id_upda['org_id'];
$usuario_captura = $org_id_upda['nombre'];
$nombre_empresa = $org_id_upda['empresa'];
$fecha = $org_id_upda['fecha_hora'];

if (isset($org_id_update)){
    $fecha = date("Y-m-d", strtotime($fecha));
    $usuario = 'gabriela.grijalva@argonautgold.com';
    $copy = 'danira.romero@argonautgold.com';
    $copy2 = 'humberto.leyva@argonautgold.com';
    $copy3 = 'Yunuem.Salcedo@argonautgold.com';

    $_subject = "Actualizaci�n de SISDO.PRO de su Empresa ".$nombre_empresa;
    $_recipient = $usuario;

    require("PHPMailer_v51/class.phpmailer.php");
    $mail = new PHPMailer();
    $mail->From = "argonautgoldvisit@argonautgoldvisit.com";
    $mail->FromName = "Bitacora de Visitas";
    $mail->Subject = "SISDO.PRO en Bitacora de Visitas de ARGONAUT GOLD INC.";			
    $mail->AddBCC("".$usuario."");
    $mail->AddBCC("".$copy."");
    $mail->AddBCC("".$copy2."");
    $mail->AddBCC("".$copy3."");
    $mail->ContentType = "text/html";
    $body = "El usuario <strong>".$usuario_captura."</strong> de la empresa <strong>".$nombre_empresa."</strong> ha declarado que actualiz&oacute la documentaci&oacuten en el sistema <strong>SISDO.PRO</strong> con fecha <strong>".$fecha."</strong><br><br>";
    $body .= "<br>Para dar seguimiento y validaci&oacuten por favor ingrese al siguiente Link:<br><br><br>";    
    $body .= "http://ag.sisdo.pro/<br><br>";	
    $body .= "Atte: CONTROL DE ACCESO DE ARGONAUT GOLD";
    $mail->Body = $body;
    $mail->Send();    
}     
?>