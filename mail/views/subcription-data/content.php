<?php
/** @var array{
 *   proveedor_sol: string,
 *   empresa_sol: string,
 *   fecha_cur: string,
 *   app_url: string
 * } $viewData
 */
?>
El proveedor <strong><?= $viewData['proveedor_sol'] ?></strong> de la empresa <strong><?= $viewData['empresa_sol'] ?></strong> ha solicitado su curso de induccion completa para la fecha pr&oacutexima: <strong><?= $viewData['fecha_cur'] ?></strong><br>
<br>Para revisar la lista de inscritos favor de ingresar al Sistema de Control de Acceso:<br><br>
<font color='red'><a href='<?= $viewData['app_url'] ?>' target='_blank'>Ir a sitio</a></font><br><br>
Atte: Control de Acceso de HelioStar Metals