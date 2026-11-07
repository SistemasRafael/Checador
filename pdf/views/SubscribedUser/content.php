<?php
/** @var array{
 *   SubscribedUsers: array
 * } $viewData
 */
?>
<div id="content">
    <table  id="motivos">
        <thead>
            <tr>
                <th colspan="5">
                    Usuarios Inscritos
                </th>
            </tr>
            <tr>
                <th>FECHA</th>
                <th>UNIDAD</th>
                <th>NOMBRE</th>
                <th>EMPRESA</th>
                <th>EMAIL</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach (($viewData['SubscribedUsers'] ?? []) as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['fecha']) ?></td>
                    <td><?= htmlspecialchars($item['unidad_nombre']) ?></td>
                    <td><?= htmlspecialchars($item['nombre']) ?></td>
                    <td><?= htmlspecialchars($item['organizacion']) ?></td>
                    <td><?= htmlspecialchars($item['email']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>