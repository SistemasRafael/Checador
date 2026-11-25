<?php 
include "config/config.php";

$unidad_id = $_GET['unidad_id'];
$fecha_i   = $_GET['fecha_i'] ?? '';
$fecha_f   = $_GET['fecha_f'] ?? '';
$u_id      = $_SESSION['u_id'];
//echo 'user'.$u_id;
        
if (is_null($fecha_i)){
    $fecha_i = date('Y-m-d',strtotime("-30 days"));
}
if (is_null($fecha_f)){
    $fecha_f = date('Y-m-d');
}

$check_id_sel = $mysqli->query("SELECT
              (CASE WHEN division = 'empleado' THEN 1
                    WHEN division = 'proveedor' THEN 100
              ELSE 0 END) AS division 
        FROM arg_usuarios WHERE u_id = ".$u_id);
$check_id_se = $check_id_sel->fetch_array(MYSQLI_ASSOC);
$check_id = $check_id_se['division'];

if ($check_id = 1)
{ 
    $org_id = 0;
}

if ($check_id = 100)
{ 
    $org_id_fir  = $mysqli->query("SELECT
                                        org_id
                                  FROM arg_usuarios WHERE u_id = " . $u_id);
    $org_id_firm = $org_id_fir->fetch_assoc();
    $org_id      = $org_id_firm['org_id'];
}
//echo $org_id;
// $org_id = 0;
$datos = $mysqli->query(
    "SELECT 
         us.nombre as nombre
        ,us.u_id as u_id
        ,us.codigo as codigo
        ,us.email as email 
        ,us.org_id as org_id
        ,cu.fecha as fecha
        ,cc.unidad_id as unidad
        ,eu.nombre as unidad_nombre
        ,og.nombre AS organizacion
    FROM 
        arg_usuarios us 
    INNER JOIN arg_calendario_usuarios cu ON cu.u_id = us.u_id 
    INNER JOIN arg_calendario_cursos cc ON cc.cal_id = cu.cal_id 
    INNER JOIN arg_empr_unidades eu ON eu.unidad_id = cc.unidad_id
    INNER JOIN arg_organizaciones og ON og.org_id = us.org_id
    WHERE 
        us.division = 'proveedor' 
        AND og.org_id = (CASE WHEN ".$org_id." = 0 THEN og.org_id ELSE ".$org_id." END)
        AND cc.unidad_id = ".$unidad_id."
        AND date_format(cu.fecha, '%Y-%m-%d') BETWEEN '".$fecha_i."' AND '".$fecha_f."'
   ORDER BY cu.fecha ASC;"
) or die(mysqli_error($mysqli));

?>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#motivos').DataTable();
    });
    $(document).ready(function() {
        $("#unidad_id_ex").on("change", function() {
            var value = $("#unidad_id_ex option:selected").text();
            console.log(value);
            $("#motivos tbody tr").filter(function() {
                $(this).toggle($(this).text().indexOf(value) > -1)
            });
        });
    });

    function imprimir(orgid){
        var value = $("#unidad_id_ex option:selected").val();
        var orgid = orgid;
        alert(orgid);
        var fecha_i = document.getElementById('fecha_inicial').value;
        var fecha_f = document.getElementById('fecha_final').value;
        var exportar = '<?php echo "\ print_usuarios.php?unidad_id=" ?>' + value+'&fecha_i='+fecha_i+'&fecha_f='+fecha_f+'&org_id='+orgid;
        window.location.href = exportar;
    }
    
    function actualizar(){
        var unidad_mina = $("#unidad_id_ex option:selected").val();
        var fecha_i = document.getElementById('fecha_inicial').value;
        var fecha_f = document.getElementById('fecha_final').value;
        
        //alert(value);
    var ver = '<?php echo "\ usuarios_inscritos.php?unidad_id=" ?>' + unidad_mina+'&fecha_i='+fecha_i+'&fecha_f='+fecha_f;
        window.location.href = ver;
    }
            
</script>

<style>
    @import url("https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css");

    .tabla {
        display: flex;
        flex-direction: column;
        padding: 5vmin;
        margin: 0 auto;
    }

    .boton {
        background-color: #57a639;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: space-evenly;
        height: 34px;
        width: 120px;
        border-radius: 15px;
        border: none;
    }
</style>

<div class="container-fluid">
    <div class="container-fluid">
        <div id="datos-listado" class="tabla">
            
            <div class="col-md-6 col-lg-6" style="display:flex; flex-direction: row;">
                <select name="unidad_id_ex" id="unidad_id_ex" class="form-control">
                    <?php  if ($_SESSION['unidad_acc'] == '0') {
                        $datos_minas = $mysqli->query("SELECT unidad_id, nombre
                                                      FROM arg_empr_unidades") or die(mysqli_error($mysqli));
                        //while ($row2 = $datos_minas->fetch_assoc()){
                        while ($row2 = $datos_minas->fetch_array(MYSQLI_ASSOC)) {
                            $met_sele = $row2['nombre'];
                    ?>
                            <option value="<?php  echo $row2['unidad_id'] ?>"><?php  echo $met_sele ?></option>
                    <?php  }
                    } ?>

                    <?php  if ($_SESSION['unidad_acc'] <> '0' and $_SESSION['unidad_acc'] <> '999') {
                        $datos_minas = $mysqli->query("SELECT unidad_id, nombre
                                                                    FROM arg_empr_unidades 
                                                                    WHERE unidad_id = " . $_SESSION['unidad_acc']) or die(mysqli_error($mysqli));
                        //while ($row2 = $datos_minas->fetch_assoc()){
                        while ($row2 = $datos_minas->fetch_array(MYSQLI_ASSOC)) {
                            $met_sele = $row2['nombre'];
                            $css = "";
                            if ($row2['unidad_id'] == $_SESSION['unidad_acc']) {
                                $css = "selected";
                            }
                    ?>
                            <option value="<?php  echo $row2['unidad_id'] ?>" $css><?php  echo $met_sele ?></option>
                    <?php 
                        }
                    } ?>

                    <?php   //999=Varias unidades de mina (No Todas) 
                    $cadena = $_SESSION['unidades'];
                    $i = 0;
                    if ($_SESSION['unidad_acc'] == '999') {
                        while ($i <= strlen($cadena)) {
                            $valor = substr($cadena, $i, 1);
                            $i = $i + 1;
                            if (is_numeric($valor)) {
                                $datos_mina = $mysqli->query("SELECT unidad_id, nombre FROM arg_empr_unidades WHERE unidad_id = " . $valor) or die(mysqli_error($mysqli));
                                while ($row2 = $datos_mina->fetch_array(MYSQLI_ASSOC)) {
                                    $met_sele = $row2['nombre'];
                    ?>
                                    <option value="<?php  echo $row2['unidad_id'] ?>"><?php  echo $met_sele ?></option>
                    <?php  }
                            }
                        }
                    } ?>
                </select>
                <div class="col-md-4 col-lg-4">                     
                      <input type="date" name="fecha_inicial" class="form-control" id="fecha_inicial" value="<?php echo $fecha_i;?>" />
                </div>
                <div class="col-md-4 col-lg-4">
                      <input type="date" name="fecha_final" class="form-control" id="fecha_final" value="<?php echo $fecha_f;?>" />                                
                </div>   
                 <div class="col-md-1 col-lg-1">
                    <button class="btn btn-primary" onclick="actualizar()">Ver <i class="fa fa-eye"></i></button>
                </div>
                <div class="col-md-1 col-lg-1">
                    <button class="boton" onclick="imprimir(<?php echo $org_id?>)">Imprimir &nbsp<i class="fa fa-file-pdf-o"></i></button>
                </div>
           
                  </div>
                   </div>
                  
                   </div>
            <br /><br /><br /><br />
            <?php 
            $html_det = "<table class='table table-striped' id='motivos'>
                    <thead>                                
                         <tr class='table-info'>      
                            <th colspan='5'>Usuarios Inscritos</th>     
                            <th></th>
                         </tr>
                        <tr class='table-info' justify-content: center;>            
                            <th scope='col1'>UNIDAD</th>
                            <th scope='col1'>FECHA</th>
                            <th scope='col2'>NOMBRE</th>
                            <th scope='col1'>CODIGO</th>
                            <th scope='col1'>EMAIL</th>
                            <th scope='col2'>EMPRESA</th>";
            $html_det .= "</tr>
                   </thead>
                   <tbody>";

            $num = 1;
            while ($fila = $datos->fetch_row()) {
                $html_det .= "<tr>";
                $html_det .= "<td>" . $fila[7] . "</td>";
                $html_det .= "<td>" . $fila[5] . "</td>";
                $html_det .= "<td>" . $fila[3] . "</td>";
                $html_det .= "<td>" . $fila[1] . "</td>";
                $html_det .= "<td>" . $fila[2] . "</td>";
                $html_det .= "<td>" . $fila[8] . "</td>";
                $html_det .= "</tr>";
            }
            $html_det .= "</tbody></table>";
            echo ("$html_det");
            ?>
        </div>
    </div>
</div>