<style type="text/css">
	.izq{
		background-color:;
	}
	.derecha{
		background-color:;
	}

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
$_SESSION['LoggedIn'] = 1; 

if(($_SESSION['LoggedIn']) <> ''){
   
    $u_id = $_SESSION['u_id'];
    $tipo_motivo = $_GET['momtivo'];
    $mes = 11;
    
   // mysqli_multi_query ($mysqli, "CALL visor_calendario (".$mes.")") OR DIE (mysqli_error($mysqli));
    $fecha = '20201020';
?>
   
 <div class="container">
    <div class="row">
    <h3><?echo 'Su motivo de visita: '.$tipo_motivo.' Requiere Inducción de Seguridad Completa. Indique su fecha de registro a la inducción:'?></h3>
           <div class="col-xl-3 col-sm-3 col-md-3 col-ld-3 ">
          <div class="card text-white text-xl-center bg-success o-hidden h-80">
            <div class="card-body">
              <div class="card-body-icon big">
                <i class="fa fa-play-circle-o fa-3x"></i>
              </div>
            </div>
                    <div class="btn-group">
                    <button type="button" class="btn btn-light btn-block dropdown-toggle" data-toggle="dropdown">
                        CURSOS
                     </button>
                        <!--<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">-->
                        <ul class="dropdown-menu" role="menu">
                            <li> <a href="app.php"><h5>Fecha: <?echo $fecha;?> </h5></a> </li>  
                        </ul>
                   </div>
                
              </div>
          </div>
       
      </div>
    </div>  
    <?           
 
        }
    ?>
    
       
<!--<script type="text/javascript" src="js/popper/src/popper.js"></script>-->
<!--<script type="text/javascript" src="js/vehiculos.js"></script>-->
         
          

