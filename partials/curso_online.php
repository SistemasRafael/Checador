<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>

<body>

    <style>
        .float-child-left {
            width: 50%;
            float: right;
            padding: 20px;
            display: flex;
        }

        .float-left {
            margin: auto;
            width: 50%;
            padding: 1vmin 24vmin;
            margin: 0 auto;
            display: flex;
        }

        .acciones {
            font-size: 2.5vmin;
            opacity: 0.8;
            border: 0.4vmin solid rgb(200, 200, 200);
            border-radius: 100%;
            color: white;
            background-color: rgb(19, 132, 150);
            height: 7vmin;
            width: 7vmin;
            cursor: pointer;
        }

        div#salud {
            float: left;
        }

        div#seguridad {
            float: right;
            padding-left: 100px;
        }
    </style>
    
    <?php 
    include "config/config.php";
    $curso_id = $_GET['curso_id'];
    $u = $_SESSION['u_id'];
    $hoy = date("Ymd");
    $examen = 0;
    $datos_usuario = $mysqli->query(
        "SELECT(SELECT IFNULL((SELECT sticker FROM arg_evaluacion_stickers WHERE usuario_prov = $u AND curso_id = 3 AND activo = 1 LIMIT 1),'curso ec nulo')) as ec, 
        (SELECT IFNULL((SELECT sticker FROM arg_evaluacion_stickers WHERE usuario_prov = $u AND curso_id = 2 AND activo = 1 LIMIT 1),'curso sa nulo')) as sa, 
        (SELECT IFNULL((SELECT sticker FROM arg_evaluacion_stickers WHERE usuario_prov = $u AND curso_id = 1 AND activo = 1 LIMIT 1),'curso lacolorada nulo')) as lacolorada
    "
    ) or die(mysqli_error($mysqli));

    $datos_usuario_e = $datos_usuario->fetch_assoc();
    $existe_elca = $datos_usuario_e['ec'];
    
    $existe_ec = 0;
    $activo_ec = 0;
    $valido_ec = 1;
    if ($existe_elca != "curso ec nulo") {
        $existe_ec = 1;
        $activo_ec = 1;
        $valido_ec = 0;
    }

    $existe_sanAg = $datos_usuario_e['sa'];
    $existe_sa = 0;
    $activo_sa = 0;
    $valido_sa = 1;
    
    if ($existe_sanAg != "curso sa nulo") {
        $existe_sa = 1;
        $activo_sa = 1;
        $valido_sa = 0;
    }

    $existe_lacol = $datos_usuario_e['lacolorada'];
    $existe_lc = 0;
    
    $activo_lc = 0;
    $valido_lc = 1;
    if ($existe_lacol != "curso lacolorada nulo") {
        $existe_lc = 1;
        $activo_lc = 1;
        $valido_lc = 0;
    }

/*
    $datos_usuario_s = $mysqli->query(
        "SELECT(SELECT IFNULL((SELECT sticker FROM arg_evaluacion_stickers WHERE usuario_prov = $u AND curso_id = 3 AND activo = 0),'curso ec activo/nulo')) as ec, 
        (SELECT IFNULL((SELECT sticker FROM arg_evaluacion_stickers WHERE usuario_prov = $u AND curso_id = 2 AND activo = 0),'curso sa activo/nulo')) as sa,
        (SELECT IFNULL((SELECT sticker FROM arg_evaluacion_stickers WHERE usuario_prov = $u AND curso_id = 1 AND activo = 0),'curso lc activo/nulo')) as lacolorada"
    ) or die(mysqli_error($mysqli));

    $datos_usuario_es = $datos_usuario_s->fetch_assoc();

    $activo_elca = $datos_usuario_es['ec'];
    $activo_ec = 0;
    if ($activo_elca == "curso ec activo/nulo") {
        $activo_ec = 1;
    }

    $activo_sanAg = $datos_usuario_es['sa'];
    $activo_sa = 0;
    if ($activo_sanAg == "curso sa activo/nulo") {
        $activo_sa = 1;
    }

    $activo_lacol = $datos_usuario_es['lacolorada'];
    $activo_lc = 0;
    if ($activo_lacol == "curso lc activo/nulo") {
        $activo_lc = 1;
    }

    $valido_ec = 1;
    $valido_sa = 1;
    $valido_lc = 1;

    if ($existe_ec != 0) {
        if ($activo_ec != 0) {
            $valido_ec = 0;
        }
    }

    if ($existe_sa != 0) {
        if ($activo_sa != 0) {
            $valido_sa = 0;
        }
    }

   if ($existe_lc != 0) {
        if ($activo_lc != 0) {
            $valido_lc = 0;
        }
    }*/

    ?>
    <div class="container">
        <h1>Bienvenidos al curso de Inducción Básica en línea</h1>
        <br />
        <h2>Una vez que visualice el contenido podrá realizar su evaluación</h2>
        <?php  if ($curso_id == "3") { ?>
            <h3>Obtenga una calificacion aprobatoria en los 3 examenes para obtener su sticker</h3>
        <?php  }  ?>


        <!-- Buttons -->

        <div class="btn-group">

            <div class="col-md-15 col-lg-15">
                <?php  if ($curso_id == 3) { ?>
                    <div>
                        <button type="button" class="acciones fa fa-sharp fa-solid fa-barcode" id="stickerSalud" name="stickerSalud" onclick="print_sticker(<?php  echo $curso_id ?>,<?php  echo $u ?>,<?php  echo $hoy ?>,<?php  echo $valido_ec ?>)"></button>
                    </div>
                    <h3>Información de mina El Castillo</h3>
                    
                    <div id="salud" class="col-md-6 col-lg-6" width="630" height="500">
                        </br>
                        <h4>Salud</h4>
                        <embed src="partials\Induccion_Servicio_Medico_2022_EC.pdf" width="600" height="500" type="application/pdf">
                        </br>
                        <div class="float-left">
                            <div id="evaluarSalud">
                                <button type="button" class="acciones fa fa-edit" data-toggle="modal" data-target="#evaluaModal0" id="evaluarSal" name="evaluarSal" href="partials\evaluar.php?curso_id=<?php  echo $curso_id; ?>&examen_id=0"></button>
                            </div>
                            <div>
                                <button type="button" class="acciones fa fa-expand" data-toggle="modal" data-target="#pdfSalud" name="Salud"> </button>
                            </div>
                        </div>
                    </div>

                    <div id="seguridad" class="col-md-6 col-lg-6" width="600" height="500">
                        </br>
                        <h4>Seguridad</h4>
                        <embed src="ControlDeAccesos\partials\Inducción_de_Seguridad_Industrial_EC.pdf" width="600" height="500" type="application/pdf">
                        </br>
                        <div class="float-left">
                            <div id="evaluarSeguridad">
                                <button type="button" class="acciones fa fa-edit" data-toggle="modal" data-target="#evaluaModal1" id="evaluarSeg" name="evaluarSeg" href="partials\evaluar.php?curso_id=<?php  echo $curso_id; ?>&examen_id=1"></button>
                            </div>
                            <div>
                                <button type="button" class="acciones fa fa-expand" data-toggle="modal" data-target="#pdfSeguridad" name="Seguridad"> </button>
                            </div>
                        </div>
                    </div>

                    <div id="MA" class="col-md-8 col-lg-8" width="630" height="500">
                        </br>
                        <h4>Medio Ambiente</h4>
                        <embed src="partials\INDUCCION_MA_EC.pdf" width="600" height="500" type="application/pdf">
                        </br>
                        <div class="float-left">
                            <div id="evaluarMA">
                                <button type="button" class="acciones fa fa-edit" data-toggle="modal" data-target="#evaluaModal2" id="evaluarMedAm" name="evaluarMedAm" href="partials\evaluar.php?curso_id=<?php  echo $curso_id; ?>&examen_id=2"></button>
                            </div>
                            <div>
                                <button type="button" class="acciones fa fa-expand" data-toggle="modal" data-target="#pdfMedioAmbiente" name="MedioAmbiente"> </button>
                            </div>
                        </div>
                    </div>

                <?php  } ?>

                <?php  if ($curso_id == 1) {   ?>
                    <h2>Información de mina La Colorada</h2>
                    <div class="col-md-10 col-lg-12">
                        <button type="button" class="acciones fa fa-sharp fa-solid fa-barcode" id="stickerLC" name="stickerLC" onclick="print_sticker(<?php  echo $curso_id ?>,<?php  echo $u ?>,<?php  echo $hoy ?>,<?php  echo $valido_lc ?>)"></button>
                    </div>
                    <embed src="assets\pdfs\INDUCCION_DE_SEGURIDAD_2022.pdf" width="600" height="500" type="application/pdf">
                    <div class="float-left">
                        <div id="evaluarLC" class="col-md-16 col-lg-12">
                            <button type="button" class="acciones fa fa-edit" data-toggle="modal" data-target="#evaluaModal" id="evaluarLaCol" name="evaluarLaCol" href="partials\evaluar.php?curso_id=<?php  echo $curso_id; ?>&examen_id=6"></button>
                        </div>
                    </div>
                    <video width=500 height=400 controls poster="vistaprevia.jpg">
                        <source src="upload\Videos\Argonaut_LC_seguridad.mp4" type="video/mp4">
                    </video>
                    <br />
                <?php  }
                if ($curso_id == 2) { ?>
                    <h2>Información de mina San Agustín</h2>
                    <div>
                        <button type="button" class="acciones fa fa-sharp fa-solid fa-barcode" id="stickerCianuro" name="stickerCianuro" onclick="print_sticker(<?php  echo $curso_id ?>,<?php  echo $u ?>,<?php  echo $hoy ?>,<?php  echo $valido_sa ?>)"></button>
                    </div>
                    <div id="cianuro" class="col-md-6 col-lg-6" width="630" height="500">
                        </br>
                        <h4>Cianuro</h4>
                        <embed src="partials\SA INDUCCIÓN GENERALES.pdf" width="600" height="500" type="application/pdf">
                        </br>
                        <div class="float-left">
                            <div id="evaluarCianuro">

                                <button type="button" class="acciones fa fa-edit" data-toggle="modal" data-target="#evaluaModal3" id="evaluarCian" name="evaluarCian" href="partials\evaluar.php?curso_id=<?php  echo $curso_id; ?>&examen_id=4"></button>
                            </div>
                            <div>
                                <button type="button" class="acciones fa fa-expand" data-toggle="modal" data-target="#pdfSAG" name="Salud"> </button>
                            </div>
                        </div>
                    </div>

                    <div id="primerosAuxilios" class="col-md-6 col-lg-6" width="300" height="500">
                        </br>

                        </br>
                        <div class="float-left" style="display:flex; flex-direction:column;">
                            <h4>Primeros Auxilios</h4>
                            <div id="evaluarPrimerosAux">

                                <button type="button" class="acciones fa fa-edit" data-toggle="modal" data-target="#evaluaModal4" id="evaluarPrimAu" name="evaluarPrimAu" href="partials\evaluar.php?curso_id=<?php  echo $curso_id; ?>&examen_id=5"></button>
                            </div>
                        </div>
                    </div>
                <?php  }
                ?>
            </div>
        </div>

        <!-- Modal video -->
        <div class="modal fade" id="videoModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-dark border-dark">
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body bg-dark p-0">
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe class="embed-responsive-item" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal evaluacion-->
        <div class="modal fade" id="evaluaModal" tabindex="-1" role="dialog">

            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">



                </div>
            </div>


        </div>

        <div class="modal fade" id="evaluaModal0" tabindex="-1" role="dialog">

            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">



                </div>
            </div>


        </div>
        <div class="modal fade" id="evaluaModal1" tabindex="-1" role="dialog">

            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">



                </div>
            </div>


        </div>
        <div class="modal fade" id="evaluaModal2" tabindex="-1" role="dialog">

            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">



                </div>
            </div>


        </div>

        <div class="modal fade" id="evaluaModal3" tabindex="-1" role="dialog">

            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">



                </div>
            </div>


        </div>

        <div class="modal fade" id="evaluaModal4" tabindex="-1" role="dialog">

            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">



                </div>
            </div>


        </div>
        <div class="modal fade" id="pdfSalud" tabindex="-1" role="dialog">

            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">

                    <embed src="partials\Induccion_Servicio_Medico_2022_EC.pdf" frameborder="0" width="100%" height="1000px">

                </div>
            </div>


        </div>
        <div class="modal fade" id="pdfSeguridad" tabindex="-1" role="dialog">

            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">

                    <embed src="assets\pdfs\Inducción_de_Seguridad_Industrial_EC.pdf" frameborder="0" width="100%" height="1000px">

                </div>
            </div>


        </div>
        <div class="modal fade" id="pdfMedioAmbiente" tabindex="-1" role="dialog">

            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">

                    <embed src="partials\INDUCCION_MA_EC.pdf" frameborder="0" width="100%" height="1000px">

                </div>
            </div>


        </div>

        <div class="modal fade" id="pdfSAG" tabindex="-1" role="dialog">

            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">

                    <embed src="partials\SA INDUCCIÓN GENERALES.pdf" frameborder="0" width="100%" height="1000px">

                </div>
            </div>


        </div>
    </div>

    <script>
    
        function redireccionar_cursos(curso_id)
            {
                 var curso_id_pos = curso_id;
                 var print_d = '<?php echo "\curso_online.php?curso_id="?>'+curso_id_pos;                
                 window.location.href = print_d;
            }
    
        window.onload = function() {
            <?php 
            $datos_usuario = $mysqli->query(
                "SELECT IFNULL((SELECT Usuario_nombre FROM vista_calificacion3 WHERE U_id = $u), 'nada') as campo;"
            ) or die(mysqli_error($mysqli));
            $usuario = $datos_usuario->fetch_assoc();
            $cal = $usuario['campo'];
            if ($cal == "nada") {
                $cal_prom_EC = 0;
            } else {
                //$datos_calificaciones = $mysqli->query(
                //"SELECT Salud, Seguridad, MedioAmbiente FROM vista_calificacion3 WHERE U_id = {$u}"
                //) or die(mysqli_error($mysqli));

                $datos_calificaciones = $mysqli->query(
                    "SELECT promedio FROM vista_calificacion3 WHERE U_id = {$u}"
                ) or die(mysqli_error($mysqli));
                $datos_cl = $datos_calificaciones->fetch_assoc();
                $cal_prom_EC = $datos_cl['promedio'];
                //$seguridad = $datos_cl['Seguridad'];
                //$ma = $datos_cl['MedioAmbiente'];
            }

            $datos_usuario_SA = $mysqli->query(
                "SELECT IFNULL((SELECT Usuario_nombre FROM vista_calificacion_SA WHERE U_id = $u), 'nada') as campo;"
            ) or die(mysqli_error($mysqli));
            $usuario_SA = $datos_usuario_SA->fetch_assoc();
            $cal_SA = $usuario_SA['campo'];
            if ($cal_SA == "nada") {
                $prom_SA = 0;
            } else {
                //$datos_calificaciones_SA = $mysqli->query(
                //"SELECT Cianuro, PrimerosAuxilios FROM vista_calificacion_SA WHERE U_id = {$u}"
                //) or die(mysqli_error($mysqli));
                $datos_calificaciones_SA = $mysqli->query(
                    "SELECT promedio FROM vista_calificacion_SA WHERE U_id = {$u}"
                ) or die(mysqli_error($mysqli));

                $datos_cl_SA = $datos_calificaciones_SA->fetch_assoc();
                $prom_SA = $datos_cl_SA['promedio'];
                //$pa_SA = $datos_cl_SA['PrimerosAuxilios'];
            }

            $datos_usuario_LC = $mysqli->query(
                "SELECT IFNULL((SELECT Usuario_nombre FROM vista_calificacion_LC WHERE U_id = $u), 'nada') as campo;"
            ) or die(mysqli_error($mysqli));
            $usuario_LC = $datos_usuario_LC->fetch_assoc();
            $cal_LC = $usuario_LC['campo'];
            if ($cal_LC == "nada") {
                $prom_LC = 0;
            } else {
                //$datos_calificaciones_LC = $mysqli->query(
                //"SELECT calificacionLC FROM vista_calificacion_LC WHERE U_id = {$u}"
                //) or die(mysqli_error($mysqli));
                $datos_calificaciones_LC = $mysqli->query(
                    "SELECT promedio FROM vista_calificacion_LC WHERE U_id = {$u}"
                ) or die(mysqli_error($mysqli));

                $datos_cl_LC = $datos_calificaciones_LC->fetch_assoc();
                $prom_LC = $datos_cl_LC['promedio'];
                //$examen_LC = $datos_cl_LC['calificacionLC'];
            }
            if ($valido_lc == 0){
                $prom_LC = 100;
            }
            if ($valido_sa == 0){
                $prom_SA = 100;
            }
            if ($valido_ec == 0){
                $cal_prom_EC = 100;
            }
            ?>
            var calif_prom_EC = <?php  echo $cal_prom_EC; ?>;
            var calif_prom_SA = <?php  echo $prom_SA; ?>;
            var calif_prom_LC = <?php  echo $prom_LC; ?>;
            var curso = <?php  echo $curso_id ?>;
            switch (curso) {
                case 1:
                    verStickerLC(calif_prom_LC);
                    break;
                case 2:
                    verStickerSA(calif_prom_SA);
                    break;
                case 3:
                    verStickerEC(calif_prom_EC);
                    break;
            }
        }

        $(document).ready(function() {
            $("#thumbnail").click(function(e) {
                var id = e.target.id;
                alert(id); // Mostrar ID solo para verificar que sea correcto
            });
        });


        function verStickerEC(calif) {
            console.log("Hola EC");
            var calif = calif;
            var promedio = 80;
            var sticker = document.getElementById("stickerSalud");
            if (calif > promedio) {
                sticker.removeAttribute("disabled");
                sticker.disabled = false;
                document.getElementById("evaluarSal").disabled = true;
                document.getElementById("evaluarSal").style.background = '#a7a7a7';
                document.getElementById("evaluarSeg").disabled = true;
                document.getElementById("evaluarSeg").style.background = '#a7a7a7';
                document.getElementById("evaluarMedAm").disabled = true;
                document.getElementById("evaluarMedAm").style.background = '#a7a7a7';

            } else {
                sticker.disabled = true;
                sticker.style.background = '#a7a7a7';
            }
        }

        function verStickerSA(calif) {
            console.log("Hola SA");
            var calif = calif;
            var promedio = 80;
            var sticker = document.getElementById("stickerCianuro");
            if (calif > promedio) {
                sticker.removeAttribute("disabled");
                sticker.disabled = false;
                document.getElementById("evaluarCian").disabled = true;
                document.getElementById("evaluarCian").style.background = '#a7a7a7';
                document.getElementById("evaluarPrimAu").disabled = true;
                document.getElementById("evaluarPrimAu").style.background = '#a7a7a7';

            } else {
                sticker.disabled = true;
                sticker.style.background = '#a7a7a7';
            }
        }

        function verStickerLC(calif) {
            console.log("Hola LC");
            var calif = calif;
            var promedio = 80;
            var sticker = document.getElementById("stickerLC");
            if (calif > promedio) {
                sticker.removeAttribute("disabled");
                sticker.disabled = false;
                document.getElementById("evaluarLaCol").disabled = true;
                document.getElementById("evaluarLaCol").style.background = '#a7a7a7';

            } else {
                sticker.disabled = true;
                sticker.style.background = '#a7a7a7';
            }
        }


        function print_sticker(curso_id, usuario_prov, fecha, valido) {
            var curso_id = curso_id;
            var usuario_prov = usuario_prov;
            var fecha = fecha;
            var valido = valido;
            const date = new Date();
            const y = date.getFullYear() + 1;
            let m = date.getMonth() + 1;
            let d = date.getDate();

            if (d < 10) d = '0' + d;
            if (m < 10) m = '0' + m;

            const formattedToday = d + '-' + m + '-' + y;

            // alert(fecha_final_ex);
            var exportar = '\sticker_usuario_pdf.php?curso_id=' + curso_id + '&usuario_prov=' + usuario_prov + '&fecha=' +
                fecha + '&valido=' + valido + '&expira=' + formattedToday;
            window.location.href = exportar;
        }

        function GuardarEvaluacion(examen) {
            var examen_id = examen;
            var calificacion = 0;
            var examen_n = "";
            var curso_id = <?php  echo $curso_id ?>;
            var aciertos = 0;
            var preguntas = 0;
            switch (examen_id) {
                case 0:
                    examen_n = "Salud";
                    preguntas = 10;
                    <?php  $preguntas_php = 10; ?>
                    break;
                case 1:
                    examen_n = "Seguridad";
                    preguntas = 10;
                    <?php  $preguntas_php = 10; ?>
                    break;
                case 2:
                    examen_n = "Medio Ambiente";
                    preguntas = 9;
                    <?php  $preguntas_php = 9; ?>
                    break;
                case 4:
                    examen_n = "Cianuro";
                    preguntas = 7;
                    <?php  $preguntas_php = 7; ?>
                    break;
                case 5:
                    examen_n = "Primeros Auxilios";
                    preguntas = 10;
                    <?php  $preguntas_php = 10; ?>
                    break;
                case 6:
                    examen_n = "La Colorada";
                    preguntas = 10;
                    <?php  $preguntas_php = 10; ?>
                    break;
            }
            var respuestas = [];
            var r = [];

            for (var i = 1; i <= preguntas; i++) {
                respuestas[i] = document.getElementById("respuestaC" + i).checked;
                r[i] = document.getElementById("respuestaC" + i).value;
                //alert(imagen_sel);
                //alert(respuesta_1);

                if (respuestas[i] == true) {
                    aciertos += 1;
                } else {
                    alert('La respuesta de la pregunta ' + i + ' es incorrecta, la respuesta correcta es ' + r[i]);
                }
            }
            var calififacion_minima = (preguntas - 2) * 10;
            calificacion = aciertos / preguntas;
            calificacion = calificacion * 100;
            if (curso_id == "1") {
                if (calificacion >= calififacion_minima) {
                    alert('Felicidades, ha completado el examen de ' + examen_n);
                    //document.getElementById("sticker").style.display = 'block';
                }
            } else if (curso_id == "3") {
                if (calificacion >= calififacion_minima) {
                    alert('Felicidades, ha completado el examen de ' + examen_n + ' de El Castillo');
                    //document.getElementById("sticker").style.display = 'block';
                }
            } else if (curso_id == "2") {
                if (calificacion >= calififacion_minima) {
                    alert('Felicidades, ha completado el examen de ' + examen_n + ' de San Agustín');
                    //document.getElementById("sticker").style.display = 'block';
                }
            }

            $.ajax({
                url: "subir_calificacion.php",
                type: "POST",
                async: false,
                data: {
                    calificacion: calificacion,
                    curso_id: curso_id,
                    u_id: <?php  echo $u ?>,
                    examen_id: examen_id
                }
            }).done(function(respuesta) {
                console.log(respuesta);
                if (calificacion >= calififacion_minima) {
                    alert('Promedio mayor a ' + calififacion_minima + ', felicidades!');
                    window.location.reload();
                    //revelarSticker(examen_id);
                }
                else{
                    alert('Usted no obtuvo una calificación aprobatoria. Favor de reintentar o bien inscribirse para un curso presencial en el apartado de Calendarios.')
                        window.location.reload();
                }
            })
        }
    </script>
    <!--
</body>
</html>   --!>
          