<?php
// Veritabanı bağlantısını projeye dahil et
include 'baglan.php';

// PDO ile urunler (ilanlar) tablosundaki her şeyi en yeniden eskiye doğru çek
$sorgu = $db->query("SELECT * FROM ilanlar ORDER BY id DESC");
$ilanlar = $sorgu->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Soughts Admin | İlan Listesi</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #0b1021;
            color: white;
            font-family: 'Montserrat', sans-serif;
            padding: 40px;
            margin: 0;
        }
        h2 {
            color: #D4AF37;
            border-bottom: 1px solid rgba(212, 175, 55, 0.3);
            padding-bottom: 15px;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #111A3A;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        th {
            background-color: rgba(212, 175, 55, 0.1);
            color: #D4AF37;
            font-weight: 600;
        }
        tr:hover {
            background-color: rgba(255,255,255,0.02);
        }
        .gorsel-mini {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #D4AF37;
        }
        .btn {
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-family: 'Montserrat', sans-serif;
            font-size: 12px;
            font-weight: 600;
        }
        .btn-duzenle {
            background-color: #D4AF37;
            color: #0b1021;
            margin-right: 5px;
        }
        .btn-sil {
            background-color: #e74c3c;
            color: white;
        }
        .bos-mesaj {
            color: #A0A5B5;
            text-align: center;
            padding: 20px;
        }
    </style>
</head>
<body>

    <h2>Koleksiyon Envanteri (Depo Kayıtları)</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Görsel</th>
                <th>Ürün Adı</th>
                <th>Fiyat</th>
                <th>İşlemler</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // SİHİRLİ DÖNGÜ: Veritabanındaki her bir ilan için yeni bir tablo satırı (tr) oluştur
            if(count($ilanlar) > 0):
                foreach($ilanlar as $ilan):
            ?>
            <tr>
                <td>#<?= $ilan['id'] ?></td>
                <td><img src="<?= htmlspecialchars($ilan['resim_yolu']) ?>" class="gorsel-mini" alt="Ürün"></td>
                <td><?= htmlspecialchars($ilan['ilan_adi']) ?></td>
                <td><?= number_format($ilan['fiyat'], 0, ',', '.') ?> ₺</td>
                <td>
                    <a href="duzenle.php?id=<?= $ilan['id'] ?>" class="btn btn-duzenle" style="text-decoration: none; display: inline-block;">Düzenle</a>
<a href="sil.php?id=<?= $ilan['id'] ?>" class="btn btn-sil" style="text-decoration: none; display: inline-block;" onclick="return confirm('Bu nadir parçayı koleksiyondan tamamen silmek istediğinize emin misiniz?');">Sil</a>
                </td>
            </tr>
            <?php 
                endforeach;
            else:
            ?>
            <tr>
                <td colspan="5" class="bos-mesaj">Sistemde henüz kayıtlı ürün bulunmamaktadır.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>