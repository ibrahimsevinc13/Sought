<?php
/**
 * Soughts Premium Marketplace — Admin: Yeni İlan Ekle
 * 
 * Security: admin auth gate, CSRF token
 */

$sayfa_basligi = 'Yeni İlan Ekle';
require_once __DIR__ . '/../includes/header.php';

admin_gerekli();
?>

<div class="admin-sayfa" style="display: flex; justify-content: center; align-items: center; min-height: calc(100vh - var(--topbar-height));">
    <div class="form-kutu">
        <h2 style="text-align: center; margin-bottom: 25px;">VIP Vitrinine Ekle</h2>

        <form action="<?= site_url('admin/ilan_kaydet.php') ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_token_input() ?>

            <div class="input-grup">
                <label>Ürün / İlan Adı</label>
                <input type="text" name="ilan_adi" required placeholder="Örn: Özel Koleksiyon Saat">
            </div>

            <div class="input-grup">
                <label>Fiyat (₺)</label>
                <input type="number" name="fiyat" required placeholder="Örn: 150000" min="0" step="0.01">
            </div>

            <div class="input-grup">
                <label>Kategori</label>
                <select name="kategori">
                    <option value="">Kategori Seçin</option>
                    <option value="saatler">Saatler</option>
                    <option value="giyim">Giyim</option>
                    <option value="sanat">Sanat Eserleri</option>
                    <option value="aksesuar">Aksesuarlar</option>
                    <option value="diger">Diğer</option>
                </select>
            </div>

            <div class="input-grup">
                <label>Açıklama</label>
                <textarea name="aciklama" rows="4" required placeholder="Ürün detaylarını girin..."></textarea>
            </div>

            <div class="input-grup">
                <label>Vitrin Görseli</label>
                <input type="file" name="gorsel" required accept=".jpg,.jpeg,.png,.webp"
                       style="padding: 10px; cursor: pointer;">
            </div>

            <button type="submit" class="btn-gonder">Sisteme Kaydet</button>
        </form>

        <div style="text-align: center; margin-top: 20px;">
            <a href="<?= site_url('admin/index.php') ?>" class="btn-geri">
                <i class="fa-solid fa-arrow-left"></i> Panele Dön
            </a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
