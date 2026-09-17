<?php 
include "connections/eds_core.php"; 

$fecha_ini = $_GET['fecha_ini'];
$fecha_fin = $_GET['fecha_fin'];
$mina = $_GET['mina'];
$empleado = $_GET['empleado'];
?>
	<html>
	<head>
		<title>Argonaut Gold</title>
		</head>
		<body>
		      <?include('partials/eds_visor_export.php'); ?>
		</body>
		</html>
