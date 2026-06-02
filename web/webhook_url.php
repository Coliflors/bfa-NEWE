<?php
include("settings.php");

$webhook_url = (isset($_SERVER['HTTPS']) || $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https' ? 'https' : 'http')
    . '://' . $_SERVER['HTTP_HOST']
    . dirname($_SERVER['PHP_SELF']) . '/bot.php';

echo '<pre style="font-family:monospace;font-size:14px;background:#111;color:#0f0;padding:20px">';
echo "════════════════════════════════════════\n";
echo "URL DEL WEBHOOK PARA TELEGRAM\n";
echo "════════════════════════════════════════\n\n";
echo "Copia esta URL y úsala para configurar el webhook:\n\n";
echo $webhook_url . "\n\n";
echo "════════════════════════════════════════\n";
echo "O ejecuta setwebhook.php para configurarlo automáticamente\n";
echo "════════════════════════════════════════\n";
echo '</pre>';
?>
