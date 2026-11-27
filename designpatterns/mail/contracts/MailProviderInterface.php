<?php
namespace App\DesignPatterns\Mail\Contracts;

use App\DesignPatterns\Mail\DTO\EmailMessageDTO;

interface MailProviderInterface
{
    public function send(EmailMessageDTO $message): bool;
}