<?php
// src/Models/ContactMessage.php

namespace Models;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ContactMessage {
    public function sendEmail(string $name, string $email, string $subject, string $message): bool {
        $mail = new PHPMailer(true);
        try {
            $mail->setFrom($email, $name);
            $mail->addAddress('contact@verreimage.com');
            $mail->addReplyTo($email, $name);
            $mail->Subject = "Nouveau message de {$name} : {$subject}";

            $body  = "Vous avez reçu un nouveau message depuis le formulaire de contact de votre site.\n\n";
            $body .= "--------------------------------------------------\n";
            $body .= "Nom : {$name}\n";
            $body .= "Email : {$email}\n";
            $body .= "Sujet : {$subject}\n\n";
            $body .= "Message :\n{$message}\n";
            $body .= "--------------------------------------------------\n";

            $mail->Body = $body;
            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
