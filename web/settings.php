<?php
// Cargar configuración desde config.php
include('config.php');

define('TELEGRAM_BOT_TOKEN', $telegram_bot_token);
define('TELEGRAM_CHAT_ID',   $telegram_chat_id);

/* Compatibilidad con código que usa $token / $chat_id */
$token          = TELEGRAM_BOT_TOKEN;
$chat_id        = TELEGRAM_CHAT_ID;
$webhook_secret = substr(hash('sha256', TELEGRAM_BOT_TOKEN . TELEGRAM_CHAT_ID), 0, 32);

/**
 * Llamada robusta a la API de Telegram (cURL en lugar de file_get_contents).
 * Devuelve la respuesta como string, o false en error.
 */
function tg_request($method, $params = []) {
    $url = "https://api.telegram.org/bot" . TELEGRAM_BOT_TOKEN . "/$method";
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => http_build_query($params),
        CURLOPT_TIMEOUT        => 10,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => 0,
    ]);
    $resp = curl_exec($ch);
    if ($resp === false) error_log("Telegram error: " . curl_error($ch));
    curl_close($ch);
    return $resp;
}
