<?php
/**
 * Soughts Premium Marketplace — Admin: İlan Kayıt İşlemi
 * 
 * Hardened file upload + DB insert.
 * Security: admin auth, CSRF, MIME validation, sanitization
 */

require_once __DIR__ . '/../config/baglan.php';
require_once __DIR__ . '/../includes/functions.php';

admin_gerekli();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    yonlendir(site_url('admin/ilan_ekle.php'));
}

csrf_dogrula();

// Girdileri temizle
$ilan_adi  = temizle($_POST['ilan_adi'] ?? '');
$fiyat     = abs((float) ($_POST['fiyat'] ?? 0));
$aciklama  = temizle($_POST['aciklama'] ?? '');
$kategori  = temizle($_POST['kategori'] ?? '');

// Doğrulama
if (empty($ilan_adi) || empty($aciklama)) {
    yonlendir(site_url('admin/ilan_ekle.php'));
}

// Dosya yükleme
if (!isset($_FILES['gorsel']) || $_FILES['gorsel']['error'] !== UPLOAD_ERR_OK) {
    basari_sayfasi(
        'Yükleme Hatası',
        'Lütfen geçerli bir görsel dosyası seçin (JPG, PNG, WebP — Maks. 5MB).',
        site_url('admin/ilan_ekle.php'),
        3
    );
}

$resim_yolu = guvenli_yukle($_FILES['gorsel'], 'uploads/');

if ($resim_yolu === false) {
    basari_sayfasi(
        'Güvenlik İhlali',
        'Sadece JPG, PNG ve WebP dosyaları yükleyebilirsiniz (Maks. 5MB).',
        site_url('admin/ilan_ekle.php'),
        3
    );
}

// Veritabanına kaydet
try {
    $sorgu = $db->prepare(
        "INSERT INTO ilanlar (kullanici_id, ilan_adi, fiyat, aciklama, kategori, resim_yolu, durum) 
         VALUES (?, ?, ?, ?, ?, ?, 'aktif')"
    );
    $sorgu->execute([aktif_kullanici_id(), $ilan_adi, $fiyat, $aciklama, $kategori, $resim_yolu]);

    basari_sayfasi(
        'VIP Ürün Başarıyla Eklendi!',
        'Yeni koleksiyon parçası vitrine mühürlendi.',
        site_url('admin/index.php'),
        3
    );

} catch (PDOException $e) {
    error_log('Soughts İlan Kayıt Hatası: ' . $e->getMessage());
    basari_sayfasi(
        'Sistem Hatası',
        'Ürün kaydedilirken bir sorun oluştu.',
        site_url('admin/ilan_ekle.php'),
        3
    );
}
