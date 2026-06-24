<?php
include 'baglan.php';

// URL'den gelen ID değerini güvenli bir şekilde yakala
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // PDO ile güvenli DELETE sorgusu (SQL Injection'a karşı prepare kullanıyoruz)
    $sorgu = $db->prepare("DELETE FROM ilanlar WHERE id = ?");
    $sorgu->execute([$id]);
}

// İşlem bitince anında listeye geri fırlat (Kullanıcı beyaz ekran görmesin)
header("Location: ilan_listesi.php");
exit;
?>