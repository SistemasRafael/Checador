
<?php

class ImageHelper
{
    public static function getCompanyIconPath(): string
    {
        $projectRoot = dirname(__DIR__);

        $imagePath = $projectRoot
            . DIRECTORY_SEPARATOR . 'assets'
            . DIRECTORY_SEPARATOR . 'images'
            . DIRECTORY_SEPARATOR . 'argonaut-logo2.jpg';

        if (!file_exists($imagePath)) {
            throw new Exception('Image does not exist: ' . $imagePath);
        }

        return $imagePath;
    }

    public static function getCompanyHorizontalIconPath(): string
    {
        $projectRoot = dirname(__DIR__);

        $imagePath = $projectRoot
            . DIRECTORY_SEPARATOR . 'assets'
            . DIRECTORY_SEPARATOR . 'images'
            . DIRECTORY_SEPARATOR . 'heliostar-icon-horizontal.png';

        if (!file_exists($imagePath)) {
            throw new Exception('Image does not exist: ' . $imagePath);
        }

        return $imagePath;
    }

    public static function getQRPath(): string
    {
        $projectRoot = dirname(__DIR__);

        $imagePath = $projectRoot
            . DIRECTORY_SEPARATOR . 'assets'
            . DIRECTORY_SEPARATOR . 'images'
            . DIRECTORY_SEPARATOR . 'QR.png';

        if (!file_exists($imagePath)) {
            throw new Exception('Image does not exist: ' . $imagePath);
        }

        return $imagePath;
    }

    public static function getSignaturePathBy(string $name): string
    {
        $projectRoot = dirname(__DIR__);

        $imagePath = $projectRoot
            . DIRECTORY_SEPARATOR . 'assets'
            . DIRECTORY_SEPARATOR . 'images'
            . DIRECTORY_SEPARATOR . $name;

        if (!file_exists($imagePath)) {
            throw new Exception('Image does not exist: ' . $imagePath);
        }

        return $imagePath;
    }

    public static function convertImageToBase64(string $imagePath): string
    {
        if (!is_readable($imagePath)) {
            throw new Exception('Image is not readable: ' . $imagePath);
        }

        $response = file_get_contents($imagePath);

        if ($response === false) {
            throw new Exception( 'Error reading the image: ' . $imagePath);
        }

        $type = mime_content_type($imagePath);

        if ($type === false) {
            throw new Exception('Unable to determine image MIME type: ' . $imagePath);
        }

        return 'data:' . $type . ';base64,' . base64_encode($response);
    }
}
