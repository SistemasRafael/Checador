<?php
namespace App\DesignPatterns\Mail\Templates;

use App\DesignPatterns\Mail\Templates\EmailTemplate;

class SuscriptionDataEmail extends EmailTemplate
{
    public function __construct(array $data)
    {
        parent::__construct($data);
        $this->viewsPath = __DIR__ . '/../views/subcription-data/';
        $this->cssPath = __DIR__ . '/../Assets/subcription-data.css';
    }

    public function subject(): string
    {
        return "Envio desde Bitacora de Registro de HeliostarVisit.";
    }

    public function render(): string
    {
        $viewData = $this->data;

        ob_start();
        include $this->viewsPath . 'header.php';
        $header = ob_get_clean();

        ob_start();
        include $this->viewsPath . 'content.php';
        $content = ob_get_clean();

        ob_start();
        include $this->viewsPath . 'footer.php';
        $footer = ob_get_clean();

        $css = file_get_contents($this->cssPath);

        return <<<HTML
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                {$css}
            </style>
        </head>
        <body>
            {$header}
            {$content}
            {$footer}
        </body>
        </html>
        HTML;
    }
}