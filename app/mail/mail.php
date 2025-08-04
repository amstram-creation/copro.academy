<?php

require_once 'add/brevo.php';

function mail_notif(string $to, string $subject, string $html, string $text): ?string
{
    try {
        return send_brevo_mail('info@copro.academy', $to, $subject, $html, $text);
    } catch (Throwable $e) {
        // Log the error or handle it silently
        error_log("Brevo mail error: " . $e->getMessage());
        return null; // or false if you prefer
    }
}