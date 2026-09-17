<?php
include "connections/config.php";
//	require("ldap.php");
// require("user_bd.php");
header("Content-Type: text/html; charset=utf-8");
$usr = $_POST["usuario"];
$passw = $_POST["clave"];

if ($passw == '') {
    echo 'La contrase�a no puede estar vac�a';
    echo"<script>window.location.href='index.php'</script>";
}
else {
    $pass = MD5($_POST["clave"]);
    //$usuario = mailboxpowerloginrd($usr, $_POST["clave"]);        
    //if($usuario == "0" || $usuario == ''){
    $mysqli -> set_charset("utf8");
    $existe_ext = $mysqli->query("SELECT u.u_id, u.codigo, u.nombre 
                                        ,u.email, emp.nombre AS empresa, emp.org_id, u.activo
                                    FROM arg_usuarios u
                                    LEFT JOIN arg_organizaciones emp
                                    ON emp.org_id = u.org_id
                                    WHERE u.codigo = '".$usr."'
                                    AND u.clave = '".$pass."'");
    $existe_extu = $existe_ext->fetch_array(MYSQLI_ASSOC);
    $cuenta_activa      = $existe_extu['activo'];

    //var_dump($existe_extu);
    //die();
    if ($existe_extu <> '' && $cuenta_activa == 1) {
        session_start();
        $_SESSION["LoggedIn"] = 1;
        $_SESSION["user"] = $usr;
        $_SESSION["autentica"] = "SIP";  
        $cuenta_usuario      = $existe_extu['codigo'];
        $_SESSION['nombre']  = $existe_extu['nombre']; 
        $_SESSION['email']   = $existe_extu['email'];
        $_SESSION['empresa'] = $existe_extu['empresa'];
        $_SESSION['org_id']  = $existe_extu['org_id'];
        $_SESSION['u_id']    = $existe_extu['u_id']; 
        $_SESSION['unidad_def'] = 0;
        $_SESSION['unidad_acc'] = 0;
        $_SESSION['empleado'] = 0;
        
        echo"<script>window.location.href='dashboard.php?tipo=1'</script>";
    } 
    else { 
        echo"<script> alert('Usuario o clave incorrecta. Vuelva a digitarlos por favor.'); window.location.href='index.php'; </script>";
    }
        
    if ($_SESSION['org_id'] == 0) {
        $existe_ext2 = $mysqli->query("SELECT u.u_id, u.codigo, u.nombre
                                            ,u.email, emp.nombre AS empresa, emp.org_id, (CASE WHEN u.division = 'empleado' THEN 1 WHEN u.division = 'vigilante' THEN 99 ELSE 0 END) AS empleado
                                            ,(CASE WHEN uni.valor = '0' THEN '1' WHEN uni.valor LIKE '%,%' THEN SUBSTRING(uni.valor, 1, 1)  ELSE uni.valor END) AS unidad_def
                                            ,(CASE WHEN uni.valor = '0' THEN '0' WHEN uni.valor LIKE '%,%' THEN 999 ELSE uni.valor END) AS unidad_acc
                                            ,uni.valor AS unidades                       
                                        FROM arg_usuarios u
                                        LEFT JOIN arg_organizaciones emp
                                        ON emp.org_id = u.org_id
                                        LEFT JOIN arg_usuarios_directivas uni
                                        ON uni.u_id = u.u_id 
                                        AND uni.directiva_id = 1
                                        WHERE u.codigo = '".$usr."'");

        $existe_extu2 = $existe_ext2->fetch_array(MYSQLI_ASSOC);
        $_SESSION["unidad_def"] = $existe_extu2['unidad_def'];
        $_SESSION["unidad_acc"] = $existe_extu2['unidad_acc'];
        $_SESSION["unidades"]   = $existe_extu2['unidades'];
        $_SESSION['empleado']   = $existe_extu2['empleado'];
        
        if($_SESSION['unidad_def'] <> '') {
            $unidad_defa = $mysqli->query("SELECT serie
                    FROM arg_empr_unidades 
                    WHERE unidad_id = ".$_SESSION["unidad_def"]);
            $unidad_def = $unidad_defa->fetch_array(MYSQLI_ASSOC);
            $serie_def = $unidad_def['serie'];
        }
    }

    echo"<script>window.location.href='dashboard.php?tipo=1&unidad=".$_SESSION["unidad_def"]."'; </script>";
}
?>
