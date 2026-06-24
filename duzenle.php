<?php
include 'baglan.php';

// URL'den hangi ürünün düzenleneceğini yakala
if (!isset($_GET['id'])) {
    header("Location: ilan_listesi.php");
    exit;
}

$id = $_GET['id'];

// Veritabanından sadece bu ID'ye ait olan o tek özel ürünü çek (fetch)
$sorgu = $db->prepare("SELECT * FROM ilanlar WHERE id = ?");
$sorgu->execute([$id]);
$ilan = $sorgu->fetch(PDO::FETCH_ASSOC);

// Eğer böyle bir ürün yoksa listeye geri gönder
if (!$ilan) {
    header("Location: ilan_listesi.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Soughts VIP | İlan Düzenle</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&family=Playfair+Display:ital,wght@0,600;1,600&display=swap" rel="stylesheet">
    <style>
        /* Önceki ekleme formumuzun aynı lüks CSS ayarları */
        body { background-color: #0b1021; color: white; font-family: 'Montserrat', sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .form-kutu { background: #111A3A; padding: 40px; border-radius: 8px; border: 1px solid rgba(212, 175, 55, 0.3); box-shadow: 0 10px 30px rgba(0,0,0,0.5); width: 400px; }
        h2 { color: #D4AF37; text-align: center; font-family: 'Playfair Display', serif; margin-top: 0; margin-bottom: 25px; }
        label { font-size: 13px; color: #D4AF37; margin-bottom: 5px; display: block; font-weight: 600; }
        input[type="text"], input[type="number"], textarea { width: 100%; padding: 12px; margin-bottom: 20px; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1); color: white; border-radius: 4px; box-sizing: border-box; font-family: 'Montserrat', sans-serif; outline: none; }
        input:focus, textarea:focus { border-color: #D4AF37; background: rgba(255,255,255,0.07); }
        button { width: 100%; padding: 14px; font-weight: bold; font-family: 'Montserrat', sans-serif; border-radius: 4px; cursor: pointer; transition: 0.3s; background: #D4AF37; color: #0b1021; border: none; }
        button:hover { background: #b5952f; }
        .uyari { font-size: 11px; color: #A0A5B5; margin-bottom: 20px; display: block; }
    </style>
</head>
<body>

    <div class="form-kutu">
        <h2>Parçayı Yeniden Değerle</h2>
        
        <form action="guncelle_islem.php" method="POST">
            <input type="hidden" name="id" value="<?= $ilan['id'] ?>">
            
            <label>Ürün / İlan Adı</label>
            <input type="text" name="ilan_adi" required value="<?= htmlspecialchars($ilan['ilan_adi']) ?>">
            
            <label>Fiyat (₺)</label>
            <input type="number" name="fiyat" required value="<?= $ilan['fiyat'] ?>">
            
            <label>Açıklama</label>
            <textarea name="aciklama" rows="4" required><?= htmlspecialchars($ilan['aciklama']) ?></textarea>
            
            <span class="uyari">* Sadece metin ve fiyat bilgileri güncellenecektir.</span>
            
            <button type="submit">Değişiklikleri Kaydet</button>
        </form>
    </div>

</body>
</html>