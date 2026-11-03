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

while ($row = $idProv->fetch_array(MYSQLI_ASSOC)) {
    $org_id = $row['id'];
}
mysqli_multi_query ($mysqli, "CALL visor_vehiculos (".$org_id.")") OR DIE (mysqli_error($mysqli));
$html_per = "<table class='table table-bordered' id='encabezadop'>
<thead>
    <tr class='table-info'>   
    <th scope='col'>Empresa</th>
    <th scope='col'>Placas</th>
    <th scope='col'>Marca</th>
    <th scope='col'>Modelo</th>
    <th scope='col'>Color</th>
    <th scope='col'>Póliza</th>
    <th scope='col'>Ckeck List</th>
    </tr>
</thead>
<tbody>";
if ($result_per = mysqli_store_result($mysqli)) {                
        while ($row = mysqli_fetch_assoc($result_per)) {
                $html_per.="<tr>
                <td> ".$row["empresa"]."</td>                                                
                <td><a href=\"doc_vehiculos.php?placas=".$row["placas"]."&unidad=".$unidad_mina_sel."\" ><font color=blue><b>".$row["placas"]."</a></td>
                <td> ".$row["marca"]."</td>
                <td> ".$row["modelo"]."</td>
                <td> ".$row["color"]."</td>
                <td> ".$row["poliza"]."</td>";
                
                if (in_array($row['veh_id'], $ids)) {
            $html_per .= "<td align='center'> <button type='button' class='open-vehModalVer btn btn-info btn-md' data-toggle='modal' data-target='#ModalVehVer' data-id='" . $row["veh_id"] . "'><i class='fa fa-check' aria-hidden='true'> Ver </i></button></td>";
        } else {
            if($org_id <>0){
            $html_per .= "<td align='center'> <button type='button' class='open-vehModal btn btn-info btn-md' data-toggle='modal' data-target='#ModalVeh' data-id='" . $row["veh_id"] . "><i aria-hidden='true'> Agregar </i></button></td>";
            }
        }
                $html_per .= "</tr>";
        }
        mysqli_free_result($result_per);
}
$html_per.="</tbody></table>";
echo ("$html_per");   
?>