<?php
/** @var array{
 *   folio: string,
 *   fecha: string,
 *   estado: string,
 *   proveedor_empresa: string,
 *   proveedor_nombre: string,
 *   atiende: string,
 *   trn_id: int,
 *   app_url: string
 * } $viewData
 */
?>
Su solicitud con folio <strong><?= $viewData['folio'] ?></strong> y fecha <?= $viewData['fecha'] ?> ha sido <strong><?= $viewData['estado'] ?></strong><br><br> Empresa: <?= $viewData['proveedor_empresa'] ?><br> Nombre: <?= $viewData['proveedor_nombre'] ?><br><br> Atiende: <strong><?= $viewData['atiende'] ?></strong><br>
<br>Para ver mas detalles favor de ingresar a la bitácora de visitas:<br><br>
<font color='red'><a href='<?= $viewData['app_url'] . "app.php?trn_id=" . $viewData['trn_id'] ?>' target='_blank'>Ir a sitio</a></font><br><br>
Atte: BITACORA DE VISITAS MineData-Access HelioStarMetals