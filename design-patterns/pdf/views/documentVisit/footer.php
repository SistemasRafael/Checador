<?php
/** @var array{
 *   empresa: array,
 *   entrada: array,
 *   herramientas: array,
 *   responsable: array,
 *   atiende: array,
 *   companyIcon: string
 * } $viewData
 */
?>
<div id='footer' class="text-center">
    <table id='veh' width='100%'  style='border:0.5px solid'>
        <thead>
            <tr class='bg-info'>            
                <th scope='col' align='center' bgcolor='gold'>INGRESO DE VEHÍCULOS VISITANTES</th>
            </tr>                                 
        </thead>
    </table>
    <table id='veh' width='100%'  style='border:0.5px solid'>
        <thead>
                <tr>            
                <th scope='col' align='center' bgcolor='gray'>Licencia</th>
                <th scope='col' align='center' bgcolor='gray'>Marca</th>
                <th scope='col' align='center' bgcolor='gray'>Modelo</th>
                <th scope='col' align='center' bgcolor='gray'>Color</th>
                <th scope='col' align='center' bgcolor='gray'>No Póliza</th>
                <th scope='col' align='center' bgcolor='gray'>Placas</th>
            </tr>
        </thead>
    </table>
    <table id='veh' width='90%'>
        <thead>
        </thead>
        <tbody>
            <?php foreach (($viewData['vehiculos'] ?? []) as $item): ?>      
            <tr>
                <td width='160px'><?=$item['licencia']?></td>
                <td width='120px'><?=$item['marca']?></td>
                <td width='130px'><?=$item['modelo']?></td>
                <td width='120px'><?=$item['color']?></td>
                <td width='160px'><?=$item['poliza']?></td>
                <td width='160px'><?=$item['placas']?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <table id='veh' width='100%'  style='border:0.5px solid'>
        <thead>
            <tr class='bg-info'>            
                <th scope='col' align='center' bgcolor='gold'>DATOS DE LA VISITA</th>
            </tr>                                 
        </thead>
    </table>
    <table id='motivos' style='font-size: 14px;'>
        <tr>
            <td width='300px'><a>Responsable de la visita:</a></td>
            <td width='300px'><a><?= $viewData['atiende']['atiende'] ?></a></td>
        </tr>
        <tr>
            <td width='300px'><a>Departamento del responsable de la visita:</a></td>
            <td width='300px'><a><?= $viewData['atiende']['departamento'] ?></a></td>
        </tr>
        <tr>
            <td width='300px'><a>Motivos de la visita:</a></td>
            <td width='600px'>    
                <?php foreach (($viewData['visita'] ?? []) as $item): ?>
                    <a><?= $item['nombre'] ?>, </a>
                <?php endforeach; ?>
                <tr>
                    <td width='300px'><a>Comentarios:</a></td>
                    <td width='300px'><a><?= $viewData['empresa']['comentario'] ?></a></td>
                </tr>
            </td>
        </tr>
    </table>
    <div class="segurity-information-title">
        INFORMACION IMPORTANTE PARA LOS VISITANTES
    </div>
    <div class="segurity-information-content">
        Los patrones también están obligados a presentar Seguro Social vigente. En el caso de personal extranjero 
        debe presentar Póliza de Gastos Médicos Mayores con cobertura amplia sin excepción alguna en cualquiera de 
        los casos antes mencionados.
    </div>
    <div class="segurity-information-title">
        SEGURIDAD PERSONAL
    </div>
    <div class="segurity-information-content">
        Toda persona que solicite ingreso a las instalaciones de CMP debe contar con el mínimo EPP requerido que es; 
        Casco de Seguridad, Chaleco Naranja con Reflejantes, Calzado de Seguridad y Lentes de Seguridad.
    </div>
    <div class="segurity-information-title">
        SEGURIDAD VEHICULAR
    </div>
    <div class="segurity-information-content">
        Para autorizar el Acceso de Vehículos se requiere que estos cuenten con Torreta, Banderola (4 m de altura desde el piso), 
        Logotipo de la empresa, Número Económico, Luces encendidas, Cinturón de Seguridad y Extintor; Vehículos de carga y 
        pesados contar con Alarma de Reversa.
    </div>
    <div class="segurity-information-content line-top line-bottom">
        Nota: Considerando que por la Naturaleza de las actividades de la empresa existen riesgos de toda clase, relevo a la compañía 
        de cualquier responsabilidad en caso de resultar un daño tanto material como personal. Acepto toda responsabilidad del mismo 
        y convengo en pagar todos los daños, además estoy de acuerdo en que mi automóvil sea revisado en su interior dentro de la planta 
        cuando se me solicite.
        <br>ESTE PERMISO ES SOLO PARA VISITA Y NO INCLUYE AUTORIZACIÓN PARA REALIZAR TRABAJOS. 
        <br><b>(Los campos marcados con (*) significan documentación en copia y escaneada que OBLIGATORIAMENTE se deberá entregar con esta solicitud)</b>
    </div>
    <table class="segurity-information-signature text-center">
        <tr>
            <td style="width:50%; padding:10px;">
                <div>Jesus Moreno</div>
                <div style="border-top:1px solid #000; width:80%; margin:2px auto 0;"></div>
                <div style="margin-top:10px;">
                    Nombre y Firma del Responsable de Atender la Visita
                </div>
            </td>
            <td style="width:50%; padding:10px;">
                <div>
                    JULIO ESQUER
                    <img src="<?= $viewData['signature'] ?>"
                        alt="Firma"
                        style="height:35px; vertical-align:middle; margin-left:5px;"/>
                </div>
                <div style="border-top:1px solid #000; width:80%; margin:2px auto 0;"></div>
                <div style="margin-top:10px;">
                    Nombre y Firma Dpto. SSMA de la Unidad de Negocios
                </div>
            </td>
        </tr>
    </table>
</div>