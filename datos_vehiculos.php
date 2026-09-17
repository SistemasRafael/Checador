<?php

include "connections/config.php";
		require("ldap.php");
       // require("user_bd.php");
		header("Content-Type: text/html; charset=utf-8");
		$usr = $_POST["usuario"];
        $pass = MD5($_POST["clave"]);        
		$usuario = mailboxpowerloginrd($usr, $_POST["clave"]);        
		if($usuario == "0" || $usuario == ''){
		      
		      $existe_ext = $mysqli->query("SELECT arg_usuarios.u_id, arg_usuarios.codigo, arg_usuarios.nombre, arg_usuarios.email, arg_organizaciones.nombre AS empresa                                 
                                     FROM arg_usuarios 
                                     LEFT JOIN arg_organizaciones 
                                        ON arg_organizaciones.org_id = arg_usuarios.org_id              
                                     WHERE arg_usuarios.codigo = '".$usr."'
                                     AND arg_usuarios.clave = '".$pass."'");
              $existe_extu = $existe_ext->fetch_array(MYSQLI_ASSOC);
              //var_dump($existe_extu);
             // die();
              if ($existe_extu <> ''){
                  session_start();
                 $_SESSION["LoggedIn"] = 1;
			     $_SESSION["user"] = $usr;
			     $_SESSION["autentica"] = "SIP";  
                 $cuenta_usuario = $existe_extu['codigo'];
                 
                 $_SESSION['nombre'] = $existe_extu['nombre']; 
                 $_SESSION['email']  = $existe_extu['email'];
                 $_SESSION['empresa']  = $existe_extu['empresa'];
                 $_SESSION['u_id'] = $existe_extu['u_id']; 
      
			     echo"<script>window.location.href='app.php'; </script>";
                } 
                else { 
			     echo"<script> alert('Usuario o clave incorrecta. Vuelva a digitarlos por favor.'); window.location.href='index.php'; </script>";
                }
                
        }else{
			session_start();
            $_SESSION["LoggedIn"] = 1;
			$_SESSION["user"] = $usuario;
			$_SESSION["autentica"] = "SIP";  
            $cuenta_usuario = $usuario['cuenta'];
             
            $existe_id = $mysqli->query("SELECT u_id, codigo FROM arg_usuarios WHERE codigo = '".$cuenta_usuario."'");
		    $existe = $existe_id->fetch_array(MYSQLI_ASSOC);
		    $existe_usuario = $existe["codigo"];
         
           if($existe_usuario == ''){
                $id_usuarios = $mysqli->query("SELECT max(id) FROM arg_usuarios");
    		    $row_id = $id_usuarios->fetch_array(MYSQLI_ASSOC);
    		    $id_max = $row_id["max(id)"];
                $id_max = $id_max+1;
                $codigo = $usuario;
              
                $email_usuario  = $usuario['correo'];
                $nombre_usuario = $usuario['nombre']." ".$usuario['last'];
                $grupos_usuario = $usuario['member'];                
                
                $query = "INSERT INTO arg_usuarios (u_id, codigo, nombre, email, org_id ) ".
                         "VALUES ($id_max, '$cuenta_usuario', '$nombre_usuario', '$email_usuario', 0)";
                            
                         $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));
                            
                         echo "<br><b>Se guardo con exito:</b><br><br>"."$fileName <br>"; 
                
                $_SESSION['u_id'] = $id_max;
           }
           $_SESSION['nombre'] = $usuario['nombre']." ".$usuario['last'];; 
           $_SESSION['email']  = $usuario['correo'];
           $_SESSION['u_id'] = $existe['u_id'];
           
			echo"<script>window.location.href='app.php'; </script>";
		}
?>
