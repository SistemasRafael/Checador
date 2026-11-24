<?php
include "config/config.php";
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/Env.php';

require_once __DIR__ . '/mail/MailService.php';
require_once __DIR__ . '/mail/providers/PHPMailerProvider.php';
require_once __DIR__ . '/mail/templates/SuscriptionDataEmail.php';

$html = '';
$u_id = $_POST['u_id'];
$fecha = $_POST['fecha'];
$existe = $_POST['existe'];
$unidad_mina = $_POST['mina_sel'];
$path = __DIR__ . '/.env';
Env::load(__DIR__ . '/.env');

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
    $dia_letra = '';
    $max_participantes = 0;

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
        
        $query = "INSERT INTO arg_calendario_usuarios (trn_id, u_id, cal_id, tipo_induccion, fecha)
                VALUES ($trn_id, $u_id, $cal_id, 1, '$y-$m-$dia')";
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

    $to = [
        $datos_in['email'], 
        'Lizbeth.Gonzalez@heliostarmetals.com'
    ];

    $cc = [];

    if ($unidad_mina == 1) {
        $cc[] = 'julio.esquer@heliostarmetals.com';
    }

    if ($unidad_mina == 2) {
        $cc[] = 'Medico.SanAgustin@heliostarmetals.com';
        $cc[] = 'juan.meneses@heliostarmetals.com';
    }

    if ($unidad_mina == 3) {
        $cc[] = 'Omar.delgado@heliostarmetals.com';
        $cc[] = 'Rigoberto.Villa@heliostarmetals.com';
        $cc[] = 'juan.meneses@heliostarmetals.com';
    }
    
    $template = new SuscriptionDataEmail([
        'proveedor_sol' => $proveedor_sol,
        'empresa_sol' =>  $empresa_sol,
        'fecha_cur' => $fecha_cur,
        'app_url' => Env::get('APP_URL')
    ]);

    $mailService = new MailService(new PHPMailerProvider());

    $message = new EmailMessageDTO(
        to: $to,
        subject: $template->subject(),
        body:  $template->render(),
        cc : $cc
    );

    $mailService->send($message);
}
else {
    $html = "Hubo un error. Por favor reintente";
}

echo $html;
?>