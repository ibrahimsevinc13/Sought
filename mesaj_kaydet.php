<?php
/**
 * Soughts Premium Marketplace — İletişim Mesajı Kayıt İşlemi
 * 
 * Contact form → mesajlar tablosuna kaydeder.
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
$ad_soyad = temizle($_POST['iletisim_ad'] ?? '');
$eposta   = temizle_email($_POST['iletisim_eposta'] ?? '');
$mesaj    = temizle($_POST['iletisim_mesaj'] ?? '');

// Doğrulama
if (empty($ad_soyad) || empty($eposta) || empty($mesaj)) {
    yonlendir(site_url('index.php'));
}

// Kullanıcı ID (giriş yapmışsa bağla)
$kullanici_id = aktif_kullanici_id();

try {
    $sorgu = $db->prepare(
        "INSERT INTO mesajlar (kullanici_id, ad_soyad, eposta, mesaj, tip, okundu) 
         VALUES (?, ?, ?, ?, 'iletisim', 0)"
    );
    $sorgu->execute([$kullanici_id, $ad_soyad, $eposta, $mesaj]);

    basari_sayfasi(
        'Mesajınız VIP Hattımıza Ulaştı!',
        'Değerli talebiniz sisteme kaydedildi. En kısa sürede sizinle iletişime geçeceğiz.',
        site_url('index.php'),
        3
    );

} catch (PDOException $e) {
    error_log('Soughts Mesaj Hatası: ' . $e->getMessage());
    basari_sayfasi(
        'Sistem Hatası',
        'Mesajınız kaydedilirken bir sorun oluştu. Lütfen tekrar deneyin.',
        site_url('index.php'),
        3
    );
}