<?php
// Česká pošta - Footer Component
?>
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
            <div class="footer-col">
                <h4>Česká pošta</h4>
                <a href="#">O nás</a>
                <a href="#">Kariéry</a>
                <a href="#">Tiskové zprávy</a>
            </div>
            <div class="footer-col" style="justify-content:flex-end; align-items:flex-end; text-align:right;">
                <img src="assets/POST.svg" height="32" style="opacity:0.5; margin-bottom:12px;">
                <p style="font-size:12px; color:#999; margin:0;">
                    &copy; 2026 <?= t('copyright') ?>
                </p>
            </div>
        </div>

        <div class="footer-mobile">
            &copy; 2026 <?= t('copyright') ?>
        </div>
    </div>
</footer>
