<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Soughts VIP | Üretim Bandı</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&family=Playfair+Display:ital,wght@0,600;1,600&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #0b1021;
            color: white;
            font-family: 'Montserrat', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .form-kutu {
            background: #111A3A;
            padding: 40px;
            border-radius: 8px;
            border: 1px solid rgba(212, 175, 55, 0.3);
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            width: 400px;
        }
        h2 {
            color: #D4AF37;
            text-align: center;
            font-family: 'Playfair Display', serif;
            margin-top: 0;
            margin-bottom: 25px;
        }
        label {
            font-size: 13px;
            color: #D4AF37;
            margin-bottom: 5px;
            display: block;
            font-weight: 600;
        }
        input[type="text"], input[type="number"], textarea, input[type="file"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.1);
            color: white;
            border-radius: 4px;
            box-sizing: border-box;
            font-family: 'Montserrat', sans-serif;
            outline: none;
            transition: 0.3s;
        }
        input:focus, textarea:focus {
            border-color: #D4AF37;
            background: rgba(255,255,255,0.07);
        }
        button {
            width: 100%;
            padding: 14px;
            font-weight: bold;
            font-family: 'Montserrat', sans-serif;
            border-radius: 4px;
            cursor: pointer;
            transition: 0.3s;
            background: #D4AF37;
            color: #0b1021;
            border: none;
        }
        button:hover { background: #b5952f; }
    </style>
</head>
<body>

    <div class="form-kutu">
        <h2>VIP Vitrinine Ekle</h2>
        
        <!-- SİHİRLİ ANAHTAR BURADA -->
        <form action="ilan_kaydet.php" method="POST" enctype="multipart/form-data">
            
            <label>Ürün / İlan Adı</label>
            <input type="text" name="ilan_adi" required placeholder="Örn: Özel Koleksiyon Saat">
            
            <label>Fiyat (₺)</label>
            <input type="number" name="fiyat" required placeholder="Örn: 150000">
            
            <label>Açıklama</label>
            <textarea name="aciklama" rows="4" required placeholder="Detayları girin..."></textarea>
            
            <label>Vitrin Görseli</label>
            <input type="file" name="gorsel" required accept=".jpg, .jpeg, .png">
            
            <button type="submit">Sisteme Kaydet</button>
        </form>
    </div>

</body>
</html>