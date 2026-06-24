<?php
/**
 * Soughts Premium Marketplace — Admin: İlan Güncelleme İşlemi
 * 
 * Security: admin auth, CSRF, input sanitization, integer ID
 */

require_once __DIR__ . '/../config/baglan.php';
require_once __DIR__ . '/../includes/functions.php';

admin_gerekli();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    yonlendir(site_url('admin/index.php'));
}

csrf_dogrula();

// Girdileri temizle
$id       = temizle_int($_POST['id'] ?? 0);
$ilan_adi = temizle($_POST['ilan_adi'] ?? '');
$fiyat    = abs((float) ($_POST['fiyat'] ?? 0));
$aciklama = temizle($_POST['aciklama'] ?? '');
$kategori = temizle($_POST['kategori'] ?? '');

// Doğrulama
if ($id <= 0 || empty($ilan_adi)) {
    yonlendir(site_url('admin/index.php'));
}

try {
    $sorgu = $db->prepare(
        "UPDATE ilanlar SET ilan_adi = ?, fiyat = ?, aciklama = ?, kategori = ? WHERE id = ?"
    );
    $sorgu->execute([$ilan_adi, $fiyat, $aciklama, $kategori, $id]);

    basari_sayfasi(
        'VIP Ürün Başarıyla Güncellendi!',
        'Koleksiyon parçasının bilgileri yeniden mühürlendi.',
        site_url('admin/index.php'),
        3
    );

} catch (PDOException $e) {
    error_log('Soughts Güncelleme Hatası: ' . $e->getMessage());
    basari_sayfasi(
        'Sistem Hatası',
        'Güncelleme sırasında bir sorun oluştu.',
        site_url('admin/duzenle.php?id=' . $id),
        3
    );
}
