<?php
// 1. GÜVENLİK DUVARI: Sadece VIP Yönetici bu dosyayı çalıştırabilir
session_start();
if (!isset($_SESSION['admin_giris']) || $_SESSION['admin_giris'] !== true) {
    header("Location: login.php");
    exit;
}

// 2. Veritabanı köprüsünü kur
include 'baglan.php';

// 3. Eğer URL'den bir 'id' numarası geldiyse operasyonu başlat
if (isset($_GET['id'])) {
    
    // Güvenlik için gelen ID'yi sadece sayısal bir değere çeviririz (Hack girişimlerini önler)
    $silinecek_id = (int)$_GET['id'];

    // 4. İMHA İŞLEMİ: SQL Delete komutu
    $sorgu = $db->prepare("DELETE FROM mesajlar WHERE id = :id");
    $sorgu->execute(['id' => $silinecek_id]);
}

// 5. İZLERİ SİL: İşlem bitince hiçbir şey olmamış gibi anında Admin paneline geri fırlat
header("Location: admin.php");
exit;
?>