<?php
namespace App\DesignPatterns\Mail;

use App\designpatterns\mail\contracts\MailProviderInterface;
use App\designpatterns\mail\dto\EmailMessageDTO;

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