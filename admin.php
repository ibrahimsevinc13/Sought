<?php
// GÜVENLİK DUVARI
session_start();
if (!isset($_SESSION['admin_giris']) || $_SESSION['admin_giris'] !== true) {
    header("Location: login.php");
    exit;
}

// Veritabanı köprüsünü kur
include 'baglan.php';

// 1. VERİ ÇEKME: Mesajlar (Talepler) tablosu
$sorgu_mesaj = $db->prepare("SELECT * FROM mesajlar ORDER BY id DESC");
$sorgu_mesaj->execute();
$mesajlar = $sorgu_mesaj->fetchAll(PDO::FETCH_ASSOC);

// 2. VERİ ÇEKME: İlanlar (Ürünler) tablosu
$sorgu_ilan = $db->query("SELECT * FROM ilanlar ORDER BY id DESC");
$ilanlar = $sorgu_ilan->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Soughts | Yönetim Merkezi</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&family=Playfair+Display:ital,wght@0,600;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #111A3A; color: white; font-family: 'Montserrat', sans-serif; padding: 40px; margin: 0; }
        .header-kutu { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(212, 175, 55, 0.3); padding-bottom: 20px; margin-bottom: 40px; }
        h1 { color: #D4AF37; margin: 0; font-family: 'Playfair Display', serif; }
        .bolum-baslik { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; margin-top: 50px; }
        h2 { color: #D4AF37; margin: 0; }
        .kutu { background: rgba(255, 255, 255, 0.03); padding: 30px; border-radius: 8px; border: 1px solid rgba(212, 175, 55, 0.2); box-shadow: 0 10px 30px rgba(0,0,0,0.5); overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th { padding: 15px 10px; border-bottom: 2px solid #D4AF37; color: #D4AF37; }
        td { padding: 15px 10px; border-bottom: 1px solid rgba(255,255,255,0.05); }
        tr:hover { background: rgba(255,255,255,0.05); }
        .btn-tehlike { color: #e74c3c; text-decoration: none; font-size: 13px; border: 1px solid #e74c3c; padding: 6px 12px; border-radius: 4px; transition: 0.3s; display: inline-block; }
        .btn-tehlike:hover { background: rgba(231, 76, 60, 0.1); }
        .btn-duzenle { color: #D4AF37; text-decoration: none; font-size: 13px; border: 1px solid #D4AF37; padding: 6px 12px; border-radius: 4px; transition: 0.3s; display: inline-block; margin-right: 5px; }
        .btn-duzenle:hover { background: rgba(212, 175, 55, 0.1); }
        .btn-yeni { background: #D4AF37; color: #0b1021; text-decoration: none; padding: 10px 20px; font-weight: 600; border-radius: 4px; font-size: 13px; transition: 0.3s; }
        .btn-yeni:hover { background: #b5952f; }
        .gorsel-mini { width: 50px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid rgba(212, 175, 55, 0.3); }
        .bos-mesaj { text-align: center; color: #A0A5B5; font-style: italic; margin: 0; padding: 20px; }
    </style>
</head>
<body>
    
    <div class="header-kutu">
        <div>
            <h1>Soughts Kontrol Merkezi</h1>
            <p style="color: #A0A5B5; margin-top: 5px; font-size: 14px;">Hoş geldin, VIP Yönetici: <strong style="color: white;"><?php echo $_SESSION['aktif_admin']; ?></strong></p>
        </div>
        <a href="cikis.php" class="btn-tehlike"><i class="fa-solid fa-power-off"></i> Dükkanı Kilitle (Çıkış)</a>
    </div>

    <div class="bolum-baslik">
        <h2><i class="fa-solid fa-envelope-open-text"></i> Gelen Kutusu (VIP Talepler)</h2>
    </div>
    
    <div class="kutu">
        <?php if(count($mesajlar) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Tarih</th>
                        <th>Ad Soyad</th>
                        <th>E-posta</th>
                        <th>Mesaj</th>
                        <th style="text-align: center;">İşlem</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($mesajlar as $mesaj): ?>
                    <tr>
                        <td style="color: #A0A5B5; font-size: 13px;"><?php echo date('d.m.Y H:i', strtotime($mesaj['tarih'] ?? 'now')); ?></td>
                        <td style="font-weight: bold;"><?php echo htmlspecialchars($mesaj['ad_soyad']); ?></td>
                        <td style="color: #A0A5B5;"><?php echo htmlspecialchars($mesaj['eposta']); ?></td>
                        <td style="font-style: italic;">"<?php echo htmlspecialchars($mesaj['mesaj']); ?>"</td>
                        <td style="text-align: center;">
                            <a href="mesaj_sil.php?id=<?php echo $mesaj['id']; ?>" class="btn-tehlike" onclick="return confirm('Bu mesajı silmek istediğinize emin misiniz?');">SİL</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="bos-mesaj">Sistemde henüz hiç mesaj bulunmuyor.</p>
        <?php endif; ?>
    </div>

    <div class="bolum-baslik">
        <h2><i class="fa-solid fa-boxes-stacked"></i> Koleksiyon Envanteri (Vitrin)</h2>
        <a href="ilan_ekle.php" class="btn-yeni"><i class="fa-solid fa-plus"></i> Yeni İlan Ekle</a>
    </div>

    <div class="kutu">
        <?php if(count($ilanlar) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Görsel</th>
                        <th>İlan ID</th>
                        <th>Ürün Adı</th>
                        <th>Fiyat</th>
                        <th style="text-align: center;">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($ilanlar as $ilan): ?>
                    <tr>
                        <td><img src="<?php echo htmlspecialchars($ilan['resim_yolu']); ?>" class="gorsel-mini" alt="Ürün"></td>
                        <td style="color: #A0A5B5;">#<?php echo $ilan['id']; ?></td>
                        <td style="font-weight: bold;"><?php echo htmlspecialchars($ilan['ilan_adi']); ?></td>
                        <td style="color: #D4AF37; font-weight: 600;"><?php echo number_format($ilan['fiyat'], 0, ',', '.'); ?> ₺</td>
                        <td style="text-align: center;">
                            <a href="duzenle.php?id=<?php echo $ilan['id']; ?>" class="btn-duzenle"><i class="fa-solid fa-pen"></i> Düzenle</a>
                            <a href="sil.php?id=<?php echo $ilan['id']; ?>" class="btn-tehlike" onclick="return confirm('Bu ürünü vitrinden kalıcı olarak silmek istediğinize emin misiniz?');"><i class="fa-solid fa-trash"></i> Sil</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="bos-mesaj">Vitrinde henüz hiç ürün bulunmuyor. Yeni ilan ekleyerek başlayın.</p>
        <?php endif; ?>
    </div>

</body>
</html>