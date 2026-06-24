<?php
/**
 * Soughts Premium Marketplace — Admin: İlan Düzenleme Formu
 * 
 * Security: admin auth gate, CSRF token, integer ID validation
 */

$sayfa_basligi = 'İlan Düzenle';
require_once __DIR__ . '/../includes/header.php';

admin_gerekli();

// ID doğrulama
if (!isset($_GET['id'])) {
    yonlendir(site_url('admin/index.php'));
}

$id = temizle_int($_GET['id']);

// Ürünü çek
$sorgu = $db->prepare("SELECT * FROM ilanlar WHERE id = ?");
$sorgu->execute([$id]);
$ilan = $sorgu->fetch();

if (!$ilan) {
    yonlendir(site_url('admin/index.php'));
}
?>

<div class="admin-sayfa" style="display: flex; justify-content: center; align-items: center; min-height: calc(100vh - var(--topbar-height));">
    <div class="form-kutu">
        <h2 style="text-align: center; margin-bottom: 25px;">Parçayı Yeniden Değerle</h2>

        <form action="<?= site_url('admin/guncelle_islem.php') ?>" method="POST">
            <?= csrf_token_input() ?>
            <input type="hidden" name="id" value="<?= $ilan['id'] ?>">

            <div class="input-grup">
                <label>Ürün / İlan Adı</label>
                <input type="text" name="ilan_adi" required value="<?= htmlspecialchars($ilan['ilan_adi']) ?>">
            </div>

            <div class="input-grup">
                <label>Fiyat (₺)</label>
                <input type="number" name="fiyat" required value="<?= $ilan['fiyat'] ?>" min="0" step="0.01">
            </div>

            <div class="input-grup">
                <label>Kategori</label>
                <select name="kategori">
                    <option value="">Kategori Seçin</option>
                    <option value="saatler" <?= ($ilan['kategori'] ?? '') === 'saatler' ? 'selected' : '' ?>>Saatler</option>
                    <option value="giyim" <?= ($ilan['kategori'] ?? '') === 'giyim' ? 'selected' : '' ?>>Giyim</option>
                    <option value="sanat" <?= ($ilan['kategori'] ?? '') === 'sanat' ? 'selected' : '' ?>>Sanat Eserleri</option>
                    <option value="aksesuar" <?= ($ilan['kategori'] ?? '') === 'aksesuar' ? 'selected' : '' ?>>Aksesuarlar</option>
                    <option value="diger" <?= ($ilan['kategori'] ?? '') === 'diger' ? 'selected' : '' ?>>Diğer</option>
                </select>
            </div>

            <div class="input-grup">
                <label>Açıklama</label>
                <textarea name="aciklama" rows="4" required><?= htmlspecialchars($ilan['aciklama'] ?? '') ?></textarea>
            </div>

            <p style="font-size: 11px; color: var(--text-secondary); margin-bottom: 20px;">
                * Sadece metin, fiyat ve kategori bilgileri güncellenecektir.
            </p>

            <button type="submit" class="btn-gonder">Değişiklikleri Kaydet</button>
        </form>

        <div style="text-align: center; margin-top: 20px;">
            <a href="<?= site_url('admin/index.php') ?>" class="btn-geri">
                <i class="fa-solid fa-arrow-left"></i> Panele Dön
            </a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
