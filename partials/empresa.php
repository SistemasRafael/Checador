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
    function redireccion() {
        var print_d = '<?php echo "\ empresa.php" ?>';
        window.location.href = print_d;
    }

    function ActualizarEmpresa() {
        var calle = document.getElementById("calle").value;
        var numero = document.getElementById("numero").value;
        var colonia = document.getElementById("colonia").value;
        var localidad = document.getElementById("localidad").value;
        var ciudad = document.getElementById("ciudad").value;
        var cp = document.getElementById("cp").value;
        var prov_ad = document.getElementById("prov_admin").value;
        var org_id = document.getElementById("idsele").value;
        $.ajax({
                url: 'editar_empresa.php',
                type: 'POST',
                dataType: 'html',
                data: {
                    org_id:org_id,
                    calle: calle,
                    numero: numero,
                    colonia: colonia,
                    localidad: localidad,
                    ciudad: ciudad,
                    cp: cp,
                    prov_ad: prov_ad,
                },
            })
            .done(function(respuesta) {
                if (respuesta == 'Se guardaron los cambios correctamente') {
                    alert(respuesta);
                } else {
                    alert('Ocurrió un error.Reintente');

                }
                redireccion();

            })
    }
    
    function Actualizarsisdo(opcion_sel) {
        var respu_sel = opcion_sel;
        var user_proveedor = document.getElementById("user_prov").value;
        $.ajax({
                url: 'editar_sisdo.php',
                type: 'POST',
                dataType: 'html',
                data: {                  
                    respu_sel: respu_sel,
                },
            })
            .done(function(respuesta) {
                if (respuesta == 'Se guardaron los cambios correctamente') {
                    alert(respuesta);
                    if (respu_sel == 1) {
                        enviar_notificacion(user_proveedor);
                    }
                } else {
                    alert('Ocurrió un error. Reintente');

                }
                redireccion();

            })
    }
    
     function enviar_notificacion(user_proveedor)
        {    
                var usuario_actualiza = user_proveedor;
              
                $.ajax({
            		url: 'enviar_notificacion_sisdo.php' ,
            		type: 'POST' ,
            		dataType: 'html',
            		data: {usuario_actualiza:usuario_actualiza},
            	})
            	.done(function(respuesta){
            	   
                })
        }
   
</script>

<?php 
//$_SESSION['LoggedIn'] = 1; 
if (($_SESSION['LoggedIn']) == 1) {
    $tipo = $_GET['tipo'] ?? 0;
    $unidad_mina_sel = $_GET['unidad'] ?? 0;
    $u_id = $_SESSION['u_id'];
    
    $vigilancia = $mysqli->query("SELECT (CASE WHEN division = 'vigilante' THEN 1 ELSE 0 END) AS tipo_user FROM arg_usuarios WHERE u_id = ".$u_id) or die(mysqli_error($mysqli));
    $vigilancia_v = $vigilancia->fetch_assoc();
    $vigilancia_ver = $vigilancia_v['tipo_user'];

   // $mysqli->set_charset("utf8");
    
    $check_id = $mysqli->query("SELECT division, org_id, administrador FROM arg_usuarios WHERE u_id = " . $u_id);
    $row_uid = $check_id->fetch_array(MYSQLI_ASSOC);
    
    $u_id_tipo = $row_uid['division'];
    $org_id = $row_uid['org_id'];
    $admin_user = $row_uid['administrador'];
    //echo 'orgA'.$org_id;
    
    $prov_administ = $mysqli->query("SELECT usuario_administrador FROM arg_organizaciones WHERE usuario_administrador = ".$u_id." AND org_id = ".$org_id);
    $row_provadmin = $prov_administ->fetch_array(MYSQLI_ASSOC);
    $row_provadministrador = $row_provadmin['usuario_administrador'] ?? null;
    
    $nombre = $mysqli->query("SELECT nombre FROM arg_organizaciones WHERE org_id = " . $org_id);
    $row_diu = $nombre->fetch_array(MYSQLI_ASSOC);
    $empresa = $row_diu['nombre'] ?? '';
?>

<!-- Modal empresa  -->

<div class="modal fade" id="ModalEmpresa" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="ModalEmpresa">Editar Empresa </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-header">
                <h6 class="modal-subtitle" style="color:#996633" id="exampleModal">Actualice los datos generales de la empresa</h6>
            </div>
            <div class="modal-body">


                <table class='table text-black' id='tbempresas'>
                    <thead class='table-secondary' align='center'>
                        <tr>
                            <input name="idsele" type='hidden' id="idsele" value="" />
                            <label for="Calle" class="col-form-label">Calle:</label>
                            <input name="calle" id="calle" size=40 style="width:470px; color:#996633" value="" enabled />
                            <label for="numero" class="col-form-label">Numero:</label>
                            <input name="numero" id="numero" size=40 style="width:470px; color:#996633" value="" enabled />
                            <label for="colonia" class="col-form-label">Colonia:</label>
                            <input name="colonia" id="colonia" size=40 style="width:470px; color:#996633" value="" enabled />
                            <br />
                            <label for="ciudad" class="col-form-label">Ciudad:</label>
                            <?php 

                            $motivotop = $_GET['ciudad'] ?? '';
                            if ($motivotop == "")
                                $motivot = "Seleccione Ciudad";

                            echo ("<select name=\"ciudad\" id=\"ciudad\" class=\"form-control\" > ");
                            echo ("<option value=$nomtop>$motivot</option>");

                            $result = $mysqli->query("SELECT ciudad_id, ciudad FROM arg_ciudades") or die(mysqli_error($mysqli));
                            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                                $actividad = ($row["ciudad"]);
                                $act_id = $row["ciudad_id"];
                                echo ("<option value=$act_id>$actividad</option>");
                            }
                            echo ("</select><br />");
                            ?>

                            <label for="localidad" class="col-form-label">Localidad:</label>
                            <input name="localidad" id="localidad" size=40 style="width:470px; color:#996633" value="" enabled />

                            <label for="cp" class="col-form-label">CP:</label>
                            <input name="cp" id="cp" size=40 style="width:470px; color:#996633" value="" enabled />
                         
                             <label for="prov_admin" class="col-form-label">Usuario Administrador:</label>
                            <?php 
                           
                            $admin_prov = $_GET['admin_prov'] ?? '';
                            if (isset($admin_provtop) && $admin_provtop == "")
                                $admintop = "Seleccione Usuario Administrador";

                            echo ("<select name=\"prov_admin\" id=\"prov_admin\" class=\"form-control\" > ");
                            echo ("<option value=$admintop>$admintop</option>");

                            $result_pro = $mysqli->query("SELECT u_id, email FROM listado_proveedores WHERE org_id = ". $org_id) or die(mysqli_error($mysqli));
                            while ($row = $result_pro->fetch_array(MYSQLI_ASSOC)) {
                                $proveedor_email = ($row["email"]);
                                $prov_sel_id = $row["u_id"];
                                echo ("<option value=$prov_sel_id>$proveedor_email</option>");
                            }
                            echo ("</select><br />");
                            ?>
                            </tbody>
                    </thead>
                </table>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="ActualizarEmpresa()">Guardar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $("body").on("click", "#tbempresas a", function(event) {
        event.preventDefault();
        idsele = $(this).attr("href");

        //Cargamos los datos
        $("#idsele").val(idsele);
    });
    
</script>


<div class="modal fade" id="ModalSisdo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="ModalEmpresa">DOCUMENTACION FISCAL</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-header">
                <h6 class="modal-subtitle" style="color:#996633" id="exampleModal">Lea cuidadosamente.</h6>
            </div>
            <div class="modal-body">


                <table class='table text-black' id='tbempresas'>
                    <thead class='table-secondary' align='center'>
                        <tr>
                            <input name="user_prov" type='hidden' id="user_prov" value="<?php echo $u_id?>" />
                            <label for="Calle" class="col-form-label">Si su empresa ya realizó el registro y actualización de sus datos fiscales en el portal de SISDO.PRO, favor de seleccionar la opción SI, en caso contrario de click al siguiente LINK y actualice sus datos:</label><br/><br/>
                            
                            
                               <a href="https://ag.sisdo.pro/" id = 'sisdoprolink' name = 'sisdoprolink' target="_blank">https://ag.sisdo.pro/</a><br/><br/>
          
                               <label for="lab" class="col-form-label">Usted puede recibir recordatorios a su email en tanto actualizan su información.</label><br/>
                              <td> </td> <td> <label for="lab" class="col-form-label">Atentamente:</label> </td>
                              <td> </td> <td> <label for="lab2" class="col-form-label">Dirección de Compras/Impuestos</label></td> 
                            
                            </tbody>
                    </thead>
                </table>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="Actualizarsisdo(1)">SI</button>
                    
                    <button type="button" class="btn btn-warning" onclick="Actualizarsisdo(0)">NO</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
        <div class="row">

            <?php  if ($vigilancia_ver == 0) { ?>
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
                            <ul class="dropdown-menu" role="menu">
                                <li> <a href="empresa.php">
                                        <h5>Datos generales</h5>
                                    </a> </li>
                                <li class="divider"></li>
                                <li> <a href="doc_personas.php">
                                        <h5>Documentación de Personas</h5>
                                    </a> </li>
                                <li> <a href="doc_vehiculos.php">
                                        <h5>Documentación de Vehículos</h5>
                                    </a> </li>
                            </ul>
                        </div>
                    </div>
                </div>

            <?php  } ?>

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
                        <ul class="dropdown-menu" role="menu">
                            <li> <a href="app.php">
                                    <h5>Crear Visita</h5>
                                </a> </li>
                            <li class="divider"></li>
                            <li> <a href="dashboard.php?tipo=1&unidad=<?php  echo $unidad_mina_sel; ?>">
                                    <h5>Visitas</h5>
                                </a> </li>
                            <li> <a href="#">
                                    <h5>Cerradas</h5>
                                </a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <?php  if ($vigilancia_ver == 0) { ?>
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
                               
                            </ul>
                        </div>

                    </div>
                </div>

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
                            <ul class="dropdown-menu" role="menu">
                                <li> <a href="#">
                                        <h5>Información</h5>
                                    </a> </li>
                            </ul>
                        </div>

                    </div>
                </div>
            <?php  } ?>
        </div>
    </div>
    
    
    <br /> <br />
    <div class="container">
        <div class="col-md-9 col-lg-9">
            <div class="col-md-3 col-lg-3">
                <a href="empresa.php?tipo=0&unidad=<?php  echo $unidad_mina_sel; ?>">Empresas</a>
            </div>

            <div class="col-md-3 col-lg-3">
                <a href="empresa.php?tipo=1&unidad=<?php  echo $unidad_mina_sel; ?>">Personas</a>
            </div>
            <div class="col-md-3 col-lg-3">
                <a href="empresa.php?tipo=2&unidad=<?php  echo $unidad_mina_sel; ?>">Vehículos</a>
            </div>
            <div class="col-md-3 col-lg-3">
                <a href="empresa.php?tipo=3&unidad=<?php  echo $unidad_mina_sel; ?>">Herramientas</a>
            </div>
        </div>
    </div>


    <?php 


    //if ($u_id_tipo == 'empleado'){
    //$tipo = $_GET['tipo'];
    if ($tipo == 0) {  //Lista de empresas
   // echo 'tipo'.$tipo;
        mysqli_multi_query($mysqli, "CALL visor_empresas (" . $org_id . ")") or die(mysqli_error($mysqli));
    ?>


        <br /> <br />

        <?php  if ($u_id_tipo == 'empleado') { ?>
            <div class="container">
                <table>
                    <th>
                        <input name="caja_bus_prov" id="caja_bus_prov" size=40 style="width:470px; color:#337AB7" placeholder="Nombre de la Empresa:" enabled />
                    </th>
                    <th>
                        <button type="button" class="btn btn-primary" onclick="ActualizarTablaEmpresas()">Filtrar</button>

                    </th>

                </table>
                <div id=datos_prov></div>
            </div>
        <?php  } ?>
        <br /> <br />

        <div class="container">
            <div id="BusquedaEmpresas">
                <?php 
                $html_en = "<table class='table table-bordered' id='tbempresas'>
                                     <thead>
                                         <tr class='table-info'>   
                                            <th scope='col'>Empresa</th>
                                            <th scope='col'>RFC</th>
                                            <th scope='col'>Calle</th>
                                            <th scope='col'>Colonia</th>
                                            <th scope='col'>Ciudad</th>
                                            <th scope='col'>Localidad</th>
                                            <th scope='col'>CP</th>
                                            <th scope='col'>SISDO Actualizado</th>                                            
                                            <th scope='col'>Administrador</th>";
                                             if($org_id <> 0){
                                            $html_en .= "<th scope='col'>Acción</th>";
                                             }
                                         $html_en .= "</tr>
                                      </thead>
                                      <tbody>";
                if ($result = mysqli_store_result($mysqli)) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $id_org = $row["org_id"];
                        $sisdo_upd = $row["sisdo_pro"];
                        if($sisdo_upd == 0){
                            $sisdo_upd_text = 'NO';
                        }
                        else{
                            $sisdo_upd_text = 'SI';                            
                        }
                        $html_en .= "<tr>
                                                        <td> " . $row["empresa"] . "</td>
                                                        <td> " . $row["rfc"] . "</td>
                                                        <td> " . $row["calle"] . "</td>
                                                        <td> " . $row["colonia"] . "</td>
                                                        <td> " . $row["ciudad"] . "</td>
                                                        <td> " . $row["localidad"] . "</td>
                                                        <td> " . $row["codigo_postal"] . "</td>";
                                                        $html_en .= "<td> <a href='$id_org' id = 'sisdopro' name = 'sisdopro' class='btn btn-success' data-toggle='modal' data-target='#ModalSisdo' class='sepV_a' title='Si su información se encuentra actualizada en sisdo.pro favor de indicar SI'>$sisdo_upd_text</a></td>
          
                                                        <td> " . $row["usuario_administrador"] . "</td>";
          if($org_id <> 0){
            $html_en .= "<td> <a href='$id_org' id = 'idsele' name = 'idsele' class='btn btn-success' data-toggle='modal' data-target='#ModalEmpresa' class='sepV_a' title='Empresa'>Editar</a></td>";
          }
          $html_en .= "</tr>";
                    }
                    mysqli_free_result($result);
                }
                $html_en .= "</tbody></table>";
                echo ("$html_en");
                ?>
            </div>
        </div>
    <?php 
    }
    
    if ($tipo == 1) {
        mysqli_multi_query($mysqli, "CALL visor_personas (" . $org_id . ")") or die(mysqli_error($mysqli));
    ?>
        <br /> <br />

        <div class="container">
            <table>
                <th>
                    <input name="caja_bus_prov" id="caja_bus_prov" size=40 style="width:470px; color:#337AB7" placeholder="Nombre de la Empresa:" enabled />
                </th>
                <th>
                    <button type="button" class="btn btn-primary" onclick="ActualizarTablaPersonas('<?php  echo $u_id_tipo ?>','<?php  echo $empresa ?>')">Filtrar</button>
                </th>

            </table>
            <div id=datos_prov></div>
            <div id=datos_usuarios></div>
        </div>

        <br /> <br />

        <div class="container-fluid">
            <div id=BusquedaPersonas>
                <?php 
                $html_per = "<table class='table table-bordered' id='encabezadop'>
                             <thead>
                                 <tr class='table-info'>   
                                    <th scope='col'>Empresa</th>
                                    <th scope='col'>Nombre</th>
                                    <th scope='col'>Email</th>
                                    <th scope='col'>INE</th>
                                    <th scope='col'>IMSS</th>
                                    <th scope='col'>Licencia</th>
                                    <th scope='col'>Cursos</th>";
                                if ($row_provadministrador <> 0 || $u_id_tipo == 'empleado' || $admin_user <> 0){
                                    $html_per .= "<th scope='col'>Documentos</th>";
                                 }
                                if ($row_provadministrador <> 0){
                                    $html_per .= "<th scope='col'>Administrador</th>";
                                 }

                                  $html_per .= "</tr>
                              </thead>
                              <tbody>";
                if ($result_per = mysqli_store_result($mysqli)) {
                    while ($row = mysqli_fetch_assoc($result_per)) {
                        ///validamos si la persona es o no administrador
                        $texto_admin = "SI";
                        if ($row['administrador'] == 0) {
                            $texto_admin = "NO";
                        }
                        
                        $html_per .= "<tr>
                                                <td> " . $row["empresa"] . "</td>
                                                <td> " . $row["nombre"] . "</td> 
                                                <td> " . $row["email"] . "</td>
                                                <td> " . $row["ine"] . "</td>
                                                <td> " . $row["seguro"] . "</td>
                                                <td> " . $row["licencia"] . "</td>
                                                <td> " . $row["cursos_vigentes"] . "</td>";
                                              if ($row_provadministrador <> 0 || $u_id_tipo == 'empleado' || $admin_user <> 0){
                                                $html_per .= "<td> <form action = 'doc_personas.php' method = 'POST'> 
                                                                <input type = 'hidden' name='u_id' value=".$row['u_id'].">
                                                                    <button class='btn btn-info btn-md' type='submit'>Ver</button>
                                                            </form> </td>";                                               
                                             }                                             
                                             if ($row_provadministrador <> 0){
                                               $html_per .= "<td> <button type='button' class='btn btn-info btn-md' data-dismiss='modal' aria-label='Close' onclick='GuardarAdmin(" . $row["u_id"] . ")''>" . $texto_admin . "</button> </td>"; 
                                             }
                                          $html_per .= "</tr>";
                    }
                    mysqli_free_result($result_per);
                }
                $html_per .= "</tbody></table>";
                echo ("$html_per");
                ?>
            </div>
        </div>
    <?php 
    }
    // Vehículos
    if ($tipo == 2) {
        $veh_ids = $mysqli->query(
            "SELECT veh_id FROM arg_vehiculos_checklist"
        ) or die(mysqli_error($mysqli));
        while ($row = $veh_ids->fetch_assoc()) {
            $ids[] = $row['veh_id'];
        }
        mysqli_multi_query($mysqli, "CALL visor_vehiculos (" . $org_id . ")") or die(mysqli_error($mysqli));
    ?>
        <br /> <br />
        <?php  if ($u_id_tipo == 'empleado') { ?>
            <div class="container">
                <table>
                    <th>
                        <input name="caja_bus_prov" id="caja_bus_prov" size=40 style="width:470px; color:#337AB7" placeholder="Nombre de la Empresa:" enabled />
                    </th>
                    <th>
                        <button type="button" class="btn btn-primary" onclick="ActualizarTablaVehiculos()">Filtrar</button>
                    </th>

                </table>
                <div id=datos_prov></div>
            </div>
        <?php  } ?>
        <br /> <br />

        <div class="container">
            <div id=BusquedaVehiculos>
                <?php 
                $html_per = "<table class='table table-bordered' id='encabezadop'>
                             <thead>
                                 <tr class='table-info'>   
                                    <th scope='col'>Empresa</th>
                                    <th scope='col'>Placas</th>
                                    <th scope='col'>Marca</th>
                                    <th scope='col'>Modelo</th>
                                    <th scope='col'>Color</th>
                                    <th scope='col'>Póliza</th>
                                    <th scope='col'>Agregar</th>
                                  </tr>
                              </thead>
                              <tbody>";
                if ($result_per = mysqli_store_result($mysqli)) {
                    while ($row = mysqli_fetch_assoc($result_per)) {
                        $html_per .= "<tr>
                                                <td> " . $row["empresa"] . "</td>                                                
                                                <td><a href=\"doc_vehiculos.php?placas=" . $row["placas"] . "&unidad=" . $unidad_mina_sel . "\" ><font color=blue><b>" . $row["placas"] . "</a></td>
                                                <td> " . $row["marca"] . "</td>
                                                <td> " . $row["modelo"] . "</td>
                                                <td> " . $row["color"] . "</td>
                                                <td> " . $row["poliza"] . "</td>";
                        if (in_array($row['veh_id'], $ids)) {
                            $html_per .= "<td align='center'> <button type='button' class='open-vehModalVer btn btn-info btn-md' data-toggle='modal' data-target='#ModalVehVer' data-id='" . $row["veh_id"] . "'><i class='fa fa-check' aria-hidden='true'> Ver </i></button></td>";
                        } else {
                            $html_per .= "<td align='center'> <button type='button' class='open-vehModal btn btn-info btn-md' data-toggle='modal' data-target='#ModalVeh' data-id='" . $row["veh_id"] . "><i aria-hidden='true'> Agregar </i></button></td>";
                        }
                        $html_per .= "</tr>";
                    }
                    mysqli_free_result($result_per);
                }
                $html_per .= "</tbody></table>";
                echo ("$html_per");
                ?>
            </div>
        </div>
    <?php 
    }

    // Herramientas
    if ($tipo == 3) {
        mysqli_multi_query($mysqli, "CALL visor_herramientas (" . $org_id . ")") or die(mysqli_error($mysqli));
    ?>
        <br />
        <div class="container">
            <br />

            <td height="26" valign="medium">
                <a href='' name="idsele" class="btn btn-primary" data-toggle="modal" data-target="#ModalHerram" class="sepV_a" title="Nueva herramienta">+ AGREGAR HERRAMIENTA</a>
            </td>
            <br />
        </div>
        <div class="container">
            <?php 
            $html_per = "<table class='table table-bordered' id='encabezadop'>
                             <thead>
                                 <tr class='table-info'>   
                                    <th scope='col'>Empresa</th>
                                    <th scope='col'>Nombre</th>
                                    <th scope='col'>Marca</th>
                                    <th scope='col'>Modelo</th>
                                    <th scope='col'>Serie</th>
                                    <th scope='col'>Estado</th>
                                  </tr>
                              </thead>
                              <tbody>";
            if ($result_per = mysqli_store_result($mysqli)) {
                while ($row = mysqli_fetch_assoc($result_per)) {
                    $texto = "Desactivar";
                    if ($row['activo'] == 0) {
                        $texto = "Activar";
                    }
                    $html_per .= "<tr>
                                                <td> " . $row["empresa"] . "</td>
                                                <td> " . $row["nombre"] . "</td>
                                                <td> " . $row["marca"] . "</td>
                                                <td> " . $row["modelo"] . "</td>
                                                <td> " . $row["serie"] . "</td>
                                                <td> <button type='button' class='btn btn-info btn-md' data-dismiss='modal' aria-label='Close' onclick='GuardarActivo(" . $row['herr_id'] . ")''>" . $texto . "</button> </td>
                                             </tr>";
                }
                mysqli_free_result($result_per);
            }
            $html_per .= "</tbody></table>";
            echo ("$html_per");
            ?>
        </div>
    <?php 
    }


    //}
    ?>

    <br />
    <br />
    <br />
<?php 
} // Si estás conectado
?>

<div class="modal fade" id="ModalHerram" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="ModalHerram">Agregar Herramienta. </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form enctype="multipart/form-data" id="form1">
                    <label for="nombre" class="col-form-label">Nombre:</label>
                    <input name="nombre" id="nombre" size=40 style="width:470px; color:#996633" value="" enabled />
                    <label for="marca" class="col-form-label">Marca:</label>
                    <input name="marca" id="marca" size=40 style="width:470px; color:#996633" value="" enabled />
                    <label for="modelo" class="col-form-label">Modelo:</label>
                    <input name="modelo" id="modelo" size=40 style="width:470px; color:#996633" value="" enabled />
                    <label for="serie" class="col-form-label">Serie:</label>
                    <input name="serie" id="serie" size=40 style="width:470px; color:#996633" value="" enabled />
                    <label for="empresa" class="col-form-label">Empresa:</label>
                    <input name="empresa" id="empresa" size=40 style="width:470px; color:#996633" value="<?php  echo $empresa; ?>" disabled />
                    <label for="org" class="col-form-label" hidden>Organización:</label>
                    <input name="org" id="org" size=40 style="width:470px; color:#996633" value="<?php echo $org_id ?>" hidden disabled />
                    <br />
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="GuardarHerramienta()">Guardar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ModalVeh" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="ModalVeh">Agregar Especificaciones de Vehiculos </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form enctype="multipart/form-data" id="form2">
                    <label for="veh_id" class="col-form-label" hidden>Vehiculo:</label>
                    <input name="veh_id" id="veh_id" size=40 style="width:470px; color:#996633" value="" hidden />
                    <label for="carroceria" class="col-form-label">Estado de la Carroceria:</label>
                    <input name="carroceria" id="carroceria" size=40 style="width:470px; color:#996633" value="" enabled />
                    <label for="espejos" class="col-form-label">Estado de los Espejos:</label>
                    <input name="espejos" id="espejos" size=40 style="width:470px; color:#996633" value="" enabled />
                    <label for="parabrisas" class="col-form-label">Estado del Parabrisas:</label>
                    <input name="parabrisas" id="parabrisas" size=40 style="width:470px; color:#996633" value="" enabled />
                    <label for="limpiaparabrisas" class="col-form-label">Estado de Limpiaparabrisas:</label>
                    <input name="limpiaparabrisas" id="limpiaparabrisas" size=40 style="width:470px; color:#996633" value="" enabled />
                    <label for="triangulo" class="col-form-label">Triangulo de Seguridad:</label>
                    <input name="triangulo" id="triangulo" size=40 style="width:470px; color:#996633" value="" enabled />
                    <label for="cintas" class="col-form-label">Cintas Reflectivas:</label>
                    <input name="cintas" id="cintas" size=40 style="width:470px; color:#996633" value="" enabled />
                    <label for="llanta" class="col-form-label">Llanta de Repuesto, Gato y Cruceta:</label>
                    <input name="llanta" id="llanta" size=40 style="width:470px; color:#996633" value="" enabled />
                    <label for="eslingas" class="col-form-label">Eslingas:</label>
                    <input name="eslingas" id="eslingas" size=40 style="width:470px; color:#996633" value="" enabled />
                    <label for="cables" class="col-form-label">Cables pasa corriente:</label>
                    <input name="cables" id="cables" size=40 style="width:470px; color:#996633" value="" enabled />
                    <label for="limpieza" class="col-form-label">Limpieza:</label>
                    <input name="limpieza" id="limpieza" size=40 style="width:470px; color:#996633" value="" enabled />
                    <br />
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="GuardarVehiculo()">Guardar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ModalVehVer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="ModalVehVer">Actualizar Especificaciones de Vehiculos </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-ver">
                <form enctype="multipart/form-data" id="form3">
                    <label for="veh_id" class="col-form-label" hidden>Vehiculo:</label>
                    <input name="veh_id" id="veh_id" size=40 style="width:470px; color:#996633" value="" hidden />
                    <label for="carroceria" class="col-form-label">Estado de la Carroceria:</label>
                    <input name="carroceria" id="carroceria" size=40 style="width:470px; color:#996633" value="" enabled />
                    <label for="espejos" class="col-form-label">Estado de los Espejos:</label>
                    <input name="espejos" id="espejos" size=40 style="width:470px; color:#996633" value="" enabled />
                    <label for="parabrisas" class="col-form-label">Estado del Parabrisas:</label>
                    <input name="parabrisas" id="parabrisas" size=40 style="width:470px; color:#996633" value="" enabled />
                    <label for="limpiaparabrisas" class="col-form-label">Estado de Limpiaparabrisas:</label>
                    <input name="limpiaparabrisas" id="limpiaparabrisas" size=40 style="width:470px; color:#996633" value="" enabled />
                    <label for="triangulo" class="col-form-label">Triangulo de Seguridad:</label>
                    <input name="triangulo" id="triangulo" size=40 style="width:470px; color:#996633" value="" enabled />
                    <label for="cintas" class="col-form-label">Cintas Reflectivas:</label>
                    <input name="cintas" id="cintas" size=40 style="width:470px; color:#996633" value="" enabled />
                    <label for="llanta" class="col-form-label">Llanta de Repuesto, Gato y Cruceta:</label>
                    <input name="llanta" id="llanta" size=40 style="width:470px; color:#996633" value="" enabled />
                    <label for="eslingas" class="col-form-label">Eslingas:</label>
                    <input name="eslingas" id="eslingas" size=40 style="width:470px; color:#996633" value="" enabled />
                    <label for="cables" class="col-form-label">Cables pasa corriente:</label>
                    <input name="cables" id="cables" size=40 style="width:470px; color:#996633" value="" enabled />
                    <label for="limpieza" class="col-form-label">Limpieza:</label>
                    <input name="limpieza" id="limpieza" size=40 style="width:470px; color:#996633" value="" enabled />
                    <br />
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="ActualizarVehiculo()">Guardar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>


<script>
    function GuardarActivo(herr_id) {
        var herr_id = herr_id;
        const frm = new FormData();
        frm.append("herr_id", herr_id);
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4) {
                var msg = xhttp.responseText;
                if (msg == 'Se cambió correctamente el estado de la herramienta') {
                    alert(msg);
                    redireccion();
                } else {
                    alert(msg);
                }
            }
        };

        xhttp.open("POST", "cambiaractivo.php", true);
        xhttp.send(frm);
    }
    
    function GuardarAdmin(u_id_prov) {
        var u_id_provee = u_id_prov;
        const frm = new FormData();
        frm.append("u_id_provee", u_id_provee);
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4) {
                var msg = xhttp.responseText;
                if (msg == 'Se cambió el perfil del proveedor') {
                    alert(msg);
                    redireccion_per();
                } else {
                    alert(msg);
                }
            }
        };

        xhttp.open("POST", "cambiaradministrador.php", true);
        xhttp.send(frm);
    }
    
    function redireccion_per() {
        var print_d = '<?php echo "\ empresa.php?tipo=1&unidad=" . $unidad_mina_sel ?>';
        window.location.href = print_d;
    }

    function GuardarVehiculo() {
        var frm = document.getElementById('form2');
        var data = new FormData(frm);
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4) {
                var msg = xhttp.responseText;
                if (msg == 'Se agregó correctamente la descripción de vehículo') {
                    alert(msg);
                    redireccion();
                } else {
                    alert(msg);
                }
            }
        };

        xhttp.open("POST", "cambiar_vehiculo.php", true);
        xhttp.send(data);
    }

    function ActualizarVehiculo() {
        var frm = document.getElementById('form3');
        var data = new FormData(frm);
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4) {
                var msg = xhttp.responseText;
                if (msg == 'Se actualizó correctamente la descripción de vehículo') {
                    alert(msg);
                    redireccion();
                } else {
                    alert(msg);
                }
            }
        };

        xhttp.open("POST", "actualizar_vehiculo.php", true);
        xhttp.send(data);
    }

    function GuardarHerramienta() {
        var frm = document.getElementById('form1');
        var data = new FormData(frm);
        data.append("org", <?php echo $org_id ?>);
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4) {
                var msg = xhttp.responseText;
                if (msg == 'Se agregó correctamente la herramienta') {
                    alert(msg);
                    $('#ModalHerramienta').modal('hide');
                    redireccion();
                } else {
                    alert(msg);
                }
            }
        };

        xhttp.open("POST", "insertar_herramienta.php", true);
        xhttp.send(data);
        $('#form1').trigger(reset);
    }

    function redireccion() {
        var print_d = '<?php echo "\ empresa.php?tipo=3&unidad=" . $unidad_mina_sel ?>';
        window.location.href = print_d;
    }

    function ActualizarTablaEmpresas() {
        var nombre = document.getElementById('caja_bus_prov').value;

        $.ajax({
                url: 'actualizar_lista_empresas.php',
                type: 'POST',
                dataType: 'html',
                data: {
                    nombre: nombre
                },
            })
            .done(function(respuesta) {
                $("#BusquedaEmpresas").html(respuesta);
            })
    }

    function Agregar() {
        alert("Se agregó");
    }

    function ActualizarTablaPersonas(u_id_tipo, empresa) {
        var nombre = document.getElementById('caja_bus_prov').value;
        //alert(nombre);
        if (u_id_tipo == 'empleado') {
            $.ajax({
                    url: 'actualizar_lista_personas.php',
                    type: 'POST',
                    dataType: 'html',
                    data: {
                        nombre: nombre
                    },
                })
                .done(function(respuesta) {
                    $("#BusquedaPersonas").html(respuesta);
                })
        }
        if (nombre == empresa) {
            $.ajax({
                    url: 'actualizar_lista_personas.php',
                    type: 'POST',
                    dataType: 'html',
                    data: {
                        nombre: nombre
                    },
                })
                .done(function(respuesta) {
                    $("#BusquedaPersonas").html(respuesta);
                })
        }
    }

    function ActualizarTablaVehiculos() {
        var nombre = document.getElementById('caja_bus_prov').value;

        $.ajax({
                url: 'actualizar_lista_vehiculos.php',
                type: 'POST',
                dataType: 'html',
                data: {
                    nombre: nombre
                },
            })
            .done(function(respuesta) {
                $("#BusquedaVehiculos").html(respuesta);
            })

    }

    $(document).on("click", ".open-vehModal", function() {
        var myBookId = $(this).data('id');
        $(".modal-body #veh_id").val(myBookId);
    });

    $(document).on("click", ".open-vehModalVer", function() {
        var veh_id = $(this).data('id');
        var carroceria = "";
        var espejos = "";
        var parabrisas = "";
        var limpiaparabrisas = "";
        var triangulo = "";
        var cintas = "";
        var llanta = "";
        var eslingas = "";
        var cables = "";
        var limpieza = "";

        $(".modal-ver #veh_id").val(veh_id);
        $.ajax({
            url: "ver_checklist.php",
            type: "POST",
            data: {
                veh_id: veh_id
            },
            success: function(respuesta) {
                console.log(respuesta);
                var cursoex = respuesta.split(';').filter(Boolean);
                carroceria = cursoex[0];
                espejos = cursoex[1];
                parabrisas = cursoex[2];
                limpiaparabrisas = cursoex[3];
                triangulo = cursoex[4];
                cintas = cursoex[5];
                llanta = cursoex[6];
                eslingas = cursoex[7];
                cables = cursoex[8];
                limpieza = cursoex[9];
                $(".modal-ver #carroceria").val(carroceria);
                $(".modal-ver #espejos").val(espejos);
                $(".modal-ver #parabrisas").val(parabrisas);
                $(".modal-ver #limpiaparabrisas").val(limpiaparabrisas);
                $(".modal-ver #triangulo").val(triangulo);
                $(".modal-ver #cintas").val(cintas);
                $(".modal-ver #llanta").val(llanta);
                $(".modal-ver #eslingas").val(eslingas);
                $(".modal-ver #cables").val(cables);
                $(".modal-ver #limpieza").val(limpieza);
            }
        })

    });
</script>
<br /><br /><br /><br /><br /><br /><br /><br />
<script type="text/javascript" src="js/buscar_prov.js"></script>