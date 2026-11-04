<?php
include "config/config.php";
$html='';

$respuesta_sele   = $_POST['respu_sel'];
$user_actualiza   = $_SESSION['u_id'];

$empresa_upd = $mysqli->query("SELECT 
                                  org_id  
                               FROM
                                  arg_usuarios
                               WHERE u_id = ".$user_actualiza) or die(mysqli_error($mysqli));             
$org_id_upda = $empresa_upd->fetch_assoc();
$org_id_update = $org_id_upda['org_id'];

       
if ($respuesta_sele == 0 || $respuesta_sele == 1){
   $max_trn_id = $mysqli->query("SELECT 
                                 IFNULL(max(trn_id), 0) AS trn_id_max 
                                 FROM
                                    arg_actualiza_sisdo") or die(mysqli_error($mysqli));             
   $max_trnid = $max_trn_id->fetch_assoc();
   $maxtrnid = $max_trnid['trn_id_max'];
   $maxtrnid = $maxtrnid+1;
   
   $query = "UPDATE arg_organizaciones 
                  SET  sisdo_pro = $respuesta_sele
            WHERE org_id = ".$org_id_update;
            
   $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));
   
   $query = "INSERT INTO arg_actualiza_sisdo (trn_id, u_id, valor)".
                       "VALUES ($maxtrnid, $user_actualiza, $respuesta_sele)";
   $mysqli->query($query);
}   

$html='Se guardaron los cambios correctamente';
echo ($html);