<?php
/**
 * Soughts Premium Marketplace — Admin: İlan Silme İşlemi
 * 
 * Security: admin auth, POST-only (not GET), CSRF, deletes associated image file
 */

require_once __DIR__ . '/../config/baglan.php';
require_once __DIR__ . '/../includes/functions.php';

admin_gerekli();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    yonlendir(site_url('admin/index.php'));
}

csrf_dogrula();

$id = temizle_int($_POST['id'] ?? 0);

if ($id <= 0) {
    yonlendir(site_url('admin/index.php'));
}

try {
    // Önce resim yolunu al (dosyayı da sileceğiz)
    $sorgu = $db->prepare("SELECT resim_yolu FROM ilanlar WHERE id = ?");
    $sorgu->execute([$id]);
    $ilan = $sorgu->fetch();

    if ($ilan) {
        // Veritabanından sil
        $sil = $db->prepare("DELETE FROM ilanlar WHERE id = ?");
        $sil->execute([$id]);

        // İlişkili görsel dosyasını da sil
        if (!empty($ilan['resim_yolu'])) {
            $dosya_yolu = PROJE_KOK . $ilan['resim_yolu'];
            if (file_exists($dosya_yolu) && is_file($dosya_yolu)) {
                unlink($dosya_yolu);
            }
        }
    }

} catch (PDOException $e) {
    error_log('Soughts Silme Hatası: ' . $e->getMessage());
}

yonlendir(site_url('admin/index.php'));
