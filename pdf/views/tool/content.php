<?php
/** @var array{
 *   empresa: array,
 *   entrada: array,
 *   herramientas: array
 * } $viewData
 */
?>
<div id='header'>
    <table id='encabezado' width='95%'  style='border:0.1px solid'>                                      
            <thead>
                <tr>            
                <th scope='col' 
                    width='50%'  
                    style='border:0.1px solid' 
                    align='left' 
                    bgcolor='white'>Nombre de la Empresa: <?= $viewData['empresa']['nombre'] ?>
                </th>
            </tr>                                
        </thead>
    </table>
    <table id='encabezado' width='95%'  style='border:0.1px solid'>                                      
        <thead>                                 
            <tr>     
                <th scope='col' 
                    width='90%' 
                    style='border:0.1px solid' 
                    align='left' 
                    bgcolor='white'>Nombre de quien elabora el resguardo: <?= $viewData['empresa']['usuario'] ?>
                </th>
            </tr>
        </thead>
    </table>
    <table id='encabezado' width='95%'  style='border:0.1px solid'>                                      
        <thead>                                 
            <tr>     
                <th scope='col' 
                    width='50%' 
                    style='border:0.1px solid' 
                    align='left' 
                    bgcolor='white'>Fecha de Ingreso: <?= $viewData['entrada']['fecha_inicio'] ?>
                </th>
                <th scope='col' 
                    width='50%' 
                    style='border:0.1px solid' 
                    align='left' 
                    bgcolor='white'>Fecha de Salida: <?= $viewData['entrada']['fecha_final'] ?>
                </th>
            </tr>
        </thead>
    </table>
    <table id='encab' width='95%'  style='border:0.1px solid'>                                      
        <thead>
            <tr>            
                <th scope='col' align='center' class='backgroud-color'>Descripción de los Materiales</th>
            </tr>
        </thead>
    </table>
    <table id='veh' width='95%'  style='border:0.1px solid'>                                      
        <thead>
            <tr>            
                <th scope='col' width='5%'  align='left' class='backgroud-color'>No</th>
                <th scope='col' width='10%' align='left' class='backgroud-color'>Cantidad</th>
                <th scope='col' width='20%' align='left' class='backgroud-color'>Marca</th>
                <th scope='col' width='30%' align='left' class='backgroud-color'>Serie</th>                                    
                <th scope='col' width='40%' align='left' class='backgroud-color'>Descripción</th>
            </tr>
        </thead>
    </table>
    <table id='herrami' width='95%' style='border:1px'>
        <tbody>
            <?php
                $contador = 1;
                foreach (($viewData['herramientas'] ?? []) as $item):
            ?>
                <tr>
                    <td width='5%'  style='border:1px solid; font-size:14px' align='left'><?= $contador++ ?></td>
                    <td width='10%' style='border:1px solid; font-size:14px' align='left'><?= $item['cantidad'] ?></td>
                    <td width='20%' style='border:1px solid; font-size:14px' align='left'><?= $item['marca'] ?></td>
                    <td width='30%' style='border:1px solid; font-size:14px' align='left'><?= $item['serie'] ?></td>
                    <td width='40%' style='border:1px solid; font-size:14px' align='left'><?= $item['nombre'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>