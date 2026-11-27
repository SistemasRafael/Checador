<?php
namespace App\DesignPatterns\Pdf\Contracts;

interface PdfTemplateInterface
{
    public function render(): string;
}