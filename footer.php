<div id="iletisimModal" class="modal-kapsayici">
    <div class="modal-icerik">
        <span class="kapat-btn" onclick="iletisimKapat()">&times;</span>
        <h2 class="modal-baslik">Bize Ulaşın</h2>
        <p class="modal-alt-baslik">VIP Destek Hattı: <?php echo isset($ayar['telefon']) ? $ayar['telefon'] : ''; ?> <br> Özel talepleriniz ve sorularınız için mesaj bırakın.</p>

        <form action="#" method="POST">
            <div class="input-grup">
                <label>Adınız Soyadınız</label>
                <input type="text" name="iletisim_ad" required placeholder="İbrahim Sevinç">
            </div>
            <div class="input-grup">
                <label>E-posta Adresiniz</label>
                <input type="email" name="iletisim_eposta" required placeholder="Size dönebileceğimiz adres">
            </div>
            <div class="input-grup">
                <label>Mesajınız</label>
                <textarea name="iletisim_mesaj" rows="4" required placeholder="Mesajınızı buraya yazın..." style="width: 100%; padding: 12px; background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 4px; color: white; font-family: 'Montserrat', sans-serif; resize: none;"></textarea>
            </div>
            <button type="submit" class="btn-gonder">Mesajı Gönder</button>
        </form>
    </div>
</div>

<div id="kayitModal" class="modal-kapsayici">
    <div class="modal-icerik">
        <span class="kapat-btn" onclick="kayitKapat()">&times;</span>
        <h2 class="modal-baslik" style="text-align: center;">Sought'a Katılın</h2>
        <p class="modal-alt-baslik" style="text-align: center;">Koleksiyonerler arasına katılın ve özel parçaları keşfedin.</p>

        <form action="kayit_kaydet.php" method="POST">
            <div class="input-grup">
                <label>Kullanıcı Adı</label>
                <input type="text" name="kullanici_adi" required placeholder="Örn: ibrahim_bey">
            </div>
            <div class="input-grup">
                <label>E-posta Adresi</label>
                <input type="email" name="eposta" required placeholder="E-posta adresiniz">
            </div>
            <div class="input-grup">
                <label>Şifre</label>
                <input type="password" name="sifre" required placeholder="Güçlü bir şifre belirleyin">
            </div>
            <button type="submit" class="btn-gonder" style="margin-top: 15px;">VIP Kaydımı Tamamla</button>
        </form>
    </div>
</div>

<footer style="text-align: center; padding: 20px; color: #A0A5B5; font-size: 14px;">
  &copy; 2026 Soughts Premium Marketplace. All rights reserved.
</footer>

<script>
    // Premium Formu
    function modalAc() { document.getElementById("premiumModal").style.display = "block"; }
    function modalKapat() { document.getElementById("premiumModal").style.display = "none"; }

    // İletişim Formu
    function iletisimAc() { document.getElementById("iletisimModal").style.display = "block"; }
    function iletisimKapat() { document.getElementById("iletisimModal").style.display = "none"; }

    // Kayıt Ol Formu
    function kayitAc() { document.getElementById("kayitModal").style.display = "block"; }
    function kayitKapat() { document.getElementById("kayitModal").style.display = "none"; }
</script>

</body>
</html>