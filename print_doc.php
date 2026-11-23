<?php
require_once __DIR__ . '/vendor/autoload.php';

include "config/config.php";
include "repositories/DocumentVisitRepository.php";
include "pdf/templates/DocumentVisitTemplate.php";
include "pdf/generators/PdfGenerator.php";

$trn_id_rel = $_GET['trn_id'];

if (isset($trn_id_rel)) {
    $repository = new DocumentVisitRepository($mysqli);
    
    $template = new DocumentVisitTemplate([
        'empresa' => $repository->getCompanyHeader($trn_id_rel),
        'visita' => $repository->getVisitInformation($trn_id_rel),
        'areas' => $repository->getAreasInformation($trn_id_rel),
        'entrada' => $repository->getEntryInformation($trn_id_rel),
        'atiende' => $repository->getAttendantInformation($trn_id_rel),
        'responsable' => $repository->getResponsibleUserInformation($trn_id_rel),
        'visitantes' => $repository->getVisitors($trn_id_rel),
        'vehiculos' => $repository->getVehicles($trn_id_rel),
    ]);

    $pdfGenerator = new PdfGenerator();
    $pdf = $pdfGenerator->generate($template);
    $pdf->stream('resguardo.pdf', ['Attachment' => false]);
}
?>