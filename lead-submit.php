<?php
session_start();

$fields = [
    'project_interest' => 'Project',
    'start_discussing' => 'Start discussing',
    'estimated_budget' => 'Estimated budget',
    'full_name' => 'Full name',
    'phone' => 'Phone',
    'email' => 'Email',
    'lead_source' => 'Lead source',
];

$telegramBotToken = '944824681:AAEhU6XVWFUGATVbH-yEtKNPQDKj0YqahRU';
$telegramChatId = '-5365168016';

function raw_field_value($name) {
    $value = $_GET[$name] ?? '';

    if (is_array($value)) {
        $value = implode(', ', $value);
    }

    $value = trim((string) $value);
    $value = preg_replace('/\s+/', ' ', $value);

    return substr($value, 0, 1000);
}

function telegram_config_value($envName, $fallback = '') {
    $value = getenv($envName);

    return $value !== false && trim($value) !== '' ? trim($value) : $fallback;
}

function send_telegram_lead($fields) {
    global $telegramBotToken, $telegramChatId;

    $botToken = telegram_config_value('TELEGRAM_BOT_TOKEN', $telegramBotToken);
    $chatId = telegram_config_value('TELEGRAM_CHAT_ID', $telegramChatId);

    if ($botToken === '' || $chatId === '') {
        return false;
    }

    $leadData = [];
    $answers = [];
    $technical = [];
    $knownFields = [];

    $leadFields = [
        'full_name' => 'Full name',
        'phone' => 'Phone',
        'email' => 'Email',
    ];
    $answerFields = [
        'project_interest' => 'Project',
        'start_discussing' => 'Start discussing',
        'estimated_budget' => 'Estimated budget',
    ];

    foreach ($fields as $name => $label) {
        $knownFields[$name] = true;
    }

    foreach ($leadFields as $name => $label) {
        $value = raw_field_value($name);
        if ($value !== '') {
            $leadData[] = $label . ': ' . $value;
        }
    }

    foreach ($answerFields as $name => $label) {
        $value = raw_field_value($name);
        if ($value !== '') {
            $answers[] = $label . ': ' . $value;
        }
    }

    $leadSource = raw_field_value('lead_source');
    if ($leadSource !== '') {
        $technical[] = 'Lead source: ' . $leadSource;
    }

    $technical[] = 'IP: ' . ($_SERVER['REMOTE_ADDR'] ?? 'Unknown');

    $utmSource = raw_field_value('utm_source');
    if ($utmSource !== '') {
        $technical[] = 'utm_source: ' . $utmSource;
    }

    $messageBlocks = [];
    if ($leadData) {
        $messageBlocks[] = implode("\n", $leadData);
    }
    if ($answers) {
        $messageBlocks[] = implode("\n", $answers);
    }
    if ($technical) {
        $messageBlocks[] = implode("\n", $technical);
    }

    if (!$leadData && !$answers) {
        return false;
    }

    $message = "New bathroom remodel lead\n\n" . implode("\n\n", $messageBlocks);
    $leadHash = hash('sha256', $message);

    if (isset($_SESSION['sent_telegram_leads'][$leadHash])) {
        return true;
    }

    $payload = http_build_query([
        'chat_id' => $chatId,
        'text' => $message,
        'disable_web_page_preview' => 'true',
    ]);
    $url = 'https://api.telegram.org/bot' . $botToken . '/sendMessage';
    $sent = false;

    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT => 8,
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $sent = $response !== false && $httpCode >= 200 && $httpCode < 300;
    } else {
        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                'content' => $payload,
                'timeout' => 8,
            ],
        ]);
        $sent = file_get_contents($url, false, $context) !== false;
    }

    if ($sent) {
        $_SESSION['sent_telegram_leads'][$leadHash] = true;
    }

    return $sent;
}

send_telegram_lead($fields);

$query = http_build_query($_GET);
$location = 'thanks.php' . ($query !== '' ? '?' . $query : '');
header('Location: ' . $location, true, 303);
exit;
