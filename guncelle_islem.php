<?php
include 'baglan.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Formdan gelen yeni verileri ve o gizli ID'yi yakala
    $id       = $_POST['id'];
    $ilan_adi = $_POST['ilan_adi'];
    $fiyat    = $_POST['fiyat'];
    $aciklama = $_POST['aciklama'];
    
    // PDO ile Güvenli UPDATE Sorgusu
    $sorgu = $db->prepare("UPDATE ilanlar SET ilan_adi = ?, fiyat = ?, aciklama = ? WHERE id = ?");
    $basari = $sorgu->execute([$ilan_adi, $fiyat, $aciklama, $id]);
    
    if ($basari) {
        // Kayıt başarılıysa JS Alert verip listeye geri gönder
        echo "<script>
            alert('VIP Ürün Başarıyla Güncellendi!');
            window.location.href = 'ilan_listesi.php';
        </script>";
    } else {
        echo "Güncelleme sırasında bir hata oluştu.";
    }
} else {
    header("Location: ilan_listesi.php");
    exit;
}
?>