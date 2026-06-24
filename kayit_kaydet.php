<?php
// Veritabanı bağlantı köprüsünü dahil et
include 'baglan.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['yeni_kullanici'])) {
    
    // Form elemanlarından gelen lüks verileri yakala
    $kullanici = $_POST['yeni_kullanici'];
    $eposta    = $_POST['yeni_eposta'];
    $sifre     = $_POST['yeni_sifre'];

    try {
        // SQL Injection saldırılarını önlemek için güvenli prepare metodunu kullanıyoruz
        $sorgu = $db->prepare("INSERT INTO kullanicilar (kullanici_adi, eposta, sifre) VALUES (?, ?, ?)");
        $basari = $sorgu->execute([$kullanici, $eposta, $sifre]);

        if ($basari) {
            // Veritabanına kayıt işlemi milisaniyeler içinde tamamlandı, gişeye geri gönder!
            header("Location: login.php?kayit=basarili");
            exit;
        } else {
            echo "Veri yazma bandında sistemsel bir hata meydana geldi.";
        }
    } catch (PDOException $e) {
        echo "Koleksiyon Veritabanı Hatası: " . $e->getMessage();
    }

} else {
    // Sayfaya form doldurmadan, link üzerinden sızmaya çalışanları kapıya postala
    header("Location: login.php");
    exit;
}
?>