<script>
    function dash($unidad_id)
            {
                var unidad_id = $unidad_id
                var print_d = '<?php echo "\dashboard.php?tipo=1&unidad="?>';                
                    window.location.href = print_d+unidad_id;
            } 
</script>

<?php
date_default_timezone_set("America/Phoenix");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'librerias/PHPMailer.php';
require 'librerias/SMTP.php';
require 'librerias/Exception.php';

include "connections/config.php";
$trn_id = $_GET['trn_id'];
$estado_id = $_GET['estado_id'];
$unidad_id = $_SESSION['unidad_id'];
$u_id = $_SESSION['u_id'];
$hoy = date("Y-m-d H:i:s");
$dia = date("Y-m-d");
$hora = date("H:i:s");

//echo $hora;

$datos_est = $mysqli->query("SELECT estado
                                FROM arg_estados 
                                WHERE estado_id = ".$estado_id) or die(mysqli_error($mysqli));
$estado_iden = $datos_est ->fetch_array(MYSQLI_ASSOC);
$estado = $estado_iden['estado'];

    $query = "INSERT INTO arg_entradas_estados (trn_id, estado_id, fecha, comentario, u_id) ".
             "VALUES ($trn_id, $estado_id, '$hoy', '', $u_id)";
    $mysqli->query($query);

if ($estado_id == 2 || $estado_id == 3){
    $query = "UPDATE arg_entradas_dias 
             SET hora_inicio = '".$hora."'
                ,estado_id   = $estado_id
                ,usuario_id  = $u_id
          WHERE
             trn_id_rel = ".$trn_id;
    $mysqli->query($query);
}

if ($estado_id == 4 || $estado_id == 5){
    $query = "UPDATE arg_entradas_dias 
             SET hora_inicio = '".$hora."'
                ,estado_id = $estado_id
                ,usuario_id = $u_id
          WHERE
             trn_id_rel = ".$trn_id." AND fecha = '".$dia."'";
    $mysqli->query($query);
}

if ($estado_id == 6){
    $query = "UPDATE arg_entradas_dias 
             SET hora_fin = '".$hora."'
                ,estado_id = $estado_id
                ,usuario_id = $u_id
                 
          WHERE
             trn_id_rel = ".$trn_id." AND fecha = '".$dia."'";
    $mysqli->query($query) ;
}
//echo $query;


$datos_vis = $mysqli->query("SELECT folio,  un.nombre AS mina, us.nombre as proveedor, us.email AS proveedor_correo, ati.nombre as atiende, us.org_id
                                ,ati.email AS atiende_correo, o.nombre as empresa, ae.fecha_inicio, ae.unidad_id
                                FROM 
                                   arg_entradas ae
                                   LEFT JOIN arg_empr_unidades un
                                       ON un.unidad_id = ae.unidad_id
                                   LEFT JOIN arg_usuarios us
                                       ON us.u_id = ae.usuario_id
                                   LEFT JOIN arg_usuarios ati
                                       ON ae.usuario_id_atie = ati.u_id                    
                                   LEFT JOIN arg_organizaciones o
                                       ON o.org_id = us.org_id
                                WHERE ae.trn_id = ".$trn_id) or die(mysqli_error($mysqli));
$datos = $datos_vis ->fetch_array(MYSQLI_ASSOC);
$folio = $datos['folio'];
$fecha = $datos['fecha_inicio'];
$atiende = $datos['atiende'];

$atiende_correo = $datos['atiende_correo'];

$proveedor_nombre = $datos['proveedor'];
$proveedor_empresa = $datos['empresa'];
$proveedor_correo = $datos['proveedor_correo'];
$unidad_id = $datos['unidad_id'];
$organ_id  = $datos['org_id'];
// $copy_email = 'rene.mansell@heliostarmetals.com';

$prov_admin = $mysqli->query("SELECT 
                                   us.email AS prov_correo_admin
                                FROM 
                                    arg_usuarios us
                                WHERE
                                    us.administrador = 1
                                    AND us.org_id = ".$organ_id) or die(mysqli_error($mysqli));
                                    
        
        $copia_areas = $mysqli->query("SELECT 
        		u.email AS email
        	FROM 
            	arg_entradas_areas ea
                LEFT JOIN arg_entradas e
                	ON e.trn_id = ea.trn_id_rel
                LEFT JOIN arg_unidades_areas AS ua
                	ON ua.area_id = ea.area_id
                    AND ua.unidad_id = e.unidad_id
            	LEFT JOIN arg_unidades_areas_notificaciones AS no
                	ON ea.area_id = no.area_id
                LEFT JOIN arg_usuarios AS u
                	ON no.u_id = u.u_id
        WHERE
        	ea.trn_id_rel = ".$trn_id) or die(mysqli_error($mysqli));

				//include("crearPdfimagen.php"); 
				//require("PHPMailer_v51/class.phpmailer.php");
				$mail = new PHPMailer();
				$mail->From = "argonautgoldvisit@argonautgoldvisit.com";
				$mail->FromName = "Bitacora de Visitas MineData-Access HelioStarMetals";
				$mail->Subject = "Envio desde Bitacora de Visitas de MineData-Access HelioStarMetals";			
				$mail->AddBCC("".$proveedor_correo."");
				$mail->AddBCC("".$atiende_correo."");
				
				// $mail->AddBCC("".$copy_email."");
				
				$mail->SMTPOptions = array(
                                          'ssl' => array(
                                            'verify_peer' => FALSE,
                                            'verify_peer_name' => FALSE,
                                            'allow_self_signed' => TRUE
                                          )
                                        );   
				
				if ($prov_admin->num_rows > 0) {
			    while ($row_emails = $prov_admin->fetch_assoc()) {
				      $copy_admin = $row_emails['prov_correo_admin'];
				      $mail->AddBCC("".$copy_admin."");
				      
			        
			        while ($row_copy = $copia_areas->fetch_assoc()) {
			            $copia_ar = $row_copy['email'];
			            $mail->AddBCC("".$copia_ar."");
			        }   
				      
				 }
				}
				$mail->ContentType = "text/html";
				$body = "Su solicitud con folio <strong>".$folio."</strong> y fecha ".$fecha." ha sido <strong>".$estado."</strong><br><br> Empresa: ".$proveedor_empresa."<br> Nombre: ".$proveedor_nombre."<br><br> Atiende: <strong>".$atiende."</strong><br>";
				$body .= "<br>Para ver mas detalles favor de ingresar a la bitácora de visitas:<br><br>";
				$body .= " <font color='red'>http://heliostarvisit.com/app.php?trn_id=</font>".$trn_id."<br><br>";
                $body .= "Atte: BITACORA DE VISITAS MineData-Access HelioStarMetals";
				$mail->Body = $body;
				$mail->Send();
                //die();
    echo "<script> dash(".$unidad_id.");</script>";
?>
