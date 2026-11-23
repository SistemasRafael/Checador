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
<div id="header">
    <table id="autorization-header" style="width:100%; border: 1px solid black; border-collapse: collapse;">
        <tr>
            <th rowspan="4" class="text-center" style="width:35%;"><img src="<?= $viewData['companyIcon'] ?>"></th>
            <th rowspan="4" class="text-center" style="width:35%;">Autorización para Acceso a<br>Unidad de Negocio</th>
            <td class="document-control">Código</td>
            <td class="document-control">AGR-SP-001</td>
        </tr>
        <tr>
            <td class="document-control">Fecha de Emisión</td>
            <td class="document-control">Agosto de 2015</td>
        </tr>
        <tr>
            <td class="document-control">Unidad de Negocio</td>
            <td class="document-control">Grupo Argonaut Gold de Mexico</td>
        </tr>
        <tr>
            <td class="document-control">Revisión</td>
            <td class="document-control">0.0</td>
        </tr>
    </table>
    <table style="width:100%; border: 1px solid black; border-collapse: collapse;">
        <tr>
            <th rowspan="4" style="width:35%; border-right: 1px solid black;">DATOS DE LA EMPRESA<br>QUE SOLICITA EL ACCESO</th>
            <td class="company-visit-information"><b>Nombre:</b> <?= $viewData['empresa']['nombre'] ?></td>
            <th rowspan="4" class="text-center" style="width:15%; border-left: 1px solid black;"><img  src="<?= $viewData['QR'] ?>"></th>
        </tr>
        <tr>
            <td class="company-visit-information"><b>Direccion:</b> <?= $viewData['empresa']['calle'] .' '.$viewData['empresa']['num_exterior'].', '.$viewData['empresa']['colonia'].', '.$viewData['empresa']['ciudad'] ?></td>
        </tr>
        <tr>
            <td class="company-visit-information"><b>Responsable de la Visita:</b> <?= $viewData['responsable']['responsable'] ?></td>
        </tr>
        <tr>
            <td class="company-visit-information"><b>Atiende:</b> <?= $viewData['atiende']['atiende'] ?></td>
        </tr>
    </table>
    <table style="width:100%; border: 1px solid black; border-collapse: collapse; font-size: 12px;">
        <tr>
            <td class="text-center" style="width:35%; background-color: #c3c3c3;"><b>Unidad de Negocio</b></td>
            <td style="padding-left: 5px;"><b><?= $viewData['entrada']['unidad'] ?></b></td>
            <td style="padding-left: 5px;"><b>Area: <?= $viewData['areas'] ?></b></td>
        </tr>
    </table>
    <table style="width:100%; border: 1px solid black; border-collapse: collapse; font-size: 12px;">
        <tr class="text-center">
            <td style="background-color: #c3c3c3;"><b>Periodo de la visita</b></td>
            <td class="border-x">Del:</td>
            <td><b><?= $viewData['entrada']['fecha_inicio'] ?></b></td>
            <td class="border-x">Al:</td>
            <td><b><?= $viewData['entrada']['fecha_final'] ?></b></td>
            <td class="border-x" style="background-color: #c3c3c3;">Fecha:</td>
            <td><b><?= $viewData['entrada']['fecha'] ?></b></td>
        </tr>
    </table>
    <p class="access-conditions">
        Máximo 3 Días; Los Gafetes de Acceso se entregan y regresan a Seguridad Patrimonial Diariamente; Para permisos por mas de tres días y para realizar trabajos se deberá asistir al Curso de Inducción
        a la Seguridad el cual se imparte solo en días Lunes y Jueves de cada semana de 08:00 a 12:00 del día.
    </p>
</div>