<?php
/**
 * Soughts Premium Marketplace — VIP Talep Kayıt İşlemi
 * 
 * Buyer request → talepler tablosuna kaydeder.
 * Security: CSRF, XSS sanitization, session-linked user ID
 */

require_once __DIR__ . '/config/baglan.php';
require_once __DIR__ . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    yonlendir(site_url('index.php'));
}

// CSRF doğrulama
csrf_dogrula();

// Girdileri temizle
$ad_soyad = temizle($_POST['ad_soyad'] ?? '');
$eposta   = temizle_email($_POST['eposta'] ?? '');
$baslik   = temizle($_POST['baslik'] ?? '');
$fiyat    = abs((float) ($_POST['fiyat'] ?? 0));

// Doğrulama
if (empty($ad_soyad) || empty($eposta) || empty($baslik)) {
    yonlendir(site_url('index.php'));
}

// Kullanıcı ID (giriş yapmışsa bağla, yoksa NULL)
$kullanici_id = aktif_kullanici_id();

try {
    $sorgu = $db->prepare(
        "INSERT INTO talepler (kullanici_id, ad_soyad, eposta, baslik, fiyat, durum) 
         VALUES (?, ?, ?, ?, ?, 'beklemede')"
    );
    $sorgu->execute([$kullanici_id, $ad_soyad, $eposta, $baslik, $fiyat]);

    basari_sayfasi(
        'Talebiniz Başarıyla Alındı!',
        'Soughts Akıllı Eşleştirme Sistemi devrede. En kısa sürede satıcılarla eşleştirileceksiniz.',
        site_url('index.php'),
        4
    );

} catch (PDOException $e) {
    error_log('Soughts Talep Hatası: ' . $e->getMessage());
    basari_sayfasi(
        'Sistem Hatası',
        'Talebiniz kaydedilirken bir sorun oluştu. Lütfen tekrar deneyin.',
        site_url('index.php'),
        3
    );
}