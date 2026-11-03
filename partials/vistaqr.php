<style type="text/css">
    a {
        color: inherit;
    }

    .btnSubmit {
        width: 70%;
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

    body {
        font-family: 'arial';
        font-size: 23px;
    }
   
    .center {
        margin: auto;
        justify-content: center;
        align-items: center;
        text-align: center;
        width: 90%;
    }


    .button-9 {
        appearance: button;
        backface-visibility: hidden;
        background-color: #43281C;
        border-radius: 10px;
        border-width: 0;
        box-shadow: rgba(179, 166, 127, .1) 0 0 0 1px inset, rgba(50, 50, 93, .1) 0 2px 5px 0, rgba(0, 0, 0, .07) 0 1px 1px 0;
        box-sizing: border-box;
        color: #fff;
        cursor: pointer;
        font-family: -apple-system, system-ui, "Segoe UI", Roboto, "Helvetica Neue", Ubuntu, sans-serif;
        font-size: 20px;
        height: 44px;
        line-height: 1.15;
        margin: 12px 0 0;
        outline: none;
        overflow: hidden;
        padding: 0 25px;
        position: relative;
        text-align: center;
        text-transform: none;
        transform: translateZ(0);
        transition: all .2s, box-shadow .08s ease-in;
        user-select: none;
        -webkit-user-select: none;
        touch-action: manipulation;
        width: 100%;
    }

    .button-9:disabled {
        cursor: default;
    }

    .button-9:focus {
        box-shadow: rgba(179, 166, 127, .1) 0 0 0 1px inset, rgba(50, 50, 93, .2) 0 6px 15px 0, rgba(0, 0, 0, .1) 0 2px 2px 0, rgba(50, 151, 211, .3) 0 0 0 4px;
        font-size: 150%;
    }

    .id-card-holder {
        width: 425px;
        padding: 4px;
        margin: 0 auto;
        background-color: #aa8c32;
        border-radius: 5px;
        position: relative;
    }

    .id-card-holder:after {
        content: '';
        width: 7px;
        display: block;
        background-color: #0a0a0a;
        height: 130px;
        position: absolute;
        top: 105px;
        border-radius: 0 5px 5px 0;
    }

    .id-card-holder:before {
        content: '';
        width: 7px;
        display: block;
        background-color: #0a0a0a;
        height: 130px;
        position: absolute;
        top: 105px;
        left: 415px;
        border-radius: 5px 0 0 5px;
    }

    .id-card {

        background-color: #fff;
        padding: 10px;
        border-radius: 10px;
        text-align: center;
        box-shadow: 0 0 1.5px 0px #b9b9b9;
    }

    .id-card img {
        margin: 0 auto;
    }

    .header img {
        width: 100px;
        margin-top: 25px;
    }

    .photo img {
        width: 490px;
        margin-top: 10px;

    }

    h2 {
        font-size: 22px;
        margin: 5px 0;
    }

    h3 {
        font-size: 17px;
        margin: 2.5px 0;
        font-weight: 300;
    }

    .qr-code img {
        width: 50px;
    }

    p {
        font-size: 55px;
        margin: 2px;
    }

    .navs {
        display: flex;
        flex-direction: row;
        background-color: #76582796;
        border-radius: 5px 5px 0 0;
        width: fit-content;
    }

    .id-card-hook {
        position: relative;
        background-color: #f6be00;
        width: 100px;
        height: 15px;
        left: 5px;
        border-radius: 5px 5px 0 0;
        color: #f6be00;
    }

    .id-card-hook:after {
        content: '';
        background-color: <?php echo $colores[$int] ?>;
        width: 67px;
        height: 6px;
        display: block;
        margin: 0px auto;
        
        position: relative;
        top: 6px;
        border-radius: 4px;
    }
</style>
<?php include "connections/config.php";

if (isset($_GET['trn_id'])) {
    session_start();

    date_default_timezone_set("America/Phoenix");
    $trn_id_rel = $_GET['trn_id'];
    $dia = date("Y-m-d");
    //$dia = '2022-10-10';
    //'2022-11-30';
    //echo $dia;

    $result = $mysqli->query("SELECT au.u_id, au.nombre, en.activo, edi.fecha, en.fecha_inicio, en.fecha_final, en.unidad_id, en.usuario_id_atie, en.usuario_id_resp, og.nombre AS empresa 
                FROM arg_entradas en 
                    LEFT JOIN arg_entradas_dias edi
                      ON  edi.trn_id_rel = en.trn_id
                    INNER JOIN arg_usuarios au 
                        ON au.u_id = en.usuario_id 
                    INNER JOIN arg_organizaciones og 
                        ON au.org_id = og.org_id 
                    WHERE en.trn_id = " . $trn_id_rel . "
                    AND edi.fecha = '" . $dia . "'");
    $query = $result->fetch_assoc();

    $result_veh = $mysqli->query("SELECT ve.placas, ve.marca, ve.modelo
                FROM arg_entradas en 
                INNER JOIN arg_vehiculos ve 
                    ON ve.veh_id = en.veh_id 
                WHERE en.trn_id = " . $trn_id_rel);
    $query_veh = $result_veh->fetch_assoc();

    $resultado = $mysqli->query("SELECT CASE WHEN EXISTS ( SELECT estado_id FROM arg_entradas_dias WHERE fecha = '" . $dia . "' AND estado_id = 4 AND trn_id_rel = " . $trn_id_rel . " ) THEN 'TRUE' ELSE 'FALSE' END AS iniciado");
    $res = $resultado->fetch_assoc();
    $iniciado = $res['iniciado'];

    $resultado2 = $mysqli->query("SELECT CASE WHEN EXISTS ( SELECT estado_id FROM arg_entradas_dias WHERE fecha = '" . $dia . "' AND estado_id = 2 AND trn_id_rel = " . $trn_id_rel . " ) THEN 'TRUE' ELSE 'FALSE' END AS iniciado");
    $res2 = $resultado2->fetch_assoc();
    $autorizado = $res2['iniciado'];

    echo "<script>console.log('Debug Objects: " . $iniciado . "' );</script>";

    $obtenido = $mysqli->query("SELECT CASE WHEN EXISTS ( SELECT estado_id FROM arg_entradas_dias WHERE fecha = '" . $dia . "' AND estado_id = 6 AND trn_id_rel = " . $trn_id_rel . " ) THEN 'TRUE' ELSE 'FALSE' END AS finalizado");
    $obt = $obtenido->fetch_assoc();
    $finalizado = $obt['finalizado'];

    echo "<script>console.log('Finalizada: " . $finalizado . "' );</script>";

    if ($iniciado == "TRUE") {
        $css = '';
        $texto = 'Finalizar Visita';
    } else if ($autorizado == "TRUE") {
        $css = '';
        $texto = 'Iniciar Visita';
    } else {
        $css = 'style="opacity: 0.5;" disabled';
        $texto = 'Falta Autorización';
    }

    if ($finalizado == "TRUE") {
        $css = 'style="opacity: 0.5;" disabled';
        $texto = 'Visita Finalizada';
    }

    $marca = $query_veh['marca'];
    $modelo = $query_veh['modelo'];
    $placas = $query_veh['placas'];

    $nombre = $query['nombre'];
    $nombre = strtoupper($nombre);
    $activo = $query['activo'];
    $fecha  = $query['fecha'];
    $fecha_inicio = $query['fecha_inicio'];
    $fecha_final = $query['fecha_final'];
    $empresa = $query['empresa'];

    $unidad = $query['unidad_id'];
    switch ($unidad) {
        case 1:
            $unidad = "La Colorada";
            break;
        case 2:
            $unidad = "San Agustin";
            break;
        case 3:
            $unidad = "El Castillo";
            break;
    }
    $atiende = $query['usuario_id_atie'];
    $responsable = $query['usuario_id_resp'];
    $u_id_prov = $query['u_id'];
    //echo $u_id_prov;
    $nat = $mysqli->query("SELECT nombre FROM arg_usuarios WHERE u_id = " . $atiende);
    $nat2 = $nat->fetch_assoc();
    $nombreatiende = $nat2['nombre'];

    $nrep = $mysqli->query("SELECT nombre FROM arg_usuarios WHERE u_id = " . $responsable);
    $nrep2 = $nrep->fetch_assoc();
    $nombreresponsable = $nrep2['nombre'];

    $doc = $mysqli->query("SELECT path as ine_path FROM arg_usuarios_documentos WHERE activo = 1 AND tipo_id = 2 AND u_id = " . $u_id_prov);
    $docine = $doc->fetch_assoc();
    $ine = $docine['ine_path'];

    /* $ids = $mysqli->query("SELECT EXISTS (SELECT * FROM arg_unidades_areas WHERE u_id=" . $responsable . ") AS existe");
    $id = $ids->fetch_assoc();
    $idexiste = $id['existe'];*/

    //if ($idexiste == 1) {
    $areap = $mysqli->query("SELECT area, color FROM `arg_entradas_areas` ea
LEFT JOIN arg_unidades_areas ua
	ON ea.area_id = ua.area_id
WHERE trn_id_rel = " . $trn_id_rel . " LIMIT 1");
    $areap2 = $areap->fetch_assoc();
    $area = $areap2['area'];
    $color = $areap2['color'];
    switch ($color) {
        case "amarillo":
            $color = "#fada28";
            break;
        case "rojo":
            $color = "#ff3f3f";
            break;
        case "cafe":
            $color = "#6F4E37";
            break;
    }
} else {
    $area = "General";
    $color = "#b3a67f";
}

$visitantes = $mysqli->query("SELECT u.nombre AS visitante, ud.path AS ine
                                            FROM `arg_entradas_detalle` ed                                  
                                            LEFT JOIN arg_usuarios u
                                            	ON ed.usuario_id = u.u_id
                                            LEFT JOIN arg_usuarios_documentos ud
                                            	ON ud.u_id = ed.usuario_id
                                                AND ud.tipo_id = 2
                                                AND ud.activo = 1
                                            WHERE trn_id_rel =  " . $trn_id_rel) or die(mysqli_error($mysqli));
// $mysqli->set_charset("utf8");
$num_visitantes = (mysqli_num_rows($visitantes));

$herramientas = $mysqli->query("SELECT arg_entradas_herramientas.cantidad, arg_herramientas.nombre, arg_herramientas.marca, arg_herramientas.serie
                                                FROM `arg_entradas_herramientas` 
                                                LEFT JOIN arg_herramientas
                                                	ON arg_herramientas.herr_id = arg_entradas_herramientas.herr_id
                                                WHERE trn_id_rel = " . $trn_id_rel) or die(mysqli_error($mysqli));
$num_herramientas = (mysqli_num_rows($herramientas));

//}

if ($num_herramientas >= 1) {
?>
<div class="navs" style="position:relative; margin:auto; right: 3px;">
    <ul class="nav nav-tabs" id="myTab" role="tablist" style="position:relative;">
        <li class="active"><a class="nav-link active"  data-toggle="tab" href="#home">INFORMACION</a></li>
        <li><a class="nav-link" data-toggle="tab" href="#menu2">HERRAMIENTAS</a></li>
    </ul>
</div>
<?php  } ?>
<div class="tab-content" id="myTabContent">
    <div class="center" style="height:1000px;">
        <div id="home" class="tab-pane fade active in" style="position:absolute; margin:auto; left: 0; right: 0;">
            <div class="id-card-holder">
                <div class="id-card">
                    <div class="photo">
                        <img src="<?php  echo $ine ?>" width="450" height="350">
                    </div>
                    <h2><strong><?php  echo $nombre ?></strong></h2>
                    <h3><strong><?php  echo $empresa ?></strong></h3>
                    <?php  if ($num_visitantes >= 1) {
                    ?>
                        <hr>
                        <h3><b>OTROS VISITANTES</b></h3>
                        <?php 
                        $num = 1;
                        while ($fila_v = $visitantes->fetch_assoc()) {
                        ?>
                            <tr>
                                <td><?php  echo $fila_v['visitante']; ?></td>
                                <td>
                                    <div class="photo"><img src="<?php  echo $fila_v['ine']; ?>">
                                    </div>
                                </td>
                            </tr>
                            <hr>
                    <?php  }
                    } ?>
                    <h6><strong>Atiende : </strong><?php  echo $nombreatiende ?></h6>
                    <h6><strong>Responsable : </strong><?php  echo $nombreresponsable ?></h6>
                    <hr>
                    <h6 style="font-size:15px;">Placas : <?php  echo $placas ?></h6>
                    <h6 style="font-size:15px;">Marca : <?php  echo $marca ?></h6>
                    <h6 style="font-size:15px;">Modelo : <?php  echo $modelo ?></h6>
                    <h2><strong>Area: <?php  echo $area ?></strong></h2>
                    <hr>
                    <h6 style="font-size:15px;">Fecha : <?php  echo $fecha ?></h6>
                    <hr>
                </div>
                <button class="button-9" role="button" onClick="boton('<?php  echo $iniciado ?>',<?php  echo $trn_id_rel ?>,'<?php  echo $responsable ?>')" <?php  echo $css ?>><?php  echo $texto ?></button>
            </div>
        </div>
        <div id="menu2" class="tab-pane fade" style="position:absolute; margin:auto; left: 0; right: 0;">
            <div class="id-card-holder">
                <div class="id-card">
                    <?php 
                    if ($num_herramientas >= 1) {
                    ?>
                        <hr>
                        <h3><b>HERRAMIENTAS</b></h3>
                        <br>
                        <table class="table table-bordered table-striped table-sm" style="font-size:small;">
                            <thead>
                                <tr>
                                    <th>Cantidad</th>
                                    <th>Marca</th>
                                    <th>Serie</th>
                                    <th>Nombre</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                while ($fila_her = $herramientas->fetch_assoc()) {
                                ?>

                                    <tr class="table-thread">
                                        <td> <?php  echo $fila_her['cantidad'] ?> </td>
                                        <td> <?php  echo $fila_her['marca'] ?> </td>
                                        <td> <?php  echo $fila_her['serie'] ?> </td>
                                        <td> <?php  echo $fila_her['nombre'] ?> </td>
                                    </tr>
                                <?php  } ?>
                            </tbody>
                        </table>
                        <hr>
                    <?php  } ?>
                </div>
                <button class="button-9" role="button" onClick="boton('<?php  echo $iniciado ?>',<?php  echo $trn_id_rel ?>,'<?php  echo $responsable ?>')" <?php  echo $css ?>><?php  echo $texto ?></button>
            </div>
        </div>
    </div>
</div>
<style>
    body {
        background-color: <?php  echo $color ?>;
        background-blend-mode: color-burn;
    }
</style>

<script>
    function boton(iniciado, trn_id_rel, u_id) {
        var iniciado = iniciado;
        var trn_id_rel = trn_id_rel;
        var u_id = u_id;
        if (iniciado == 'TRUE') {
            var estado_id_sel = 6;
        } else {
            var estado_id_sel = 4;
        }

        var exportar = '<?php echo "\ cambiar_estado.php?trn_id=" ?>' + trn_id_rel + '&estado_id=' + estado_id_sel;
        window.location.href = exportar;

        /*<a href='cambiar_estado.php?trn_id=" . $row['trn_id'] . "&estado_id=" . $row['estado_1'] . "'*/
        /* $.ajax({
             url: "actualizar_visita.php",
             type: "POST",
             async: false,
             data: {
                 iniciado: iniciado,
                 trn_id_rel: trn_id_rel,
                 u_id: u_id
             }
         });
         window.location.reload();*/
    }
</script>