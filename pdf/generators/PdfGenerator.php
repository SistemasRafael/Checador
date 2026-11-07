<?php

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfGenerator
{
    public function generate(
        PdfTemplateInterface $template,
        string $paper = 'letter',
        string $orientation = 'portrait'
    ): Dompdf {

        $options = new Options();
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);

        $dompdf->loadHtml(
            $template->render()
        );

        $dompdf->setPaper(
            $paper,
            $orientation
        );

        $dompdf->render();

        return $dompdf;
    }
}