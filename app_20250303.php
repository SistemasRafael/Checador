<?php include("seguridad.php"); 
$mina = $_GET['mina'];
$inf = $_GET['inf']; //= 2;
//echo $inf;
//echo $mina;
?>
	<html>
	<head>
		<title>Argonaut Gold</title>
		</head>
		<body>		
            <?include('partials/header.php'); ?>
            <?
            if ($inf == 3){
                include('partials/eds_visor_roles.php');
            }
            else{
                include('partials/eds_visor_val.php'); 
            }?>
            <?include('partials/footer.php'); ?>
		</body>
		</html>
