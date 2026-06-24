<?php
/**
 * Soughts Premium Marketplace — Satıcı Vitrini (Product Showcase)
 * 
 * Public product grid with search, filter, and sort controls.
 */

$sayfa_basligi = 'Satıcı Vitrini';
require_once __DIR__ . '/includes/header.php';

// Aktif ilanları çek
$sorgu = $db->prepare("SELECT * FROM ilanlar WHERE durum = 'aktif' ORDER BY id DESC");
$sorgu->execute();
$ilanlar = $sorgu->fetchAll();
?>

<div class="sayfa-govde">
    <!-- Sol Dekoratif Zincir -->
    <div class="sol-dekor">
        <div class="sol-dekor-yazi">Soughts</div>
    </div>

    <!-- Başlık -->
    <div class="vitrin-header">
        <h1>Soughts <span>Koleksiyon Vitrini</span></h1>
        <?php if (admin_mi()): ?>
            <a href="<?= site_url('admin/ilan_ekle.php') ?>" class="btn-ekle">
                <i class="fa-solid fa-plus"></i> Yeni Özel İlan Ekle
            </a>
        <?php endif; ?>
    </div>

    <!-- Kontrol Paneli -->
    <div class="kontrol-paneli">
        <div class="kategori-haplari">
            <span class="aktif">Tümü</span>
            <span>Saatler</span>
            <span>Giyim</span>
            <span>Sanat Eserleri</span>
        </div>
        <div class="arama-siralama">
            <input type="text" placeholder="Nadir bir parça arayın..." id="vitrinArama">
            <select id="vitrinSiralama">
                <option value="yeni">En Yeniler</option>
                <option value="artan">Fiyat (Düşük > Yüksek)</option>
                <option value="azalan">Fiyat (Yüksek > Düşük)</option>
            </select>
        </div>
    </div>

    <!-- Ürün Grid -->
    <div class="vitrin-grid">
        <?php if (count($ilanlar) > 0): ?>
            <?php foreach ($ilanlar as $ilan): ?>
                <div class="ilan-karti" data-kategori="<?= htmlspecialchars($ilan['kategori'] ?? '') ?>">
                    <div class="gorsel-kutu">
                        <div class="favori-ikon"><i class="fa-regular fa-heart"></i></div>
                        <img src="<?= site_url(htmlspecialchars($ilan['resim_yolu'] ?? 'assets/images/pusula.jpg')) ?>"
                             alt="<?= htmlspecialchars($ilan['ilan_adi']) ?>"
                             class="ilan-gorsel"
                             loading="lazy">
                    </div>
                    <div class="ilan-detay">
                        <h3 class="ilan-adi"><?= htmlspecialchars($ilan['ilan_adi']) ?></h3>
                        <div class="ilan-fiyat"><?= number_format($ilan['fiyat'], 0, ',', '.') ?> ₺</div>
                        <p class="ilan-aciklama"><?= htmlspecialchars($ilan['aciklama'] ?? '') ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="bos-durum">
                <i class="fa-solid fa-store-slash"></i>
                Vitrin şu an boş. Koleksiyonunuza ilk parçayı eklemek için yönetim panelini kullanın.
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>