<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">   
<script>
  function redireccion($trn_id) {
    var trn_id = $trn_id;
    var print_d = '<?php echo "\app.php?trn_id="?>'+trn_id;                
    window.location.href = print_d;
  }
</script>
<style type="text/css">
  .btnSubmit
  {
      width: 50%;
      border-radius: 1rem;
      padding: 1.5%;
      border: none;
      cursor: pointer;
  }

  .circulos{
    padding-top: 5em;
  }

  img{
    max-width: 100%;
  }
</style>
<?php 
include "config/config.php";
$_SESSION['LoggedIn'] = 1; 

if(($_SESSION['LoggedIn']) <> '') { ?>
  <div class="row">
    <div class="col-xl-3 col-sm-6 mb-3">
      <div class="card text-white bg-primary o-hidden h-100">
        <div class="card-body">
          <div class="card-body-icon">
            <i class="fa fa-fw fa-comments"></i>
          </div>
          <div class="mr-5"><?php echo 'EMPRESAS'; ?> Vistors</div>
        </div>
        <a class="card-footer text-white clearfix small z-1" href="#">
          <span class="float-left">View Details</span>
          <span class="float-right">
            <i class="fa fa-angle-right"></i>
          </span>
        </a>
      </div>
    </div> 
    <?php
      $u_id = 55;
      mysqli_multi_query ($mysqli, "CALL visor_visitas (2)") OR DIE (mysqli_error($mysqli));
      if ($result = mysqli_store_result($mysqli)) {
        while ($row = mysqli_fetch_assoc($result)) {
          echo "row = ".$row["nombre"]."<br />";
        }
        mysqli_free_result($result);
      }    
    ?>
    <div class="container">                          
      <?php          
        $html_en = "<table class='table table-bordered' id='encabezado'>
                    <thead>
                        <tr class='table-secondary'>   
                          <th scope='col'>Unidad de Mina: ".$entrada['unidad']."</th>
                          <th scope='col'>Desde: ".$entrada['fecha_inicio']."</th>
                          <th scope='col'>Hasta: ".$entrada['fecha_final']."</th>
                        </tr>;
                    </thead></tbody></table>";
        echo ("$html_en");
      ?>      
      <form method="post" action="print_doc.php?trn_id=<?php echo $trn_id_rel;?>" name="Printform" id="Printform">  
        <fieldset>  
          <input type="submit" class="btn btn-success" name="print" id="print" value="Ver PDF" />                
        </fieldset>  
      </form> 
      <form method="post" action="cancelar.php" name="newform" id="newform">  
        <fieldset>  
          <input type="submit" class="btn btn-danger" name="cancelar_visita" id="cancelar_visita" value="Cancelar" />                
        </fieldset>  
      </form> 
    </div>
  </div>
<?php } ?>
<script>
    redireccion($trn_id);
</script>
<script type="text/javascript" src="js/jquery.min.js"></script>