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
</style>

<script>
  function inscribir(u_id, año, mes, dia, existe) {
    $('#iniciando_modal').modal('show'); 
    var u_id = u_id;
    var fecha = String(año) + "-" + String(mes) + "-" + String(dia);
    var mina_sel = document.getElementById('mina_seleccionada').value;
    var existe = existe;

    $.ajax({
        url: 'datos_inscr.php',
        type: 'POST',
        dataType: 'html',
        data: {
          u_id: u_id,
          fecha: fecha,
          existe: existe,
          mina_sel:mina_sel
        },
      })
      .done(function(respuesta) {
        alert(respuesta);
      })
  }

  function refrescar() {
    var mina = document.getElementById('mina_seleccionada').value;
    var exportar = '<?php echo "\ calendario.php?motivo=" ?>' + 0 + '&unidad=' + mina;
    window.location.href = exportar;
  }

  function llama_datos(cal_id) {
    cal_id = cal_id;
    $.ajax({
        url: 'inscritos.php',
        type: 'POST',
        dataType: 'html',
        data: {
          cal_id: cal_id
        },
      })
      .done(function(respuesta) {
        jQuery.noConflict();
        $('#inscritos').modal('show');
        $("#datos_inscritos").html(respuesta);
      })
  }
</script>


<?php
//$_SESSION['LoggedIn'] = 1; 

if (($_SESSION['LoggedIn']) <> '') {

date_default_timezone_set("America/Phoenix");
  $u_id = $_SESSION['u_id'];
  $tipo_motivo = $_GET['motivo'];
  $unidad_mina_sel = $_GET['unidad'];
  $mes = date("m");
  $año = date("Y");
  $motivo_vis = $mysqli->query("SELECT nombre FROM arg_actividad WHERE act_id = " . $tipo_motivo) or die(mysqli_error($mysqli));
  $mot_visita = $motivo_vis->fetch_assoc();
  $motivo_visita = $mot_visita['nombre'] ?? '';

  $fechas = $mysqli->query("SELECT cal_id as cal_id, fecha as fecha FROM arg_calendario_cursos") or die(mysqli_error($mysqli));
  while ($row = $fechas->fetch_array(MYSQLI_ASSOC)) {
    $cal_id = ($row["cal_id"]);
    $fecha  = $row["fecha"];
    $cursos = array();
    $cursos[$cal_id] = $fecha;
  }
  function getAllDaysInAMonth($year, $month, $day = 'Monday', $daysError = 0)
  {
    $dateString = 'first ' . $day . ' of ' . $year . '-' . $month;

    if (!strtotime($dateString)) {
      throw new \Exception('"' . $dateString . '" is not a valid strtotime');
    }

    $startDay = new \DateTime($dateString);

    if ($startDay->format('j') > $daysError) {
      $startDay->modify('- 7 days');
    }

    $days = array();

    while ($startDay->format('Y-m') <= $year . '-' . str_pad($month, 2, 0, STR_PAD_LEFT)) {
      $days[] = clone ($startDay);
      $startDay->modify('+ 7 days');
    }



    return $days;
  }
  
   function getDaysInAPeriod($year, $month, $day = 'Thursday', $daysError = 0)
  {
    $dateString = 'first ' . $day . ' of ' . $year . '-' . $month;

    if (!strtotime($dateString)) {
      throw new \Exception('"' . $dateString . '" is not a valid strtotime');
    }

    $startDay = new \DateTime($dateString);

    if ($startDay->format('j') > $daysError) {
      $startDay->modify('- 14 days');
    }

    $days = array();

    while ($startDay->format('Y-m') <= $year . '-' . str_pad($month, 2, 0, STR_PAD_LEFT)) {
      $days[] = clone ($startDay);
      $startDay->modify('+ 14 days');
    }
    return $days;
  }

?>

  <div class="container">

    <br />
    <br />
    <h4><?php  if ($tipo_motivo > 0) echo 'Su motivo de visita: ' . $motivo_visita . ' requiere Inducción de Seguridad Completa.' ?></h4>

    <h4><b><?php  echo 'Por favor seleccione la unidad de mina y su fecha de registro para la inducción avanzada:' ?></b></h4>
    <br />
    <select name='mina_seleccionada' id='mina_seleccionada' onchange="refrescar()">
     
      <?php  $result = $mysqli->query("SELECT 0 AS unidad_id, 'Seleccione Mina' AS                              Nombre UNION ALL 
                                   SELECT unidad_id, Nombre FROM arg_empr_unidades
                                   WHERE unidad_id NOT IN(4,5)") or die(mysqli_error($mysqli));
      while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        $nombre = ($row["Nombre"]);
        $nomenclatura = $row["unidad_id"];
        if ($nomenclatura == $unidad_mina_sel) {
          $ls = "selected";
        } else {
          $ls = "";
        }
        echo ("<option value=$nomenclatura $ls>$nombre</option>");
      }
      ?>
    </select>
    <br />
    <br />
    <div class="row">
      <?php 
      $i = 0;
      $j = 0;
      $cita = "";
      if ($unidad_mina_sel == 1) {
        $cita = 'Monday';
      } else if ($unidad_mina_sel == 2) {
        $cita = 'Thursday';
      } else {
        $cita = 'Thursday';
      }
      
      if ($unidad_mina_sel == 2){
        $jueves_sa = getDaysInAPeriod($año, $mes, $cita);
        $jueves_sa = getDaysInAPeriod($año, $mes + 1, $cita);
        $merge = array_unique(array_merge($jueves_sa, $jueves_sa), SORT_REGULAR);
        
      }else{
        $lunes = getAllDaysInAMonth($año, $mes, $cita);
      $lunes2 = getAllDaysInAMonth($año, $mes + 1, $cita);
      $merge = array_unique(array_merge($lunes, $lunes2), SORT_REGULAR);
        
      }
      
      
      $ins = "FALSE";
      
    
      foreach ($merge as $day) {
        $dia = $day->format('Y-m-d');
        //echo $dia;
        if($unidad_mina_sel == 3){
          $total = 15;
        }else{
          $total = 20;
        }
        $exist = $mysqli->query("SELECT cal_id FROM arg_calendario_cursos WHERE unidad_id = ".$unidad_mina_sel." AND fecha = '" . $dia . "'");
        $ver = "FALSE";
        $existe = 0;
        $anterior = $dia;
        $estilo = "";
        $css = "";
        $inval = "pointer-events: auto;";
        if (mysqli_num_rows($exist) > 0) {
          $existe = 1;
          $cal = $exist->fetch_array(MYSQLI_ASSOC);
          $cal_id = $cal['cal_id'];
          $query = $mysqli->query("SELECT max_part FROM arg_calendario_cursos WHERE cal_id = $cal_id");
          $que = $query->fetch_array(MYSQLI_ASSOC);
          $total = $que['max_part'];
          $usins = $mysqli->query("SELECT u_id FROM arg_calendario_usuarios WHERE cal_id = $cal_id");
          while ($row = $usins->fetch_array(MYSQLI_ASSOC)) {
            if ($row['u_id'] == $u_id) {
              $ins = "TRUE";
            }
          }
          $ver = "TRUE";
        }
        $d = date("d", strtotime($dia));
        $d = str_pad($d, 2, 0, STR_PAD_LEFT);
        $m = date("m", strtotime($dia));
        $m = str_pad($m, 2, 0, STR_PAD_LEFT);
        $y = date("Y", strtotime($dia));
        if (strtotime($dia) < strtotime('now')) {
          continue;
        }
        if ($total != 0) {
          $string = "Lugares: $total";
          $style = "";
        } else {
          $style = "style='background-color:brown'";
          $string = "Curso no disponible";
        }
        //$inscrito = $row['inscritos'];
        $texto = "Solicitar Inducción";
        $css = "";

        if ($ins == "TRUE") {
          $texto = "Usted ya está inscrito a un curso";
          $css = "pointer-events: none; cursor: default; opacity: 0.7;";
        }
        if ($ver == "FALSE") {
          $inval = "pointer-events: none; cursor: default; opacity: 0.7;";
        }
        if ($i == 4 || $i == 8) {
      ?>
          <br />
          <br />
          <br />
          <div class="row">
          <?php 
        }
          ?>

          <div class="col-xl-2 col-sm-2 col-md-2 col-ld-2 ">
            <div class="card text-white text-center bg-info o-hidden h-100">
              <div class="card-body" <?php  echo $estilo ?>>
                <div class="card-body-icon big">
                  <i class="fa fa-play-circle-o fa-2x"></i>
                  <br />
                  <?php  echo $string ?>
                </div>
              </div>
              <div class="btn-group">
                <button type="button" class="btn btn-info btn-block dropdown-toggle" data-toggle="dropdown">
                  <?php  echo date("D Y-m-d", strtotime($anterior)); ?>
                </button>
                <!--<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">-->
                <ul class="dropdown-menu" role="menu">
                  <li> <a href='' id='aLink' style='<?php  echo $css ?>' onclick="inscribir(<?php  echo $u_id ?>,'<?php  echo $y ?>','<?php  echo $m ?>',<?php  echo $d ?>,<?php  echo $existe ?>  );">
                      <h5><?php  echo $texto ?></h5>
                    </a> </li>
                  <li> <a href='' id='aLink' style="<?php  echo $inval ?>" onclick="llama_datos(<?php  echo $cal_id ?>); return false;">
                      <h5>Ver inscritos</h5>
                    </a> </li>
                </ul>
              </div>
            </div>
          </div>
          <?php 
          $i = $i + 1;
          if ($i == 4 || $i == 8) {
          ?>
          </div> <?php 
                }
                $j = $j + 1;
              } //Fin if result
                  ?>
    </div>
    
    <div class="modal fade" data-backdrop="static" data-keyboard="false" id="inscritos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable" style="max-width:850px!important;" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="humedad">Inscritos</h5>
          </div>

          <div class="modal-body" style="font-size:5px;" id="datos_inscritos">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>
        
    <!-- Modal Iniciando PROGRESS-->
    <div class="modal fade" data-backdrop="static" data-keyboard="false" id="iniciando_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-center" id="exampleModalLabel">Incribiendo al curso...</h5>
            <input type="hidden"  name="mina_abs_esp" id="mina_abs_esp" size=20 style="width:125px; color:#996633"  disabled /> 
          </div>
          <div class="modal-body">
            <div class="text-center">
                <h4>Se está realizando la inscripción por favor espere...</h4>
                <input type="hidden" id="mina_prep" size=20 style="width:125px; color:#996633" value="<?php echo $unidad_id;?>" disabled />        
                <img  src="images\upload.gif">          
            </div>
          </div>
        </div>
      </div>
    </div>

    
  </div>
<?php
}

?>
<!--<script type="text/javascript" src="js/popper/src/popper.js"></script>-->
<!--<script type="text/javascript" src="js/vehiculos.js"></script>-->