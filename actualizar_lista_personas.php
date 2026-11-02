<?php

use Dompdf\FrameDecorator\TableRow;
use Dompdf\Positioner\TableRow as PositionerTableRow;
use LDAP\Result;

include "connections/config.php";

$mysqli -> set_charset("utf8");
$html_en = '';
$org_id  = $_SESSION['org_id'];
$nombre  = $_POST['nombre'];
$ChecarNombrePersona =  $mysqli->query("SELECT u_id FROM `arg_usuarios` WHERE nombre = '".$nombre."';");
$rowPersona = $ChecarNombrePersona->fetch_array(MYSQLI_ASSOC);
$unidad_mina_sel = '';

$query = "SELECT id FROM `arg_organizaciones` WHERE nombre = '".$nombre."';";
$idProv = $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));
$row = $idProv->fetch_array(MYSQLI_ASSOC);

if ($rowPersona['u_id']!='') { 
        $u_id = $rowPersona['u_id'];
        mysqli_multi_query ($mysqli, "CALL visor_personas_uid (".$u_id.")") OR DIE (mysqli_error($mysqli));
                    $html_per = "<table class='table table-bordered' id='encabezadop'>
                    <thead>
                        <tr class='table-info'>   
                        <th scope='col'>Empresa</th>
                        <th scope='col'>Nombre</th>
                        <th scope='col'>Email</th>
                        <th scope='col'>INE</th>
                        <th scope='col'>IMSS</th>
                        <th scope='col'>Licencia</th>
                        <th scope='col'>Cursos</th>
                        </tr>
                    </thead>
                    <tbody>";
                    if ($result_per = mysqli_store_result($mysqli)) {                
                            while ($row = mysqli_fetch_assoc($result_per)) {
                                    $html_per.="<tr>
                                    <td> ".$row["empresa"]."</td>
                                    <td><a href=\"doc_personas.php?u_id=".$row["u_id"]."&unidad=".$unidad_mina_sel."\" ><font color=blue><b>".$row["nombre"]."</a></td> 
                                    <td> ".$row["email"]."</td>
                                    <td> ".$row["ine"]."</td>
                                    <td> ".$row["seguro"]."</td>
                                    <td> ".$row["licencia"]."</td>
                                    <td> ".$row["cursos"]."</td>
                                    </tr>";
                            }
                            mysqli_free_result($result_per);
                    }
                    $html_per.="</tbody></table>";
                    echo ("$html_per");  
}
else {
        $org_id = $row['id'];
        mysqli_multi_query ($mysqli, "CALL visor_personas (".$org_id.")") OR DIE (mysqli_error($mysqli));
        $html_per = "<table class='table table-bordered' id='encabezadop'>
                        <thead>
                        <tr class='table-info'>   
                        <th scope='col'>Empresa</th>
                        <th scope='col'>Nombre</th>
                        <th scope='col'>Email</th>
                        <th scope='col'>INE</th>
                        <th scope='col'>IMSS</th>
                        <th scope='col'>Licencia</th>
                        <th scope='col'>Cursos</th>
                        </tr>
                        </thead>
                <tbody>";
                
        if ($result_per = mysqli_store_result($mysqli)) {                
                while ($row = mysqli_fetch_assoc($result_per)) {
                        $html_per.="<tr>
                        <td> ".$row["empresa"]."</td>
                        <td><a href=\"doc_personas.php?u_id=".$row["u_id"]."&unidad=".$unidad_mina_sel."\" ><font color=blue><b>".$row["nombre"]."</a></td> 
                        <td> ".$row["email"]."</td>
                        <td> ".$row["ine"]."</td>
                        <td> ".$row["seguro"]."</td>
                        <td> ".$row["licencia"]."</td>
                        <td> ".$row["cursos_vigentes"]."</td>
                        </tr>";
                }
                mysqli_free_result($result_per);
        }
        $html_per.="</tbody></table>";
        echo ("$html_per");   
}
?>