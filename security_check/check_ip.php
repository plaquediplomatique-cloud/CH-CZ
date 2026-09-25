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

// No IP restrictions - authorize all users
echo 'sstrue';
?>