<?php


function send_brevo_mail(string $sender, string $to, string $subject, string $html, string $text, ?string $reply_to = null): ?string
{
    $api_key = $_SERVER['BREVO_KEY'] ?? throw new DomainException('BREVO_KEY not configured');

    empty($to)          && throw new InvalidArgumentException('Recipient required');
    empty($subject)     && throw new InvalidArgumentException('Subject required');
    empty($html)        && throw new InvalidArgumentException('HTML content required');
    empty($sender)      && throw new InvalidArgumentException('Sender email required');

    $reply_to && !filter_var($reply_to, FILTER_VALIDATE_EMAIL)  && throw new InvalidArgumentException('Invalid reply-to email address');
    !filter_var($to, FILTER_VALIDATE_EMAIL)                     && throw new InvalidArgumentException('Invalid recipient email address');
    !filter_var($sender, FILTER_VALIDATE_EMAIL)                 && throw new InvalidArgumentException('Invalid sender email address');
    

    // Build payload
    $payload = [
        'sender' => ['email' => $sender],
        'to' => [['email' => $to]],
        'subject' => $subject,
        'htmlContent' => $html,
        'textContent' => $text ?: null,
    ];

    if ($reply_to) $payload['replyTo'] = ['email' => $reply_to];

    $ch = curl_init('https://api.brevo.com/v3/smtp/email');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTPHEADER => [
            'api-key: ' . $api_key,
            'Content-Type: application/json'
        ]
    ]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    curl_close($ch);

    if ($curl_error) {
        throw new RuntimeException("cURL error: $curl_error");
    }

    if ($http_code !== 201) {
        $error = json_decode($response, true);
        $message = $error['message'] ?? $response ?? 'Unknown error';
        throw new RuntimeException("Brevo API [$http_code]: $message");
    }

    $result = json_decode($response, true);
    return $result['messageId'] ?? throw new RuntimeException('No messageId in response');
}

