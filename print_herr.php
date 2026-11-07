<?php
require_once __DIR__ . '/vendor/autoload.php';

include "config/config.php";
include "pdf/templates/ToolTemplate.php";
include "pdf/generators/PdfGenerator.php";
include "repositories/ToolRepository.php";

if (isset($_GET['trn_id'])){
    $trn_id_rel = $_GET['trn_id'];

    $repository = new ToolRepository($mysqli);
    $template = new ToolsTemplate([
        'empresa'      => $repository->getEmpresa($trn_id_rel),
        'entrada'      => $repository->getEntrada($trn_id_rel),
        'herramientas' => $repository->getHerramientas($trn_id_rel)
    ]);

    $pdfGenerator = new PdfGenerator();
    $pdf = $pdfGenerator->generate($template);
    $pdf->stream('resguardo.pdf', ['Attachment' => false]);
}
?>
