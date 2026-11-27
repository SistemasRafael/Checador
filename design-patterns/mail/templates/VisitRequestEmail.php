<?php

require_once __DIR__ . '/EmailTemplate.php';

class VisitRequestEmail extends EmailTemplate
{
    public function __construct(array $data)
    {
        parent::__construct($data);
        $this->viewsPath = __DIR__ . '/../views/visit-request/';
        $this->cssPath = __DIR__ . '/../Assets/visit-request.css';
    }

    public function subject(): string
    {
        return 'Solicitud de visita ' . $this->data['folio'];
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

        $css = file_get_contents(
            $this->cssPath
        );

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