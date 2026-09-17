<? 



    $u_id = $_SESSION['u_id'];

    //$trn_id_rel = $_GET['trn_id'];

    $trn_id_rel = $_POST['trn_id'];

    date_default_timezone_set('America/Phoenix');

  //  echo 'aqui'.$trn_id_rel;

  // echo $u_id;

  

  use PHPMailer\PHPMailer\PHPMailer;

  use PHPMailer\PHPMailer\Exception;



  require 'librerias/PHPMailer.php';

  require 'librerias/SMTP.php';

  require 'librerias/Exception.php';

   

    function enviar($atiende_correo, $proveedor_correo, $folio, $proveedor, $empresa, $fecha, $trn_id){     

				//require("PHPMailer_v51/class.phpmailer.php");

				$mail = new PHPMailer();

				$mail->From = "argonautgoldvisit@argonautgoldvisit.com";

			//	$mail->From = "heliostarvisit@heliostarvisit.com";

				$mail->FromName = "Bitacora de Visitas MineData-Access de HelioStarMetals";

				$mail->Subject = "Envio desde Bitacora de Visitas MineData-Access HelioStarMetals";			

				$mail->AddBCC("".$atiende_correo."");

				

				$mail->SMTPOptions = array(

                                          'ssl' => array(

                                            'verify_peer' => FALSE,

                                            'verify_peer_name' => FALSE,

                                            'allow_self_signed' => TRUE

                                          )

                                        );   

        

				$mail->ContentType = "text/html";

				$body = "Has recibido una solicitud de visita con folio <strong>".$folio."</strong> del proveedor <strong>".$proveedor."</strong> empresa <strong>".$empresa."</strong> con fecha <strong>".$fecha."</strong><br>";

				$body .= "<br>Para ver mas detalles favor de ingresar a la bitácora de visitas:<br><br>";

				$body .= " <font color='red'>http://heliostarvisit.com/print_doc.php?trn_id=</font>".$trn_id."<br><br>";

                $body .= "Atte: MineData-Access Control de Acceso de HelioStar Metals";

				$mail->Body = $body;

				//$mail->AddAttachment("imgYaqui/pdf/weatherlink.pdf", "Weatherlink_Yaqui.pdf");

				$mail->Send(); 

    }



if(isset($_POST['subir_poliza'])){

       

    $placa  = $_POST['placa'];

    $marca  = $_POST['marca'];

    $modelo = $_POST['modelo'];

    $color  = $_POST['color'];

    $poliza = $_POST['poliza'];

    $expira_veh = $_POST['expira_veh'];

    $path_pol = $_POST['path_pol'];

    $org_id = $_SESSION['org_id'];

    

    $id_max_ve = $mysqli->query("SELECT max(veh_id) FROM arg_vehiculos");

        $id_maximo = $id_max_ve->fetch_array(MYSQLI_ASSOC);

    	$id_max_veh = $id_maximo['max(veh_id)'];

        $id_max_veh = $id_max_veh+1;

 

   $placa_dup = $mysqli->query("SELECT placas FROM arg_vehiculos WHERE placas = '".$placa."'");

        $placa_dupli = $placa_dup->fetch_array(MYSQLI_ASSOC);

    	$placa_duplic = $placa_dupli['placas'];

        $placa_duplicada = $placa_duplic;

        

  //Documento poliza importar

 /* if ($placa == '' || $marca == '' || $modelo == '' || $poliza == '' || $expira_veh == '' || $path_pol == '' ){

      $html = 'Error: Debe capturar toda la información.';

  }*/

 

  // else{

   

    

                    $archivo = $_FILES['pol_vehi']['name'];

                    $dest  = 'upload/vehiculos/'; 

                    $desti = rtrim($dest).$archivo;  

                    

                    copy($_FILES['pol_vehi']['tmp_name'],$desti);

                    

           if (isset($placa)){

    

           $query = "INSERT INTO arg_vehiculos (veh_id, placas, marca, modelo, color, poliza, org_id, path, fecha_expira ) ".

                     "VALUES ($id_max_veh, '$placa', '$marca', '$modelo', '$color', '$poliza', $org_id, '$desti', '$expira_veh')";

                                

             $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));

             

             $resultado = $mysqli->query("SELECT veh_id, placas, marca FROM arg_vehiculos WHERE org_id = ".$org_id." ORDER BY veh_id DESC") or die(mysqli_error());

        } 

  //  }

}

 if ($trn_id_rel <> '' || $trn_id_rel > 0){

    

           $mysqli -> set_charset("utf8");

           $datos_v = $mysqli->query("SELECT arg_actividad.nombre FROM arg_entradas_actividad 

                                       LEFT JOIN arg_actividad 

                                            ON arg_actividad.act_id = arg_entradas_actividad.act_id

                                       WHERE trn_id_rel = ".$trn_id_rel) or die(mysqli_error()); 

                                       

            $datos_e = $mysqli->query("SELECT folio, fecha_inicio, fecha_final, arg_empr_unidades.nombre AS unidad, arg_entradas.unidad_id AS unidad_id, arg_usuarios.nombre AS usuario,

                                        	  arg_entradas.comentario

                                        FROM `arg_entradas` 

                                        LEFT JOIN arg_entradas_detalle

                                        	ON arg_entradas_detalle.trn_id_rel = arg_entradas.trn_id

                                        LEFT  JOIN arg_empr_unidades

                                            ON arg_empr_unidades.unidad_id = arg_entradas.unidad_id

                                        LEFT JOIN arg_usuarios

                                        	ON arg_usuarios.u_id = arg_entradas.usuario_id

                                        WHERE trn_id = ".$trn_id_rel) or die(mysqli_error());

            $entrada = $datos_e->fetch_assoc();

            /*$unidad_id_sel = $entrada['unidad_id'];*/

            

           $datos_areas = $mysqli->query("SELECT

                                            ear.area_id, ua.area, uar.email AS encargado_area

                                           FROM `arg_entradas_areas` ear

                                                LEFT JOIN arg_entradas e

                                                	ON e.trn_id = ear.trn_id_rel

                                            	LEFT JOIN arg_unidades_areas ua

                                                	ON ear.area_id = ua.area_id

                                                    AND ua.unidad_id = e.unidad_id

                                                LEFT JOIN arg_usuarios AS uar

                                                	ON uar.u_id = ua.u_id

                                          WHERE ear.trn_id_rel = ".$trn_id_rel) or die(mysqli_error($mysqli));

                                       

            $datos_at = $mysqli->query("SELECT arg_usuarios.nombre AS atiende

                                        FROM `arg_entradas`                                         

                                        LEFT JOIN arg_usuarios

                                        	ON arg_usuarios.u_id = arg_entradas.usuario_id_atie

                                        WHERE trn_id = ".$trn_id_rel) or die(mysqli_error());

            $usuario_atiende = $datos_at->fetch_assoc();

            

            $datos_res = $mysqli->query("SELECT arg_usuarios.nombre AS responsable

                                        FROM `arg_entradas`                                         

                                        LEFT JOIN arg_usuarios

                                        	ON arg_usuarios.u_id = arg_entradas.usuario_id_resp

                                        WHERE trn_id = ".$trn_id_rel) or die(mysqli_error());

            $usuario_resp = $datos_res->fetch_assoc();     

            

            $visitantes = $mysqli->query("SELECT arg_usuarios.nombre AS visitante, usuarios_doc.imss AS ine

                                            FROM `arg_entradas_detalle`                                         

                                            LEFT JOIN arg_usuarios

                                            	ON arg_entradas_detalle.usuario_id = arg_usuarios.u_id

                                            LEFT JOIN usuarios_doc

                                            	ON usuarios_doc.u_id = arg_entradas_detalle.usuario_id

                                                AND tipo_id = 2

                                            WHERE trn_id_rel =  ".$trn_id_rel) or die(mysqli_error());

                                            

            $vehiculos = $mysqli->query("SELECT arg_usuarios_documentos.nombre, arg_usuarios_documentos.fecha_expira, arg_vehiculos.marca, arg_vehiculos.modelo, arg_vehiculos.placas

                                            FROM `arg_entradas` 

                                            LEFT JOIN arg_vehiculos

                                                ON arg_vehiculos.veh_id = arg_entradas.veh_id

                                            LEFT JOIN arg_usuarios_documentos

                                            	ON arg_entradas.usuario_id = arg_usuarios_documentos.u_id

                                                AND tipo_id = 3

                                                AND arg_usuarios_documentos.activo = 1

                                            WHERE trn_id = ".$trn_id_rel) or die(mysqli_error());

                                            

            $herramientas = $mysqli->query("SELECT arg_entradas_herramientas.cantidad, arg_herramientas.nombre, arg_herramientas.marca

                                                FROM `arg_entradas_herramientas` 

                                                LEFT JOIN arg_herramientas

                                                	ON arg_herramientas.herr_id = arg_entradas_herramientas.herr_id

                                                WHERE trn_id_rel = ".$trn_id_rel) or die(mysqli_error());         

            ?>

             <div class="container">

                    <h3>                         

                 <? 

                 echo ("Folio Visita: ".$entrada['folio']);

                 ?>

                 </h3><?

                 $html_en = "<table class='table table-bordered' id='encabezado'>

                             <thead>

                                 <tr class='table-secondary'>   

                                    <th scope='col'>Unidad de Mina: ".$entrada['unidad']."</th>

                                     <th scope='col'>Areas: ";

                                     	while ($fila = $datos_areas->fetch_assoc()) {

                                    		$html_en.="<a>".$fila['area'].", "."</a>";

                                    	}

                                     

                                     $html_en.="</th>

                                     <th scope='col'>Desde: ".$entrada['fecha_inicio']."</th>

                                    <th scope='col'>Hasta: ".$entrada['fecha_final']."</th>

                                  </tr>

                                  <tr>            

                                    <th scope='col'>Registr&oacute: ".$entrada['usuario']."</th>

                                    <th scope='col'>Atiende: ".$usuario_atiende['atiende']."</th>

                                    <th scope='col'>Responsable: ".$usuario_resp['responsable']."</th>

                                  </tr>";

                    

                  $html_m = "<table class='table table-bordered' id='motivos'>

                                <thead>

                                <tr class='bg-info'>            

                                    <th scope='col'>Motivos de la Visita</th>

                                </tr>

                            </thead>

                            <tbody>

                            <td>";  

                                

                                               

                            	while ($fila = $datos_v->fetch_assoc()) {

               	                     

                            		$html_m.="<a>".$fila['nombre'].", "."</a>";

                            	}

                  $html_m.="</td></tbody></table>";

                  $html_c = "<table class='table table-bordered' id='comentarios'>

                                <thead>

                                <tr>            

                                    <th scope='col'>Comentario General</th>

                                </tr>

                            </thead>

                            <tbody>";   

                            		$html_c.="<tr><td>".$entrada['comentario']."</td></tr>";

                  $html_c.="</tbody></table>";

                  $html_v = "<table class='table table-bordered' id='visitantes'>

                                <thead>

                                <tr class='bg-info'>            

                                    <th scope='col'>Otros Visitantes</th>

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

                  $html_v.="</tbody></table>";

                  $html_veh = "<table class='table table-bordered' id='visitantes'>

                                <thead>

                                <tr class='bg-info'>            

                                    <th scope='col'>Licencia</th>

                                    <th scope='col'>Marca</th>

                                    <th scope='col'>Modelo</th>

                                    <th scope='col'>Placa</th>

                                </tr>

                            </thead>

                            <tbody>";                    

                            	while ($fila_ve = $vehiculos->fetch_assoc()) {

                            		$html_veh.="<tr>

                                                <td>".$fila_ve['nombre']."</td>

                                                <td>".$fila_ve['marca']."</td>

                                                <td>".$fila_ve['modelo']."</td>

                                                <td>".$fila_ve['placas']."</td>

                                             </tr>";

                            	}

                  $html_veh.="</tbody></table>";

                  $html_veh.="<table class='table table-bordered' id='visitantes'>

                                <thead>

                                <tr class='bg-info'>            

                                    <th scope='col'>Cantidad</th>

                                    <th scope='col'>Herramienta</th>

                                    <th scope='col'>Marca</th>

                                </tr>

                            </thead>

                            <tbody>";                    

                            	while ($fila_herr = $herramientas->fetch_assoc()) {

                            		$html_veh.="<tr>

                                                <td>".$fila_herr['cantidad']."</td>

                                                <td>".$fila_herr['nombre']."</td>

                                                <td>".$fila_herr['marca']."</td>

                                             </tr>";

                            	}

                  $html_veh.="</tbody></table>";

                      echo utf8_decode("$html_en");

                      echo utf8_decode("$html_m");

                      echo utf8_decode("$html_c");

                      echo utf8_decode("$html_v");

                      echo utf8_decode("$html_veh");
                      
                   /* $respuesta  = $html_en;
                    $respuesta .= $html_m;
                    $respuesta .= $html_c;
                    $respuesta .= $html_v;
                    $respuesta .= $html_veh;
                    echo ($respuesta);*/
                ?>

              

           <div class="container">

           <div class="col-4 col-md-11 col-lg-11">

                <div class="col-4 col-md-4 col-lg-4">

                     <form method="post" action="print_doc.php?trn_id=<?echo $trn_id_rel;  ?>" target="_blank" name="Printform" id="Printform">  

                        <fieldset>  

                            <input type="submit" class="btn btn-success" name="print" id="print" value="Imprimir Visita" />                

                       </fieldset>  

                    </form> 

                </div>

                

                <div class="col-4 col-md-4 col-lg-4">

                    <form method="post" action="print_herr.php?trn_id=<?echo $trn_id_rel;?>" target="_blank" name="Printherr" id="Printherr">  

                        <fieldset>  

                            <input type="submit" class="btn btn-warning" name="Printherram" id="Printherram" value="Imprimir Herramientas" />                

                       </fieldset>  

                    </form> 

                </div>

                  

                <div class="col-4 col-md-4 col-lg-4">

                      <form method="post" action="app.php" name="newform" id="newform">  

                            <fieldset>  

                                <input type="submit" class="btn btn-info" name="nueva_visita" id="nueva_visita" value="Nueva visita" />                

                           </fieldset>  

                        </form>

                </div>

             </div>

             </div>

            <?

      }

?>   



  

 <script>

    var count_click  = 0;

    var count_click2 = 0;

    var count_click4 = 0;

    var count_click5 = 0;

     

    //Mostrar datos de vehículos seleccionados y validar sus vigencias de licencia

    function ShowSelected()

        {

           // var placa = document.getElementById("placa").value;                         

            var fecha_ini = document.getElementById("fecha_inicial").value;

            var fecha_fin = document.getElementById("fecha_final").value;

            if (fecha_fin == ''){

                alert('Por favor especifique una fecha para su visita. Reintente')

                document.getElementById("placas").value = '';

            }

            else{

            var veh_id = document.getElementById("placas").value;

            $.ajax({

            		url: 'datos_veh.php' ,

            		type: 'POST' ,

            		dataType: 'html',

            		data: {veh_id: veh_id, fecha_ini:fecha_ini, fecha_fin:fecha_fin},

            	})

            	.done(function(respuesta){

            		$("#placas_dat").html(respuesta);

                                       

                    console.log(respuesta);

                    if (respuesta == 'Poliza vencida' || respuesta == 'Licencia vencida'){

                       document.getElementById("placas").value = "0"; 

                    }

              })

            }

      }

      

      function error_fecha()

            {

                alert('Debe seleccionar una fecha para su visita. Por favor reintente.')

                 var print_dr = '<?php echo "\app.php"?>';                

                 window.location.href = print_dr;

            }

      

       function redireccion($trn_id)

            {
                alert(llego);

                 var trn_id = $trn_id;

                  $.ajax({

            		url: 'app.php' ,

            		type: 'POST' ,

            		dataType: 'html',

            		data: {trn_id: trn_id},

            	})

            	.done(function(respuesta){
                     console.log(respuesta);
                     alert(respuesta)
            	
           // window.location.href = 'app.php';
        

                  

              })

            }

            

    //Agregar vehículos

     function GuardarVeh()

        {

            var placa  = document.getElementById("placa").value;                         

            var marca  = document.getElementById("marca").value; 

            var modelo = document.getElementById("modelo").value; 

            var color  = document.getElementById("color").value; 

            var poliza = document.getElementById("poliza").value;

            var expira_veh = document.getElementById("expira_veh").value;

            var path_pol = document.getElementById("pol_vehi").files[0].name;

            alert(path_pol);

            /*var path_pol_temp = document.getElementById("pol_vehi").files[0].tmp_name;

           

            alert(path_pol_temp);*/

            //var path_pol_t = new FormData(document.getElementById("fileinfo"));

            //var path_pol_t = document.getElementById("userfile").files[0].tmp_name;

           

            //var file = document.getElementById("userfile").files[0].name;//path_pol.files[0];

			//var data = new FormData(document.getElementById("userfile"));//.files[0].name);

			//data.append("userfile",file);

            

            //alert(path_pol);

            $.ajax({

            		url: 'insertar_veh.php' ,

            		type: 'POST' ,

            		dataType: 'html',

            		data: {placa: placa, marca:marca, modelo:modelo, color:color, poliza:poliza, expira_veh:expira_veh, path_pol:path_pol},

            	})

            	.done(function(respuesta){

            		$("#placas").html(respuesta);

                    var veh_id = document.getElementById("placas").value;

                    alert('Se guardó con éxito!');

                     ShowSelected();

                     $('#ModalVeh').hide();

              })               

      }

      

      //Agregar herramientas

      function GuardarHerr()

        {

            var table = document.getElementById("tbherr");

            var total_rows = parseInt(table.rows.length)+1;

            var i = 0;

            alert(total_rows);

            var nombre_h = document.getElementById("nombre_h").value;                      

            var marca_h  = document.getElementById("marca_h").value; 

            var modelo_h = document.getElementById("modelo_h").value; 

            var serie_h  = document.getElementById("serie_h").value;



            $.ajax({

            		url: 'insertar_herr.php' ,

            		type: 'POST' ,

            		dataType: 'html',

            		data: {nombre_h:nombre_h, marca_h:marca_h, modelo_h:modelo_h, serie_h:serie_h},

            	})

            	.done(function(respuesta){

            	    while (i <= total_rows){

            	        cajacontenedor = 'busqueda_herr'+i;

            	    	$("#"+cajacontenedor).html(respuesta);

                        i++;

            	    }

                    alert('Se guardó con éxito!');                   

                     ShowSelected();

                     $('#Modalherr').hide(2);

                     

              })                 

      }

      //Seleccionar más proveedores

      function ShowProveedor(count_click)

        {

            var caja = 'busqueda_proveedor'+count_click;

            var caja_visor = 'dbusqueda_proveedor'+count_click;

           

            var prov_id = document.getElementById(caja).value;

            var fecha = document.getElementById("fecha_final").value;

            var motivo = document.getElementById("actividad0").value;

            var organ_id = document.getElementById("organizacion").value;

            //alert(motivo);

            $.ajax({

            		url: 'datos_prov.php' ,

            		type: 'POST' ,

            		dataType: 'html',

            		data: {prov_id: prov_id, fecha: fecha, motivo: motivo,organ_id:organ_id},

            	})

            	.done(function(respuesta){

            		$("#"+caja_visor).html(respuesta);

                    if (respuesta == '<a>Documentos vencidos</a>'){

                        document.getElementById(caja).value = "0";

                    }

              })

      }

    

    function Showherr(count_click2)

        {

           

            var total_herr = count_click2;

            var i = 1;

            //alert(total_herr);

            $.ajax({

            		url: 'combo_herr.php' ,

            		type: 'POST' ,

            		dataType: 'html',

            		data: {total_herr: total_herr},

            	})

            	.done(function(respuesta){

            	    

            	    while (i <= total_herr){

            	         var caja = 'busqueda_herr'+i;

            		    $("#"+caja).html(respuesta);

                        document.getElementById(caja).value = "0";

                        i++;

                    }

              })

      }

     

     //Agregar renglones para proveedores 

    function agregarFila() {

        var htmlTags = '';

        var eti = '';

        count_click += 1;       

        var name_id = "busqueda_proveedor"+count_click;       

       if (count_click == 1){

                eti = '<label>Otros visitantes:</label>';

        }

      

       htmlTags = '<div class="container" >'        

        +'<div class="col-md-3 col-lg-3" >'

        +'<select name="'+name_id+'" id="'+name_id+'" class="form-control" onchange="ShowProveedor('+count_click+')">' 

        <?$result = $mysqli->query("SELECT '0' AS u_id, 'Seleccione proveedor' AS nombre UNION ALL SELECT u_id, nombre FROM `arg_usuarios` 

                                    WHERE division = 'proveedor' AND org_id = ".$_SESSION['org_id']." AND u_id <> ".$_SESSION['u_id']) or die(mysqli_error());                             

                              while ( $row1 = $result ->fetch_array(MYSQLI_ASSOC)) {

                                $nombre_pro = $row1['nombre'];                                

                              ?>       

        +'<option value="<?echo $row1['u_id']?>"><?echo $nombre_pro?></option>'

        <?}?>

        +'</select></div>'

        +'<div class="col-md-5 col-lg-5" id="d'+name_id+'"></div></br>'

         

         +'</br>'

         +'</div>'

        $('#tbdoctor tbody').append(htmlTags);

        console.log(htmlTags);        

        htmlTags = '';

    }

   

   //Agregar fila para herramientas

   function agregarFilaHerr() {

        var htmlTagsH = '';

        count_click2 += 1;   

        

        var name_id_herr = "busqueda_herr"+count_click2;

        var input_id_herr = "cantidad"+count_click2;   

        var herr = "herr"+count_click2;

      

       htmlTagsH = '<div class="container">'

      

        +'<div class="col-md-2 col-lg-2">'

            +'<input name="'+input_id_herr+'" placeholder="Cantidad" class = "form-control" >'

        +'</div>'

        +'<div class="col-md-1 col-lg-1">'

        +'</div>'

         +'<div class="col-md-3 col-lg-3">'

        +'<span class="input-group-btn">'

        +'<div class="input-group">'

        +'<select name="'+name_id_herr+'" id="'+name_id_herr+'" class="form-control" onclick="Showherr('+count_click2+')">' 

        <?$result_h = $mysqli->query("SELECT herr_id, nombre, modelo, serie FROM `arg_herramientas` WHERE org_id = ".$_SESSION['org_id']." AND activo=1") or die(mysqli_error());                             

                              while ( $row2 = $result_h ->fetch_array(MYSQLI_ASSOC)) {

                                $nombre_herr = $row2['nombre'].' '.$row2['modelo'].' '.$row2['serie'];                                

                              ?>       

        +'<option value="<?echo $row2['herr_id']?>"><?echo $nombre_herr?></option>'

        <?}?>

        +'</select>'

       

        +'<a href="" name="'+herr+'" id="'+herr+'" class="btn btn-primary" data-toggle="modal" data-target="#Modalherr"  class="sepV_a" title="Nueva Herramienta">+ </a>' 

        +'</span>'

        +'</div>'

        +'</div>'

    

        +'</br>'

        $('#tbherr tbody').append(htmlTagsH);

        console.log(htmlTagsH);        

        htmlTagsH = '';

    }

    

      //Agregar fila distintos motivos de visitas

      function agregarMot() {

        var htmlTagsM = '';

        count_click4 += 1;   

        

        var name_id_act = "actividad"+count_click4;        

        htmlTagsM = '<class="container">'

        +'<select name="'+name_id_act+'" id="'+name_id_act+'" class="form-control">' 

        <?$result_m = $mysqli->query("SELECT 0 AS act_id, 'Motivo de la visita' AS nombre UNION ALL

                                      SELECT act_id, nombre FROM `arg_actividad`") or die(mysqli_error());                             

                              while ( $row6 = $result_m ->fetch_array(MYSQLI_ASSOC)) {

                              ?>       

        +'<option value="<?echo $row6['act_id']?>"><?echo $row6['nombre']?></option>'

        <?}?>

        +'</select>'

         +'</br>'

        +'</div>'

        $('#tbmotivo tbody').append(htmlTagsM);

        console.log(htmlTagsM);        

        htmlTagsM = '';

    }

    

    //Agregar fila distintas areas en la visita

      function agregarArea() {

        var htmlTagsM = '';

        count_click5 += 1;   

        

        var name_id_area = "area"+count_click5;  

        var mina_sel = document.getElementById("organizacion").value;

        

        if (mina_sel ==''){

            alert('Debe elegir una unidad de mina');

        }

        else{

            $.ajax({

            		url: 'combo_areas.php' ,

            		type: 'POST' ,

            		dataType: 'html',

            		data: {unidad_id:mina_sel,name_id_area:name_id_area},

            	})

            	.done(function(respuesta){

            	    //alert(respuesta)

            	

            		$('#tbarea tbody').append(respuesta);

              })

        }

    }

    

    //Agregar fila distintas areas en la visita

      function actualizar_areas(uid) {

        var htmlTagsM = '';

        //count_click5 += 1;  

        var user_id = uid;

        ValidaCursos(user_id);

        

        var name_id_area = "area0";

        var mina_sel = document.getElementById("organizacion").value;

        $.ajax({

            		url: 'combo_areas.php' ,

            		type: 'POST' ,

            		dataType: 'html',

            		data: {unidad_id:mina_sel, name_id_area:name_id_area},

            	})

            	.done(function(respuesta){

            	    //alert(respuesta)

            		$("#area0").html(respuesta);

            		$('#tbarea tbody').html('');

            	    document.getElementById("fecha_inicial").value = '';

            	    document.getElementById("fecha_final").value = '';

              })

        

    }



 </script>

 

 

<script>

    function ValidaCursos($u_id)

        {

            var u_id = $u_id

            var organ_id = document.getElementById("organizacion").value;

            var motivo   = document.getElementById("actividad0").value;

            if (motivo == 0){

                alert('Debe seleccionar una motivo para su visita. Reintente');

            }

           /* alert(u_id);

            alert(organ_id);*/

            $.ajax({

            		url: 'datos_cursos.php' ,

            		type: 'POST' ,

            		dataType: 'html',

            		data: {u_id: u_id, motivo:motivo,organ_id:organ_id},

            	})

            	.done(function(respuesta){

            	    //alert(respuesta);

                    var resp =  respuesta  

                    if (resp == 'NO'){

                        alert('Usted no tiene los cursos de inducción requeridos. Favor de solicitarlos por este medio');                   

                        var cadena = '/cursos.php?tipo=0&unidad=0';

                        window.location.href = cadena;

                    }

              })

      }

    

    //Validar vigencias del usuario firmado 

    function ValidaVigencias($u_id)

        {

           var u_id = $u_id

           var fecha = document.getElementById("fecha_inicial").value;

           document.getElementById("fecha_final").value = fecha;

           var usuario_seleccionado = document.getElementById("empleado").value;

           var area_seleccionada = document.getElementById("area0").value;

           

           if (area_seleccionada == 0){

               alert('Por favor seleccione el área a visitar. Reintente');

               document.getElementById("fecha_inicial").value = '';

               document.getElementById("fecha_final").value = '';

           }

           

           if (usuario_seleccionado == 0){

                alert('Por favor especifique el empleado a quien visita. Reintente');

                document.getElementById("fecha_inicial").value = '';

                document.getElementById("fecha_final").value = '';

            }

            else{

                //alert(u_id);           

                //alert(fecha);

                $.ajax({

                		url: 'datos_vigencias.php' ,

                		type: 'POST' ,

                		dataType: 'html',

                		data: {u_id: u_id, fecha:fecha},

                	})

                	.done(function(respuesta){

                		//$("#"+caja_visor).html(respuesta); 

                        var resp =  respuesta                

                       // console.log(respuesta);

                        //alert(resp);

                        if (resp != ""){

                            alert('Usted tiene documentos vencidos:  '+resp);                   

                            var cadena = '/doc_personas.php?u_id='+u_id;

                            window.location.href = cadena;

                        }

                                           

                  })

            }

      }

      

      //Validación de fecha final Vs fecha inicial

      function ValidaVigenciasFin($u_id)

        {

            var u_id = $u_id

            var fecha_ini = document.getElementById("fecha_inicial").value;

            var fecha = document.getElementById("fecha_final").value;

            //alert(fecha_ini);           

            //alert(fecha);

            if (fecha < fecha_ini){

                alert('La fecha final no puede ser menor que la fecha inicial.')

                document.getElementById("fecha_final").value = fecha_ini;

            }         

            else{            

                $.ajax({

                		url: 'datos_vigencias.php' ,

                		type: 'POST' ,

                		dataType: 'html',

                		data: {u_id: u_id, fecha:fecha},

                	})

                	.done(function(respuesta){

                        var resp =  respuesta

                        if (resp != ""){

                            alert('Usted tiene documentos vencidos:  '+resp);                     

                            var cadena = '/doc_personas.php?u_id='+u_id;

                            window.location.href = cadena;

                        }

                                           

                  })

        }

      }

</script>



  

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

 

 <!-- Modal Vehículos  --> 

 <div class="modal fade" id="ModalVeh" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

          <div class="modal-dialog" role="document">

            <div class="modal-content">

              <div class="modal-header">

                <h5 class="modal-title" id="exampleModalLabel">Agregar Vehículo</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                  <span aria-hidden="true">&times;</span>

                </button>

              </div>

              <div class="modal-body">

                 

                 <form name='importar_veh' method='post' action='app.php' enctype='multipart/form-data' >

                 <table class='table text-black' id='datos_importar'>

                     

                    <label for="placa" class="col-form-label">Placa:</label>

                    <input name="placa" id="placa" size=40 style="width:470px; color:#996633"  value="" enabled />

                    <label for="marca" class="col-form-label">Marca:</label>

                    <input name="marca" id="marca" size=40 style="width:470px; color:#996633"  value="" enabled />

                    <label for="modelo" class="col-form-label">Modelo:</label>

                    <input name="modelo" id="modelo" size=40 style="width:470px; color:#996633"  value="" enabled />

                     <label for="color" class="col-form-label">Color:</label>

                    <input name="color" id="color" size=40 style="width:470px; color:#996633"  value="" enabled />

                     <label for="poliza" class="col-form-label">Póliza:</label>

                    <input name="poliza" id="poliza" size=40 style="width:470px; color:#996633"  value="" enabled />

                     <label for="expira_veh"  class="col-form-label">Fecha Expira:</label>

                    <input type="date" name="expira_veh" id="expira_veh" size=40 style="width:470px; color:#996633"  value="" enabled />

                    

                     <table width="470" border="0" cellpadding="1" cellspacing="1" class="box">

                        <!--DWLayoutTable-->

                        <tr> 

                         

                                    <th>IMPORTAR POLIZA</th>

                                    <th>  </th>

                                    <tr class='table-primary' align='left'>

                                        <th colspan='4'>                                        

                                        

                                                <input type='file' name='pol_vehi' id='pol_vehi' />

                                                </br>                           

                          <td width="85" height="18"></td>

                        </tr>

                       

                      </table>

                      

                      <button type='input' class='btn btn-success' name='subir_poliza' id='subir_poliza' >

                                                <i class='fa-upload fa bootstrap'> Guardar </i>

                                              </button>

                      

                       </form> 

                        </th></table>

                    

              </div>

              <div class="modal-footer">

                

                 <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>

              </div>

            </div>

          </div>

   </div>        



 <!-- Modal Herramientas  --> 

<div class="modal fade" id="Modalherr" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

          <div class="modal-dialog" role="document">

            <div class="modal-content">

              <div class="modal-header">

                <h5 class="modal-title" id="modalHerr">Agregar Herramienta</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                  <span aria-hidden="true">&times;</span>

                </button>

              </div>

              <div class="modal-body">

                    <label for="nombre_h" class="col-form-label">Nombre:</label>

                    <input name="nombre_h" id="nombre_h" size=40 style="width:470px; color:#996633"  value="" enabled />

                    <label for="marca_h" class="col-form-label">Marca:</label>

                    <input name="marca_h" id="marca_h" size=40 style="width:470px; color:#996633"  value="" enabled />

                    <label for="modelo_h" class="col-form-label">Modelo:</label>

                    <input name="modelo_h" id="modelo_h" size=40 style="width:470px; color:#996633"  value="" enabled />

                    <label for="serie_h" class="col-form-label">Serie:</label>

                    <input name="serie_h" id="serie_h" size=40 style="width:470px; color:#996633"  value="" enabled />

              </div>

              <div class="modal-footer">

                <button type="button" class="btn btn-primary" onclick="GuardarHerr()">Guardar</button>

                 <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>

              </div>

            </div>

          </div>

   </div> 

 





<?

$mysqli -> set_charset("utf8");



    

 

/*else{*/

   //echo 'aca';

    if (isset($_POST['visita'])){

    //    echo 'aca';

    $fecha_ini       = $_POST['fecha_inicial'];

    $fecha_fin       = $_POST['fecha_final'];

    $unidad_mina     = $_POST['organizacion'];

    $empleado_visita = $_POST['empleado'];

    $empleado_responsable = $_POST['empleado_visita'];    

    $placas          = $_POST['placas'];

    $cantidad1       = $_POST['cantidad_h'];

    $herr            = $_POST['herr'];

    $comentario_vis  = $_POST['comentario_visita'];

   

    //echo $fecha_ini;    

    //Guardar proceso

    

        if ($fecha_ini == ''){

                echo "<script> error_fecha();</script>";

        }

        else{   

            $max_trn_id = $mysqli->query("SELECT MAX(trn_id) AS trn_id FROM arg_entradas") or die(mysqli_error());

            $max_trn = $max_trn_id ->fetch_array(MYSQLI_ASSOC);

            $trn_id = $max_trn['trn_id'];

            $trn_id = $trn_id +1;

            

            if ($placas == ''){

                $placas = 0;

            }                

            $max_folio_id = $mysqli->query("SELECT MAX(folio) AS folio_max FROM arg_entradas WHERE unidad_id = ".$unidad_mina) or die(mysqli_error());

            $max_folio = $max_folio_id ->fetch_array(MYSQLI_ASSOC);

            $folio = $max_folio['folio_max'];

            $folio = $folio +1;    

            $hoy = date("Y-m-d H:i:s");             

            //$mysqli->query("CALL arg_prc_entradasProcesar(2);");

            //Insertando en arg_entradas


            $query = "INSERT INTO arg_entradas (trn_id, folio, fecha, fecha_inicio, fecha_final, unidad_id, usuario_id, usuario_id_atie, usuario_id_resp, veh_id, comentario ) ".

                     "VALUES ($trn_id, $folio, '$hoy', '$fecha_ini', '$fecha_fin', $unidad_mina, $u_id,$empleado_visita, $empleado_responsable, $placas, '$comentario_vis')";

            $mysqli->query($query) ;

            //echo $query;

            //die();

            

            //Insertando en arg_entradas_estados

            $query = "INSERT INTO arg_entradas_estados (trn_id, estado_id, fecha, comentario ) ".

                     "VALUES ($trn_id, 1, '$hoy', '')";

            $mysqli->query($query) ;

            //Diferentes motivos

            $x = 1;

            $z = 0;

            while ($x <> 0){        

                $ac = 'actividad';

                $act = $ac.$z;

                $actividad = $_POST[$act];

                if ($actividad==''){

                    $x = 0;

                    }

                else{

                    //Insertando en arg_entradas_actividad para los motivos

                    $query = "INSERT INTO arg_entradas_actividad (trn_id_rel, act_id) ".

                             "VALUES ($trn_id, $actividad)";

                    $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));        

                    $z = $z+1;

                }

            }

            //echo $query;

            

             //Diferentes areas de visitas

            $l = 1;

            $m = 0;

            while ($l <> 0){        

                $ar = 'area';

                $are = $ar.$m;

                $area_sel = $_POST[$are];

                if ($area_sel==''){

                    $l = 0;

                    }

                else{

                    //Insertando en arg_entradas_actividad para los motivos

                    $query = "INSERT INTO arg_entradas_areas (trn_id_rel, area_id, estado_id, fecha) ".

                             "VALUES ($trn_id, $area_sel, 1, '$hoy')";

                    $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));        

                    $m = $m+1;

                }

            }

            

            //Proveedores

            $i = 1;

            $p = 1;

            $proveedores=array();

            while ($i <> 0){        

                $pro = 'busqueda_proveedor';

                $prov = $pro.$p;

                $p1 = $_POST[$prov];

                if ($p1==''){

                    $i = 0;

                    }

                else{

                    //Insertando en arg_entradas_usuarios

                    $query = "INSERT INTO arg_entradas_detalle (trn_id_rel, usuario_id) ".

                             "VALUES ($trn_id, $p1)";

                    $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));      

                    $p = $p+1;

                }

            }

            

            //Herramientas Cantidad

            $j = 1;

            $k = 0;

            $herramientas=array();

            while ($j <> 0){        

                $herr = 'cantidad';

                $herramienta_cant = $herr.$k;

                $c1 = $_POST[$herramienta_cant];

                $herr_bus = 'busqueda_herr';

                $herr_busqueda = $herr_bus.$k;

                $h1 = $_POST[$herr_busqueda];

                if ($c1==''){

                    $j = 0;

                    }

                else{

                    $herramientas[$k] = $c1;

                    //Insertando en arg_herramientas campos herramienta

                    $query = "INSERT INTO arg_entradas_herramientas (trn_id_rel, herr_id, cantidad) ".

                             "VALUES ($trn_id, $h1, $c1 )";

                             $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));

                    $k = $k+1;            

                }

            }

            

            //Datos para el correo

            $datos_vis = $mysqli->query("SELECT folio,  un.nombre AS mina, us.nombre as proveedor, us.email AS proveedor_correo, ati.nombre as atiende

                                        ,ati.email AS atiende_correo, o.nombre as empresa, ae.fecha_inicio

                                        FROM arg_entradas ae

                                        LEFT JOIN arg_empr_unidades un

                                            ON un.unidad_id = ae.unidad_id

                                        LEFT JOIN arg_usuarios us

                                            ON us.u_id = ae.usuario_id

                                        LEFT JOIN arg_usuarios ati

                                            ON ae.usuario_id_atie = ati.u_id                    

                                        LEFT JOIN arg_organizaciones o

                                            ON o.org_id = us.org_id

                                        WHERE ae.trn_id = ".$trn_id) or die(mysqli_error());

            $datos = $datos_vis ->fetch_array(MYSQLI_ASSOC);

            $folio = $datos['folio'];

            $proveedor = $datos['proveedor'];

            $empresa = $datos['empresa'];

            $fecha = $datos['fecha_inicio'];

            $atiende_correo = $datos['atiende_correo'];

            $proveedor_correo = $datos['proveedor_correo'];

            if ($folio <> 0){

                enviar($atiende_correo, $proveedor_correo, $folio, $proveedor, $empresa, $fecha, $trn_id);

            }

      

          //  echo "<script> redireccion($trn_id);</script>";
          $mysqli -> set_charset("utf8");

           $datos_v = $mysqli->query("SELECT arg_actividad.nombre FROM arg_entradas_actividad 

                                       LEFT JOIN arg_actividad 

                                            ON arg_actividad.act_id = arg_entradas_actividad.act_id

                                       WHERE trn_id_rel = ".$trn_id) or die(mysqli_error()); 

                                       

            $datos_e = $mysqli->query("SELECT folio, fecha_inicio, fecha_final, arg_empr_unidades.nombre AS unidad, arg_entradas.unidad_id AS unidad_id, arg_usuarios.nombre AS usuario,

                                        	  arg_entradas.comentario

                                        FROM `arg_entradas` 

                                        LEFT JOIN arg_entradas_detalle

                                        	ON arg_entradas_detalle.trn_id_rel = arg_entradas.trn_id

                                        LEFT  JOIN arg_empr_unidades

                                            ON arg_empr_unidades.unidad_id = arg_entradas.unidad_id

                                        LEFT JOIN arg_usuarios

                                        	ON arg_usuarios.u_id = arg_entradas.usuario_id

                                        WHERE trn_id = ".$trn_id) or die(mysqli_error());

            $entrada = $datos_e->fetch_assoc();

           $datos_areas = $mysqli->query("SELECT

                                            ear.area_id, ua.area, uar.email AS encargado_area

                                           FROM `arg_entradas_areas` ear

                                                LEFT JOIN arg_entradas e

                                                	ON e.trn_id = ear.trn_id_rel

                                            	LEFT JOIN arg_unidades_areas ua

                                                	ON ear.area_id = ua.area_id

                                                    AND ua.unidad_id = e.unidad_id

                                                LEFT JOIN arg_usuarios AS uar

                                                	ON uar.u_id = ua.u_id

                                          WHERE ear.trn_id_rel = ".$trn_id) or die(mysqli_error($mysqli));

                                       

            $datos_at = $mysqli->query("SELECT arg_usuarios.nombre AS atiende

                                        FROM `arg_entradas`                                         

                                        LEFT JOIN arg_usuarios

                                        	ON arg_usuarios.u_id = arg_entradas.usuario_id_atie

                                        WHERE trn_id = ".$trn_id) or die(mysqli_error());

            $usuario_atiende = $datos_at->fetch_assoc();
            

            $datos_res = $mysqli->query("SELECT arg_usuarios.nombre AS responsable

                                        FROM `arg_entradas`                                         

                                        LEFT JOIN arg_usuarios

                                        	ON arg_usuarios.u_id = arg_entradas.usuario_id_resp

                                        WHERE trn_id = ".$trn_id) or die(mysqli_error());

            $usuario_resp = $datos_res->fetch_assoc();     
            

            $visitantes = $mysqli->query("SELECT arg_usuarios.nombre AS visitante, usuarios_doc.imss AS ine

                                            FROM `arg_entradas_detalle`                                         

                                            LEFT JOIN arg_usuarios

                                            	ON arg_entradas_detalle.usuario_id = arg_usuarios.u_id

                                            LEFT JOIN usuarios_doc

                                            	ON usuarios_doc.u_id = arg_entradas_detalle.usuario_id

                                                AND tipo_id = 2

                                            WHERE trn_id_rel =  ".$trn_id) or die(mysqli_error());
                                            

            $vehiculos = $mysqli->query("SELECT arg_usuarios_documentos.nombre, arg_usuarios_documentos.fecha_expira, arg_vehiculos.marca, arg_vehiculos.modelo, arg_vehiculos.placas

                                            FROM `arg_entradas` 

                                            LEFT JOIN arg_vehiculos

                                                ON arg_vehiculos.veh_id = arg_entradas.veh_id

                                            LEFT JOIN arg_usuarios_documentos

                                            	ON arg_entradas.usuario_id = arg_usuarios_documentos.u_id

                                                AND tipo_id = 3

                                                AND arg_usuarios_documentos.activo = 1

                                            WHERE trn_id = ".$trn_id) or die(mysqli_error());
                                            

            $herramientas = $mysqli->query("SELECT arg_entradas_herramientas.cantidad, arg_herramientas.nombre, arg_herramientas.marca

                                                FROM `arg_entradas_herramientas` 

                                                LEFT JOIN arg_herramientas

                                                	ON arg_herramientas.herr_id = arg_entradas_herramientas.herr_id

                                                WHERE trn_id_rel = ".$trn_id) or die(mysqli_error());         

            ?>

             <div class="container">
                    <h3>                         

                 <? 

                    echo ("Folio Visita: ".$entrada['folio']);

                 ?>

                 </h3><?

                 $html_en = "<table class='table table-bordered' id='encabezado'>

                             <thead>

                                 <tr class='table-secondary'>   

                                    <th scope='col'>Unidad de Mina: ".$entrada['unidad']."</th>

                                     <th scope='col'>Areas: ";

                                     	while ($fila = $datos_areas->fetch_assoc()) {

                                    		$html_en.="<a>".$fila['area'].", "."</a>";

                                    	}

                                     

                                     $html_en.="</th>

                                     <th scope='col'>Desde: ".$entrada['fecha_inicio']."</th>

                                    <th scope='col'>Hasta: ".$entrada['fecha_final']."</th>

                                  </tr>

                                  <tr>            

                                    <th scope='col'>Registr&oacute: ".$entrada['usuario']."</th>

                                    <th scope='col'>Atiende: ".$usuario_atiende['atiende']."</th>

                                    <th scope='col'>Responsable: ".$usuario_resp['responsable']."</th>

                                  </tr>";

                    

                  $html_m = "<table class='table table-bordered' id='motivos'>

                                <thead>

                                <tr class='bg-info'>            

                                    <th scope='col'>Motivos de la Visita</th>

                                </tr>

                            </thead>

                            <tbody>

                            <td>";  

                                

                                               

                            	while ($fila = $datos_v->fetch_assoc()) { 
                            		$html_m.="<a>".$fila['nombre'].", "."</a>";
                            	}

                  $html_m.="</td></tbody></table>";

                  $html_c = "<table class='table table-bordered' id='comentarios'>

                                <thead>

                                <tr>            

                                    <th scope='col'>Comentario General</th>

                                </tr>

                            </thead>

                            <tbody>";   

                            		$html_c.="<tr><td>".$entrada['comentario']."</td></tr>";

                  $html_c.="</tbody></table>";

                  $html_v = "<table class='table table-bordered' id='visitantes'>

                                <thead>

                                <tr class='bg-info'>            

                                    <th scope='col'>Otros Visitantes</th>

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

                  $html_v.="</tbody></table>";

                  $html_veh = "<table class='table table-bordered' id='visitantes'>

                                <thead>

                                <tr class='bg-info'>            

                                    <th scope='col'>Licencia</th>

                                    <th scope='col'>Marca</th>

                                    <th scope='col'>Modelo</th>

                                    <th scope='col'>Placa</th>

                                </tr>

                            </thead>

                            <tbody>";                    

                            	while ($fila_ve = $vehiculos->fetch_assoc()) {

                            		$html_veh.="<tr>

                                                <td>".$fila_ve['nombre']."</td>

                                                <td>".$fila_ve['marca']."</td>

                                                <td>".$fila_ve['modelo']."</td>

                                                <td>".$fila_ve['placas']."</td>

                                             </tr>";

                            	}

                  $html_veh.="</tbody></table>";

                  $html_veh.="<table class='table table-bordered' id='visitantes'>

                                <thead>

                                <tr class='bg-info'>            

                                    <th scope='col'>Cantidad</th>

                                    <th scope='col'>Herramienta</th>

                                    <th scope='col'>Marca</th>

                                </tr>

                            </thead>

                            <tbody>";                    

                            	while ($fila_herr = $herramientas->fetch_assoc()) {

                            		$html_veh.="<tr>

                                                <td>".$fila_herr['cantidad']."</td>

                                                <td>".$fila_herr['nombre']."</td>

                                                <td>".$fila_herr['marca']."</td>

                                             </tr>";

                            	}

                  $html_veh.="</tbody></table>";

                      echo utf8_decode("$html_en");

                      echo utf8_decode("$html_m");

                      echo utf8_decode("$html_c");

                      echo utf8_decode("$html_v");

                      echo utf8_decode("$html_veh");
                      
                  /*  $respuesta  = $html_en;
                    $respuesta .= $html_m;
                    $respuesta .= $html_c;
                    $respuesta .= $html_v;
                    $respuesta .= $html_veh;
                    echo ($respuesta);*/
                ?>

              

           <div class="container">

           <div class="col-4 col-md-11 col-lg-11">

                <div class="col-4 col-md-4 col-lg-4">

                     <form method="post" action="print_doc.php?trn_id=<?echo $trn_id;  ?>" target="_blank" name="Printform" id="Printform">  

                        <fieldset>  

                            <input type="submit" class="btn btn-success" name="print" id="print" value="Imprimir Visita" />                

                       </fieldset>  

                    </form> 

                </div>

                

                <div class="col-4 col-md-4 col-lg-4">

                    <form method="post" action="print_herr.php?trn_id=<?echo $trn_id;?>" target="_blank" name="Printherr" id="Printherr">  

                        <fieldset>  

                            <input type="submit" class="btn btn-warning" name="Printherram" id="Printherram" value="Imprimir Herramientas" />                

                       </fieldset>  

                    </form> 

                </div>

                  

                <div class="col-4 col-md-4 col-lg-4">

                      <form method="post" action="app.php" name="newform" id="newform">  

                            <fieldset>  

                                <input type="submit" class="btn btn-info" name="nueva_visita" id="nueva_visita" value="Nueva visita" />                

                           </fieldset>  

                        </form>

                </div>

             </div>

             </div>

            <?


        }

         

    }

    else{  

        if(($_SESSION['LoggedIn']) <> '' and $trn_id_rel == '')

        {          

            $user_fir = $mysqli->query("SELECT arg_usuarios_documentos.nombre FROM `arg_usuarios_documentos` 

                                        LEFT JOIN arg_tipo_documentos

                                        	ON arg_tipo_documentos.tipo_id = arg_usuarios_documentos.tipo_id   

                                        	AND arg_usuarios_documentos.activo = 1

                                        WHERE arg_tipo_documentos.tipo_id = 1

                                        AND activo = 1

                                        AND u_id = ".$_SESSION['u_id']) or die(mysqli_error());

            $user_firmado = $user_fir ->fetch_array(MYSQLI_ASSOC);

            $seguro = $user_firmado['nombre'];

            

            $_SESSION['seguro'] = $seguro;

            

            $user_fir1 = $mysqli->query("SELECT arg_usuarios_documentos.nombre FROM `arg_usuarios_documentos` 

                                        LEFT JOIN arg_tipo_documentos

                                        	ON arg_tipo_documentos.tipo_id = arg_usuarios_documentos.tipo_id   

                                        WHERE arg_tipo_documentos.tipo_id = 2

                                        AND activo = 1

                                        AND u_id = ".$_SESSION['u_id']) or die(mysqli_error());

            $user_firmado1 = $user_fir1 ->fetch_array(MYSQLI_ASSOC);

            $ine = $user_firmado1['nombre'];

            

            $user_fir2 = $mysqli->query("SELECT arg_usuarios_documentos.nombre FROM `arg_usuarios_documentos` 

                                        LEFT JOIN arg_tipo_documentos

                                        	ON arg_tipo_documentos.tipo_id = arg_usuarios_documentos.tipo_id   

                                        WHERE arg_tipo_documentos.tipo_id = 3

                                        AND activo = 1

                                        AND u_id = ".$_SESSION['u_id']) or die(mysqli_error());

            $user_firmado3 = $user_fir2 ->fetch_array(MYSQLI_ASSOC);

            $licencia = $user_firmado3['nombre'];

            ?>  

                <div class="container">

                <div class="col-4 col-md-12 col-lg-12">

                

                <form method="post" action="app.php" name="Visitaform" id="Visitaform">  

                <fieldset>  

                     <div class="container" class="col-md-12 col-lg-12">

                        <div class="container" class="col-md-12 col-lg-12">            

                                <div class="col-md-7 col-lg-7 bg-info text-white text-center">

                                    <label>MOTIVOS DE LA VISITA</label>

                                     <input type="button" class="btn btn-warning" name="agregar_motivo" id="agregar_motivo" onclick="agregarMot();" value="+ Agregar Motivo" /> 

                               

                                </div>

                              <br/>

                              <br/>

                        </div>

                        <div class="col-md-3 col-lg-3">                    

                                       <?

                                            $u_id = $_SESSION['u_id'];

                                            $motivotop = $_GET['actividad'];

                                            if ($motivotop == "")

                                                $motivot = "Motivo de la visita";

                                                  

                                            echo ("<select name=\"actividad0\" id=\"actividad0\" class=\"form-control\"  > ");        

                                            echo ("<option value=$nomtop>$motivot</option>");

                                                    

                                            $result = $mysqli->query("SELECT act_id, nombre FROM arg_actividad") or die(mysqli_error());

                                            while( $row = $result ->fetch_array(MYSQLI_ASSOC)) 

                                            

                                            {

                                                $actividad =($row["nombre"]);

                                                $act_id = $row["act_id"];

                                                echo ("<option value=$act_id>$actividad</option>");

                                            }          

                                            echo ("</select><br />");

                                       ?>  

                        </div>

                                                                                      

                             <br/>

                             <br/>

                             <br/>

                            <div class="container">                                

                                        <?echo ("<div class=\"maintable\">

                                            <table class=\"sortable\"id=\"tbmotivo\"cellspacing=\0;>

                                            <thead>

                                               

                                            </thead>

                                            <body>"); 

                                            echo ("<tr>"); 

                                           

                                            echo ("</tr>"); 

                                            echo("</body></table></div> ");

                                            echo ("</br>");

                                        ?>

                            </div>

                            <div class="col-md-5 col-lg-5">

                                <input type="text" name="comentario_visita" class="form-control" id="comentario_visita" placeholder="Comentario General" />

                            </div>  

                   

                                <br/>                                         

                                <br/>

                                <br/>

                       

                        <div class="container" class="col-md-12 col-lg-12">

                            <div class="col-md-7 col-lg-7 bg-info text-white text-center">

                                    <label>DATOS GENERALES</label>

                                     <input type="button" class="btn btn-warning" name="agregar_area" id="agregar_area" onclick="agregarArea();" value="+ Agregar Area" /> 

                            </div>

                            <br/>

                         </div>                                                                        

                         <br />                                                

                          

                          <div class="col-md-11 col-lg-11">

                              <div class="col-md-2 col-lg-2">

                                    <?                           

                                    $organizaciontop = $_GET['organizacion'];

                                    if ($organizaciontop == "")

                                    $nombretop = "Unidad Mina";

                                  

                                    echo ("<form name=\"Busqueda\" id=\"Busqueda\">");

                                   

                                    echo ("<select name=\"organizacion\" id=\"organizacion\" onchange=\"actualizar_areas(".$u_id.")\" class=\"form-control\" > ");        

                                    echo ("<option value=$nomtop>$nombretop</option>");                            

                                    

                                    $result = $mysqli->query("SELECT unidad_id, Nombre FROM arg_empr_unidades") or die(mysqli_error());

                                    while( $row = $result ->fetch_array(MYSQLI_ASSOC)) 

                                      

                                      {

                                      $nombre =($row["Nombre"]);

                                      $nomenclatura = $row["unidad_id"];

                                      

                                      echo ("<option value=$nomenclatura>$nombre</option>");

                                      }          

                                              echo ("</select>");   

                           

                                    ?>

                            </div>

                       

                        <div class="col-md-2 col-lg-2">

                                    <?                

                                    $empleadotop = $_GET['empleado'];

                                    if ($empleadotop == "")

                                    $emptop = "Areas";

                                  

                                    //echo ("<form name=\"empleado_busqueda\" id=\"empleado_busqueda\">");

                                   

                                    echo ("<select name=\"area0\" id=\"area0\" class=\"form-control\" > ");        

                                    echo ("<option value=$nomtop>$emptop</option>");

                                    

                                    echo ("</select>");

                                     ?>  

                             </div>

                       

                       <div class="col-md-2 col-lg-2">

                                    <?                

                                    $empleadotop = $_GET['empleado'];

                                    if ($empleadotop == "")

                                    $emptop = "A quien visita";

                                  

                                    //echo ("<form name=\"empleado_busqueda\" id=\"empleado_busqueda\">");

                                   

                                    echo ("<select name=\"empleado\" id=\"empleado\" class=\"form-control\" > ");        

                                    echo ("<option value=$nomtop>$emptop</option>");

                                    

                                    

                                    $result = $mysqli->query("SELECT u_id, Nombre FROM arg_usuarios WHERE division = 'empleado' AND activo = 1 ORDER BY Nombre") or die(mysqli_error());

                                    while( $row = $result ->fetch_array(MYSQLI_ASSOC)) 

                                      

                                      {

                                      $nombre =($row["Nombre"]);

                                      $nomenclatura = $row["u_id"];

                                      

                                      echo ("<option value=$nomenclatura>$nombre</option>");

                                      }          

                                              echo ("</select>");

                                     ?>  

                         </div>

                         

                         <div class="col-md-2 col-lg-2">

                                       <?

                                            $empleadotop = $_GET['empleado'];

                                            if ($empleadotop == "")

                                                $emptop = "Responsable visita";

                                                  

                                            echo ("<select name=\"empleado_visita\" id=\"empleado_visita\" class=\"form-control\" > ");        

                                            echo ("<option value=$nomtop>$emptop</option>");

                                                    

                                            $result = $mysqli->query("SELECT u_id, Nombre FROM arg_usuarios WHERE division = 'empleado' AND activo = 1 ORDER BY nombre") or die(mysqli_error());

                                            while( $row = $result ->fetch_array(MYSQLI_ASSOC)) 

                                            

                                            {

                                                $nombre =($row["Nombre"]);

                                                $nomenclatura = $row["u_id"];

                                                echo ("<option value=$nomenclatura>$nombre</option>");

                                            }          

                                            echo ("</select>");

                                       ?>                   

                       </div>

                      </div>

                            

                      <br /> 

                      <br /> 

                           

                    <div class="container">                              

                                        <?echo ("<div>

                                                    <div class=\"maintable\">

                                            <table class=\"sortable\"id=\"tbarea\"cellspacing=\0;>

                                            

                                            <div><body></div>"); 

                                            echo ("<div><tr>"); 

                                           

                                            echo ("</tr></div>"); 

                                            echo("</body></table></div></div> ");

                                        ?>

                    </div>

                             

                             <div class="col-md-2 col-lg-2">                            

                                <label for="fecha_inicial"><b>Visita Desde:</b></label><br/>

                                <?  $fecha_minima_val = date('Y-m-j');

                                    $nuevafecha = strtotime ( '+0 day' , strtotime ( $fecha_minima_val ) ) ;

                                    $nuevafecha = date ( 'Y-m-d' , $nuevafecha );                                

                                    //echo $nuevafecha; //2020-12-31

                                ?>

                                <input type="date" name="fecha_inicial" class="form-control" id="fecha_inicial" onchange="ValidaVigencias(<?echo $u_id;?>);" min="<?echo $nuevafecha;?>"/>

                          </div>

                          <div class="col-md-2 col-lg-2">

                                <label for="fecha_final"><b>Hasta:</b></label><br/>

                                <input type="date" name="fecha_final" class="form-control" id="fecha_final" onchange="ValidaVigenciasFin(<?echo $u_id;?>);" min="<?echo $nuevafecha;?>"/>                                

                          </div>

                          <br/><br/>

                                

                             

                          <!--   </div>  Fin de datos generales--!>                                                            

                         <br />   

                          <br /> 

                           <br />

                           <br />

                               <div class="container" class="col-md-12 col-lg-12">

                                    <div class="col-md-7 col-lg-7 bg-info text-white text-center">

                                        <label>DATOS DE LOS VISITANTES</label>

                                            <input type="button" class="btn btn-warning" name="agregar" id="agregar" onclick="agregarFila(<?echo $cont_click=$cont_click+1?>);" value="+ Agregar Visitante" /> 

                                    </div>

                               </div>

                              

                                <?echo ("<div class=\"maintable\">

                                        <table class=\"sortable\"id=\"tbdoctor\"cellspacing=\0;>

                                        

                                        <body>");

                                            echo ("<tr>");       

                                            echo ("<div class=\"col-md-2 col-lg-2\">");

                                            echo ("<label for=\"usuario_id\">Nombre<label>");

                                            echo ("<input type=\"usuario_id\" name=\"usuario_id\" placeholder=\"Nombre\" class=\"form-control\" value=\"".$_SESSION['nombre']."\" id=\"usuario_id\" readonly /></div>");

                                            echo ("<div class=\"col-md-2 col-lg-2\">");

                                            echo ("<label for=\"usuario_id\">IMSS/Gastos MM<label>");

                                            echo ("<input type=\"seguro\" name=\"seguro\" placeholder=\"IMSS-Gastos MM\" class=\"form-control\" value=\"".$_SESSION['seguro']."\" id=\"seguro\" readonly/></div>");

                                            echo ("<div class=\"col-md-2 col-lg-2\">");

                                            echo ("<label for=\"usuario_id\">INE<label>");

                                            echo ("<input type=\"ine\" name=\"ine\" placeholder=\"INE\" class=\"form-control\" value=\"".$ine."\" id=\"ine\" readonly /></div><br/>");

                                            echo ("</tr>");    

                                            echo ("</br>");    

                                            echo ("</br>"); 

                                            echo ("</br>");         

                                        echo("</body></table></div> <br/>");

                                ?>

                                              

                        <!--    </div>   Fin de visitantes--!>

                                        

                                    <div class="container" class="col-md-10 col-lg-10">

                                        <div class="col-md-7 col-lg-7 bg-info text-white text-center">

                                            <label>DATOS DEL VEHÍCULO</label>

                                        </div>                                        

                                        <br/>

                                    </div> 

                                    <div class="col-md-2 col-lg-2">     

                                        <label for="licencia_id">Licencia: </label>                           

                                            <input type="licencia_id" name="licencia_id" placeholder="Licencia" value="<?echo $licencia;?>" class="form-control" id="licencia_id" readonly />

                                    </div>

                                    <div class="col-md-1 col-lg-1"> 

                                    </div>

                                        

                                    <div id="content" class="col-lg-3">

                                    		<label for="placas">Placas: </label>

                                            <span class="input-group-btn" >

                                            <div class="input-group">                            

                                                <?

                                                    $placastop = $_GET['placas'];

                                                    if ($placastop == ""){

                                                        $nomtop = "0";

                                                        $placastop = "Seleccione un vehículo...";

                                                    }

                                                  

                                                    echo ("<select name=\"placas\" id=\"placas\" class=\"form-control\" onchange=\"ShowSelected();\" > ");

                                                    echo ("<option value=$nomtop>$placastop</option>");

                                                    $result_pl = $mysqli->query("SELECT veh_id, placas FROM arg_vehiculos WHERE org_id = ".$_SESSION['org_id']);

                                                    while( $row = $result_pl ->fetch_array(MYSQLI_ASSOC))                                              

                                                      {

                                                        $nombre =($row["placas"]);

                                                        $nomenclatura = $row["veh_id"];

                                                        echo ("<option value=$nomenclatura>$nombre</option>");

                                                      }          

                                                      echo ("</select><br /><br />");

                                                ?>                 

                                                <a href='' name="idsele" class="btn btn-primary" data-toggle="modal" data-target="#ModalVeh"  class="sepV_a" title="Nuevo vehículo">+ </a>

                                            </span>

                                            	</div>

                                      </div>

                                      <div class="col-md-11 col-lg-11 derecha" id="placas_dat"></div>

                               <br /> <br /> <br /> 

                                <br /> 

                            

                              <div class="container" class="col-md-10 col-lg-10">

                                        <div class="col-md-7 col-lg-7 bg-info text-white text-center">

                                            <label>LISTA HERRAMIENTAS</label>

                                            <input type="botton" class="btn btn-warning" name="agregar_herr" id="agregar_herr" onclick="agregarFilaHerr();" value="+ Agregar Herramienta" readonly />                                        

                                        </div>       

                                        <br /> 

                                        <br />  

                              </div>

                                    <br/> 

                                  <div class="container" class="col-md-5 col-lg-5">                                   

                                    <div class="col-md-2 col-lg-2"> 

                                             <input name="cantidad0" id="cantidad0" placeholder="Cantidad" class = "form-control" value="" enabled />                                             

                                    </div>

                                    <div class="col-md-1 col-lg-1"> 

                                    </div>

                                    <div class="col-md-3 col-lg-3">  

                                    

                                            <span class="input-group-btn" >

                                            <div class="input-group">                            	

                                                <?

                                                    $herrtop = $_GET['herramientas'];

                                                    if ($herrtop == "")

                                                    $herrtop = "Herramientas...";

                                                  

                                                    echo ("<select name=\"busqueda_herr0\" id=\"busqueda_herr0\" class=\"form-control\" onchange=\"Showherr();\" > ");

                                                    echo ("<option value=$nomtop>$herrtop</option>");   

                                                    $result_herr = $mysqli->query("SELECT herr_id, nombre FROM arg_herramientas WHERE org_id =".$_SESSION['org_id']." AND activo = 1");

                                                    while( $row = $result_herr ->fetch_array(MYSQLI_ASSOC)) 

                                                      

                                                      {

                                                        $nombre =($row["nombre"]);

                                                        $nomenclatura = $row["herr_id"];                                              

                                                        echo ("<option value=$nomenclatura>$nombre</option>");

                                                      }          

                                                      echo ("</select><br/>");

                                                ?>                                                           

                                                <a href='' name="herr" class="btn btn-primary" data-toggle="modal" data-target="#Modalherr"  class="sepV_a" title="Nueva Herramienta">+ </a>

                                            </span>

                                           	</div>

                            	     </div>    

                                  </div> 

                                  <br/>

                                  <div class="container-fluid">                                

                                        <?echo ("<div class=\"maintable\">

                                            <table class=\"sortable\"id=\"tbherr\"cellspacing=\0;>

                                            <thead>

                                               

                                            </thead>

                                            <body>"); 

                                            echo ("<tr>"); 

                                           

                                            echo ("</tr>"); 

                                            echo("</body></table></div> ");

                                            echo ("</br>");

                                        ?>

                                    </div>  

                                    

                           </div>   

                           

                      </div>

                       <div class="container">

                             <div class="col-md-6 col-lg-6">   

                                <br/>

                                <input type="submit" class="btn btn-primary" name="visita" id="visita" value="Registrar Visita" />                            

                            </div>

                        </div>                                 

                                 <? 	

            }

             ?>              

                              

                        

               </div> 

               </fieldset>  

            </form> 

          

          </div>

        </div>           

      <?      

    }

  

  //}

?>                    

    

<script type="text/javascript" src="js/jquery.min.js"></script>

<!--<script type="text/javascript" src="js/vehiculos.js"></script>-->  

          



