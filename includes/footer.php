<?php
/**
 * Soughts Premium Marketplace — Global Footer
 * 
 * Contact modal (with CSRF), copyright from DB, and unified JS loading.
 */
?>

    <!-- ===== İLETİŞİM MODAL ===== -->
    <div id="iletisimModal" class="modal-kapsayici">
        <div class="modal-icerik">
            <button class="kapat-btn" onclick="iletisimKapat()">&times;</button>
            <h2 class="modal-baslik">Bize Ulaşın</h2>
            <p class="modal-alt-baslik">
                VIP Destek Hattı: <?= htmlspecialchars($ayar['telefon'] ?? '') ?><br>
                Özel talepleriniz ve sorularınız için mesaj bırakın.
            </p>

            <form action="<?= site_url('mesaj_kaydet.php') ?>" method="POST">
                <?= csrf_token_input() ?>

                <div class="input-grup">
                    <label>Adınız Soyadınız</label>
                    <input type="text" name="iletisim_ad" required
                           placeholder="Adınız ve Soyadınız"
                           value="<?= giris_yapildi_mi() ? htmlspecialchars($_SESSION['ad_soyad'] ?? aktif_kullanici_adi()) : '' ?>">
                </div>
                <div class="input-grup">
                    <label>E-posta Adresiniz</label>
                    <input type="email" name="iletisim_eposta" required
                           placeholder="Size dönebileceğimiz adres"
                           value="<?= giris_yapildi_mi() ? htmlspecialchars(aktif_kullanici_eposta()) : '' ?>">
                </div>
                <div class="input-grup">
                    <label>Mesajınız</label>
                    <textarea name="iletisim_mesaj" rows="4" required placeholder="Mesajınızı buraya yazın..."></textarea>
                </div>
                <button type="submit" class="btn-gonder">Mesajı Gönder</button>
            </form>
        </div>
    </div>

    <!-- ===== FOOTER ===== -->
    <footer class="site-footer">
        &copy; 2026 Soughts Premium Marketplace. T&uuml;m haklar&#305; sakl&#305;d&#305;r.
    </footer>

    <!-- Unified JavaScript -->
    <script src="<?= site_url('assets/js/app.js') ?>?v=<?= filemtime(PROJE_KOK . 'assets/js/app.js') ?>"></script>
</body>
</html>
