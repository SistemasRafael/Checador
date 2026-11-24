<?php

include "mail/dto/EmailMessageDTO.php";

interface MailProviderInterface
{
    public function send(EmailMessageDTO $message): bool;
}