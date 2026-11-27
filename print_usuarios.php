<?php
require_once __DIR__ . '/vendor/autoload.php';

include "config/config.php";

use App\Repositories\SubscribedUserRepository;
use App\DesignPatterns\Pdf\Templates\SubscribedUsersTemplate;
use App\DesignPatterns\Pdf\Generators\PdfGenerator;

if (isset($_GET['unidad_id'])) {
    $unidad_id = (int) $_GET['unidad_id'];
    $fecha_i   = $_GET['fecha_i'];
    $fecha_f   = $_GET['fecha_f'];
    $org_id    = (int) $_GET['org_id'];

    $subscribedUserRepository = new SubscribedUserRepository($mysqli);

    $template = new SubscribedUsersTemplate([
        'SubscribedUsers' => $subscribedUserRepository->getCursosProveedor($unidad_id, $fecha_i, $fecha_f, $org_id)
    ]);

    $pdfGenerator = new PdfGenerator();
    $pdf = $pdfGenerator->generate($template);
    $pdf->stream('resguardo.pdf', ['Attachment' => false]);
}