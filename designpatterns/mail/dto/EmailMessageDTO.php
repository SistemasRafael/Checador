<?php
namespace App\DesignPatterns\Mail\DTO;

class EmailMessageDTO
{
    public function __construct(
        public string $subject,
        public string $body,
        public array $to = [],
        public array $cc = [],
        public array $bcc = [],
        public array $attachments = []
    ) {}
}