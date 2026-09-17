<?php
include '\xampp\htdocs\registro\connections\config.php';

$nombre = $_GET['nombre'];
$rfc    = $_GET['rfc'];
$calle  = $_GET['calle'];
$numero = $_GET['numero'];

//echo $nombre; echo $rfc; echo $calle; echo $numero;

$rfc_empresa = $mysqli->query("SELECT rfc FROM arg_organizaciones WHERE rfc = '".$rfc."'");
$existe_rfc = $rfc_empresa->fetch_array(MYSQLI_ASSOC);
$val_rfc = count($existe_rfc);

$max_id = $mysqli->query("SELECT max(id) AS maximo FROM arg_organizaciones");
$maximo = $max_id->fetch_array(MYSQLI_ASSOC);
$maximo_id = ($maximo['maximo']+1);
//echo $val_rfc;

if($val_rfc > 0 ){
    echo 'El RFC ya se encuentra registrado';
}
else{
        //echo $maximo_id;
       //echo 'entro';        
        $query ="INSERT INTO arg_organizaciones (id, rfc, nombre, calle, num_exterior, colonia, localidad, ciudad_id, codigo_postal, org_id ) ".
                            "VALUES ($maximo_id, '$rfc', '$nombre','$calle','$numero','colonia','localidad',1,'83250',$maximo_id)";
        
                        
        $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));   
        $_SESSION['empresa_nueva'] =  $nombre;   
        header("Location:".$_SERVER['HTTP_REFERER']);                     
       // echo "<br><b>Se guardó con exito:</b><br><br>"."$nombre <br>"; 
       // (die);
       ?>
     
<?
   }
   /* <script>
            var regresa = '<?php echo "\register.php?"?>';                
            window.location.href = regresa;
            //window.history.back();
       </script>*/
?>

