<?php
include "connections/config.php";
$org_id = $_SESSION['org_id'];

if ($org_id == 0) {
    $replace = 'em.org_id = em.org_id';
}
else {
    $replace = 'em.org_id = '.$org_id;
}

$html = '';

if (isset($_POST['consulta'])) {
    $q = ($_POST['consulta']);
    $resultado = $mysqli->query("SELECT id, nombre
                                    FROM arg_organizaciones em 
                                    WHERE 
                                    ".$replace."
                                    AND em.nombre LIKE '%".strip_tags($q)."%' UNION 
                                    SELECT id, nombre
                                    FROM arg_usuarios em
                                    WHERE ".$replace."
                                    AND nombre LIKE '%".strip_tags($q)."%'") 
                            OR DIE(mysqli_error($mysqli));
                            if ($resultado->num_rows > 0) {
    $html.="<div id='datos_muestra'> 
            <table>
                <tbody>";    
    while ($row = $resultado->fetch_assoc()) {
        $html .= '<div>
                    <a class="suggest-element" data="'.$row['nombre'].'" id="id'.$row['id'].'">'.$row['nombre'].'</a>
                </div>';      
        }
        
        $html .= '</tbody></table></div>';
    }
}

echo $html;