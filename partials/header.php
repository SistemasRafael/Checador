<?php
  include "connections/config.php";
  $_SESSION['unidad_sel'] = $_GET['unidad'] ?? ''; 
  $userId = $_SESSION['u_id'] ?? 0;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>HelioStarVisit</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="Argonaut Gold">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css"/>
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script type="text/javascript" src="js/bootstrap.min.js"></script> 
  <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">  
  <script src="//code.jquery.com/jquery-1.11.0.min.js"></script> 
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <style type="text/css">
    body {
      padding-bottom: 20px;
    }

    .navbar {
      margin-bottom: 20px;
    }

    .bg-blue {
      background-color: #152c52;
    }

    .navbar-dark .navbar-nav .nav-link {
      color: white;
    }

    .navbar-brand {

      height: 80px;
    }

    img {
      max-width: 100%;
    }

    .barra {
      width: 100%;
      padding: 5px;
      height: 35px;
      background-color: #cecece;
      text-align: left;
      color: blue;
    }

    .barra a {
      color: #152c52;

    }

    .nav-item a {
      font-size: 12px;
      font-weight: lighter;
      font-family: tahoma;
    }
  </style>
</head>
<body>
  <div class="barra">
    <div class="container">
      <ul class="nav nav-pills">
        <li class="nav-item">
          <a class="text-muted" href="https://www.heliostarmetals.com" target="_blank">Aviso de Privacidad</a>
        </li>
         <li class="nav-item">
          <a class="text-muted" data-toggle="modal" data-target="#pdfManualUsuarioProv" name="Manual">Manual Proveedores</a>
        </li>
        <li class="nav-item">
          <a class="text-muted" data-toggle="modal" data-target="#pdfManualUsuarioEmp" name="Manual">Manual Empleados</a>
        </li>
        <li class="nav-item">
          <a class="text-muted" data-toggle="modal" data-target="#pdfEtica" name="Etica">Código de Ética Proveedores</a>
        </li>
        

        <?php if (isset($_SESSION['LoggedIn']) && $_SESSION['LoggedIn'] == 1) {
            
            $cat_usuarios = $mysqli->query("SELECT count(*) AS cat
                            FROM arg_usuarios_directivas
                            WHERE directiva_id = 3 AND u_id = $userId") or die(mysqli_error($mysqli));

                      $cat_us = $cat_usuarios->fetch_array(MYSQLI_ASSOC);
                      $cat_user = $cat_us['cat'];
            
            if ($cat_user <> 0) {?>
            <li class="nav-item" >
              <a class="nav-link"   href="usuarios.php">Catalogo de Usuarios</a>
            </li>
   <?php     }
        
             
$stic = $mysqli->query("SELECT count(*) AS permiso
                            FROM arg_usuarios_directivas
                            WHERE directiva_id = 2 AND u_id = $userId") or die(mysqli_error($mysqli));

                      $sticker = $stic->fetch_array(MYSQLI_ASSOC);
                      $asigna_sticker = $sticker['permiso'];
        ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">Hola <?php  echo $_SESSION['nombre'] ?></a>
            <div class="dropdown-menu">
              <a class="dropdown-item" href="mi_cuenta.php">Mi cuenta</a>
             
             <?php if ($asigna_sticker == 1){?> 
              <a class="dropdown-item" href="sticker_manual.php?u_id=<?php echo $userId?>&unidad=<?php echo $_SESSION['unidad_sel'];?>">Asignar STICKER</a>
              <?php }?> 
              <a class="dropdown-item" href="visor_visitas.php">Mis Visitas</a>
              <a class="dropdown-item" href="#">Mis Cursos</a>
              <a class="dropdown-item" href="logout.php">Cerra sesión</a>
          </li>

        

          <?php  if ($_SESSION['empleado'] == 1) { ?>
            <div class="col md-1 lg-1">
            </div>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">Cambiar de Mina</a>
              <div class="dropdown-menu">
                <?php 
                //0 = Todos
                if ($_SESSION['unidad_acc'] == '0') {
                  $datos_at = $mysqli->query("SELECT serie, unidad_id, nombre
                     FROM arg_empr_unidades") or die(mysqli_error($mysqli));
                  while ($row = $datos_at->fetch_assoc()) {
                ?>
                    <a class="dropdown-item" href="dashboard.php?tipo=1&unidad=<?php  echo $row['unidad_id']; ?>&org=0"><?php  echo $row['nombre']; ?></a>
                <?php 
                  } //Fin while
                } //Fin if para todas las unidades
                ?>
                <?php 
                //1|2|3=Solo una unidad de mina
                if ($_SESSION['unidad_acc'] <> '0' and $_SESSION['unidad_acc'] <> '999') {
                  $datos_umina = $mysqli->query("SELECT serie, unidad_id, nombre
                     FROM arg_empr_unidades WHERE unidad_id = " . $_SESSION['unidad_acc']) or die(mysqli_error($mysqli));
                  while ($row = $datos_umina->fetch_assoc()) {
                ?>
                    <a class="dropdown-item" href="dashboard.php?tipo=1&unidad=<?php  echo $row['unidad_id']; ?>"><?php  echo $row['nombre']; ?></a>
                <?php  }
                } ?>
                <?php 
                //999=Varias unidades de mina (No Todas)
                $cadena = strlen($_SESSION['unidades']);
                $i = 0;
                if ($_SESSION['unidad_acc'] == '999') {
                  while ($i <= $cadena) {
                    $valor = substr($_SESSION['unidades'], $i, 1);
                    if (is_numeric($valor)) {
                      // echo $valor;
                      $datos_umina = $mysqli->query("SELECT serie, unidad_id, nombre
                            FROM arg_empr_unidades
                            WHERE unidad_id = " . $valor) or die(mysqli_error($mysqli));

                      $mina_acce = $datos_umina->fetch_array(MYSQLI_ASSOC);
                      $mina_acc = $mina_acce['unidad_id'];
                      $mina_acc_nombre = $mina_acce['nombre'];

                ?>
                      <a class="dropdown-item" href="dashboard.php?tipo=1&unidad=<?php  echo $mina_acc; ?>"><?php  echo $mina_acc_nombre; ?></a>
                <?php 
                    }
                    $i = $i + 1;
                  }
                }
                ?>
            </li>
        <?php } } ?>
        <div class="modal fade" id="pdfEtica" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <embed src=".\CODIGO DE CONDUCTA Y ETICA EMPRESARIAL PARA PROVEEDORES HSTM.pdf" frameborder="0" width="100%" height="1000px">
                </div>
            </div>
         </div>
          <div class="modal fade" id="pdfManualUsuarioProv" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <embed src=".\Manual de Usuario SISTEMA BITACORA DE VISITAS ARGONAUT GOLD.pdf" frameborder="0" width="100%" height="1000px">
                </div>
            </div>
        </div>
        <div class="modal fade" id="pdfManualUsuarioEmp" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <embed src=".\Manual de usuario (empleado) BITACORA DE VISITAS ARGONAUT GOLD.pdf" frameborder="0" width="100%" height="1000px">
                </div>
            </div>
        </div>
      </ul>
      <br />
    </div>
  </div>
  <nav class="navbar navbar-expand-xl navbar-dark bg-white">
    <?php
      $unidad_mina_sel = $_GET['unidad'] ?? '';
      if ($unidad_mina_sel == '') {
        $unidad_mina_sel = $_SESSION['unidad_def'] ?? '';
      }
    ?>
    <a class="navbar-brand logos" href="dashboard.php?tipo=1&unidad=<?php  echo $unidad_mina_sel; ?>">
      <img src="images/argonaut-logo2.jpg" alt="ArgonautGold Logo">
    </a>
    <div class="col md-2 lg- "> </div>
    <div class="col md-2 lg-2">
      <h1><?php  echo ' ' . ($_SESSION['empresa'] ?? '') ?></h1>
    </div>
  </nav>