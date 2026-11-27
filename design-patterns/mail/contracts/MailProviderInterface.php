<?php

require_once __DIR__ . "/../dto/EmailMessageDTO.php";

interface MailProviderInterface
{
    public function send(EmailMessageDTO $message): bool;
}