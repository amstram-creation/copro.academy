<?php

require_once 'add/arrow/arrow.php';
require_once 'add/brevo.php';
require_once 'app/mapper/taxonomy.php';

return function () {

    // Only handle POST requests
    empty($_POST) && throw new BadMethodCallException('This route only handles POST requests', 400);

    // Tracking metadata
    $tracking = [
        'ip_address'  => $_SERVER['REMOTE_ADDR'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? null,
        'user_agent'  => $_SERVER['HTTP_USER_AGENT'] ?? null,
        'referer'     => $_SERVER['HTTP_REFERER'] ?? null,
        'language'    => $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? null,
        'session_id'  => session_id() ?: null
    ];

    // Clean and prepare data
    $clean = [
        'subject_id'    => (int) tag_id_by_slug($_POST['sujet'], 'contact_demande-sujet'),
        'status_id'     => (int) tag_id_by_slug('statut-en-attente', 'contact_demande-statut'),
        'consented_at'  => isset($_POST['consent']) ? date('Y-m-d H:i:s') : null,
        'message'       => htmlspecialchars($_POST['content'] ?? '', ENT_QUOTES, 'UTF-8'),
        'email'         => filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL) ?: null,
        'name'          => htmlspecialchars($_POST['label'] ?? '', ENT_QUOTES, 'UTF-8'),
        'phone'         => htmlspecialchars($_POST['telephone'] ?? '', ENT_QUOTES, 'UTF-8'),
        'company'       => htmlspecialchars($_POST['entreprise'] ?? '', ENT_QUOTES, 'UTF-8'),
        'subject_slug'  => $_POST['sujet'] ?? '',
    ];



    // Save contact request to DB
    $contact = row(db(), 'contact_request');
    $contact(ROW_SET | ROW_SCHEMA);
    $contact(ROW_SET | ROW_SAVE, $clean + $_POST + $tracking);

    // Send notification to site admin using mail_notif()
    try {
        [$html, $text] = contact_request_content($clean);
        send_brevo_mail('info@copro.academy', 'lannoy@jbrmanagement.be', 'Copro.Academy - Nouvelle demande de contact', $html, $text);
        $sent = send_brevo_mail('info@copro.academy', 'cc_test_copro.academy@amstram.be', 'Copro.Academy - Nouvelle demande de contact', $html, $text);
    } catch (Throwable $e) {
        error_log("Brevo mail error: " . $e->getMessage());
    }

    $message = ($sent || $contact(ROW_GET | ROW_ERROR) === null) ? 'success' : 'failure';
    header('Location: /contact/?message='.$message);
    exit;
};


function contact_request_content($clean)
{
    // Build email content in French
    $html = '<h1>Nouvelle demande de contact</h1>' .
        '<p><strong>Nom :</strong> ' . $clean['name'] . '</p>' .
        '<p><strong>Email :</strong> ' . $clean['email'] . '</p>' .
        (!empty($clean['phone']) ? '<p><strong>Téléphone :</strong> ' . $clean['phone'] . '</p>' : '') .
        (!empty($clean['company']) ? '<p><strong>Entreprise :</strong> ' . $clean['company'] . '</p>' : '') .
        '<p><strong>Sujet :</strong> ' . htmlspecialchars($clean['subject_slug'], ENT_QUOTES, 'UTF-8') . '</p>' .
        '<p><strong>Message :</strong></p><p>' . nl2br($clean['message']) . '</p>';

    $text = "Nouvelle demande de contact\n" .
        "Nom : {$clean['name']}\n" .
        "Email : {$clean['email']}\n" .
        (!empty($clean['phone']) ? "Téléphone : {$clean['phone']}\n" : '') .
        (!empty($clean['company']) ? "Entreprise : {$clean['company']}\n" : '') .
        "Sujet : {$clean['subject_slug']}\n" .
        "Message : {$clean['message']}\n";

    return [$html, $text];
}
