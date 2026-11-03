<?php

use Dompdf\FrameDecorator\TableRow;
use Dompdf\Positioner\TableRow as PositionerTableRow;
use LDAP\Result;

include "config/config.php";
$html_en='';
$org_id=0;
$nombre = $_POST['nombre'];

$query = "SELECT id FROM `arg_organizaciones` WHERE nombre = '".$nombre."';";
$idProv = $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));
$row = $idProv->fetch_array(MYSQLI_ASSOC);

mysqli_multi_query ($mysqli, "CALL visor_empresas (".$org_id.")") OR DIE (mysqli_error($mysqli));
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
                  </tr>
            </thead>
            <tbody>";
if ($result = mysqli_store_result($mysqli)) {                
   while ($row = mysqli_fetch_assoc($result)) {
         $id_org = $row["org_id"];
         $html_en.="<tr>
            <td> ".$row["empresa"]."</td>
            <td> ".$row["rfc"]."</td>
            <td> ".$row["calle"]."</td>
            <td> ".$row["colonia"]."</td>
            <td> ".$row["ciudad"]."</td>
            <td> ".$row["localidad"]."</td>
            <td> ".$row["codigo_postal"]."</td>

         </tr>";
   }
   mysqli_free_result($result);
}
$html_en.="</tbody></table>";
echo ("$html_en"); 
?>