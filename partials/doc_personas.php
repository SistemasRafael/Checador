<style type="text/css">
	.btnSubmit
    {
        width: 50%;
        border-radius: 1rem;
        padding: 1.5%;
        border: none;
        cursor: pointer;
    }
    
     .modal-lg {
        max-width: 60%;
    }

    .circulos{
    	padding-top: 5em;
    }
    
    img{
      max-width: 100%;
    }
</style>
<script>
    function redireccion($u_id)
            {
                 var u_id_fir = $u_id
                 var print_d = '<?php echo "\doc_personas.php"?>';  
                 window.location.href = print_d;              
                 //window.location.href = print_d+u_id_fir;
            }
            
    function falla_upload_tipo(tipo_upload)
            {
                 var tipo_upload = tipo_upload
                 if (tipo_upload == 1){
                    alert('El tipo de archivo no es correcto, para la INE debe ser un archivo de tipo IMAGEN');
                 }
                 if (tipo_upload == 2){
                    alert('El tipo de archivo no es correcto, para la LICENCIA debe ser un archivo de tipo IMAGEN');
                 }
                 if (tipo_upload == 3){
                    alert('El tipo de archivo no es correcto, para este documento POLIZA GMM/IMSS debe ser un archivo de tipo PDF');
                 }
                 if (tipo_upload == 4){
                    alert('El tipo de archivo no es correcto, para este documento PAGO IMSS+INFONAVIT debe ser un archivo de tipo PDF');
                 }
                 if (tipo_upload == 5){
                    alert('El tipo de archivo no es correcto, para este documento CONSTANCIA DC3 debe ser un archivo de tipo PDF');
                 }
                if (tipo_upload == 6){
                    alert('El tipo de archivo no es correcto, para este documento CERTIFICADO MEDICO debe ser un archivo de tipo PDF');
                 }
                if (tipo_upload == 7){
                    alert('El tipo de archivo no es correcto, para este documento ANTIDOPING debe ser un archivo de tipo PDF');
                 }
                if (tipo_upload == 8){
                    alert('El tipo de archivo no es correcto, para este documento CARTA DE NO ANTECEDENTES PENALES debe ser un archivo de tipo PDF');
                 }
                 var print_d = '<?php echo "\doc_personas.php"?>';  
                 window.location.href = print_d;              
                 //window.location.href = print_d+u_id_fir;
            }      
</script>
<?php
include "connections/config.php";
include "config/Env.php";
Env::load(dirname(__DIR__) . '/.env');

if(($_SESSION['LoggedIn']) <> ''){
   
    //$u_id = $_GET['u_id'];
    $u_id = $_POST['u_id'] ?? null;
    if (is_null($u_id)){
      $u_id = $_SESSION['u_id'];
    }
    $u_id_firmado = $_SESSION['u_id'];
    $unidad_mina_sel = $_GET['unidad'] ?? 0;
    $contenido_imagen = '';
    
    $organizac = $mysqli->query("SELECT org_id
                                       ,UPPER(nombre) AS nombre
                                       ,email AS correo_proveedor
                                   FROM 
                             	        arg_usuarios
                                     WHERE 
                                     	u_id = ".$u_id) or die(mysqli_error($mysqli));
    $organizacion = $organizac ->fetch_array(MYSQLI_ASSOC);
    $organi = $organizacion['org_id']; 
    $nombre_proveedor = $organizacion['nombre'];
    $correo_proveedor = $organizacion['correo_proveedor'];
    
    $organizac_admin = $mysqli->query("SELECT usuario_administrador
                                   FROM 
                             	        arg_organizaciones
                                     WHERE 
                                     	org_id = ".$organi
                              ) or die(mysqli_error($mysqli));
    $organizac_adminis = $organizac_admin ->fetch_array(MYSQLI_ASSOC);
    $org_admin = $organizac_adminis['usuario_administrador']; 
    
    $otros_prov_admin = $mysqli->query("SELECT administrador
                                   FROM 
                             	        arg_usuarios
                                     WHERE 
                                     	org_id = ".$organi."
                                     	AND u_id = ".$u_id_firmado
                              ) or die(mysqli_error($mysqli));
    $otros_prov_adm = $otros_prov_admin ->fetch_array(MYSQLI_ASSOC);
    $org_admin = $otros_prov_adm['administrador']; 
    
  if ($u_id == $u_id_firmado || $u_id_firmado == $org_admin || $org_admin == 1)
  {
      $editar = 1;
    }else{
        $editar = 0;
    }

    $unidad_mina = $mysqli->query("SELECT um.serie
                                   FROM 
                             	        arg_usuarios_directivas ud
                                        LEFT JOIN arg_empr_unidades um
                                        	ON ud.valor = um.unidad_id
                                     WHERE 
                                     	ud.u_id = ".$u_id) or die(mysqli_error($mysqli));
    $user_unidad_mina = $unidad_mina ->fetch_array(MYSQLI_ASSOC);
    $serie_mina = $user_unidad_mina['serie'] ?? null; 
    
    $user_fir = $mysqli->query("SELECT arg_usuarios_documentos.nombre, arg_usuarios_documentos.fecha_expira, arg_usuarios_documentos.path
                                FROM `arg_usuarios_documentos` 
                                LEFT JOIN arg_tipo_documentos
                                    ON arg_tipo_documentos.tipo_id = arg_usuarios_documentos.tipo_id   
                                WHERE arg_tipo_documentos.tipo_id = 1 AND activo = 1 AND u_id = ".$u_id) or die(mysqli_error($mysqli));
    $user_firmado = $user_fir ->fetch_array(MYSQLI_ASSOC);
    $seguro_act = $user_firmado['nombre']; 
    $seguro_act_exp = $user_firmado['fecha_expira']; 
    $seguro_act_path = $user_firmado['path']; 
            
    $user_fir1 = $mysqli->query("SELECT arg_usuarios_documentos.nombre, arg_usuarios_documentos.fecha_expira,  arg_usuarios_documentos.path 
                                 FROM `arg_usuarios_documentos` 
                                 LEFT JOIN arg_tipo_documentos
                                    ON arg_tipo_documentos.tipo_id = arg_usuarios_documentos.tipo_id   
                                 WHERE arg_tipo_documentos.tipo_id = 2 AND activo = 1 AND u_id = ".$u_id) or die(mysqli_error($mysqli));
    $user_firmado1 = $user_fir1 ->fetch_array(MYSQLI_ASSOC);
    $ine_act = $user_firmado1['nombre'];
    $ine_act_exp = $user_firmado1['fecha_expira'];
    $ine_act_path = $user_firmado1['path'];
            
    $user_fir2 = $mysqli->query("SELECT arg_usuarios_documentos.nombre, arg_usuarios_documentos.fecha_expira, arg_usuarios_documentos.path
                                 FROM `arg_usuarios_documentos` 
                                 LEFT JOIN arg_tipo_documentos
                                	ON arg_tipo_documentos.tipo_id = arg_usuarios_documentos.tipo_id   
                                 WHERE arg_tipo_documentos.tipo_id = 3 AND activo = 1 AND u_id = ".$u_id) or die(mysqli_error($mysqli));
    $user_firmado3 = $user_fir2 ->fetch_array(MYSQLI_ASSOC);
    $licencia_act = $user_firmado3['nombre'];
    $licencia_act_exp = $user_firmado3['fecha_expira'];
    $licencia_act_path = $user_firmado3['path'];
    
    $user_fir4 = $mysqli->query("SELECT arg_usuarios_documentos.nombre, arg_usuarios_documentos.fecha_expira, arg_usuarios_documentos.path
                                  FROM `arg_usuarios_documentos` 
                                  LEFT JOIN arg_tipo_documentos
                                    ON arg_tipo_documentos.tipo_id = arg_usuarios_documentos.tipo_id   
                                  WHERE arg_tipo_documentos.tipo_id = 4 AND activo = 1 AND u_id = ".$u_id) or die(mysqli_error($mysqli));
    $user_firmado4 = $user_fir4 ->fetch_array(MYSQLI_ASSOC);
    $pago_imssinf = $user_firmado4['nombre']; 
    $pago_imss_fec = $user_firmado4['fecha_expira']; 
    $pago_imss_pat = $user_firmado4['path'];
    
    $user_fir5 = $mysqli->query("SELECT arg_usuarios_documentos.nombre, arg_usuarios_documentos.fecha_expira, arg_usuarios_documentos.path
                                  FROM `arg_usuarios_documentos` 
                                  LEFT JOIN arg_tipo_documentos
                                    ON arg_tipo_documentos.tipo_id = arg_usuarios_documentos.tipo_id   
                                  WHERE arg_tipo_documentos.tipo_id = 5 AND activo = 1 AND u_id = ".$u_id) or die(mysqli_error($mysqli));
    $user_firmado5 = $user_fir5 ->fetch_array(MYSQLI_ASSOC);
    $constancia_dc     = $user_firmado5['nombre'] ?? null; 
    $constancia_dc_fec = $user_firmado5['fecha_expira'] ?? null; 
    $constancia_dc_pat = $user_firmado5['path'] ?? null; 
    
    $user_fir6 = $mysqli->query("SELECT arg_usuarios_documentos.nombre, arg_usuarios_documentos.fecha_expira, arg_usuarios_documentos.path
                                  FROM `arg_usuarios_documentos` 
                                  LEFT JOIN arg_tipo_documentos
                                    ON arg_tipo_documentos.tipo_id = arg_usuarios_documentos.tipo_id   
                                  WHERE arg_tipo_documentos.tipo_id = 6 AND activo = 1 AND u_id = ".$u_id) or die(mysqli_error($mysqli));
    $user_firmado6 = $user_fir6 ->fetch_array(MYSQLI_ASSOC);
    $certificado_m     = $user_firmado6['nombre'] ?? null; 
    $certificado_m_fec = $user_firmado6['fecha_expira'] ?? null; 
    $certificado_m_path = $user_firmado6['path'] ?? null; 
    
    $user_fir7 = $mysqli->query("SELECT arg_usuarios_documentos.nombre, arg_usuarios_documentos.fecha_expira, arg_usuarios_documentos.path
                                  FROM `arg_usuarios_documentos` 
                                  LEFT JOIN arg_tipo_documentos
                                    ON arg_tipo_documentos.tipo_id = arg_usuarios_documentos.tipo_id   
                                  WHERE arg_tipo_documentos.tipo_id = 7 AND activo = 1 AND u_id = ".$u_id) or die(mysqli_error($mysqli));
    $user_firmado7 = $user_fir7 ->fetch_array(MYSQLI_ASSOC);
    $antidoping    = $user_firmado7['nombre'] ?? null; 
    $antidoping_fec = $user_firmado7['fecha_expira'] ?? null; 
    $antidoping_path = $user_firmado7['path'] ?? null; 

    $user_fir8 = $mysqli->query("SELECT arg_usuarios_documentos.nombre, arg_usuarios_documentos.fecha_expira, arg_usuarios_documentos.path
                                  FROM `arg_usuarios_documentos` 
                                  LEFT JOIN arg_tipo_documentos
                                    ON arg_tipo_documentos.tipo_id = arg_usuarios_documentos.tipo_id   
                                  WHERE arg_tipo_documentos.tipo_id = 8 AND activo = 1 AND u_id = ".$u_id) or die(mysqli_error($mysqli));
    $user_firmado8 = $user_fir8 ->fetch_array(MYSQLI_ASSOC);
    $no_ant_pen   = $user_firmado8['nombre'] ?? null; 
    $no_ant_pen_fec = $user_firmado8['fecha_expira'] ?? null; 
    $no_ant_pen_path = $user_firmado8['path'] ?? null; 
?>
<div class="modal fade" id="ModalImagen" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">INE</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img src="<?php echo Env::get('APP_PATH').$ine_act_path;?>" class="img-rounded" alt="INE" width="370" height="236" />   
            </div>      
            <div class="modal-footer">        
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>     
            </div>  
        </div>
    </div>
</div>
<div class="modal fade" id="ModalLicencia" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Licencia</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img src="<?php echo Env::get('APP_PATH').$licencia_act_path;?>" class="img-rounded" alt="INE" width="370" height="236" />   
            </div>      
            <div class="modal-footer">        
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>     
            </div>  
        </div>
    </div>
</div>

<div class="modal fade" id="ModalSeguro" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Poliza GMM / IMSS</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div>
                    <object data="your_url_to_pdf" type="application/pdf">
                        <iframe  width="450" height="650" src="<?php echo Env::get('APP_PATH').$seguro_act_path;?>"&embedded=true"></iframe>
                    </object>
                </div> 
            </div>      
            <div class="modal-footer">        
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>     
            </div>  
        </div>
    </div>
</div>
   
<div class="modal fade" id="ModalPagoSeguro" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Pago IMSS+Infonavit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div>
                    <object data="your_url_to_pdf" type="application/pdf">
                        <iframe  width="450" height="650" src="<?php echo Env::get('APP_PATH').$pago_imss_pat;?>"&embedded=true"></iframe>
                    </object>
                </div> 
            </div>      
            <div class="modal-footer">        
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>     
            </div>  
        </div>
    </div>
</div>

<div class="modal fade" id="ModalConstancia" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Constancia DC3</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                     <div>
                     <object data="your_url_to_pdf" type="application/pdf">
            <iframe  width="800" height="650" src="<?php echo Env::get('APP_PATH').$constancia_dc_pat;?>"&embedded=true"></iframe>
        </object>
                     </div> 
              </div>      
              <div class="modal-footer">        
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>     
              </div>  
            </div>
          </div>
   </div>
   
<div class="modal fade" id="ModalCertificado" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Certificado Medico</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                     <div>
                     <object data="your_url_to_pdf" type="application/pdf">
            <iframe  width="800" height="650" src="<?php echo Env::get('APP_PATH').$certificado_m_path;?>"&embedded=true"></iframe>
        </object>
                     </div> 
              </div>      
              <div class="modal-footer">        
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>     
              </div>  
            </div>
          </div>
   </div>
   
<div class="modal fade" id="ModalAntidoping" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Antidoping </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                     <div>
                     <object data="your_url_to_pdf" type="application/pdf">
            <iframe  width="800" height="650" src="<?php echo Env::get('APP_PATH').$antidoping_path;?>"&embedded=true"></iframe>
        </object>
                     </div> 
              </div>      
              <div class="modal-footer">        
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>     
              </div>  
            </div>
          </div>
   </div>

<div class="modal fade" id="ModalNoAntePen" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">No Antecedentes Penales </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                     <div>
                     <object data="your_url_to_pdf" type="application/pdf">
            <iframe  width="800" height="650" src="<?php echo Env::get('APP_PATH').$no_ant_pen_path;?>"&embedded=true"></iframe>
        </object>
                     </div> 
              </div>      
              <div class="modal-footer">        
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>     
              </div>  
            </div>
          </div>
   </div>

<div class="container"  class="col-md-12 col-lg-12">  
    <br />
    <br />
    <h4><b><?php echo 'Para poder ingresar a una unidad de mina es necesaria tener actualizados los siguientes documentos:'?></b></h4>
    <p style="color:red;"><b><?php echo 'DOCUMENTACION DEL PROVEEDOR: '.$nombre_proveedor;?></b></p>
    
    <p style="color:red;"><b><?php echo 'EMAIL DEL PROVEEDOR: '.$correo_proveedor;?></b></p>
    <br />
    <div class="row">
    <div class="container"  class="col-md-12 col-lg-12"> 
        <form method="post" enctype="multipart/form-data">                     
                        <div id="content" class="col-lg-2">  
                            <label for="ine"><b>INE:</b></label><br><input type="text" name="ine" class="form-control" value="<?php echo $ine_act;?>" id="ine" />
                        </div>
                        <div id="content" class="col-lg-2"> 
                            <label for="fecha_ine"><b>Expira:</b></label><br><input type="date" name="fecha_ine" value="<?php echo $ine_act_exp;?>" class="form-control" id="fecha_ine" />          
                        </div>
                         <div id="content" class="col-lg-1"> 
                           </br>
                           
                            <a name="idsele" class="btn btn-success" data-toggle="modal" data-target="#ModalImagen"  class="sepV_a" title="Ine">Ver </a></td>
                         </div>
                         <?php  if ($editar == 1){ ?>
                            <div id="content" class="col-lg-3">                          
                                <label for="ine_img"><b>Imagen:</b></label></br>
                                <input type="hidden" name="MAX_FILE_SIZE" value="200000000">
                                <input name="ine_img" type="file" accept="image/png" id="ine_img"> 
                             </div>
                              <div id="content" class="col-lg-1"> 
                                <label for="archivo_ant"></label><input type="hidden" name="archivo_ant" value="<?php echo $ine_act_path;?>" class="form-control" id="archivo_ant" />          
                              </div>                        
                             <div id="content" class="col-lg-2">
                              <br />                           
                            <td height="26" valign="medium"><input name="upload_ine" type="submit" class="box btn btn-info" id="upload_ine" value=" Actualizar "></td>
                            <?php  } ?>
                       </div>
        </form>
    </div>
    </div>
    <br />
    <br />
    
    <div class="row">
    <div class="container"  class="col-md-12 col-lg-12"> 
        <form method="post" enctype="multipart/form-data">                     
                        <div id="content" class="col-lg-2">  
                            <label for="licencia"><b>Licencia:</b></label><br><input type="text" name="licencia" class="form-control" value="<?php echo $licencia_act;?>" id="licencia" />
                        </div>
                        <div id="content" class="col-lg-2"> 
                            <label for="fecha_lic"><b>Expira:</b></label><br><input type="date" name="fecha_lic" value="<?php echo $licencia_act_exp;?>" class="form-control" id="fecha_lic" />          
                        </div>
                         <div id="content" class="col-lg-1"> 
                            </br>                           
                            <a name="idlic" class="btn btn-success" data-toggle="modal" data-target="#ModalLicencia"  class="sepV_a" title="Licencia">Ver </a></td>
                         </div>
                         <?php  if ($editar == 1){ ?>
                                <div id="content" class="col-lg-3">                          
                                    <label for="licencia_img"><b>Imagen:</b></label></br>
                                    <input type="hidden" name="MAX_FILE_SIZE" value="200000000">
                                    <input name="licencia_img" type="file" accept="image/png" id="licencia_img"> 
                                 </div>
                                  <div id="content" class="col-lg-1"> 
                                    <label for="archivo_ant"></label><input type="hidden" name="licencia_ant" value="<?php echo $licencia_act_path;?>" class="form-control" id="licencia_ant" />          
                                  </div>
                                 <div id="content" class="col-lg-2">                          
                                
                                  <br />                        
                                <td height="26" valign="medium"><input name="upload_lic" type="submit" class="box btn btn-info" id="upload_lic" value="Actualizar"></td>
                        <?php }?> 
                           </div>
        </form>
    </div>
    </div>
    <br />
    <br />
    
    <div class="row"> 
    <div class="container"  class="col-md-12 col-lg-12">  
        <form method="post" enctype="multipart/form-data">                     
                        <div id="content" class="col-lg-2">  
                            <label for="seguro"><b>Poliza GMM/IMSS:</b></label><br>
                            <input type="text" name="seguro" class="form-control" value="<?php echo $seguro_act;?>" id="seguro" />
                        </div>
                        <div id="content" class="col-lg-2"> 
                            <label for="fecha_seg"><b>Expira:</b></label><br>
                            <input type="date" name="fecha_seg" value="<?php echo $seguro_act_exp;?>" class="form-control" id="fecha_seg" />          
                        </div>
                        <div id="content" class="col-lg-1"> 
                            </br>                           
                            <a name="idpol" class="btn btn-success" data-toggle="modal" data-target="#ModalSeguro"  class="sepV_a" title="P�liza">Ver </a></td>
                         </div>
                         <?php  if ($editar == 1){ ?>   
                                <div id="content" class="col-lg-3">                          
                                    <label for="poliza_img"><b>Archivo PDF:</b></label></br>
                                    <input type="hidden" name="MAX_FILE_SIZE" value="200000000">
                                    <input name="poliza_img" type="file" id="poliza_img"> 
                                 </div>
                                 <div id="content" class="col-lg-1"> 
                                    <label for="archivo_ant"></label><input type="hidden" name="seguro_ant" value="<?php echo $seguro_act_path;?>" class="form-control" id="seguro_ant" />          
                                 </div>
                                 <div id="content" class="col-lg-2">
                                    <br />                                        
                                    <td height="26" valign="medium"><input name="upload_pol" type="submit" class="box btn btn-info" id="upload_pol" value="Actualizar"></td>
                        <?php  } ?>
                                </div>
        </form>
    </div>
    </div>
    
    <br />
    <br />
    <div class="row"> 
    <div class="container"  class="col-md-12 col-lg-12">  
        <form method="post" enctype="multipart/form-data">                     
                        <div id="content" class="col-lg-2">  
                            <label for="pago_imss"><b>Pago IMSS+Infonavit:</b></label><br>
                            <input type="text" name="pago_imss" class="form-control" value="<?php echo $pago_imssinf;?>" id="pago_imss" />
                        </div>
                        <div id="content" class="col-lg-2"> 
                            <label for="pago_fecha"><b>Expira:</b></label><br>
                            <input type="date" name="pago_fecha" value="<?php echo $pago_imss_fec;?>" class="form-control" id="pago_fecha" />          
                        </div>
                        <div id="content" class="col-lg-1"> 
                            </br>                           
                            <a name="idpagopol" class="btn btn-success" data-toggle="modal" data-target="#ModalPagoSeguro"  class="sepV_a" title="Pago IMSS+Infonavit">Ver </a></td>
                         </div>
                         <?php  if ($editar == 1){ ?>   
                                <div id="content" class="col-lg-3">                          
                                    <label for="pago_img"><b>Archivo PDF:</b></label></br>
                                    <input type="hidden" name="MAX_FILE_SIZE" value="200000000">
                                    <input name="pago_img" type="file" id="pago_img"> 
                                 </div>
                                 <div id="content" class="col-lg-1"> 
                                    <label for="archivo_ant"></label>
                                    <input type="hidden" name="seguro_ant" value="<?php echo $pago_imss_pat;?>" class="form-control" id="seguro_ant" />          
                                 </div>
                                 <div id="content" class="col-lg-2">
                                  <br />
                                        
                                    <td height="26" valign="medium">
                                        <input name="upload_pago" type="submit" class="box btn btn-info" id="upload_pago" value="Actualizar">
                                    </td>
                        <?php  } ?>
                                </div>
        </form>
    </div>
    </div>
    
    <br />
    <br />
    <div class="row"> 
    <div class="container"  class="col-md-12 col-lg-12">  
        <form method="post" enctype="multipart/form-data">                     
                        <div id="content" class="col-lg-2">  
                            <label for="constancia"><b>Constancia DC3:</b></label><br>
                            <input type="text" name="constancia" class="form-control" value="<?php echo $constancia_dc;?>" id="constancia" />
                        </div>
                        <div id="content" class="col-lg-2"> 
                            <label for="constancia_fecha"><b>Expira:</b></label><br>
                            <input type="date" name="constancia_fecha" value="<?php echo $constancia_dc_fec;?>" class="form-control" id="constancia_fecha" />          
                        </div>
                        <div id="content" class="col-lg-1"> 
                            </br>                           
                            <a name="idcons" class="btn btn-success" data-toggle="modal" data-target="#ModalConstancia"  class="sepV_a" title="Constancia">Ver </a></td>
                         </div>
                         <?php  if ($editar == 1){ ?>   
                                <div id="content" class="col-lg-3">                          
                                    <label for="constancia_img"><b>Archivo PDF:</b></label></br>
                                    <input type="hidden" name="MAX_FILE_SIZE" value="200000000">
                                    <input name="constancia_img" type="file" id="constancia_img"> 
                                 </div>
                                 <div id="content" class="col-lg-1"> 
                                    <label for="archivo_ant"></label>
                                    <input type="hidden" name="seguro_ant" value="<?php echo $constancia_dc_pat;?>" class="form-control" id="seguro_ant" />          
                                 </div>
                                 <div id="content" class="col-lg-2">
                                  <br />
                                        
                                    <td height="26" valign="medium">
                                        <input name="upload_constancia" type="submit" class="box btn btn-info" id="upload_constancia" value="Actualizar">
                                    </td>
                        <?php  } ?>
                                </div>
        </form>
    </div>
    </div>
    
    <br />
    <br />
    <div class="row"> 
    <div class="container"  class="col-md-12 col-lg-12">  
        <form method="post" enctype="multipart/form-data">                     
                        <div id="content" class="col-lg-2">  
                            <label for="certificado"><b>Certificado Medico:</b></label><br>
                            <input type="text" name="certificado" class="form-control" value="<?php echo $certificado_m;?>" id="certificado" />
                        </div>
                        <div id="content" class="col-lg-2"> 
                            <label for="certificado_fecha"><b>Fecha:</b></label><br>
                            <input type="date" name="certificado_fecha" value="<?php echo $certificado_m_fec;?>" class="form-control" id="certificado_fecha" />          
                        </div>
                        <div id="content" class="col-lg-1"> 
                            </br>                           
                            <a name="idcertif" class="btn btn-success" data-toggle="modal" data-target="#ModalCertificado"  class="sepV_a" title="Certificado">Ver </a></td>
                         </div>
                         <?php  if ($editar == 1){ ?>   
                                <div id="content" class="col-lg-3">                          
                                    <label for="certificado_img"><b>Archivo PDF:</b></label></br>
                                    <input type="hidden" name="MAX_FILE_SIZE" value="200000000">
                                    <input name="certificado_img" type="file" id="certificado_img"> 
                                 </div>
                                 <div id="content" class="col-lg-1"> 
                                    <label for="archivo_ant"></label>
                                    <input type="hidden" name="seguro_ant" value="<?php echo $certificado_m_path;?>" class="form-control" id="seguro_ant" />          
                                 </div>
                                 <div id="content" class="col-lg-2">
                                  <br />
                                        
                                    <td height="26" valign="medium">
                                        <input name="upload_certificado" type="submit" class="box btn btn-info" id="upload_certificado" value="Actualizar">
                                    </td>
                        <?php  } ?>
                                </div>
        </form>
    </div>
    </div>
    
    <br />
    <br />
    <div class="row"> 
    <div class="container"  class="col-md-12 col-lg-12">  
        <form method="post" enctype="multipart/form-data">                     
                        <div id="content" class="col-lg-2">  
                            <label for="antidoping"><b>Antidoping:</b></label><br>
                            <input type="text" name="antidoping" class="form-control" value="<?php echo $antidoping;?>" id="antidoping" />
                        </div>
                        <div id="content" class="col-lg-2"> 
                            <label for="antidoping_fecha"><b>Fecha:</b></label><br>
                            <input type="date" name="antidoping_fecha" value="<?php echo $antidoping_fec;?>" class="form-control" id="antidoping_fecha" />          
                        </div>
                        <div id="content" class="col-lg-1"> 
                            </br>                           
                            <a name="idantidoping" class="btn btn-success" data-toggle="modal" data-target="#ModalAntidoping"  class="sepV_a" title="Antidoping">Ver </a></td>
                         </div>
                         <?php  if ($editar == 1){ ?>   
                                <div id="content" class="col-lg-3">                          
                                    <label for="antidoping_img"><b>Archivo PDF:</b></label></br>
                                    <input type="hidden" name="MAX_FILE_SIZE" value="200000000">
                                    <input name="antidoping_img" type="file" id="antidoping_img"> 
                                 </div>
                                 <div id="content" class="col-lg-1"> 
                                    <label for="archivo_ant"></label>
                                    <input type="hidden" name="seguro_ant" value="<?php echo $antidoping_path;?>" class="form-control" id="seguro_ant" />          
                                 </div>
                                 <div id="content" class="col-lg-2">
                                  <br />
                                        
                                    <td height="26" valign="medium">
                                        <input name="upload_antidoping" type="submit" class="box btn btn-info" id="upload_antidoping" value="Actualizar">
                                    </td>
                        <?php  } ?>
                                </div>
        </form>
    </div>
    </div>

    <br />
    <br />
    <div class="row"> 
    <div class="container"  class="col-md-12 col-lg-12">  
        <form method="post" enctype="multipart/form-data">                     
                        <div id="content" class="col-lg-2">  
                            <label for="noantepen"><b>No Antecedentes Penales:</b></label><br>
                            <input type="text" name="noantepen" class="form-control" value="<?php echo $no_ant_pen;?>" id="noantepen" />
                        </div>
                        <br>
                        <div id="content" class="col-lg-2"> 
                            <label for="noantepen_fecha"><b>Fecha:</b></label><br>
                            <input type="date" name="noantepen_fecha" value="<?php echo $no_ant_pen_fec;?>" class="form-control" id="noantepen_fecha" />          
                        </div>
                        <div id="content" class="col-lg-1"> 
                            </br>                        
                            <a name="idnoantepen" class="btn btn-success" data-toggle="modal" data-target="#ModalNoAntePen"  class="sepV_a" title="NoAntePen">Ver </a></td>
                         </div>
                         <?php  if ($editar == 1){ ?>
                                <div id="content" class="col-lg-3">                          
                                    <label for="noantepen_img"><b>Archivo PDF:</b></label></br>
                                    <input type="hidden" name="MAX_FILE_SIZE" value="200000000">
                                    <input name="noantepen_img" type="file" id="noantepen_img"> 
                                 </div>
                                 <div id="content" class="col-lg-1"> 
                                    <label for="archivo_ant"></label>
                                    <input type="hidden" name="seguro_ant" value="<?php echo $no_ant_pen_path;?>" class="form-control" id="seguro_ant" />          
                                 </div>
                                 <div id="content" class="col-lg-2">
                                  <br />
                                        
                                    <td height="26" valign="medium">
                                        <input name="upload_noantepen" type="submit" class="box btn btn-info" id="upload_noantepen" value="Actualizar">
                                    </td>
                        <?php  } ?>
                                </div>
        </form>
    </div>
    </div>
    
</div> 
    <?php 
    //Validacion del post ine
    if(isset($_POST['upload_ine']))
    {      
       $ine = $_POST['ine'];
       $fecha_ine = $_POST['fecha_ine'];
       $archivo_eliminar = $_POST['archivo_ant'];
       
       $uploadDir = 'upload/Ine/';
                            
        if (file_exists(Env::get('UPLOAD_ROOT') . $uploadDir) <> 'true') { 
            mkdir (Env::get('UPLOAD_ROOT') . $uploadDir);
        }
                             
        $filePath = $uploadDir;
                             
        $fileName = $_FILES['ine_img']['name'];
        $tmpName  = $_FILES['ine_img']['tmp_name'];
        $fileSize = $_FILES['ine_img']['size'];
        $fileType = $_FILES['ine_img']['type'];
        
        //unlink ($archivo_eliminar); 
        //echo 'TipoAr:'.$fileType;
      
        //Validar si es tipo imagen
        $contenido_imagen = 'image';
        
       // $aqui = strstr($fileType, $contenido_imagen);
       // echo 'ver:'.$aqui;
  //die();
        if (strpos($fileType, $contenido_imagen) !== false){
        
            $maxDoc = $mysqli->query("SELECT cast(MAX(id) AS char(30))  AS maximo_id 
                                     FROM                                    
                                         arg_usuarios_documentos") or die(mysqli_error($mysqli));
            $maxDocID = $maxDoc ->fetch_array(MYSQLI_ASSOC);
            $maxDocCons = $maxDocID['maximo_id']; 
            $maxDocCons = $maxDocCons+1;
            
            $fileName = $maxDocCons.$fileName; 
                                    
            $moverarchivo = $filePath.$fileName;                            
            $result = move_uploaded_file($tmpName, $moverarchivo);
            if (!$result) {
                echo "Error al intentar subir archivo";
                exit;
            }        
                 
            $fileName = addslashes($fileName);
            $filePath = addslashes($filePath);
            
            $archivo = $filePath.$fileName;
       
            if ($ine <> $ine_act)
            {
                $query = "INSERT INTO arg_usuarios_documentos (u_id, tipo_id, nombre, fecha_expira, path, activo ) ".
                "VALUES ($u_id, 2, '$ine', '$fecha_ine', '$archivo', 1)";
                                    
                $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));
                $query = "UPDATE arg_usuarios_documentos
                            SET activo = 0
                          WHERE
                              tipo_id = 2 AND nombre = '".$ine_act."' AND u_id = ".$u_id;
                $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));
                                    
                echo "<br><b>Se guardo con exito:</b><br><br>"."$fileName <br>";
                echo "<script> redireccion(".$u_id.") </script>";
            }
           else{
                $query = "UPDATE arg_usuarios_documentos
                            SET nombre = '$ine', fecha_expira = '$fecha_ine', path = '$archivo'
                          WHERE
                              tipo_id = 2 AND nombre = '".$ine_act."' AND u_id = ".$u_id;
                $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));
                                    
                echo "<br><b>Se guardo con exito:</b><br><br>"."$fileName <br>";
                echo "<script> redireccion(".$u_id.") </script>";
            }
       }
       else{ ?>
           <script>falla_upload_tipo(1);</script>";
          <?php
      }
    }
    
    //Validacion del post licencia
    if(isset($_POST['upload_lic']))
    {      
       $licencia = $_POST['licencia'];
       $fecha_lic = $_POST['fecha_lic'];
       $archivo_eliminar = $_POST['licencia_ant'];
       $uploadDir = 'upload/Licencias/';
       
        if (file_exists(Env::get('UPLOAD_ROOT') . $uploadDir) <> 'true') { 
            mkdir (Env::get('UPLOAD_ROOT') . $uploadDir);
        }
                             
        $filePath = $uploadDir;
                             
        $fileName = $_FILES['licencia_img']['name'];
        $tmpName  = $_FILES['licencia_img']['tmp_name'];
        $fileSize = $_FILES['licencia_img']['size'];
        $fileType = $_FILES['licencia_img']['type'];
        
        $contenido_imagen = 'image';
    
        if (strpos($fileType, $contenido_imagen) !== false){
            $maxDoc = $mysqli->query("SELECT cast(MAX(id) AS char(30))  AS maximo_id 
                                     FROM                                    
                                         arg_usuarios_documentos") or die(mysqli_error($mysqli));
            $maxDocID = $maxDoc ->fetch_array(MYSQLI_ASSOC);
            $maxDocCons = $maxDocID['maximo_id']; 
            $maxDocCons = $maxDocCons+1;
            
            $fileName = $maxDocCons.$fileName; 
            
            $moverarchivo = $filePath.$fileName;                            
            $result = move_uploaded_file($tmpName, $moverarchivo);
            if (!$result) {
                echo "Error al intentar subir archivo";
                exit;
            }

            $fileName = addslashes($fileName);
            $filePath = addslashes($filePath);
            
            $archivo = $filePath.$fileName;
       
            if ($licencia_act <> $licencia)
            {
                $query = "INSERT INTO arg_usuarios_documentos (u_id, tipo_id, nombre, fecha_expira, path, activo ) ".
                "VALUES ($u_id, 3, '$licencia', '$fecha_lic', '$archivo', 1)";
                                    
                $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));
                
                $query = "UPDATE arg_usuarios_documentos
                            SET activo = 0
                          WHERE
                              tipo_id = 3 AND nombre = '".$licencia_act."' AND u_id = ".$u_id;
                $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));
                                    
                echo "<br><b>Se actualiz� con exito:</b><br><br>"."$fileName <br>";
                echo "<script> redireccion(".$u_id.") </script>";
            }
           else{
                $query = "UPDATE arg_usuarios_documentos
                            SET nombre = '$licencia', fecha_expira = '$fecha_lic', path = '$archivo'
                          WHERE
                              tipo_id = 3 AND nombre = '".$licencia_act."' AND u_id = ".$u_id;
                $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));
                                    
                echo "<br><b>Se actualiz� con exito:</b><br><br>"."$fileName <br>";
                echo "<script> redireccion(".$u_id.") </script>";
            }
        }
        else{?>
           <script>falla_upload_tipo(2);</script>";
          <?php
        }
    }
    
    //Validacion del post seguro/gmm
    if(isset($_POST['upload_pol']))
    {      
       $seguro = $_POST['seguro'];
       $fecha_seg = $_POST['fecha_seg'];
       $uploadDir = 'upload/Polizas/';

       if (file_exists(Env::get('UPLOAD_ROOT') . $uploadDir) <> 'true') { 
            mkdir (Env::get('UPLOAD_ROOT') . $uploadDir);
        }
                             
        $filePath = $uploadDir;
                             
        $fileName = $_FILES['poliza_img']['name'];
        $tmpName  = $_FILES['poliza_img']['tmp_name'];
        $fileSize = $_FILES['poliza_img']['size'];
        $fileType = $_FILES['poliza_img']['type'];
        
        //unlink ($archivo_eliminar); 
        
        //Validación del tipo de archivo
         $contenido_imagen = 'pdf';

        if (strpos($fileType, $contenido_imagen) !== false){
            
            $maxDoc = $mysqli->query("SELECT cast(MAX(id) AS char(30))  AS maximo_id 
                                     FROM                                    
                                         arg_usuarios_documentos") or die(mysqli_error($mysqli));
            $maxDocID = $maxDoc ->fetch_array(MYSQLI_ASSOC);
            $maxDocCons = $maxDocID['maximo_id']; 
            $maxDocCons = $maxDocCons+1;
            
            $fileName = $maxDocCons.$fileName;  
            
            $moverarchivo = $filePath.$fileName;                            
            $result = move_uploaded_file($tmpName, $moverarchivo);
            if (!$result) {
                echo "Error al intentar subir archivo";
                exit;
            }

            $fileName = addslashes($fileName);
            $filePath = addslashes($filePath);
            
            $archivo = $filePath.$fileName;
       
            if ($seguro_act <> $seguro)
            {
                $query = "INSERT INTO arg_usuarios_documentos (u_id, tipo_id, nombre, fecha_expira, path, activo ) ".
                "VALUES ($u_id, 1, '$seguro', '$fecha_seg', '$archivo', 1)";
                                    
                $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));
                
                $query = "UPDATE arg_usuarios_documentos
                            SET activo = 0
                          WHERE
                              tipo_id = 1 AND nombre = '".$seguro_act."' AND u_id = ".$u_id;
                $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));
                                    
                echo "<br><b>Se actualiz� con exito:</b><br><br>"."$fileName <br>";
                echo "<script> redireccion(".$u_id.") </script>";
            }
           else{
                $query = "UPDATE arg_usuarios_documentos
                            SET nombre = '$seguro', fecha_expira = '$fecha_seg', path = '$archivo'
                          WHERE
                              tipo_id = 1 AND nombre = '".$seguro_act."' AND u_id = ".$u_id;
                $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));
                                    
                echo "<br><b>Se actualiz� con exito:</b><br><br>"."$fileName <br>"; 
                echo "<script> redireccion(".$u_id.") </script>";
            }
        }
         else{?>
           <script>falla_upload_tipo(3);</script>";
          <?php
        }
    }
    
    //Validacion del post Pago IMSS+Infonavit
    if(isset($_POST['upload_pago']))
    {      
       $pago_imss  = $_POST['pago_imss'];
       $pago_fecha = $_POST['pago_fecha'];
       $uploadDirPago = 'upload/Pagos/';
       
       if (file_exists(Env::get('UPLOAD_ROOT') . $uploadDirPago) <> 'true') { 
            mkdir (Env::get('UPLOAD_ROOT') . $uploadDirPago);
        }
                             
        $filePath = $uploadDirPago;
                             
        $fileName = $_FILES['pago_img']['name'];
        $tmpName  = $_FILES['pago_img']['tmp_name'];
        $fileSize = $_FILES['pago_img']['size'];
        $fileType = $_FILES['pago_img']['type'];
        
        //unlink ($archivo_eliminar);
        
         //Validación del tipo de archivo
         $contenido_imagen = 'pdf';

        if (strpos($fileType, $contenido_imagen) !== false){
            $maxDoc = $mysqli->query("SELECT cast(MAX(id) AS char(30))  AS maximo_id 
                                     FROM                                    
                                         arg_usuarios_documentos") or die(mysqli_error($mysqli));
            $maxDocID = $maxDoc ->fetch_array(MYSQLI_ASSOC);
            $maxDocCons = $maxDocID['maximo_id']; 
            $maxDocCons = $maxDocCons+1;
            
            $fileName = $maxDocCons.$fileName;
            
            $moverarchivo = $filePath.$fileName;                            
            $result = move_uploaded_file($tmpName, $moverarchivo);
            if (!$result) {
                echo "Error al intentar subir archivo";
                exit;
            }
                                
            $fileName = addslashes($fileName);
            $filePath = addslashes($filePath);
            
            $archivo = $filePath.$fileName;
            //echo $pago_imss;
            if ($pago_imss <> $pago_imssinf)
            {
                $query = "INSERT INTO arg_usuarios_documentos (u_id, tipo_id, nombre, fecha_expira, path, activo ) ".
                "VALUES ( $u_id, 4, '$pago_imss', '$pago_fecha', '$archivo', 1 )";
                                    
                $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));
                $query = "UPDATE arg_usuarios_documentos
                            SET activo = 0
                          WHERE
                              tipo_id = 4 AND nombre = '".$pago_imssinf."' AND u_id = ".$u_id;
                $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));
                                    
                echo "<br><b>Se actualiz� con exito:</b><br><br>"."$fileName <br>";
                echo "<script> redireccion(".$u_id.") </script>";
            }
           else{
                $query = "UPDATE arg_usuarios_documentos
                            SET nombre = '$pago_imss', fecha_expira = '$pago_fecha', path = '$archivo'
                          WHERE
                              tipo_id = 4 AND nombre = '".$pago_imssinf."' AND u_id = ".$u_id;
                $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));
                                    
                echo "<br><b>Se actualiz� con exito:</b><br><br>"."$fileName <br>"; 
                echo "<script> redireccion(".$u_id.") </script>";
            }
        }
        else{?>
           <script>falla_upload_tipo(4);</script>";
          <?php
        }
    }
    
    //Validacion del post Constacia DC3
    if(isset($_POST['upload_constancia']))
    {      
       $constancia       = $_POST['constancia'];
       $constancia_fecha = $_POST['constancia_fecha'];
       
       //$archivo_eliminar = $_POST['seguro_ant'];
       
       $uploadDirPago = 'upload/Constancias/';
                            
        if (file_exists(Env::get('UPLOAD_ROOT') . $uploadDirPago) <> 'true') { 
            mkdir (Env::get('UPLOAD_ROOT') . $uploadDirPago);
        }
                             
        $filePath = $uploadDirPago;
                             
        $fileName = $_FILES['constancia_img']['name'];
        $tmpName  = $_FILES['constancia_img']['tmp_name'];
        $fileSize = $_FILES['constancia_img']['size'];
        $fileType = $_FILES['constancia_img']['type'];
        
        //unlink ($archivo_eliminar);
        //Validación del tipo de archivo
         $contenido_imagen = 'pdf';

        if (strpos($fileType, $contenido_imagen) !== false){
            
            $maxDoc = $mysqli->query("SELECT cast(MAX(id) AS char(30))  AS maximo_id 
                                     FROM                                    
                                         arg_usuarios_documentos") or die(mysqli_error($mysqli));
            $maxDocID = $maxDoc ->fetch_array(MYSQLI_ASSOC);
            $maxDocCons = $maxDocID['maximo_id']; 
            $maxDocCons = $maxDocCons+1;
            
            $fileName = $maxDocCons.$fileName;
            
            $moverarchivo = $filePath.$fileName;                            
            $result = move_uploaded_file($tmpName, $moverarchivo);
            if (!$result) {
                echo "Error al intentar subir archivo";
                exit;
            }
                    
            $fileName = addslashes($fileName);
            $filePath = addslashes($filePath);
            
            $archivo = $filePath.$fileName;
            //echo $pago_imss;
            if ($constancia <> $constancia_dc)
            {
                $query = "INSERT INTO arg_usuarios_documentos (u_id, tipo_id, nombre, fecha_expira, path, activo ) ".
                "VALUES ( $u_id, 5, '$constancia', '$constancia_fecha', '$archivo', 1 )";
                                    
                $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));
                $query = "UPDATE arg_usuarios_documentos
                            SET activo = 0
                          WHERE
                              tipo_id = 5 AND nombre = '".$constancia_dc."' AND u_id = ".$u_id;
                $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));
                                    
                echo "<br><b>Se actualiz� con exito:</b><br><br>"."$fileName <br>";
                echo "<script> redireccion(".$u_id.") </script>";
            }
           else{
                $query = "UPDATE arg_usuarios_documentos
                            SET nombre = '$constancia', fecha_expira = '$constancia_fecha', path = '$archivo'
                          WHERE
                              tipo_id = 5 AND nombre = '".$constancia_dc."' AND u_id = ".$u_id;
                $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));
                                    
                echo "<br><b>Se actualiz� con exito:</b><br><br>"."$fileName <br>"; 
                echo "<script> redireccion(".$u_id.") </script>";
            }
        }
        else{?>
           <script>falla_upload_tipo(5);</script>";
          <?php
      } 
    }
    
    //Validacion del post Certificado Medico
    if(isset($_POST['upload_certificado']))
    {      
       $certificado_fol   = $_POST['certificado'];
       $certificado_fecha = $_POST['certificado_fecha'];
       
       //$archivo_eliminar = $_POST['seguro_ant'];
       
       $uploadDirCer = 'upload/Certificados/';
                            
        if (file_exists(Env::get('UPLOAD_ROOT') . $uploadDirCer) <> 'true') { 
            mkdir (Env::get('UPLOAD_ROOT') . $uploadDirCer);
        }
        //echo $uploadDir;
                             
        $filePath = $uploadDirCer;
                             
        $fileName = $_FILES['certificado_img']['name'];
        $tmpName  = $_FILES['certificado_img']['tmp_name'];
        $fileSize = $_FILES['certificado_img']['size'];
        $fileType = $_FILES['certificado_img']['type'];
        
        //unlink ($archivo_eliminar);
        
        //Validación del tipo de archivo
         $contenido_imagen = 'pdf';

        if (strpos($fileType, $contenido_imagen) !== false){
            
            $maxDoc = $mysqli->query("SELECT cast(MAX(id) AS char(30))  AS maximo_id 
                                     FROM                                    
                                         arg_usuarios_documentos") or die(mysqli_error($mysqli));
            $maxDocID = $maxDoc ->fetch_array(MYSQLI_ASSOC);
            $maxDocCons = $maxDocID['maximo_id']; 
            $maxDocCons = $maxDocCons+1;
            
            $fileName = $maxDocCons.$fileName;
            
            $moverarchivo = $filePath.$fileName;                            
            $result = move_uploaded_file($tmpName, $moverarchivo);
            if (!$result) {
                echo "Error al intentar subir archivo";
                exit;
            }
              
            $fileName = addslashes($fileName);
            $filePath = addslashes($filePath);
            
            $archivo = $filePath.$fileName;
            //echo $pago_imss;
            if ($certificado_fol <> $certificado_m)
            {
                $query = "INSERT INTO arg_usuarios_documentos (u_id, tipo_id, nombre, fecha_expira, path, activo ) ".
                "VALUES ( $u_id, 6, '$certificado_fol', '$certificado_fecha', '$archivo', 1 )";
                                    
                $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));
                $query = "UPDATE arg_usuarios_documentos
                            SET activo = 0
                          WHERE
                              tipo_id = 6 AND nombre = '".$certificado_m."' AND u_id = ".$u_id;
                $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));
                                    
                echo "<br><b>Se actualiz� con exito:</b><br><br>"."$fileName <br>";
                echo "<script> redireccion(".$u_id.") </script>";
            }
           else{
                $query = "UPDATE arg_usuarios_documentos
                            SET nombre = '$certificado_fol', fecha_expira = '$certificado_fecha', path = '$archivo'
                          WHERE
                              tipo_id = 6 AND nombre = '".$certificado_m."' AND u_id = ".$u_id;
                $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));
                                    
                echo "<br><b>Se actualiz� con exito:</b><br><br>"."$fileName <br>"; 
                echo "<script> redireccion(".$u_id.") </script>";
            }
        }
        else{?>
           <script>falla_upload_tipo(6);</script>";
          <?php
        } 
    }
    
    //Validacion del post Antidoping
    if(isset($_POST['upload_antidoping']))
    {      
       $antidoping_fol   = $_POST['antidoping'];
       $antidoping_fecha = $_POST['antidoping_fecha'];
       
       //$archivo_eliminar = $_POST['seguro_ant'];
       
       $uploadDirAntid = 'upload/Antidoping/';
                            
        if (file_exists(Env::get('UPLOAD_ROOT') . $uploadDirAntid) <> 'true') { 
            mkdir (Env::get('UPLOAD_ROOT') . $uploadDirAntid);
        }
                             
        $filePath = $uploadDirAntid;
                             
        $fileName = $_FILES['antidoping_img']['name'];
        $tmpName  = $_FILES['antidoping_img']['tmp_name'];
        $fileSize = $_FILES['antidoping_img']['size'];
        $fileType = $_FILES['antidoping_img']['type'];
        
        //unlink ($archivo_eliminar);
        
        //Validación del tipo de archivo
         $contenido_imagen = 'pdf';

        if (strpos($fileType, $contenido_imagen) !== false){
            $maxDoc = $mysqli->query("SELECT cast(MAX(id) AS char(30))  AS maximo_id 
                                     FROM                                    
                                         arg_usuarios_documentos") or die(mysqli_error($mysqli));
            $maxDocID = $maxDoc ->fetch_array(MYSQLI_ASSOC);
            $maxDocCons = $maxDocID['maximo_id']; 
            $maxDocCons = $maxDocCons+1;
            
            $fileName = $maxDocCons.$fileName;
            
            $moverarchivo = $filePath.$fileName;                            
            $result = move_uploaded_file($tmpName, $moverarchivo);
            if (!$result) {
                echo "Error al intentar subir archivo";
                exit;
            }
               
            $fileName = addslashes($fileName);
            $filePath = addslashes($filePath);
            
            $archivo = $filePath.$fileName;
            //echo $pago_imss;
            if ($antidoping_fol <> $antidoping)
            {
                $query = "INSERT INTO arg_usuarios_documentos (u_id, tipo_id, nombre, fecha_expira, path, activo ) ".
                "VALUES ( $u_id, 7, '$antidoping_fol', '$antidoping_fecha', '$archivo', 1 )";
                                    
                $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));
                $query = "UPDATE arg_usuarios_documentos
                            SET activo = 0
                          WHERE
                              tipo_id = 7 AND nombre = '".$antidoping."' AND u_id = ".$u_id;
                $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));
                                    
                echo "<br><b>Se actualiz� con exito:</b><br><br>"."$fileName <br>";
                echo "<script> redireccion(".$u_id.") </script>";
            }
           else{
                $query = "UPDATE arg_usuarios_documentos
                            SET nombre = '$antidoping_fol', fecha_expira = '$antidoping_fecha', path = '$archivo'
                          WHERE
                              tipo_id = 7 AND nombre = '".$antidoping."' AND u_id = ".$u_id;
                $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));
                                    
                echo "<br><b>Se actualiz� con exito:</b><br><br>"."$fileName <br>"; 
                echo "<script> redireccion(".$u_id.") </script>";
            }
        }
         else{?>
           <script>falla_upload_tipo(7);</script>";
          <?php
        } 
    }

    if(isset($_POST['upload_noantepen']))
    {      
       $noantepen_fol   = $_POST['noantepen'];
       $noantepen_fecha = $_POST['noantepen_fecha'];
       $uploadDirNoAntPen = 'upload/NoAntecedentesPenales/';
                            
        if (file_exists(Env::get('UPLOAD_ROOT') . $uploadDirNoAntPen) <> 'true') { 
            mkdir (Env::get('UPLOAD_ROOT') . $uploadDirNoAntPen);
        }
                             
        $filePath = $uploadDirNoAntPen;
                             
        $fileName = $_FILES['noantepen_img']['name'];
        $tmpName  = $_FILES['noantepen_img']['tmp_name'];
        $fileSize = $_FILES['noantepen_img']['size'];
        $fileType = $_FILES['noantepen_img']['type'];
        
        //unlink ($archivo_eliminar);
        
        //Validación del tipo de archivo
         $contenido_imagen = 'pdf';

        if (strpos($fileType, $contenido_imagen) !== false){
            $maxDoc = $mysqli->query("SELECT cast(MAX(id) AS char(30))  AS maximo_id 
                                     FROM                                    
                                         arg_usuarios_documentos") or die(mysqli_error($mysqli));
            $maxDocID = $maxDoc ->fetch_array(MYSQLI_ASSOC);
            $maxDocCons = $maxDocID['maximo_id']; 
            $maxDocCons = $maxDocCons+1;
            
            $fileName = $maxDocCons.$fileName;
            
            $moverarchivo = $filePath.$fileName;                            
            $result = move_uploaded_file($tmpName, $moverarchivo);
            if (!$result) {
                echo "Error al intentar subir archivo";
                exit;
            }
               
            $fileName = addslashes($fileName);
            $filePath = addslashes($filePath);
            
            $archivo = $filePath.$fileName;
            //echo $pago_imss;
            if ($noantepen_fol <> $no_ant_pen)
            {
                $query = "INSERT INTO arg_usuarios_documentos (u_id, tipo_id, nombre, fecha_expira, path, activo ) ".
                "VALUES ( $u_id, 8, '$noantepen_fol', '$noantepen_fecha', '$archivo', 1 )";
                                    
                $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));
                $query = "UPDATE arg_usuarios_documentos
                            SET activo = 0
                          WHERE
                              tipo_id = 8 AND nombre = '".$no_ant_pen."' AND u_id = ".$u_id;
                $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));
                                    
                echo "<br><b>Se actualiz� con exito:</b><br><br>"."$fileName <br>";
                echo "<script> redireccion(".$u_id.") </script>";
            }
           else{
                $query = "UPDATE arg_usuarios_documentos
                            SET nombre = '$noantepen_fol', fecha_expira = '$noantepen_fecha', path = '$archivo'
                          WHERE
                              tipo_id = 8 AND nombre = '".$no_ant_pen."' AND u_id = ".$u_id;
                $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));
                                    
                echo "<br><b>Se actualiz� con exito:</b><br><br>"."$fileName <br>"; 
                echo "<script> redireccion(".$u_id.") </script>";
            }
        }
        else{?>
           <script>falla_upload_tipo(8);</script>";
          <?php
        }
    }          
    } 
?>
         
          

