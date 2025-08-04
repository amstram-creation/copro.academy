<?php

function send_brevo_mail($to, $subject, $html, $text = null, $sender = null)
{
    $api_key = $_SERVER['BREVO_KEY'] ?? throw new RuntimeException('BREVO_KEY not found');

    $data = [
        'to' => [['email' => $to]],
        'subject' => $subject,
        'htmlContent' => $html
    ];

    if ($text) $data['textContent'] = $text;
    if ($sender) $data['sender'] = ['email' => $sender];

    $ch = curl_init('https://api.brevo.com/v3/smtp/email');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'api-key: ' . $api_key,
            'Content-Type: application/json'
        ]
    ]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code !== 201) {
        throw new RuntimeException("Brevo API error: $response");
    }

    return json_decode($response, true)['messageId'];
}



    // // emails brevo
    // info@copro.academy
    // info@copro.academy