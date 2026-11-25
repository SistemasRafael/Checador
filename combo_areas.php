<?php
include "config/config.php";

$html = '';
$unidad_id  = $_POST['unidad_id'];
$name = $_POST['name_id_area'];

if (isset($unidad_id)){
 $mysqli -> set_charset("utf8");
       
           $html =  "<div class='col-md-11 col-lg-11'>
                        <select name='".$name."' id='".$name."' class='form-control'>";
                           $result_a = $mysqli->query("SELECT 0 AS area_id, ' Area: ' AS                             
                                                            nombre UNION ALL
                                                        SELECT area_id, area FROM `arg_unidades_areas` 
                                                        WHERE
                                                            unidad_id = ".$unidad_id." ORDER BY nombre") or die(mysqli_error($mysqli));                             
                                              while ( $row7 = $result_a ->fetch_array(MYSQLI_ASSOC)) {                                     
                                               $html.= "<option value=".$row7['area_id'].">".$row7['nombre']."</option>";
                                              }
            $html.="</select></div>";
                
        echo ($html);
    }
?>