<?php
/**
 * Soughts Premium Marketplace — Alıcı Talepleri Sayfası
 * 
 * Queries the new `talepler` table and displays as glassmorphism cards.
 */

$sayfa_basligi = 'Alıcı Talepleri';
require_once __DIR__ . '/includes/header.php';

// Talepleri çek (yeni tablo)
$sorgu = $db->prepare("SELECT * FROM talepler ORDER BY id DESC");
$sorgu->execute();
$talepler = $sorgu->fetchAll();
?>

<div class="sayfa-govde">
    <!-- Sol Dekoratif Zincir -->
    <div class="sol-dekor">
        <div class="sol-dekor-yazi">Soughts</div>
    </div>

    <!-- Başlık -->
    <div class="vitrin-header">
        <h1>Aranan <span>Koleksiyonlar</span></h1>
        <a href="<?= site_url('index.php') ?>" class="btn-geri">
            <i class="fa-solid fa-arrow-left"></i> Ana Sayfaya Dön
        </a>
    </div>

    <!-- Talep Kartları -->
    <div class="talep-grid">
        <?php if (count($talepler) > 0): ?>
            <?php foreach ($talepler as $talep): ?>
                <div class="talep-karti">
                    <div class="talep-ikon"><i class="fa-solid fa-gem"></i></div>
                    <div class="alici-isim">
                        <i class="fa-solid fa-user-tie"></i>
                        <?= htmlspecialchars($talep['ad_soyad']) ?> arıyor:
                    </div>
                    <div class="talep-metni">
                        "<?= htmlspecialchars($talep['baslik']) ?>"
                    </div>
                    <?php if ($talep['fiyat'] > 0): ?>
                        <div class="talep-butce">
                            <i class="fa-solid fa-coins"></i>
                            <?= number_format($talep['fiyat'], 0, ',', '.') ?> ₺
                        </div>
                    <?php endif; ?>
                    <a href="mailto:<?= htmlspecialchars($talep['eposta']) ?>?subject=Soughts Platformundaki Talebiniz Hakkında" class="btn-teklif">
                        <i class="fa-solid fa-envelope"></i> Teklif Gönder
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="bos-durum">
                <i class="fa-solid fa-inbox"></i>
                Şu an için açık bir alıcı talebi bulunmamaktadır.<br>
                Koleksiyon arayışları başladığında burada listelenecektir.
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>