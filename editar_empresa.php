<?php
include "connections/config.php";

$html='';
$org_id  = $_POST['org_id'];
$calle   = $_POST['calle'];
$numero  = $_POST['numero'];
$colonia = $_POST['colonia'];
$ciudad  = $_POST['ciudad'];
$localidad = $_POST['localidad'];
$codigo_postal = $_POST['cp'];
$prov_admin = $_POST['prov_ad'];

if ($calle <> ''){
    $query = "UPDATE arg_organizaciones 
                    SET  calle= '$calle'
              WHERE org_id = ".$org_id;
    $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));
}   

if ($numero <> '') {
    $query = "UPDATE arg_organizaciones 
                    SET num_exterior = $numero
                    WHERE org_id = ".$org_id;
    $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));
}

if ($colonia <> '') {
    $query = "UPDATE arg_organizaciones 
                    SET colonia = '$colonia'
            WHERE org_id = ".$org_id;
    $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));
}
  
if ($localidad <> '') {
    $query = "UPDATE arg_organizaciones 
                    SET  localidad = '$localidad'
              WHERE org_id = ".$org_id;
    $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli)); 
}

if ($ciudad <> '') {
    $query = "UPDATE arg_organizaciones 
                    SET ciudad_id = '$ciudad'
            WHERE org_id = ".$org_id;
    $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli)); 
}

if ($codigo_postal <> '') {
    $query = "UPDATE arg_organizaciones 
                    SET codigo_postal = '$codigo_postal'
              WHERE org_id = ".$org_id;
    $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));
}


$admin_empr = $mysqli->query("SELECT COUNT(*) as existe
                                FROM arg_organizaciones 
                                WHERE usuario_administrador <> 0 AND org_id = ".$org_id) or die(mysqli_error($mysqli));
$admin_empre = $admin_empr->fetch_assoc();
$admin_empresa = $admin_empre['existe'];

if ($admin_empresa == 0){
    $query = "UPDATE arg_organizaciones 
                    SET usuario_administrador = $prov_admin
              WHERE org_id = ".$org_id;
    $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));
 
    $query = "UPDATE arg_usuarios 
                    SET administrador = 1
              WHERE 
              u_id = $prov_admin
              AND org_id = ".$org_id;
    $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));
}
   
$html='Se guardaron los cambios correctamente';
echo ($html);