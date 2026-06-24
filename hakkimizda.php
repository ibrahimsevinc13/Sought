<?php
/**
 * Soughts Premium Marketplace — Hakkımızda Sayfası
 */

$sayfa_basligi = 'Hakkımızda';
require_once __DIR__ . '/includes/header.php';
?>

<section class="hakkimizda-section">
    <h1>Hakkımızda</h1>

    <div class="hakkimizda-icerik">
        <p>
            <strong class="text-gold">Soughts</strong>, nadide koleksiyon parçalarının, lüks aksesuarların ve özel
            tasarım ürünlerin güvenli, zarif ve premium bir arayüzle buluştuğu özel bir pazar yeridir.
        </p>

        <p>
            Misyonumuz, koleksiyonerleri ve özel parça arayışında olan VIP alıcıları, güvenilir satıcılarla
            bir araya getirmektir. <strong class="text-highlight">Güvenli Havuz (Escrow)</strong> sistemimiz
            sayesinde her işlem, hem alıcı hem satıcı için sıfır riskle gerçekleşir.
        </p>

        <p>
            Platform, en yüksek güvenlik standartlarıyla tasarlanmıştır: şifreli iletişim, güvenli ödeme
            altyapısı ve onaylı satıcı ağı ile koleksiyonunuzu tamamlamanın en güvenilir yolu Soughts'tur.
        </p>

        <p style="text-align: center; margin-top: 40px;">
            <a href="<?= site_url('index.php') ?>" class="btn-geri">
                <i class="fa-solid fa-arrow-left"></i> Ana Sayfaya Dön
            </a>
        </p>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
