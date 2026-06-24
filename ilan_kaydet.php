<?php
// 1. Veritabanı köprüsünü kur
include 'baglan.php';

// Formdan bir istek gelip gelmediğini kontrol et
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Müşterinin yazdığı metinleri yakala
    $ilan_adi = $_POST['ilan_adi'];
    $fiyat    = $_POST['fiyat'];
    $aciklama = $_POST['aciklama'];
    
    // Müşterinin yüklediği dosyayı (resmi) $_FILES ile yakala
    $gelen_gorsel = $_FILES['gorsel'];
    
    // Dosyanın asıl adını ve geçici hafızadaki yerini al
    $orijinal_isim = $gelen_gorsel['name'];
    $gecici_yol    = $gelen_gorsel['tmp_name'];
    
    // --- MEYDAN OKUMA 1: GÜVENLİK KAPISI (Sadece Resimlere İzin Ver) ---
    // Dosyanın uzantısını bul (örn: jpg, png, exe)
    $uzanti = strtolower(pathinfo($orijinal_isim, PATHINFO_EXTENSION));
    $izin_verilenler = array("jpg", "jpeg", "png");
    
    if (!in_array($uzanti, $izin_verilenler)) {
        die("<h1>Güvenlik İhlali!</h1><p>Sadece .jpg, .jpeg ve .png yükleyebilirsiniz. Lütfen geri dönün.</p>");
    }
    
    // --- MEYDAN OKUMA 2: İSİM ÇAKIŞMASINI ÖNLEME ---
    // uniqid() ile resmin başına benzersiz bir kod ekle (Örn: 64b8e..._saat.jpg)
    $benzersiz_isim = uniqid("vip_") . "." . $uzanti;
    
    // Resmin taşınacağı nihai adresi belirle
    $hedef_klasor = "images/" . $benzersiz_isim;
    
    // 2. DOSYA YÜKLEME İŞLEMİ (move_uploaded_file)
    if (move_uploaded_file($gecici_yol, $hedef_klasor)) {
        
        // 3. VERİTABANINA KAYDET (PDO CREATE İşlemi)
        // DİKKAT: Eğer veritabanınızdaki sütun adları farklıysa, aşağıdaki satırı ona göre güncelleyin.
        $sorgu = $db->prepare("INSERT INTO ilanlar (ilan_adi, fiyat, aciklama, resim_yolu) VALUES (?, ?, ?, ?)");
        
        // PDO bind işlemi: Değişkenleri güvenli bir şekilde dizi içinde gönderiyoruz
        $kayit_basarili = $sorgu->execute([$ilan_adi, $fiyat, $aciklama, $hedef_klasor]);
        
        if ($kayit_basarili) {
            echo "<script>
                alert('Tebrikler! VIP Ürün Başarıyla Vitrine Eklendi.');
                window.location.href = 'ilan_ekle.php';
            </script>";
        } else {
            echo "Veritabanına kayıt sırasında bir sorun oluştu.";
        }
        
    } else {
        echo "<h1>Hata!</h1><p>Resim klasöre yüklenirken bir sorun oluştu. 'images' klasörünün var olduğundan emin olun.</p>";
    }
} else {
    // Sayfaya direkt linkten girmeye çalışanları geri gönder
    header("Location: ilan_ekle.php");
    exit;
}
?>