<?php
require_once __DIR__ . '/../contracts/MailProviderInterface.php';
require_once __DIR__ . '/../dto/EmailMessageDTO.php';
require_once __DIR__ . '/../../config/Env.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class PHPMailerProvider implements MailProviderInterface
{
    public function __construct()
    {
        $path = dirname(__DIR__) . '/../.env';
        Env::load(dirname(__DIR__) . '/../.env');
    }
    
    public function send(EmailMessageDTO $message): bool
    {
        $mail = new PHPMailer(true);

        try {

            $mail->isSMTP();
            $mail->Host = Env::get('SMTP_HOST');
            $mail->SMTPAuth = true;
            $mail->Username = Env::get('SMTP_USER');
            $mail->Password = Env::get('SMTP_PASSWORD');

            $mail->SMTPSecure =
                PHPMailer::ENCRYPTION_STARTTLS;

            $mail->Port = Env::get('SMTP_PORT');

            $mail->CharSet = 'UTF-8';

            $mail->setFrom(Env::get('SMTP_USER'), 'HelioStar Access Control');

            foreach ($message->to as $email) {
                $mail->addAddress($email);
            }

            foreach ($message->cc as $email) {
                $mail->addCC($email);
            }

            foreach ($message->bcc as $email) {
                $mail->addBCC($email);
            }

            foreach ($message->attachments as $path) {
                $mail->addAttachment($path);
            }

            $mail->isHTML(true);

            $mail->Subject = $message->subject;
            $mail->Body = $message->body;

            return $mail->send();

        } catch (Exception $e) {

            error_log(
                '[MAIL_ERROR] ' .
                $e->getMessage()
            );

            return false;
        }
    }
}