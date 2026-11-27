<?php
/** @var array{
 *   folio: string,
 *   proveedor: string,
 *   empresa: string,
 *   fecha: string,
 *   app_url: string
 * } $viewData
 */
?>
Has recibido una solicitud de visita con folio <strong><?= $viewData['folio'] ?></strong> del proveedor <strong><?= $viewData['proveedor'] ?></strong> empresa <strong><?= $viewData['empresa'] ?></strong> con fecha <strong><?= $viewData['fecha'] ?></strong><br>
<br>Para ver mas detalles favor de ingresar a la bitácora de visitas:<br><br>
<font color='red'><a href='<?= $viewData['app_url'] ?>' target='_blank'>Ver solicitud</a></font><br><br>
<b>Atte:</b> MineData-Access Control de Acceso de HelioStar Metals