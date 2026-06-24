<?php
/**
 * Soughts Premium Marketplace — Ana Sayfa (Homepage)
 * 
 * Hero Section with images/map.jpeg background + VIP Request Modal
 */

$sayfa_basligi = 'Ana Sayfa';
require_once __DIR__ . '/includes/header.php';
?>

<!-- ===== HERO SECTION ===== -->
<main class="hero-section">
    <div class="hero-bg"></div>

    <div class="hero-content">
        <h1 class="hero-title">
            Değerli Parçalar ve <span class="text-gold"><br>Nadir Koleksiyonlar</span><br>İçin İstek Oluşturun
        </h1>

        <p class="hero-objective">
            Eksik parçanızı veya aradığınız nadir eseri sisteme girin. Elinde olan onaylı satıcılar doğrudan size
            özel tekliflerle gelsin. Güvenli Havuz (Escrow) sistemiyle <strong class="text-highlight">sıfır riskle</strong>
            koleksiyonunuzu tamamlayın.
        </p>

        <button class="btn-premium" onclick="premiumModalAc()" id="btn-talep-olustur">
            <i class="fa-solid fa-gem btn-icon"></i>
            <span>Talebinizi Oluşturun</span>
        </button>
    </div>
</main>

<!-- ===== VIP TALEP MODAL ===== -->
<div id="premiumModal" class="modal-kapsayici">
    <div class="modal-icerik">
        <button class="kapat-btn" onclick="premiumModalKapat()">&times;</button>
        <h2 class="modal-baslik">Nadir Eser Talebi</h2>
        <p class="modal-alt-baslik">Koleksiyonunuzdaki eksik parçayı bulmak için detayları girin.</p>

        <form action="<?= site_url('veri_kaydet.php') ?>" method="POST" id="talepForm">
            <?= csrf_token_input() ?>

            <div class="input-grup">
                <label>Adınız Soyadınız</label>
                <input type="text" name="ad_soyad" required placeholder="Örn: İbrahim Sevinç"
                       value="<?= giris_yapildi_mi() ? htmlspecialchars($_SESSION['ad_soyad'] ?? aktif_kullanici_adi()) : '' ?>">
            </div>
            <div class="input-grup">
                <label>E-posta Adresiniz</label>
                <input type="email" name="eposta" required placeholder="Sizinle iletişime geçeceğiz"
                       value="<?= giris_yapildi_mi() ? htmlspecialchars(aktif_kullanici_eposta()) : '' ?>">
            </div>
            <div class="input-grup">
                <label>Aradığınız Eser / Parça</label>
                <input type="text" name="baslik" required placeholder="Örn: 1960 Rolex Daytona Kadranı">
            </div>
            <div class="input-grup">
                <label>Maksimum Bütçeniz (₺)</label>
                <input type="number" name="fiyat" required placeholder="Örn: 50000" min="0" step="0.01">
            </div>
            <button type="submit" class="btn-gonder">Talebi Sisteme İlet</button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>