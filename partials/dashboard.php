<script>
  function redireccion($trn_id) {
    var trn_id = $trn_id;
    var print_d = '<?php echo "\app.php?trn_id=" ?>' + trn_id;
    window.location.href = print_d;
  }

  function expo($u_id, $fecha_ini, $fechafinal, $empleado, $unidad_mina_sel) {
    var hoy = $fecha_ini;
    var fecha_fin = $fechafinal;
    var mina = $unidad_mina_sel;
    var empleado = $empleado;
    var u_id = $u_id;
    alert(hoy);

    var cadena = u_id + '&hoy=' + hoy + '&fecha_final=' + fecha_fin + '&empleado=' + empleado + '&unidad=' + mina;
    var print_d = '<?php echo "https://heliostarvisit.com/visitas_export.php?u_id=" ?>' + cadena;
    //alert(print_d);
    window.location.href = print_d;
  }
</script>

<style type="text/css">
  .btnSubmit {
    width: 50%;
    border-radius: 1rem;
    padding: 1.5%;
    border: none;
    cursor: pointer;
  }

  .circulos {
    padding-top: 5em;
  }

  img {
    max-width: 100%;
  }

  #chart-container {
    width: 100%;
    height: auto;
  }
</style>
<?php

if (($_SESSION['LoggedIn']) <> '') {
  $u_id = $_SESSION['u_id'];
  $unidad_mina_sel = $_GET['unidad'] ?? null;
  $vig  = $_SESSION['empleado'];
  //echo $vig;
  $vigilancia = $mysqli->query("SELECT (CASE WHEN division = 'vigilante' THEN 1 ELSE 0 END) AS tipo_user FROM arg_usuarios WHERE u_id = ".$u_id) or die(mysqli_error($mysqli));             
  $vigilancia_v = $vigilancia->fetch_assoc();
  $vigilancia_ver = $vigilancia_v['tipo_user'];
  
  if (is_null($unidad_mina_sel)) {
    $unidad_mina_sel = $_SESSION['unidad_def'];
  }
  
  //echo $_SESSION['unidad_def'];
?>

  <div class="container">
    <div class="row">
      <?php  if ($vigilancia_ver == 0){?>
      
      <div class="col-xl-3 col-sm-3 col-md-3 col-ld-3 ">
        <div class="card text-white text-xl-center bg-info o-hidden h-80">
          <div class="card-body">
            <div class="card-body-icon big">
              <i class="fa fa-building-o fa-2x"></i>
            </div>
          </div>
          <div class="btn-group">
            <button type="button" class="btn btn-light btn-block dropdown-toggle" data-toggle="dropdown">
              EMPRESA
            </button>
            <!--<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">-->
            <ul class="dropdown-menu" role="menu">
              <li> <a href="empresa.php?tipo=0&unidad=<?php  echo $unidad_mina_sel ?>">
                  <h5>Datos generales</h5>
                </a> </li>
              <li class="divider"></li>
              <li> <a href="doc_personas.php">
                  <h5>Documentación de Personas</h5>
                </a> </li>
              <li> <a href="doc_vehiculos.php?unidad=<?php  echo $unidad_mina_sel; ?>">
                  <h5>Documentación de Vehículos</h5>
                </a> </li>
            </ul>
          </div>
        </div>
      </div>
     <?php }?>
     
      <div class="col-xl-3 col-sm-3 col-md-3 col-ld-3 ">
        <div class="card text-white text-xl-center bg-secondary o-hidden h-80">
          <div class="card-body">
            <div class="card-body-icon">
              <i class="fa fa-bars fa-2x"></i>
            </div>
          </div>
          <div class="btn-group">
            <button type="button" class="btn btn-light btn-block dropdown-toggle" data-toggle="dropdown">
              BITÁCORA
            </button>
            <!--<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">-->
            <ul class="dropdown-menu" role="menu">
              <li> <a href="app.php">
                  <h5>Crear Visita</h5>
                </a> </li>
              <li class="divider"></li>
              <li> <a href="dashboard.php?tipo=1&unidad=<?php  echo $unidad_mina_sel; ?>">
                  <h5>Visitas</h5>
                </a> </li>
              <li> <a href="dashboard.php?tipo=6&unidad=<?php  echo $unidad_mina_sel; ?>">
                  <h5>Cerradas</h5>
                </a></li>
              
            </ul>
          </div>
        </div>
      </div>

    <?php  if ($vigilancia_ver == 0){?>
    
      <div class="col-xl-3 col-sm-3 col-md-3 col-ld-3 ">
        <div class="card text-white text-xl-center bg-success o-hidden h-80">
          <div class="card-body">
            <div class="card-body-icon big">
              <i class="fa fa-play-circle-o fa-2x"></i>
            </div>
          </div>
          <div class="btn-group">
            <button type="button" class="btn btn-light btn-block dropdown-toggle" data-toggle="dropdown">
              CURSOS
            </button>
            <!--<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">-->
            <ul class="dropdown-menu" role="menu">
              <li> <a href="cursos.php?tipo=0&unidad=<?php  echo $unidad_mina_sel; ?>">
                  <h5>Visita General</h5>
                </a> </li>
            
              <li> <a href="calendario.php?motivo=0&unidad=<?php  echo $unidad_mina_sel; ?>">
                  <h5>Calendarios</h5>
                </a></li>
               
             <li> <a href="usuarios_inscritos.php?unidad_id=<?php  echo $unidad_mina_sel; ?>">
                  <h5>Reporte de Usuarios Inscritos </h5>
                </a></li>
            </ul>
          </div>
        </div>
      </div>
  <?php }?>
  
  <?php  if ($vigilancia_ver == 0){?>  
  
      <div class="col-xl-3 col-sm-3 col-md-3 col-ld-3 ">
        <div class="card text-white text-xl-center bg-warning o-hidden h-80">
          <div class="card-body">
            <div class="card-body-icon big">
              <i class="fa fa-medkit fa-2x"></i>
            </div>
          </div>
          <div class="btn-group">
            <button type="button" class="btn btn-light btn-block dropdown-toggle" data-toggle="dropdown">
              C O V I D 19
            </button>
            <!--<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">-->
            <ul class="dropdown-menu" role="menu">
              <li> <a href="#">
                  <h5>Información</h5>
                </a> </li>
            </ul>
          </div>

        </div>
      </div>
      <?php }?>

    </div>
  </div>
  <?php 
  date_default_timezone_set("America/Phoenix");

  //echo $u_id;
  
  $mysqli->set_charset("utf8");
  $check_id = $mysqli->query("SELECT
              (CASE WHEN (division = 'empleado' AND administrador = 0) THEN 1
                    WHEN (division = 'empleado' AND administrador = 1) THEN 99
                    WHEN  division = 'vigilante' THEN 99
                    WHEN (division = 'proveedor' AND administrador = 1) THEN 100
              ELSE 0 END) AS division 
        FROM arg_usuarios WHERE u_id = " . $u_id);
  $row_uid = $check_id->fetch_array(MYSQLI_ASSOC);
  $empleado = $row_uid['division'];
  
  /*(CASE WHEN division = 'empleado' THEN 1 
          WHEN division = 'vigilante' THEN 99 
    ELSE 0 END) AS division */
  
  $mes_actual = date("m");
  $mes_sel = $_GET['mes'] ?? '';
  $datos_meses = $mysqli->query("SELECT num_mes, mes FROM `meses` WHERE num_mes > " . $mes_actual) or die(mysqli_error($mysqli));

  $tipo = $_GET['tipo'];
  
  if (is_null($tipo)){$tipo = 1;};
  //Visitas Abiertas
  if ($tipo <= 5) {
    //Hoy
    if ($tipo == 1) {
      $hoy = date("Y-m-d");
      $fecha_ini = $hoy;
      $fechafinal = date("Y-m-d");
    }
    //Semana
    else {
      if ($tipo == 2 and $vig <> 99) {
        $hoy = date("Y-m-d");
        $fecha_ini = $hoy;
        $fechafinal = date("Y-m-d", strtotime($hoy . "+ 7 days"));
      }
      //Un mes
      else {
        $hoy = date("Y-m-d");
        $fecha_ini = $hoy;
        $fechafinal = date("Y-m-d", strtotime($hoy . "+ 1 month"));
      }
    }
    //Seleccionando un mes diferente al mes actual
    if ($tipo == 4 and $empleado <> 99) {
      $ano_actual = date("Y");

      if ($mes_sel <= 9) {
        $hoy = $ano_actual . '-0' . $mes_sel . '-01';
        $fecha_ini = $hoy;
      } else {
        $hoy = $ano_actual . '-' . $mes_sel . '-01';
        $fecha_ini = $hoy;
      }

      $dia_final = date(t, strtotime($hoy));
      if ($mes_sel <= 9) {
        $fechafinal = $ano_actual . '-0' . $mes_sel . '-' . $dia_final;
      } else {
        $fechafinal = $ano_actual . '-' . $mes_sel . '-' . $dia_final;
      }
    }
    //var_dump("CALL visor_visitas (" . $u_id . ",'" . $hoy . "','" . $fechafinal . "'," . $empleado . ", " . $unidad_mina_sel . ", 0" . ")");
    //En visitas abiertas muestra todos los estados
    //echo 'va'.$empleado;
    mysqli_multi_query($mysqli, "CALL visor_visitas (" . $u_id . ",'" . $hoy . "','" . $fechafinal . "'," . $empleado . ", " . $unidad_mina_sel . ", 0" . ")") or die(mysqli_error($mysqli));

  ?>
    <br />
    <br />
    <div class="container">
      <div class="col-md-12 col-lg-12">
        <div class="col-md-3 col-lg-3">
          <a href="dashboard.php?tipo=1&unidad=<?php  echo $unidad_mina_sel; ?>">Hoy</a>
        </div>
        <?php //if ($vig <> 99){?>
             <?php  if ($vigilancia_ver == 0){?>
            <div class="col-md-3 col-lg-3">
              <a href="dashboard.php?tipo=2&unidad=<?php  echo $unidad_mina_sel; ?>">Semana</a>
            </div>
            <div class="col-md-3 col-lg-3">
              <a href="dashboard.php?tipo=3&unidad=<?php  echo $unidad_mina_sel; ?>">Mes</a>
            </div>
            
            <div class="col-md-3 col-lg-3">
             <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">Selecciones mes</a>
            
            <div class="dropdown-menu">
                <?php 
                while ($row = $datos_meses->fetch_assoc()) {
                ?>
                  <a class="dropdown-item" href="dashboard.php?tipo=4&unidad=<?php  echo $unidad_mina_sel; ?>&mes=<?php  echo $row['num_mes']; ?>"><?php  echo $row['mes']; ?></a>
                <?php  } ?>
          </div>
          </div>
         
        <?php  } ?>
        
      </div>
    </div>
    <?php 
  } else   //Visitas Cerradas
  {
    //Estados de las visitas
    $datos_estados = $mysqli->query("SELECT 0 AS estado_id, 'Todos' AS estado
                                                    UNION ALL
                                                    SELECT estado_id, estado FROM `arg_estados` WHERE  estado_id < 7") or die(mysqli_error($mysqli));

    //Último mes de visitas cerradas mostrando todos los estados         
    if ($tipo == 6) {
      $ano_actual = date("Y");
      $mes_actual = date("m");
      $hoy = date("Y-m-d");

      $fecha_ini =  date("Y-m-d", strtotime($hoy . "- 30 days"));
      $fechafinal = date("Y-m-d", strtotime($hoy . "- 1 days"));
      //echo $fecha_ini.'fin';

      mysqli_multi_query($mysqli, "CALL visor_visitas (" . $u_id . ",'" . $fecha_ini . "','" . $fechafinal . "'," . $empleado . ", " . $unidad_mina_sel . ", 0" . ")") or die(mysqli_error($mysqli));
    }
    //Seleccionando un periodo de visitas y  un estado
    if ($tipo == 7 and $vig <> 99) {
      if (isset($_POST['visita'])) {
        $fecha_ini  = $_POST['fecha_inicial'];
        $fechafinal = $_POST['fecha_final'];
        $estado = $_POST['estado'];
        $hoy = $fecha_ini; // date("Y-m-d");

        mysqli_multi_query($mysqli, "CALL visor_visitas (" . $u_id . ",'" . $fecha_ini . "','" . $fechafinal . "'," . $empleado . ", " . $unidad_mina_sel . ", " . $estado . ")") or die(mysqli_error($mysqli));
      }
    }
    if ($tipo != 10) {
    ?>
      <br />
      <br />
      <form method="post" action="dashboard.php?tipo=7&unidad=<?php  echo $unidad_mina_sel; ?>" name="Visitaform" id="Visitaform">
        <fieldset>
          <div class="container">
            <div class="col-md-12 col-lg-12">
              <div class="col-md-2 col-lg-2">
                <label for="fecha_inicial"><b>Desde:</b></label><br />
                <?php  $fecha_minima_val = date('Y-m-j');
                $nuevafecha = strtotime('-1 day', strtotime($fecha_minima_val));
                $nuevafecha = date('Y-m-d', $nuevafecha);
                //echo $nuevafecha; //2020-12-31
                ?>
                <input type="date" name="fecha_inicial" class="form-control" id="fecha_inicial" max="<?php  echo $nuevafecha; ?>" />
              </div>
              <div class="col-md-2 col-lg-2">
                <label for="fecha_final"><b>Hasta:</b></label><br>
                <input type="date" name="fecha_final" class="form-control" id="fecha_final" max="<?php  echo $nuevafecha; ?>" />
              </div>

              <div class="col-md-2 col-lg-2">
              </div>

              <div class="col-md-3 col-lg-3">
                <br />
                <?php  if ($motivotop == "")
                  $motivot = "Estado Visita";

                echo ("<select name=\"estado\" id=\"estado\" class=\"form-control\" > ");
                //echo ("<option value=$nomtop>$motivot</option>");
                while ($row = $datos_estados->fetch_array(MYSQLI_ASSOC)) {
                  $actividad = ($row["estado"]);
                  $act_id = $row["estado_id"];
                  echo ("<option value=$act_id>$actividad</option>");
                }
                echo ("</select><br />");
                ?>
              </div>
              <br />
              <input type="submit" class="btn btn-primary" name="visita" id="visita" value="Ver" />

            </div>

          </div>
          </div>
        </fieldset>
      </form>
  <?php 
    }
  }
  ?>

  <br />
  <br />
 <div class="container">
    <div class="row">
      <br />
      <input type="submit" class="btn btn-warning" name="export" id="export" value="Exportar" onclick="expo(<?php  echo $u_id . ", '" . $fecha_ini . "', '" . $fechafinal . "'," . $empleado . "," . $unidad_mina_sel ?>);" readonly />
      <br />
    </div>
    <div class="row">
      <?php 
      if ($tipo != 10) {
        $html_en = "<table class='table table-striped' style='font-size:13px' id='encabezado'>
                      <thead>
                          <tr class='table-info'>   
                             <th scope='col'>EMPRESA</th>
                             <th scope='col'>PROVEEDOR</th>
                             <th scope='col'>MINA</th>
                             <th scope='col'>FOLIO</th>
                             <th scope='col'>ATIENDE</th>                   
                             <th scope='col'>PLACAS VEHIC</th>              
                             <th scope='col'>FECHA INICIO</th>
                             <th scope='col'>FECHA FINAL</th>
                             <th scope='col'>DIA</th>
                             <th scope='col'>ESTADO</th>";
        if ($tipo == 6 or $tipo == 7) {
          $html_en .= "<th scope='col'>INICIADO</th>
                             <th scope='col'>FINALIZADO</th>
                             <th scope='col'>DURACION</th>";
        }
        $html_en .= "</tr>
                       </thead>
                       <tbody>";
        if ($result = mysqli_store_result($mysqli)) {
          while ($row = mysqli_fetch_assoc($result)) {
            $html_en .= "<tr>
                                         <td> " . $row["empresa"] . "</td>
                                        <td> <form action = 'doc_personas.php' method = 'POST'> 
                                              <input type = 'hidden' name='u_id' value=".$row['u_id'].">
                                                <button  type='submit' style='background: none; border: none; color: blue; text-decoration: underline; cursor: pointer;'>" . $row["nombre"] . "</button>
                                              </form> </td>
                                         <td> " . $row["mina"] . "</td>
                                        <td> <form action='app.php' method='POST' style='display: inline;'>
                <input type='hidden' name='trn_id' value= " . $row["trn_id"] . ">

                <button type='submit' style='background: none; border: none; color: blue; text-decoration: underline; cursor: pointer;'>
                    " . $row["folio"] . "
                </button>
            </form></td>
                                        <td> " . $row["atiende"] . "</td>
                                         <td> <form action='doc_vehiculos.php' method='POST' style='display: inline;'>
                <input type='hidden' name='placas' value= " . $row["placas"] . ">
                <button type='submit' style='background: none; border: none; color: blue; text-decoration: underline; cursor: pointer;'>
                    " . $row["placas"] . "
                </button>
            </form></td>
                                         <td> " . $row["fecha_inicio"] . "</td>
                                         <td> " . $row["fecha_final"] . "</td>
                                         <td> " . $row["hoy"] . "</td>";
            if ($tipo == 6 or $tipo == 7 and $vig <> 99) {
              $html_en .= "<td>" . $row["estado"] . "</td>
                                          <td> " . $row["iniciado"] . "</td>  
                                          <td> " . $row["finalizado"] . "</td>  
                                          <td> " . $row["duracion_visita"] . "</td>";
            } else {
              $html_en .= "<td> 
                                             <div class='btn-group'>
                                             <button type='button' class='btn btn-info btn-block dropdown-toggle' data-toggle='dropdown'>
                                                 " . $row["estado"] . "
                                             </button>
                                             <ul class='dropdown-menu' role='menu'>
                                        
                                                 <li> <a href='cambiar_estado.php?trn_id=" . $row['trn_id'] . "&estado_id=" . $row['estado_1'] . "'><h5>" . $row['estado_1_n'] . "</h5></a> </li>
                                                 <li> <a href='cambiar_estado.php?trn_id=" . $row['trn_id'] . "&estado_id=" . $row['estado_2'] . "'><h5>" . $row['estado_2_n'] . "</h5></a> </li>
                                             </ul>
                                             </div> 
                                         </td>";
            }
          
            $html_en .= "</tr>";
          }
          mysqli_free_result($result);
        }
      }

      $html_en .= "</tbody></table>";
      //$mysqli->set_charset("utf8");
      echo utf8_decode("$html_en");
      ?>
    </div>
  </div>
<?php 
}
?>