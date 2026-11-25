<style type="text/css">
    .btnSubmit {
        width: 100%;
        border-radius: 1rem;
        padding: 1.5%;
        border: none;
        cursor: pointer;
        background-color: #008CBA;
    }

    .circulos {
        padding-top: 5em;
    }

    img {
        max-width: 100%;
    }
</style>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/contact.js"></script>
<?php

if (($_SESSION['LoggedIn']) <> '' && $_SESSION) {

    $u_id = $_SESSION['u_id'];

    $nombre_u = $mysqli->query("SELECT usu.nombre
                                   FROM arg_usuarios usu WHERE usu.u_id = " . $u_id) or die(mysqli_error($mysqli));
    $nombre_usu = $nombre_u->fetch_assoc();
    $nombre_usuario = $nombre_usu['nombre'];

    $correo_u = $mysqli->query("SELECT usu.email
                                   FROM arg_usuarios usu WHERE usu.u_id = " . $u_id) or die(mysqli_error($mysqli));
    $correo_usu = $correo_u->fetch_assoc();
    $correo_usuario = $correo_usu['email'];

    // $clave_u = $mysqli->query("SELECT usu.clave
    //                                FROM arg_usuarios usu WHERE usu.u_id = " . $u_id) or die(mysqli_error($mysqli));
    //$clave_usu = $correo_u->fetch_assoc();
    //$clave_usuario = $clave_usu['clave'];
}
?>

<div class="container" class="col-md-12 col-lg-12">
    <br />
    <br />
    <h4><b><?php  echo 'Si desea actualizar su nombre o correo capture la información correspondiente y de click en ACTUALIZAR DATOS:' ?></b></h4>
    <br />
    <div id="nombre" class="col-lg-3">
        <label for="nombre"><b>Nombre:</b></label><br><input type="text" name="nombre_usur" class="form-control" value="<?php  echo $nombre_usuario; ?>" id="nombre_usur" />
    </div>
    <div id="em" class="col-lg-3">
        <label for="email"><b>Correo Electrónico:</b></label><br><input type="text" name="email" class="form-control" value="<?php  echo $correo_usuario; ?>" id="email" />
    </div>
    <br />
    
    
    <div class="col-lg-2">
        <button class="btn btn-info btn" onclick="actualizar(<?php  echo $u_id; ?>)">Actualizar Datos</button>
    </div>
    <br />    
    <br />
    <hr />
    <br />
     <h4><b><?php  echo 'Si desea actualizar su contraseña de click en ACTUALIZAR CONTRASEÑA:' ?></b></h4>
    <div id="contact">
        <button type="button" class="btn btn-info btn" data-toggle="modal" data-target="#contact-modal">Actualizar contraseña</button>
    </div>
    <br />
    

</div>
    
<div class="modal fade" id="contact-modal" data-backdrop="static" data-keyboard="false"  tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-scrollable" style="max-width: 650px!important;" role="document">

        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" >Actualizar Contraseña</h3>
            </div>
            <form id="contactForm" name="contact" role="form">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="password">Nueva Contraseña</label>
                        <input id="pass" type="password" name="password" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="password">Confirmar Contraseña</label>
                        <input id="compass" type="password" name="password" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="submit" onclick="contraseña(<?php  echo $u_id; ?>)">GUARDAR</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    function actualizar(id) {
        var nombre = document.getElementById("nombre_usur").value;
        var email = document.getElementById("email").value;
        var id = id;
        $.ajax({
                url: 'actualizar_nombre.php',
                type: 'POST',
                dataType: 'html',
                data: {
                    nombre: nombre,
                    email: email,
                    id: id
                }
            })
            .done(function(respuesta) {
                console.log(respuesta);
            })
        alert("Datos actualizados")
        window.location.reload();
    }

    function contraseña(id) {
        var pass = document.getElementById("pass").value;
        var compass = document.getElementById("compass").value;
        var id = id;
        if (pass == "") {
            alert("Contraseña inválida")
        } else {
            if (pass != compass) {
                alert("Contraseñas no corresponden")
            } else {
                $.ajax({
                        url: 'actualizar_contraseña.php',
                        type: 'POST',
                        dataType: 'html',
                        data: {
                            pass: pass,
                            id: id
                        }
                    })
                    .done(function(respuesta) {
                        console.log(respuesta);
                    })

                alert("Contraseña actualizada")
                window.location.reload();
            }
        }
    }
</script>