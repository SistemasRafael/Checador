<?php //@session_start(); 
  include('partials/header.php');
   $_SESSION['empresa_nueva'] = '';
?>
 <script>
    function resetPass()
            {                        
                var email = document.getElementById("usuario").value;
                if (email == ''){
                    alert('Debes ingresar la cuenta de email registrada por favor. Reintenta');
                }
                else if (email.match(/@.*/)) {
                   window.location.href = 'reset_password.php?email='+email;
                }
                else{
                    email = email+'@heliostarmetals.com';
                    window.location.href = 'reset_password.php?email='+email;
                }
                
           }
 </script>  
<style type="text/css">
	.btnSubmit
    {
        width: 80%;
        border-radius: 1rem;
        padding: 1.5%;
        border: none;
        cursor: pointer;
    }

    .circulos{
	   padding-top: 5em;
    }

    img{
        max-width: 80%;
    }

</style>
         
<div class="container">
	<div class="row">
	<div class="col-6 col-md-4 left">
         
	<html>
	<head>
	<title>Registro HelioStarVisit</title>
	<body>
     <h1>Registro</h1>
	<form method="post" action="control.php" name="loginform" id="loginform" style="width:198px; border:none" ><br>
		 <fieldset>
    		 <label for="usuario">Username/Email:</label>
                      <input type="text" class="form-control" name="usuario" id="usuario" size="15" />
                      <br />
                    <label for="clave">Password:</label>
                      <input type="password" class="form-control" name="clave" id="clave" size="15" />
                      <br />
    			<p>
                    <button type="submit" class="btn btn-primary">Login</button>
                    <a href="register.php" class="btn btn-info">Registro</a>
    		    </p>
                
                <div class="col-md-9 col-lg-9">
                    <a href="#" onclick="resetPass();">Reset password</a>
                </div>
              
		   </fieldset>
		</form>
	</body>
	</html>
 </div>
 
 

</div>
</div>
<?php exit(); ?>

              