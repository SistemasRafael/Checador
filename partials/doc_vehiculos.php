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
<script>
            
    function GuardarActivoVeh(veh_id) {
        var veh_id = veh_id;
        const frm = new FormData();
        frm.append("veh_id",veh_id);
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4) {
                var msg = xhttp.responseText;
                if (msg == 'Estado actualizado correctamente') {
                    alert(msg);
                    console.log
                    redireccion();
                } else {
                    alert(msg);
                }
            }
        };
    
        xhttp.open("POST", "cambiaractivo_veh.php", true);
        xhttp.send(frm);
        
    }   
    
    function redireccion()
            {
                 var print_d = '<?php echo "\doc_vehiculos.php"?>';                
                 window.location.href = print_d;
            }
    
            
    function ActualizaVeh(idsele)
                {  
                    var placas = idsele;
                    var eliminar_ruta = '<?php echo "\doc_vehiculos.php?placas="?>'+placas;                
                    window.location.href = eliminar_ruta;
               	}
                
    //Agregar veh�culos
     function GuardarVeh()
        {
            var placa  = document.getElementById("placa").value;                         
            var marca  = document.getElementById("marca").value; 
            var modelo = document.getElementById("modelo").value; 
            var color  = document.getElementById("color").value; 
            var poliza = document.getElementById("poliza").value;
            var expira_veh = document.getElementById("expira_veh").value;
            var path_pol = document.getElementById("file").file.name;

            $.ajax({
            		url: 'insertar_veh.php' ,
            		type: 'POST' ,
            		dataType: 'html',
            		data: {placa: placa, marca:marca, modelo:modelo, color:color, poliza:poliza, expira_veh:expira_veh},
            	})
            .done(function(respuesta){
                    //html(respuesta);
            		//$("#vehic").html(respuesta);
                    //var veh_id = document.getElementById("placas").value;
                    if(respuesta == 'Error: Debe capturar toda la informaci�n.' || respuesta == 'Error: Esa placa se encuentra duplicada. Reintente por favor')
                    {
                        alert(respuesta);
                    }
                    else{
                        alert('Se agrego el veh�culo correctamente');
                        redireccion();
                    }
                    
              })                 
      }
</script>
  
   <script type="text/javascript">
  
    $("body").on("click","#vehic a",function(event){
                                        event.preventDefault();
                                       
                                        idsele = $(this).attr("href");
                                        placas = $(this).parent().parent().children("td:eq(0)").text();
                                        marca  = $(this).parent().parent().children("td:eq(1)").text();
                                        modelo = $(this).parent().parent().children("td:eq(2)").text();
                                        poliza = $(this).parent().parent().children("td:eq(4)").text();
                                        detalle = marca+modelo;
                                        //Cargamos los datos
                                       $("#placas").val(placas);
                                       $("#idsele").val(idsele);
                                       $("#detalle").val(detalle);
                                       $("#poliza").val(poliza);
                                       
                                       ActualizaVeh(idsele);
                                      });
        
        </script>


<!-- Modal Veh�culos  -->

 <div class="modal fade" id="ModalVeh" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title" id="ModalVeh">AGREGAR VEHICULO. </h4>                
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>               
              </div>
                <div class="modal-header">  
                  <h6 class="modal-subtitle" style="color:#996633"  id="exampleModal">IMPORTANTE: Debe importar la de poliza de seguro vigente</h6>
                </div>
              <div class="modal-body">
              
          <form name='importa' method='post' action='doc_vehiculos.php' enctype='multipart/form-data' >
                 <table class='table text-black' id='datos_importar'>
                                <thead class='table-secondary' align='center'>
                                <tr>
                                <label for="placa" class="col-form-label">Placa:</label>
                    <input name="placa" id="placa" size=40 style="width:470px; color:#996633"  value="" enabled />
                    <label for="marca" class="col-form-label">Marca:</label>
                    <input name="marca" id="marca" size=40 style="width:470px; color:#996633"  value="" enabled />
                    <label for="modelo" class="col-form-label">Modelo:</label>
                    <input name="modelo" id="modelo" size=40 style="width:470px; color:#996633"  value="" enabled />
                    <label for="color" class="col-form-label">Color:</label>
                    <input name="color" id="color" size=40 style="width:470px; color:#996633"  value="" enabled />
                    <?php $etiqueta_pol = 'P�liza Seguro:';?>
                    <label for="poliza" class="col-form-label"><?php echo $etiqueta_pol;?></label>
                    <input name="poliza" id="poliza" size=40 style="width:470px; color:#996633"  value="" enabled />
                    <label for="expira_veh"  class="col-form-label">Fecha Expira:</label>
                    <input type="date" name="expira_veh" id="expira_veh" size=40 style="width:470px; color:#996633"  value="" enabled />
                    <br />  
                    <?php $etiqueta_tc = 'Tarjeta de Circulaci�n:';?>
                    <label for="tc" class="col-form-label"><?php echo $etiqueta_tc;?></label>
                    <input name="tc" id="tc" size=40 style="width:470px; color:#996633"  value="" enabled />
                    <label for="expira_tc"  class="col-form-label">Fecha Expira TC :</label>
                    <input type="date" name="expira_tc" id="expira_tc" size=40 style="width:470px; color:#996633"  value="" enabled />
                    <br/>
                                    <th>IMPORTAR POLIZA</th>
                                    <th>  </th>
                                    <tr class='table-primary' align='left'>
                                        <th colspan='7'>                                        
                                        
                                                <input type='file' name='pol_vehi' id='pol_vehi' />
                                                </br>                             
                                    </tr>
                                    <th>TARJETA DE CIRCULACION</th>
                                     <th>  </th>
                                    <tr class='table-primary' align='left'>
                                        <th colspan='8'>                                        
                                        
                                                <input type='file' name='tc_vehi' id='tc_vehi' />
                                                </br>                              
                                               <button type='input' class='btn btn-success' name='subir_poliza' id='subir_poliza' >
                                                <i class='fa-upload fa bootstrap'> Guardar </i>
                                              </button>
                                            </form>    
                                     </th>
                                     </tr>    
                </tbody></thead></table>
    
              <?php 
               if(isset($_POST['subir_poliza'])){
                   $placa  = $_POST['placa'];
                   $marca  = $_POST['marca'];
                   $modelo = $_POST['modelo'];
                   $color  = $_POST['color'];
                   $poliza = $_POST['poliza'];
                   $expira = $_POST['expira_veh'];
                   $org_id = $_SESSION['org_id'];
                   $tc = $_POST['tc'];
                   $tc_expira = $_POST['expira_tc'];
                  
                  
                    $id_max_ve = $mysqli->query("SELECT max(veh_id) FROM arg_vehiculos");
                    $id_maximo = $id_max_ve->fetch_array(MYSQLI_ASSOC);
                	$id_max_veh = $id_maximo['max(veh_id)'];
                    $id_max_veh = $id_max_veh+1;
                    
                    
                    $archivo = $_FILES['pol_vehi']['name'];
                    $dest  = 'upload/vehiculos/'; 
                    $archivo = $id_max_veh.$archivo;
                    $desti = rtrim($dest).$archivo;  
                    
                    
                    copy($_FILES['pol_vehi']['tmp_name'],$desti);
                    
                    $archivo_tc = $_FILES['tc_vehi']['name'];
                    $dest_tc  = 'upload/vehiculos_tc/'; 
                    $archivo_tc = $id_max_veh.$archivo_tc;
                    $desti_tc = rtrim($dest_tc).$archivo_tc;  
                    
                    copy($_FILES['tc_vehi']['tmp_name'],$desti_tc);
                    
                $query = "INSERT INTO arg_vehiculos (veh_id, placas, marca, modelo, color, poliza, org_id, path, fecha_expira, tarjeta_circulacion, tc_fecha_expira, tc_path ) ".
                     "VALUES ($id_max_veh, '$placa', '$marca', '$modelo', '$color', '$poliza', $org_id, '$desti', '$expira', '$tc', '$tc_expira', '$desti_tc')";
                                
                $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));
           
                ///echo "<script> redireccion() </script>";
                    
                    
               }
              
                  ?> 
                    </div>
              </div>
              <div class="modal-footer">
                 
                 <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
              </div>
            </div>
          </div>
   </div>  
   
         
  
<?php 
include "config/Env.php";
Env::load(dirname(__DIR__) . '/.env');

if(($_SESSION['LoggedIn']) <> ''){
   
    $u_id = $_SESSION['u_id'];    
    $org_id = $_SESSION['org_id'];                 
    $placas = $_GET['placas'] ?? '';
    
    $empleado_vis = $mysqli->query("SELECT division
                                    FROM 
                                        arg_usuarios
                                    WHERE arg_usuarios.u_id = ".$u_id) or die(mysqli_error($mysqli));
    $division_emplea = $empleado_vis ->fetch_array(MYSQLI_ASSOC);
    $division_empleado = $division_emplea['division']; 
    //echo $division_empleado;
    if($division_empleado == 'empleado'){
        $editar_veh = 'false';
        $_SESSION['org_id'] = 0;
        $org_id = $_SESSION['org_id'] ;
    }
    else{
        $editar_veh = 'true';
    }
    //echo $_SESSION['org_id'];  
    if ($placas == ''){
        mysqli_multi_query ($mysqli, "CALL visor_vehiculos (".$org_id.")") OR DIE (mysqli_error($mysqli));  
    }
    else{
        $veh_edit = $mysqli->query("SELECT arg_vehiculos.poliza
           ,arg_vehiculos.fecha_expira
           ,REPLACE(arg_vehiculos.path, ' ', '%20') AS path
           ,arg_vehiculos.tarjeta_circulacion 
           ,arg_vehiculos.tc_fecha_expira
           ,REPLACE(arg_vehiculos.tc_path, ' ', '%20') AS tc_path
                                        FROM arg_vehiculos
                                        WHERE arg_vehiculos.placas = '".$placas."' ORDER BY arg_vehiculos.marca") or die(mysqli_error($mysqli));
            $veh_editar = $veh_edit ->fetch_array(MYSQLI_ASSOC);
            $poliza_act = $veh_editar['poliza']; 
            $poliza_act_fec = $veh_editar['fecha_expira']; 
            $poliza_act_path = $veh_editar['path']; 
            $tc_act = $veh_editar['tarjeta_circulacion']; 
            $tc_expira_act = $veh_editar['tc_fecha_expira']; 
            $tc_path_act = $veh_editar['tc_path']; 
            //echo $poliza_act_path;
    }
    
?>

<div class="modal fade" id="ModalVer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">POLIZA VEHICULO</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                     <div>
                       <iframe src="<?php echo Env::get('APP_PATH').$poliza_act_path;?>" width="480" height="600" ></iframe>
                     </div> 
              </div>      
              <div class="modal-footer">        
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>     
              </div>  
            </div>
          </div>
   </div>
   

  <div class="modal fade" id="ModalVertc" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">TARJETA CIRCULACION VEHICULAR</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                     <div>
                       <iframe src="<?php echo Env::get('APP_PATH').$tc_path_act;?>" width="480" height="600" ></iframe>
                     </div> 
              </div>      
              <div class="modal-footer">        
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>     
              </div>  
            </div>
          </div>
   </div>
   
    <?php if ($placas == ''){?>
                  <div class="container">                                                
                 <?php 
                 if ($editar_veh == 'true'){ ?>       
                               <td height="26" valign="medium">                                  
                                    <a href='' name="idsele" class="btn btn-primary" data-toggle="modal" data-target="#ModalVeh"  class="sepV_a" title="Nuevo veh�culo">+ Agregar VEHICULO</a>
                               </td>
                          <?php }
        
                 $html_per = "<table class='table table-bordered' id='vehic'>
                             <thead>
                                 <tr class='table-info'>   
                                    <th scope='col'>Empresa</th>
                                    <th scope='col'>Marca</th>
                                    <th scope='col'>Modelo</th>
                                    <th scope='col'>Color</th>
                                    <th scope='col'>Placas</th>
                                    <th scope='col'>P�liza</th>
                                    <th scope='col'>Expira</th>
                                    <th scope='col'>Acciones</th>
                                    <th scope='col'>Estado</th>
                                  </tr>
                              </thead>
                              <tbody>";
                               if ($result_per = mysqli_store_result($mysqli)) {                
                                      while ($row = mysqli_fetch_assoc($result_per)) {
                                            $texto_veh = "Desactivar";
                                              if ($row['activo'] == 0){
                                                $texto_veh = "Activar";
                                              }
                                             $html_per.="<tr>                                               
                                                <td> ".$row["empresa"]."</td>
                                                <td> ".$row["placas"]."</td>
                                                <td> ".$row["marca"]."</td>
                                                <td> ".$row["modelo"]."</td>
                                                <td> ".$row["color"]."</td>
                                                <td> ".$row["poliza"]."</td>
                                                <td> ".$row["fecha_expira"]."</td>                                               
                                                <td> <a href=".$row["placas"]." name = 'idsele' class='btn btn-success' class='sepV_a' title='Poliza'>Editar</a></td>
                                                <td> <button type='button' class='btn btn-info btn-md' data-dismiss='modal' aria-label='Close' onclick='GuardarActivoVeh(".$row['veh_id'].")''>".$texto_veh."</button> </td>";
                                            
                                             "</tr>";
                                      }
                                      mysqli_free_result($result_per);
                                }
                $html_per.="</tbody></table>";
                
                echo "$html_per"; 
                ?>
             </div>  

    <?php 
    }
    else{
        
    ?>        
    <div class="container"  class="col-md-12 col-lg-12">  
 
        <form method="post" enctype="multipart/form-data"> 
        <div id="content" class="col-lg-2">  
                        <label for="placas_veh"><b>Placas:</b></label><br><input type="text" name="placas_veh" class="form-control" value="<?php echo $placas;?>" id="placas_veh" />
                        </div>                    
                        <div id="content" class="col-lg-2">  
                            <label for="poliza_veh"><b>Poliza:</b></label><br><input type="text" name="poliza_veh" class="form-control" value="<?php echo $poliza_act;?>" id="poliza_veh" />
                        </div>
                        <div id="content" class="col-lg-2"> 
                            <label for="fecha_pol"><b>Expira:</b></label><br><input type="date" name="fecha_pol" value="<?php echo $poliza_act_fec;?>" class="form-control" id="fecha_pol" />          
                        </div>
                         <div id="content" class="col-lg-1"> 
                           </br>                           
                            <a name="idsele" class="btn btn-success" data-toggle="modal" data-target="#ModalVer"  class="sepV_a" title="Poliza">Ver </a></td>
                         </div>
                        <div id="content" class="col-lg-2">                          
                            <label for="poliza_img"><b>Imagen:</b></label></br>
                            <input type="hidden" name="MAX_FILE_SIZE" value="200000000">
                            <input name="poliza_img" type="file" id="poliza_img"> 
                         </div>
                          <div id="content" class="col-lg-1"> 
                            <label for="poliza_ant"></label><input type="hidden" name="poliza_ant" value="<?php echo $poliza_act_path;?>" class="form-control" id="poliza_ant" />          
                          </div>                        
                        
                       <br/><br/><br/><br/><br/>
                        <div id="content" class="col-lg-2">  
                          
                        </div>
                       
                       <div id="content" class="col-lg-2">  
                            <label for="vtc"><b><?php echo $etiqueta_tc;?></b></label><br><input type="text" name="vtc" class="form-control" value="<?php echo $tc_act;?>" id="vtc" />
                        </div>
                        <div id="content" class="col-lg-2"> 
                            <label for="fecha_tc"><b>Expira:</b></label><br><input type="date" name="fecha_tc" value="<?php echo $tc_expira_act;?>" class="form-control" id="fecha_tc" />          
                        </div>
                         <div id="content" class="col-lg-1"> 
                           </br>                           
                            <a name="idsele" class="btn btn-success" data-toggle="modal" data-target="#ModalVertc"  class="sepV_a" title="Tarjeta">Ver </a></td>
                         </div>
                        <div id="content" class="col-lg-2">                          
                            <label for="tc_img"><b>Imagen TC:</b></label></br>
                            <input type="hidden" name="MAX_FILE_SIZE" value="200000000">
                            <input name="tc_img" type="file" id="tc_img"> 
                         </div>
                          
                          <br/><br/><br/><br/><br/>                       
                         <div  class="col-lg-2">
                        
                          <?php  if ($editar_veh == 'true'){ ?>
                               <td height="26" valign="medium"><input name="upload_pol" type="submit" class="box btn btn-info" id="upload_pol" value=" Actualizar "></td>
                          <?php }?>
                          </div>
        </form>
    
    </div>  
        <?php 
        
      }  
        
     if(isset($_POST['upload_pol'])){
           $placas_veh = $_POST['placas_veh'];
           $poliza_veh = $_POST['poliza_veh'];
           $fecha_pol = $_POST['fecha_pol'];
           $archivo_eliminar = $_POST['poliza_ant'];
           
           $tc_nueva = $_POST['vtc'];
           $tc_nueva_fecha = $_POST['fecha_tc'];
           
           $uploadDir = 'upload/vehiculos/';
                                
            if (file_exists(Env::get('UPLOAD_ROOT') . $uploadDir) <> 'true') { 
                mkdir (Env::get('UPLOAD_ROOT') . $uploadDir);
            }
                                 
            $filePath = $uploadDir;
                                 
            $fileName = $_FILES['poliza_img']['name'];
            $tmpName  = $_FILES['poliza_img']['tmp_name'];
            $fileSize = $_FILES['poliza_img']['size'];
            $fileType = $_FILES['poliza_img']['type'];
            
            $maxDoc = $mysqli->query("SELECT cast(id AS char(30))  AS maximo_id 
                                 FROM                                    
                                     arg_vehiculos  
                                 WHERE
                                    placas = '".$placas."'") or die(mysqli_error($mysqli));
            $maxDocID = $maxDoc ->fetch_array(MYSQLI_ASSOC);
            $maxDocCons = $maxDocID['maximo_id']; 
            $maxDocCons = $maxDocCons;
            $moverarchivo = '';
            if ($fileName <> ''){
                $fileName = $maxDocCons.$fileName;
                
                $moverarchivo = $filePath.$fileName;                            
                $result = move_uploaded_file($tmpName, $moverarchivo);
                if (!$result) {
                    echo "Error al intentar subir archivo";
                    exit;
                }
            }            
                                
            
            $fileName = addslashes($fileName);
            $filePath = addslashes($filePath);
            
            
            $archivo = $filePath.$fileName;
            
            
             $uploadDirTC = 'upload/vehiculos_tc/';

             if (file_exists(Env::get('UPLOAD_ROOT') . $uploadDirTC) <> 'true') { 
                mkdir (Env::get('UPLOAD_ROOT') . $uploadDirTC);
            }
                                 
            $filePathTC = $uploadDirTC;
                                 
            $fileName_tc = $_FILES['tc_img']['name'];
            $tmpName_tc  = $_FILES['tc_img']['tmp_name'];
            $fileSize_tc = $_FILES['tc_img']['size'];
            $fileType_tc = $_FILES['tc_img']['type'];
            $moverarchivotc = '';
            
            if ($fileName_tc <> '') {
                 $fileName_tc = $maxDocCons.$fileName_tc;
                 
                $moverarchivotc = $filePathTC.$fileName_tc;                           
                $result_tc = move_uploaded_file($tmpName_tc, $moverarchivotc);
                if (!$result_tc) {
                    echo "Error al intentar subir archivo";
                    exit;
                }
            }
            
               
                $fileName_tc = addslashes($fileName_tc);
                $filePath_tc = addslashes($filePathTC);
            
            
            $archivotc = $filePathTC.$fileName_tc;
       
           
                $query = "UPDATE arg_vehiculos
                            SET placas = '$placas_veh'
                               ,poliza = '$poliza_veh'
                               ,fecha_expira = '$fecha_pol'
                               ,path = '$moverarchivo'
                               ,tarjeta_circulacion = '$tc_nueva'
                               ,tc_fecha_expira = '$tc_nueva_fecha'
                               ,tc_path = '$moverarchivotc'
                          WHERE
                              placas = '".$placas."'";
                $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));

                echo "<br><b>Se actualiz� con exito:</b><br><br>"."$fileName <br>"; 
                echo "<script> redireccion() </script>";
            
        }
                            
               
    }

?>

