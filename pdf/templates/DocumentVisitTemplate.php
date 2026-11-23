<?php
require_once __DIR__ . '/../contracts/PdfTemplateInterface.php';
require_once __DIR__ . '/../../utilities/ImageHelper.php';

class DocumentVisitTemplate implements PdfTemplateInterface
{
    private array $data;
    private string $viewsPath;
    private string $cssPath;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->viewsPath = __DIR__ . '/../views/documentVisit/';
        $this->cssPath = __DIR__ . '/../css/document_visit.css';
    }
        
    public function render(): string
    {
        $CompanyIconBase64 = ImageHelper::convertImageToBase64(ImageHelper::getCompanyHorizontalIconPath());
        $QRBase64 = ImageHelper::convertImageToBase64(ImageHelper::getQRPath());
        $SignatureBase64 = ImageHelper::convertImageToBase64(ImageHelper::getSignaturePathBy('signature.jpg'));

        $viewData = $this->data;
        $viewData['companyIcon'] = $CompanyIconBase64;
        $viewData['QR'] = $QRBase64;
        $viewData['signature'] = $SignatureBase64;
        $viewData['areas'] = implode(', ', array_column($viewData['areas'] ?? [], 'area'));

        ob_start();
        include $this->viewsPath . 'header.php';
        $header = ob_get_clean();
        
        ob_start();
        include $this->viewsPath . 'content.php';
        $content = ob_get_clean();

        ob_start();
        include $this->viewsPath . 'footer.php';
        $footer = ob_get_clean();

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
                {$footer}
            </body>
            </html>
        HTML;
    }
}