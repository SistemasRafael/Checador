<?php
/** @var array{
 *   empresa: array,
 *   visitantes: array,
 *   visita: array,
 *   vehiculos: array,
 *   atiende: array
 * } $viewData
 */
?>
<div id='content'>
    <table id='veh' width='100%'  style='border:0.5px solid'>
        <thead>
            <tr class='bg-info'>            
                <th scope='col' align='center' bgcolor='gold'>EMPRESA VISITANTE</th>
            </tr>                              
        </thead>
    </table>
    <table id='veh' width='100%'  style='border:0.5px solid'>
        <thead>
            <tr>            
                <th scope='col' align='center' width='40%'  bgcolor='gray'>Nombre de los visitantes</th>
                <th scope='col' align='center' bgcolor='gray'>No de Gafete</th>
                <th scope='col' align='center' bgcolor='gray'>No. IMSS */ Gastos Medicos Mayores*</th>
                <th scope='col' align='center' bgcolor='gray'>No. Identificación* (No. Credencial IFE)</th>
            </tr>
        </thead>
    </table>
    <table id='visitantes' style='font-size: 14px;'>
        <tr>
            <td width='350'><a><?= $viewData['empresa']['usuario'] ?></a></td>
            <td width='120'><a><?= $viewData['empresa']['nss'] ?></a></td>
            <td><a><?= $viewData['empresa']['ine'] ?></a></td>
        </tr>
            <?php foreach (($viewData['visitantes'] ?? []) as $item): ?>
                <tr>
                    <td width='350'><a><?= $item['cantidad'] ?></a></td>
                    <td width='120'><a><?= $item['nss'] ?></a></td>
                    <td><a><?= $item['ine'] ?></a></td>
                </tr>
            <?php endforeach; ?>
    </table>
</div>