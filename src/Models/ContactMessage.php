<?php
// src/Models/ContactMessage.php

namespace Models;

class ContactMessage {
    public function sendEmail(string $name, string $email, string $subject, string $message): bool {
        $to = "contact@verreimage.com"; // Adresse de destination réelle
        $emailSubject = "Nouveau message de {$name} : {$subject}";
        $emailBody = "Vous avez reçu un nouveau message depuis le formulaire de contact de votre site.\n\n";
        $emailBody .= "--------------------------------------------------\n";
        $emailBody .= "Nom : {$name}\n";
        $emailBody .= "Email : {$email}\n";
        $emailBody .= "Sujet : {$subject}\n\n";
        $emailBody .= "Message :\n{$message}\n";
        $emailBody .= "--------------------------------------------------\n";

        $headers = "From: {$name} <{$email}>\r\n";
        $headers .= "Reply-To: {$email}>\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();

        // Utilisez un service d'envoi d'emails comme Mailgun, SendGrid, ou PHPMailer pour une production réelle
        // La fonction mail() de PHP est souvent non fiable et bloquée par les hébergeurs
        return mail($to, $emailSubject, $emailBody, $headers);
    }
}