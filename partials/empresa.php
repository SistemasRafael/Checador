
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
?>
   
 <div class="container">
    <div class="row">
        <div class="col-xl-3 col-sm-3 col-md-3 col-ld-3 ">
          <div class="card text-white text-xl-center bg-info o-hidden h-80">
            <div class="card-body">
              <div class="card-body-icon big">
                <i class="fa fa-building-o fa-3x"></i>
              </div>
            </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-light btn-block dropdown-toggle" data-toggle="dropdown">
                        EMPRESA
                     </button>
                        <!--<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">-->
                        <ul class="dropdown-menu" role="menu">
                            <li> <a href="empresa.php"><h5>Personas</h5></a> </li>                                              
                            <li> <a  href="#"><h5>Herramientas</h5></a> </li>
                            <li> <a  href="#"><h5>Vehículos</h5></a></li>
                        </ul>
                   </div>
              </div>
          </div>  
          
        <div class="col-xl-3 col-sm-3 col-md-3 col-ld-3 ">
          <div class="card text-white text-xl-center bg-secondary o-hidden h-80">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-bars fa-3x"></i>
              </div>
            </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-light btn-block dropdown-toggle" data-toggle="dropdown">
                        BITÁCORA
                     </button>
                        <!--<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">-->
                        <ul class="dropdown-menu" role="menu">
                            <li> <a href="app.php"><h5>Crear Visita</h5></a> </li>
                            <li class="divider"></li>                   
                            <li> <a  href="#"><h5>Visitas</h5></a> </li>
                            <li> <a  href="#"><h5>Cerradas</h5></a></li>
                        </ul>
                   </div>
              </div>
          </div>
          
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
                           <li> <a href="cursos.php?tipo=0"><h5>Visita General</h5></a> </li>                                              
                            <li> <a  href="cursos.php?tipo=1"><h5>Trabajos de altos riesgos</h5></a> </li>
                            <li> <a  href="calendario.php?motivo=0"><h5>Calendarios</h5></a></li>
                            <li> <a  href="#"><h5>Mis cursos</h5></a></li>
                        </ul>
                   </div>
                
              </div>
          </div>
       
      </div>
    </div>  
    <?           
       $u_id = $_SESSION['u_id'];     
       
        $check_id = $mysqli->query("SELECT division FROM users WHERE u_id = ".$_SESSION['u_id']);
    	$row_uid = $check->fetch_array(MYSQLI_ASSOC);
    	$u_id_tipo = $rowdep['division'];
        
        echo $u_id_tipo;
            
       $tipo = $_GET['tipo'];
       if ($tipo == 1){  //Lista de empresas
            
       }
        else{
            if ($tipo == 2){//Usuarios de la empresa
                $hoy = date("Y-m-d");
                $fechafinal = date("Y-m-d",strtotime($hoy."+ 7 days")); 
            }
            else{  // Vehículos de la empresa
                $hoy = date("Y-m-d");
                $fechafinal = date("Y-m-d",strtotime($hoy."+ 1 month")); 
            }
        }     
        mysqli_multi_query ($mysqli, "CALL visor_visitas (".$u_id.",'".$hoy."','".$fechafinal."')") OR DIE (mysqli_error($mysqli));
           
       ?>
       
       <br/>
       <br/>
       <br/>
       
        <div class="container" >
            <div class="col-md-9 col-lg-9">
                <div class="col-md-3 col-lg-3">
                    <a href="dashboard.php?tipo=1">Hoy</a>
                 </div>
                 
                <div class="col-md-3 col-lg-3">
                    <a href="dashboard.php?tipo=2">Semana</a>
                </div>
                <div class="col-md-3 col-lg-3">
                    <a href="dashboard.php?tipo=3">Mes</a>
                </div>
            </div>
        </div>
       
             <div class="container">                                                
                 <? 
                 $html_en = "<table class='table table-bordered' id='encabezado'>
                             <thead>
                                 <tr class='table-info'>   
                                    <th scope='col'>Empresa</th>
                                    <th scope='col'>Usuario</th>
                                    <th scope='col'>Unidad de Mina</th>
                                    <th scope='col'>Desde</th>
                                    <th scope='col'>Hasta</th>
                                    <th scope='col'>Comentario</th>
                                  </tr>
                              </thead>
                              <tbody>";
                               if ($result = mysqli_store_result($mysqli)) {                
                                      while ($row = mysqli_fetch_assoc($result)) {
                                             $html_en.="<tr>
                                                <td> ".$row["empresa"]."</td>
                                                <td> ".$row["nombre"]."</td>
                                                <td> ".$row["mina"]."</td>
                                                <td> ".$row["fecha_inicio"]."</td>
                                                <td> ".$row["fecha_final"]."</td>
                                                <td> ".$row["comentario"]."</td>
                                             </tr>";
                                      }
                                      mysqli_free_result($result);
                                }
                $html_en.="</tbody></table>";
                    
                 
                  /*$html_v = "<table class='table table-bordered' id='visitantes'>
                                <thead>
                                <tr class='bg-info'>            
                                    <th scope='col'>Visitantes</th>
                                    <th scope='col'>INE</th>
                                </tr>
                            </thead>
                            <tbody>";                    
                            	while ($fila_v = $visitantes->fetch_assoc()) {
                            		$html_v.="<tr>
                                                <td>".$fila_v['visitante']."</td>
                                                <td>".$fila_v['ine']."</td>
                                             </tr>";
                            	}
                  $html_v.="</tbody></table>";*/
                 
                      echo ("$html_en");
                     
                ?>
             </div>
            <?
        }
    ?>
    
       
<!--<script type="text/javascript" src="js/popper/src/popper.js"></script>-->
<!--<script type="text/javascript" src="js/vehiculos.js"></script>-->
         
          

