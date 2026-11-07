<?php

require_once __DIR__ . '/../contracts/PdfTemplateInterface.php';
include "config/Env.php";

class SubscribedUsersTemplate implements PdfTemplateInterface
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function render(): string
    {
        $viewData = $this->data;

        Env::load(__DIR__ . '/../../.env');

        ob_start();
        include "pdf/views/SubscribedUser/header.php";
        $header = ob_get_clean();

        ob_start();
        include "pdf/views/SubscribedUser/content.php";
        $content = ob_get_clean();

        $css = file_get_contents(__DIR__ . '/../css/subscribed_users.css');

        return <<<HTML
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
            </body>
            </html>
        HTML;
    }
}