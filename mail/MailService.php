<?php
require_once __DIR__ . '/contracts/MailProviderInterface.php';
require_once __DIR__ . '/dto/EmailMessageDTO.php';

class MailService
{
    public function __construct(
        private MailProviderInterface $provider
    ) {}

    public function send(EmailMessageDTO $message): bool
    {
        return $this->provider->send($message);
    }
}