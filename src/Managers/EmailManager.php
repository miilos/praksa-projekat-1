<?php

namespace App\Managers;

use PHPMailer\PHPMailer\PHPMailer;
use Throwable;

class EmailManager
{
    private $mailer;
    public function __construct()
    {
        $this->mailer = new PHPMailer(true);
        $this->mailer->isSMTP();
        $this->mailer->SMTPAuth = true;

        $this->mailer->Host = 'sandbox.smtp.mailtrap.io';
        $this->mailer->Port = 2525;

        $this->mailer->Username = $_ENV['EMAIL_USERNAME'];
        $this->mailer->Password = $_ENV['EMAIL_PASSWORD'];
    }

    public function sendMail(string $recipientAddress, string $recipientName, string $subject, string $body): void
    {
        try {
            $this->mailer->setFrom($_ENV['EMAIL_FROM_ADDRESS'], $_ENV['EMAIL_FROM_NAME']);
            $this->mailer->addAddress($recipientAddress, $recipientName);

            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $this->emailHtml($subject, $body);
            $this->mailer->AltBody = $body;

            $this->mailer->send();
        }
        catch (Throwable $t) {
            echo 'Error sending email!';
        }
    }

    private function emailHtml($subject, $text): string
    {
        return '
            <h1 style="background-color: #054dba; padding: 12px; color: #fff; font-family: sans-serif">' . $subject . '</h1>
            <p style="padding: 12px; font-family: sans-serif">' . $text . '</p>
        ';
    }
}