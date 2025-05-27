<?php

namespace App\Helper;

class Mailer
{
    public static function send(string $to, string $subject, string $body): bool
    {
        // Simulação de envio. Em produção, integre com PHPMailer ou outro serviço.
        return mail($to, $subject, $body);
    }
}
