<? include "connections/config.php";
set_include_path(get_include_path() . PATH_SEPARATOR . "/xampp/htdocs/registro/dompdf");
require_once 'autoload.inc.php';
use Dompdf\Dompdf;
include 'dompdf/eds_dompdf_prep.php';
require('\xampp\htdocs\registro\mod_html2fpdf\html2fpdf.php');

if (isset($_GET['trn_id'])){
    
//$pdf = new HTML2FPDF();

$pdf = new HTML2FPDF('L','mm','A4');

$pdf_style = '';

$pdf_style .= '@page { margin-top: 240px; margin-bottom: 190px; margin-left: 0px; margin-right: 0px; }';
$pdf_style .= 'html, body, table { font-family: helvetica; font-size: 100%; }';
$pdf_style .= '#header { position: fixed; left: 15px; top: -245px; right: 0px; height: 140px; text-align: center;font-size: 100% }';
$pdf_style .= '#footer { position: fixed; left: 15px; bottom: -20px; right: 0px; height: 150px; }';
$pdf_style .= '#footer.page:after { content: counter(page, upper-roman); }';
$pdf_style .= '';

            $trn_id_rel = $_GET['trn_id'];
            $empresa_encab = $mysqli->query("SELECT
                                            	arg_organizaciones.nombre, arg_organizaciones.calle, arg_organizaciones.num_exterior, arg_organizaciones.colonia, arg_ciudades.ciudad
                                            FROM `arg_entradas` 
                                            LEFT JOIN arg_usuarios
                                            	ON arg_usuarios.u_id = arg_entradas.usuario_id
                                            LEFT JOIN arg_organizaciones
                                                ON arg_usuarios.org_id = arg_organizaciones.org_id
                                            LEFT JOIN arg_ciudades 
                                                ON arg_ciudades.ciudad_id = arg_organizaciones.ciudad_id
                                            WHERE trn_id = ".$trn_id_rel) or die(mysqli_error());
                                            $empresa = $empresa_encab ->fetch_array(MYSQLI_ASSOC);
            $datos_v = $mysqli->query("SELECT arg_actividad.nombre FROM arg_entradas_actividad 
                                       LEFT JOIN arg_actividad 
                                            ON arg_actividad.act_id = arg_entradas_actividad.act_id
                                       WHERE trn_id_rel = ".$trn_id_rel) or die(mysqli_error()); 
            $datos_e = $mysqli->query("SELECT folio, DATE_FORMAT(fecha,'%d/%m/%Y') AS fecha, DATE_FORMAT(fecha_inicio,'%d/%m/%Y') AS fecha_inicio, DATE_FORMAT(fecha_final,'%d/%m/%Y') AS fecha_final, arg_empr_unidades.nombre AS unidad, arg_usuarios.nombre AS usuario,
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
                                            WHERE trn_id_rel = ".$trn_id_rel) or die(mysqli_error());
             $usuario_visitante = $visitantes->fetch_assoc(); 
             $visitantes_imss = $mysqli->query("SELECT arg_usuarios.nombre AS visitante, usuarios_doc.imss AS imss
                                            FROM `arg_entradas_detalle`                                         
                                            LEFT JOIN arg_usuarios
                                            	ON arg_entradas_detalle.usuario_id = arg_usuarios.u_id
                                            LEFT JOIN usuarios_doc
                                            	ON usuarios_doc.u_id = arg_entradas_detalle.usuario_id
                                                AND tipo_id = 1
                                            WHERE trn_id_rel = ".$trn_id_rel) or die(mysqli_error());
            $usuario_imss = $visitantes_imss->fetch_assoc();                               
            $vehiculos = $mysqli->query("SELECT arg_usuarios_documentos.nombre, arg_usuarios_documentos.fecha_expira, arg_vehiculos.marca, arg_vehiculos.modelo, arg_vehiculos.placas
                                            FROM `arg_entradas` 
                                            LEFT JOIN arg_vehiculos
                                                ON arg_vehiculos.veh_id = arg_entradas.veh_id
                                            LEFT JOIN arg_usuarios_documentos
                                            	ON arg_entradas.usuario_id = arg_usuarios_documentos.u_id
                                                AND tipo_id = 3
                                            WHERE trn_id = ".$trn_id_rel) or die(mysqli_error());
            $herramientas = $mysqli->query("SELECT arg_entradas_herramientas.cantidad, arg_herramientas.nombre, arg_herramientas.marca
                                                FROM `arg_entradas_herramientas` 
                                                LEFT JOIN arg_herramientas
                                                	ON arg_herramientas.herr_id = arg_entradas_herramientas.herr_id
                                                WHERE trn_id_rel = ".$trn_id_rel) or die(mysqli_error());           
        

                 $html_en.= "<table>
                                 <tr>   
                                    <th scope='col'>Unidad de Mina: ".$entrada['unidad']."</th>
                                    <th scope='col' colspan='6'></th>
                                    <th scope='col' colspan='6'></th>
                                    <th scope='col'>Visita Desde: ".$entrada['fecha_inicio']."</th>
                                    <th scope='col'>Hasta: ".$entrada['fecha_final']."</th>
                                  </tr>
                                  <tr> 
                                    <td>  <img src='..images/encabezado_visita.jpg'> </td>        
                                   
                                  </tr>
                            </table>";
                    
                  $html_m = "<table id='motivos'>                               
                                <tr>            
                                    <th scope='col'>Motivos de la Visita</th>
                                </tr>                         
                            <td>";            
                            	while ($fila = $datos_v->fetch_assoc()) {               	                     
                            		$html_m.="<a>".$fila['nombre']."</a>";
                            	}
                  $html_m.="</td></table>";
                 
                 /* $html_c = "<table class='table table-bordered' id='comentarios'>
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
                                    <th scope='col'>Visitantes</th>
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
                                                <td>".$fila_ve['placa']."</td>
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
                  $html_veh.="</tbody></table>";*/
                    // echo ("$pdf_header");
                      /*echo ("$html_m");
                      echo ("$html_c");
                      echo ("$html_v");
                      echo ("$html_veh");*/
               
        }

$pdf_header = '';
$img_factor = 1.0;//0.95;

$par_x = 3;
$par_y = 68;

//Encabezado
$ifactor = 220 / 950;
$iwidth = 800;
$pdf_header .= "<CXY X='15' Y='15'></CXY>";
$pdf_header .= '<img width="' . (floor($iwidth * $img_factor)) . '" height="' . (floor(($iwidth * $ifactor) * $img_factor)) . '" src="http://192.168.20.3:81/registro/images/encabezado_visita.jpg">';
$pdf_header .= '<cfs FONTSIZE="5"></cfs>';
$pdf_header .= '<CXY X="' . $par_x . '" Y="' . ($par_y + 50) . '"></CXY>';
$section_html .= '<strong>Nombre: </strong>'.$empresa['nombre'].'<br />';
$section_html .= '<CXY X="' . $par_x . '" Y="' . ($par_y + 20) . '"></CXY>';
$section_html .= '<strong>Dirección: </strong>'.$empresa['calle'].' '.$empresa['num_exterior'].', '.$empresa['colonia'].', '.$empresa['ciudad'].'<br />';
$section_html .= '<CXY X="' . $par_x . '" Y="' . ($par_y + 10) . '"></CXY>';
$section_html .= '<strong>Responsable de la Visita: </strong>'.$usuario_resp['responsable'].'<br/>';
$section_html .= '<strong> Atiende: </strong>'.$usuario_atiende['atiende'].'<br/>';
$section_html .= '<strong>'.$entrada['unidad'].'</strong><br/><br/>';

$par_options = array();
$par_options['top'] = '80px;';
$par_options['left'] = '220px';
$par_options['font-size'] = '13px;';
$pdf_header .= par_place($section_html, $par_options);

$par_options = array();
$par_options['top'] = '168px;';
$par_options['left'] = '220px';
$par_options['font-size'] = '13px;';

   $section_html2 .= '<CXY X="' . $par_x . '" Y="' . ($par_y + 20) . '"></CXY>';
   $section_html2 .= '<strong>'.$entrada['fecha_inicio'].'</strong>';
$pdf_header .= par_place($section_html2, $par_options);    

   $section_html3 .= '<CXY X="' . ($par_x+390) . '" Y="' . ($par_y + 20) . '"></CXY>';
   $section_html3 .= '<strong>'.$entrada['fecha_final'].'</strong>'; 

$par_options = array();
$par_options['top'] = '168px;';
$par_options['left'] = '360px';
$par_options['font-size'] = '13px;';
$pdf_header .= par_place($section_html3, $par_options);

   $section_html4 .= '<CXY X="' . ($par_x+390) . '" Y="' . ($par_y + 20) . '"></CXY>';
   $section_html4 .= '<strong>'.$entrada['fecha'].'</strong>'; 

$par_options = array();
$par_options['top'] = '168px;';
$par_options['left'] = '590px';
$par_options['font-size'] = '13px;';
$pdf_header .= par_place($section_html4, $par_options); 

$par_options = array();
$par_options['top']  = '200px;';
$par_options['left'] = '20px';
$par_options['font-size'] = '12px;';
$pdf_header2 .='Máximo 3 Días; Los   Gafetes de  Acceso se  entregan y regresan a Seguridad  Patrimonial Diariamente;'.'<br/>';
$pdf_header2 .='Para permisos por  mas de tres días y  para realizar trabajos se deberá asistir al Curso de Inducción'.'<br/>';
$pdf_header2 .='a la Seguridad el cual se imparte solo en días Lunes y Jueves de cada semana de 08:00 a 12:00 del día.'.'<br/>';

$pdf_header .= par_place($pdf_header2, $par_options); 

$img_factor = 1.0;//0.95;
///////Footer
$ifactor = 220 /950;
$iwidth = 400;
$pdf_footer  = "<CXY X='15' Y='150'></CXY>";
$pdf_footer .= '<img width="800" height="320" src="http://192.168.20.3:81/registro/images/pie_visita.jpg">';

//'<img width="' . (floor($iwidth * $img_factor)) . '" height="' . (floor(($iwidth * $ifactor) * $img_factor)) . '" src="http://192.168.20.3:81/registro/images/encabezado_visita.jpg">';

//$pdf_footer = $html_m;

///Contenido
$ifactor = 220 /950;
$iwidth = 300;
$section_html = '';
$pdf_html  = "<CXY X='25' Y='150'></CXY>";
$pdf_html .= '<img width="815" height="50" src="http://192.168.20.3:81/registro/images/detalle_visita.jpg">';
$section_html .= '<CXY X="' . $par_x . '" Y="' . ($par_y + 60) . '"></CXY>';
$section_html .= $usuario_visitante['visitante'];

$par_options = array();
$par_options['top'] = '60px;';
$par_options['left'] = '60px';
$par_options['font-size'] = '13px;';
$pdf_html .= par_place($section_html, $par_options);

$section_html6 .= $usuario_imss['imss'];

$par_options = array();
$par_options['top'] = '60px;';
$par_options['left'] = '550px';
$par_options['font-size'] = '13px;';
$pdf_html .= par_place($section_html6, $par_options);

$section_html7 .= $usuario_visitante['ine'];

$par_options = array();
$par_options['top'] = '60px;';
$par_options['left'] = '680px';
$par_options['font-size'] = '13px;';
$pdf_html .= par_place($section_html7, $par_options);
//$pdf_html = $html_en;        

$options = array();
$options["isRemoteEnabled"] = true;

$pdf = new Dompdf($options);
$pdf->setPaper('letter');

$pdf_content = '';

$pdf_content .= '<html>';
$pdf_content .= '<head>';
$pdf_content .= '<style>';
$pdf_content .= $pdf_style;
$pdf_content .= '</style>';
$pdf_content .= '<body>';
$pdf_content .= '<div id="header">';
$pdf_content .= $pdf_header;
$pdf_content .= '</div>';
$pdf_content .= '<div id="footer">';
$pdf_content .= $pdf_footer;
$pdf_content .= '</div>';
$pdf_content .= '<div id="content">';
$pdf_content .= $pdf_html;
$pdf_content .= '</div>';
$pdf_content .= '</body>';
$pdf_content .= '</html>';


$replace_what = array('á','é','í','ó','ú','ñ','Á','É','Í','Ó','Ú','Ñ');
$replace_with = array('&aacute;','&eacute;','&iacute;','&oacute;','&uacute;','&ntilde;','&Aacute;','&Eacute;','&Iacute;','&Oacute;','&Uacute;','&Ntilde;');

$pdf_content = str_replace($replace_what, $replace_with, $pdf_content);

$pdf->loadHtml($pdf_content);

$output_options = array();

$output_options["Accept-Ranges"] = 1;
$output_options["Attachment"] = 0;

$pdf->render();

$pdf->stream($file_name . ".pdf", $output_options);

file_put_contents($file_path . $file_name . '.pdf', $pdf->output());

/*                
$pdf->htmlHeader = $pdf_header;
$pdf->tMargin = 55;
 //$pdf_header = ob_get_clean();
// Pie de página

$pdf_footer .= $pdf_html;
$pdf->htmlBeforePageText = '';

$pdf->htmlFooter = $pdf_footer;
//$pdf->WriteHTML($pdf_html); 

$pdf->Output('../registro/argonaut4.pdf');
        
if ($_GET['html'] == 1) {
	print $pdf_header;
	print $pdf_html;
	print $pdf_footer;
} else {
		header('Accept-Ranges: bytes');
        
    	//$pdf->Output();
    $mi_pdf = '/xampp/htdocs/registro/argonaut4.pdf';
    $mi_pdf = '../registro/argonaut4.pdf';
    header('Content-type: application/pdf');
    header('Content-Disposition: attachment; filename="'.$mi_pdf.'"');
    readfile($mi_pdf);
}
*/          

?>
