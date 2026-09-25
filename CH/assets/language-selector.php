<?php
/**
 * Language Selector Widget
 * Displays language switching buttons with current language highlighted
 */

$languages = [
    'fr' => ['name' => 'Français', 'flag' => '🇫🇷'],
    'de' => ['name' => 'Deutsch', 'flag' => '🇩🇪'],
    'it' => ['name' => 'Italiano', 'flag' => '🇮🇹'],
    'en' => ['name' => 'English', 'flag' => '🇬🇧'],
    'cs' => ['name' => 'Čeština', 'flag' => '🇨🇿'],
];

$current = $current_lang ?? 'cs';
?>

<div class="language-selector">
    <?php foreach ($languages as $lang_code => $lang_info): ?>
        <a href="?lang=<?php echo htmlspecialchars($lang_code); ?>"
           class="lang-btn <?php echo ($lang_code === $current) ? 'active' : ''; ?>"
           title="<?php echo htmlspecialchars($lang_info['name']); ?>"
           data-lang="<?php echo htmlspecialchars($lang_code); ?>">
            <span class="lang-flag"><?php echo $lang_info['flag']; ?></span>
            <span class="lang-code"><?php echo strtoupper($lang_code); ?></span>
        </a>
    <?php endforeach; ?>
</div>

<style>
.language-selector {
    position: fixed;
    top: 16px;
    right: 16px;
    z-index: 1000;
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    justify-content: flex-end;
    align-items: center;
}

.lang-btn {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 8px 14px;
    border: 2px solid #FE5518;
    background: white;
    color: #FE5518;
    text-decoration: none;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    text-transform: uppercase;
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    box-shadow: 0 2px 8px rgba(254, 85, 24, 0.1);
    letter-spacing: 0.5px;
}

.lang-btn:hover {
    background: #FE5518;
    color: white;
    transform: translateY(-3px);
    box-shadow: 0 6px 16px rgba(254, 85, 24, 0.25);
}

.lang-btn.active {
    background: linear-gradient(314deg, #FE5518 39.86%, #FF3052 99.02%);
    color: white;
    border-color: transparent;
    font-weight: 700;
    box-shadow: 0 4px 12px rgba(254, 85, 24, 0.3);
}

.lang-flag {
    font-size: 16px;
    display: inline-block;
}

.lang-code {
    display: inline-block;
}

@media (max-width: 768px) {
    .language-selector {
        top: 8px;
        right: 8px;
        gap: 6px;
    }

    .lang-btn {
        padding: 6px 10px;
        font-size: 11px;
    }

    .lang-flag {
        font-size: 14px;
    }
}

@media (max-width: 480px) {
    .language-selector {
        top: 4px;
        right: 4px;
    }

    .lang-btn {
        padding: 4px 8px;
        font-size: 10px;
    }

    .lang-code {
        display: none;
    }

    .lang-flag {
        font-size: 12px;
    }
}
</style>
