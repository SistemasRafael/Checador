<?php 
include "config/config.php";
$datos_usuarios_detalle = $mysqli->query(
    "SELECT
    us.u_id,
    us.codigo,
    us.nombre,
    us.email,
    us.clave,
    us.division,
    `usuarios_minas`(us.u_id) AS mina,
    us.activo
FROM
    `arg_usuarios` us"
) or die(mysqli_error($mysqli));
$datos_organizaciones = $mysqli->query(
    "SELECT DISTINCT og.nombre, og.org_id FROM arg_usuarios us INNER JOIN arg_organizaciones og WHERE us.org_id = og.org_id;"
) or die(mysqli_error($mysqli));

$datos_divisiones = $mysqli->query(
    "SELECT DISTINCT division FROM arg_usuarios;"
) or die(mysqli_error($mysqli));

$total_cursos = $mysqli->query("SELECT COUNT(*) AS total_cursos FROM arg_usuarios");
$total_cursos_cont = $total_cursos->fetch_array(MYSQLI_ASSOC);
$numero = $total_cursos_cont['total_cursos'];
$num = 0;
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#motivos').DataTable({
            pagingType: 'full_numbers',
        });
    });
</script>

<style>
    @import url("https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css");
</style>

<div class="container">
    <ul class="nav nav-tabs" id="myTab">
        <li class="active"><a data-toggle="tab" href="#home">USUARIOS</a></li>
    </ul>
    <div class="container">
        <div class="container" id="ustabla" class="col-md-12 col-lg-12">
            <br />
            <div class="btn-group" class="col-md-2 col-lg-4">
                <button type='button' class='btn btn-primary' name='agregar_usuario' id='agregar_usuario' data-toggle="modal" data-target="#ModalBan">+ AGREGAR USUARIO</button>
            </div>
            <br /><br />

            <?php 
            $html_det = "<div class='container'>
                                        <table class='table table-striped' id='motivos'>
                                        <thead>
                                        <tr class='table-info' justify-content: center;>            
                                            <th scope='col1'>Usuario</th>
                                            <th scope='col1'>Nombre</th>
                                            <th scope='col1'>Correo</th>
                                            <th scope='col1'>Tipo</th>
                                            <th scope='col1'>Mina</th>
                                            <th scope='col1'>Editar</th>
                                            <th scope='col1'>Estado</th>
                                            <th scope='col1'>Password</th>";
            $html_det .= "</tr>
                                        </thead>
                                        <tbody>";

            while ($fila = $datos_usuarios_detalle->fetch_assoc()) {
                $u_id = $fila['u_id'];
                if ($fila['activo'] == "1") {
                    $texto = "Desactivar";
                    $estado = 0;
                    $css = "btn-secondary";
                } else {
                    $texto = "Activar";
                    $estado = 1;
                    $css = "btn-primary";
                }

                $html_det .= "<tr>";
                $html_det .= "<td>" . $fila['codigo'] . "</td>";
                $html_det .= "<td>" . $fila['nombre'] . "</td>";
                $html_det .= "<td>" . $fila['email'] . "</td>";
                $html_det .= "<td>" . $fila['division'] . "</td>";
                $html_det .= "<td>" . $fila['mina'] . "</td>";
                $html_det .= "<td> <button type='button' class='btn btn-info' style='width:7vmin;'";
                $html_det .= " name='agregar_material' data-id='$u_id' data-toggle='modal' data-target='#ModalEditar'";
                $html_det .= "><span class='fa fa-pencil'>  </span>
                                    </button>
                                </td>";
                $html_det .= "<td> <button type='button' class='btn $css' style='width:12vmin;' onclick='activo($u_id,$estado)'>$texto</td>";
                $html_det .= "<td> <button type='button' class='btn btn-info' style='width:12vmin;'";
                $html_det .= "onclick='confirmarResetPass($u_id)'";
                $html_det .= "> Reset
                                    </button>
                            </td>";
                $html_det .= "</tr>";
                $num = $num + 1;
            }
            $html_det .= "</tbody></table></div>";
            echo ("$html_det");
            ?>
        </div>
    </div>
</div>
<style>
    .btn-group button {
        height: 5vmin;
        background-color: #0A66C2;
        color: white;
    }

    .btn-group:hover * {
        box-shadow: inset 0 0 1pt 0.2pt black;
    }

    .modal-content {
        background-color: #e1effd;
        width: fit-content;
        position: absolute;
        float: left;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
    }

    .fields {
        width: 100%;
        height: 25%;
        border-radius: 3px;
        transition: 0.5s;
        font-family: sans-serif;
    }

    .fields:hover {
        box-shadow: inset 0 0 1pt 0.2pt black;
    }

    .fields:focus {
        box-shadow: inset 0 0 3pt 0.2pt black;
        outline-width: 0px;
    }
</style>

<div class="modal fade" id="ModalBan" tabindex="0" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="ModalHerram">Agregar Usuario </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form enctype="multipart/form-data" id="form1">
                    <label for="nombre" class="col-form-label">Nombre:</label>
                    <br />
                    <input class="fields" name="nombre" id="nombre" size=40 value="" enabled />
                    <br />
                    <label for="codigo" class="col-form-label">Codigo:</label>
                    <br />
                    <input class="fields" name="codigo" id="codigo" size=40 value="" enabled />
                    <br />
                    <label for="division" class="col-form-label">Division:</label>
                    <br />
                    <select class="fields" name="division" id="division">
                        <?php 
                        while ($fila = $datos_divisiones->fetch_assoc()) {
                        ?>
                            <option value="<?php  echo $fila['division'] ?>">
                                <?php  echo $fila['division'] ?>
                            </option>
                        <?php  } ?>
                    </select>
                    <br />
                    <label for="email" class="col-form-label">Email:</label>
                    <br />
                    <input class="fields" name="email" id="email" size=40 value="" enabled />
                    <br />
                    <label for="clave" class="col-form-label">Clave:</label>
                    <br />
                    <input class="fields" type="password" name="clave" id="clave" size=40 value="" enabled />
                    <br />
                    <label for="comclave" class="col-form-label">Confirmar Clave:</label>
                    <br />
                    <input class="fields" type="password" name="comclave" id="comclave" size=40 value="" enabled />
                    <br />
                    <br />
                    <label for="mina_seleccionada" class="col-form-label">Unidad de Mina:</label>
                    <br />
                    <select name='mina_seleccionada' id='mina_seleccionada' class="fields">
                        <?php  $nombretop = "Asignar Mina"; ?>
                        <option value='0'>
                            <?php  echo $nombretop ?>
                        </option>
                        <?php  $result = $mysqli->query("SELECT unidad_id, Nombre FROM arg_empr_unidades") or die(mysqli_error($mysqli));
                        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                            $nombre = ($row["Nombre"]);
                            $nomenclatura = $row["unidad_id"];
                            echo ("<option value=$nomenclatura>$nombre</option>");
                        }
                        echo ("</select>");
                        ?>
                    </select>
                    <br />
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="GuardarUsuario()">Guardar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ModalEditar" data-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="ModalHerram">Editar Usuario </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form enctype="multipart/form-data" id="form2">
                    <label for="id" class="col-form-label" hidden>ID:</label>
                    <input class="fields" name="uidedit" id="uidedit" hidden size=40 value="" />
                    <label for="nombre" class="col-form-label">Nombre:</label>
                    <br />
                    <input class="fields" name="nombreedit" id="nombreedit" size=40 value="" enabled />
                    <br />
                    <label for="email" class="col-form-label">Email:</label>
                    <br />
                    <input class="fields" name="emailedit" id="emailedit" size=40 value="" enabled />
                    <br />
                    <label for="email" class="col-form-label">Unidad de Mina:</label>
                    <br />
                    <form name='mina_seleccionada' id='mina_seleccionada'>
                        <select name='mina_seleccionada' id='mina_seleccionada' class='form-control'>
                            <?php  $nombretop = "Asignar Mina"; ?>
                            <option value='0'>
                                <?php  echo $nombretop ?>
                            </option>
                            <?php  $result = $mysqli->query("SELECT unidad_id, Nombre FROM arg_empr_unidades") or die(mysqli_error($mysqli));
                            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                                $nombre = ($row["Nombre"]);
                                $nomenclatura = $row["unidad_id"];
                                echo ("<option value=$nomenclatura>$nombre</option>");
                            }
                            echo ("</select>");
                            ?>
                        </select>
                        <br />
                    </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="EditarUsuario()">Guardar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $("#motivos").on('click', '.btn', function() {
            var currentRow = $(this).closest("tr");
            var col1 = $(this).data('id');
            var col2 = currentRow.find("td:eq(1)").text();
            var col3 = currentRow.find("td:eq(2)").text();

            $("#uidedit").attr("value", col1);
            $("#nombreedit").attr("value", col2);
            $("#emailedit").attr("value", col3);
        });
    });

    function GuardarUsuario() {
        var frm = document.getElementById('form1');
        var data = new FormData(frm);
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4) {
                var msg = xhttp.responseText;
                if (msg == 'Se agregó correctamente el usuario') {
                    alert(msg);
                    $('#ModalBan').modal('hide');
                    window.location.reload();
                } else {
                    alert(msg);
                    reload();
                }
            }
        };

        xhttp.open("POST", "insertar_usuario.php", true);
        xhttp.send(data);
        $('#form1').trigger(reset);
    }

    function EditarUsuario() {
        var frm = document.getElementById('form2');
        var data = new FormData(frm);
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4) {
                var msg = xhttp.responseText;
                if (msg == 'Se editó correctamente el usuario') {
                    alert(msg);
                } else {
                    alert(msg);
                    var direccionar = '<?php echo "\ usuarios.php" ?>';
                    window.location.href = direccionar;
                }

            }
        };
        xhttp.open("POST", "editar_usuario.php", true);
        xhttp.send(data);
        reload();
    }

    function activo(id, estado) {
        var estado = estado;
        var id = id;
        $.ajax({
                url: 'usuario_activo.php',
                type: 'POST',
                dataType: 'html',
                data: {
                    estado: estado,
                    id: id
                },
            })
            .done(function(respuesta) {
                if (respuesta == 'Se cambió exitosamente.') {
                    alert('Se guardó con éxito');
                    var direccionar = '<?php echo "\ usuarios.php" ?>';
                    window.location.href = direccionar;
                }
            })
    }

    function reload() {
        $("#ModalEditar").modal('hide');
        window.location.reload();
    }

    function confirmarResetPass(u_id) {
        let text;
        var u_id = u_id;
        if (confirm("Desea reiniciar la contraseña?") == true) {
            $.ajax({
                    url: 'cambio_password.php',
                    type: 'POST',
                    dataType: 'html',
                    data: {
                        u_id: u_id
                    },
                })
                .done(function(respuesta) {
                    if (respuesta == 'Se cambió la contraseña correctamente.') {
                        alert('Se guardó con éxito');
                        var direccionar = '<?php echo "\ usuarios.php" ?>';
                        window.location.href = direccionar;
                    }
                })
        }
    }
</script>