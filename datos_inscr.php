<?php
include "config/config.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'librerias/PHPMailer.php';
require 'librerias/SMTP.php';
require 'librerias/Exception.php';

$html = '';
$u_id = $_POST['u_id'];
$fecha = $_POST['fecha'];
$existe = $_POST['existe'];
$unidad_mina = $_POST['mina_sel'];

$max_trn = $mysqli->query("SELECT MAX(trn_id) AS trn_id FROM arg_calendario_usuarios") or die(mysqli_error($mysqli));
$max = $max_trn->fetch_array(MYSQLI_ASSOC);
$trn_id = $max['trn_id'];
$trn_id = $trn_id + 1;

if ($existe == 1) {
    $dia = str_pad(date("d", strtotime($fecha)), 2, "0", STR_PAD_LEFT);
    $m = date("m", strtotime($fecha));
    $y = date("Y", strtotime($fecha));
    $ins = $mysqli->query("SELECT cal_id as cal_id FROM arg_calendario_cursos WHERE unidad_id = ".$unidad_mina." AND fecha = '$y-$m-$dia'");
    $in = $ins->fetch_array(MYSQLI_ASSOC);
    $cal_id = $in['cal_id'];
    $ins2 = $mysqli->query("INSERT INTO arg_calendario_usuarios (trn_id, u_id, cal_id, tipo_induccion, fecha) 
                            VALUES ($trn_id, $u_id, $cal_id, 1, '$y-$m-$dia')");
} 
else {
    $max_trn_id = $mysqli->query("SELECT MAX(cal_id) AS cal_id FROM arg_calendario_cursos") or die(mysqli_error($mysqli));
    $max_trn = $max_trn_id->fetch_array(MYSQLI_ASSOC);
    $cal_id = $max_trn['cal_id'];
    $cal_id = $cal_id + 1;

    $dia = str_pad(date("d", strtotime($fecha)), 2, "0", STR_PAD_LEFT);
    $m = date("m", strtotime($fecha));
    $y = date("Y", strtotime($fecha));
    if($unidad_mina == 1){
        $dia_letra = 'Lunes';
        $max_participantes = 20;
    }
    if($unidad_mina == 2){        
        $dia_letra = 'Jueves';
        $max_participantes = 20;
    }
    if($unidad_mina == 3){        
        $dia_letra = 'Jueves';
        $max_participantes = 15;
    }
    
    if (isset($u_id)) {
        $query = "INSERT INTO arg_calendario_cursos (unidad_id, cal_id, nombre, dia, mes, ejercicio, fecha, max_part)
                VALUES ($unidad_mina, $cal_id, '$dia_letra', $dia, $m, $y, '$y-$m-$dia', $max_participantes)";
        $mysqli->query($query);
        
        $query = "INSERT INTO arg_calendario_usuarios (trn_id, u_id, cal_id, tipo_induccion, fecha)"."VALUES ($trn_id, $u_id, $cal_id, 1, '$y-$m-$dia')";
        $mysqli->query($query);
        
    }
}

$resultado = $mysqli->query("SELECT * FROM arg_calendario_usuarios WHERE trn_id = " . $trn_id) or die(mysqli_error($mysqli));

if ($resultado->num_rows > 0) {
    $query2 = "UPDATE arg_calendario_cursos 
                SET max_part = max_part - 1 
                WHERE cal_id =" . $cal_id;
    $mysqli->query($query2);
    $html .= "Se ha inscrito correctamente";

    $datos_ins = $mysqli->query("SELECT org.nombre AS empresa, us.nombre, us.email, cc.fecha
                                    FROM arg_calendario_usuarios cu                                   
                                    LEFT JOIN arg_usuarios us
                                        ON cu.u_id = us.u_id
                                    LEFT JOIN arg_calendario_cursos cc ON cc.cal_id = cu.cal_id
                                    LEFT JOIN arg_organizaciones org ON org.org_id = us.org_id
                                    WHERE cu.trn_id = " . $trn_id) or die(mysqli_error($mysqli));
    $datos_in = $datos_ins->fetch_array(MYSQLI_ASSOC);
    $proveedor_sol = $datos_in['nombre'];
    $empresa_sol = $datos_in['empresa'];
    $fecha_cur = $datos_in['fecha'];
    $email_pr = $datos_in['email'];
    
    $mail = new PHPMailer();
    $mail->From = "argonautgoldvisit@argonautgoldvisit.com";
    $mail->FromName = "Bitacora de Registro";
    $mail->Subject = "Envio desde Bitacora de Registro de Argonaut Gold INC.";
    $copy_d = 'danira.romero@argonautgold.com';
    $mail->SMTPOptions = array('ssl' => array(
                                    'verify_peer' => FALSE,
                                    'verify_peer_name' => FALSE,
                                    'allow_self_signed' => TRUE
                            ));
    
    if($unidad_mina == 1) {
        $medico_unidad = 'julio.esquer@argonautgold.com';
        $mail->AddBCC("" . $medico_unidad . "");
        $mail->AddBCC("" . $copy_d . "");
    }

    if($unidad_mina == 2) {        
        $medico_unidad = 'Medico.SanAgustin@argonautgold.com';
        $copy_j = 'juan.meneses@argonautgold.com';
        $mail->AddBCC("" . $medico_unidad . "");
        $mail->AddBCC("" . $copy_d . "");
        $mail->AddBCC("" . $copy_j . "");
    }

    if($unidad_mina == 3) {         
        $medico_unidad  = 'Omar.delgado@argonautgold.com';
        $medico2_unidad = 'Rigoberto.Villa@argonautgold.com';
        $copy_j         = 'juan.meneses@argonautgold.com';
        $mail->AddBCC("" . $medico_unidad . "");
        $mail->AddBCC("" . $medico2_unidad . "");
        $mail->AddBCC("" . $copy_d . "");
        $mail->AddBCC("" . $copy_j . "");
    }
    
    $seguridad = 'Lizbeth.Gonzalez@argonautgold.com';
    $mail->AddBCC("" . $seguridad . "");
    $mail->AddBCC("" . $email_pr . "");

    $mail->ContentType = "text/html";
    $body = "El proveedor <strong>" . $proveedor_sol . "</strong> de la empresa <strong>" . $empresa_sol . "</strong> ha solicitado su curso de induccion completa para la fecha pr&oacutexima: <strong>" . $fecha_cur . "</strong><br>";
    $body .= "<br>Para revisar la lista de inscritos favor de ingresar al Sistema de Control de Acceso:<br><br>";
    $body .= "http://argonautgoldvisit.com <br><br>";
    $body .= "Atte: Control de Acceso Argonaut Gold INC";
    $mail->Body = $body;
    $mail->Send();
}
else {
    $html = "Hubo un error. Por favor reintente";
}

echo $html;
?>