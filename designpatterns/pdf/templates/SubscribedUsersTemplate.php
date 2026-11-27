<?php
namespace App\DesignPatterns\Pdf\Templates;

use App\DesignPatterns\Pdf\Contracts\PdfTemplateInterface;
use App\Utilities\ImageHelper;
use Exception;

class SubscribedUsersTemplate implements PdfTemplateInterface
{
    private array $data;
    private string $viewsPath;
    private string $cssPath;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->viewsPath = __DIR__ . '/../views/SubscribedUser/';
        $this->cssPath = __DIR__ . '/../css/subscribed_users.css';
    }

    public function render(): string
    {
        $imageBase64 = ImageHelper::convertImageToBase64(ImageHelper::getCompanyIconPath());

        $viewData = $this->data;
        $viewData['imageBase64'] = $imageBase64;

        ob_start();
        include $this->viewsPath . 'header.php';
        $header = ob_get_clean();
        
        ob_start();
        include $this->viewsPath . 'content.php';
        $content = ob_get_clean();
        
        if (!is_readable($this->cssPath)) {
            throw new Exception('CSS file not found or unreadable: ' . $this->cssPath);
        }

        $css = file_get_contents($this->cssPath);

        if ($css === false) {
            throw new Exception('Error reading CSS file: ' . $this->cssPath);
        }

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
            </body>
            </html>
        HTML;
    }
}