<?php
session_start();

// Veritabanı köprüsünü kur
include 'baglan.php'; 

// Oturum kontrolü: Kim nereye gidecek?
if (isset($_SESSION['kullanici_giris']) && $_SESSION['kullanici_giris'] === true) {
    header("Location: index.php");
    exit;
} elseif (isset($_SESSION['admin_giris']) && $_SESSION['admin_giris'] === true) {
    header("Location: admin.php");
    exit;
}

$hata = "";

// GİŞE MOTORU: Hangi formdan giriş yapıldı? (Admin mi, Kullanıcı mı?)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['giris_tipi'])) {
    $tip = $_POST['giris_tipi'];
    $kullanici = $_POST['kullanici_adi'];
    $sifre = $_POST['sifre'];

    // 1. SOL TARAF: Kurucu Admin Girişi
    if ($tip == 'admin') {
        if ($kullanici === 'admin' && $sifre === '12345') {
            $_SESSION['admin_giris'] = true;
            $_SESSION['aktif_admin'] = "İbrahim Sevinç"; 
            header("Location: admin.php"); 
            exit;
        } else {
            $hata = "Hatalı giriş! Yönetici kimliğiniz doğrulanamadı.";
        }
    } 
    // 2. SAĞ TARAF: Normal Kullanıcı (Koleksiyoner) Girişi
    elseif ($tip == 'kullanici') {
        $sorgu = $db->prepare("SELECT * FROM kullanicilar WHERE kullanici_adi = ? AND sifre = ?");
        $sorgu->execute([$kullanici, $sifre]);
        $uye = $sorgu->fetch(PDO::FETCH_ASSOC);

        if ($uye) {
            $_SESSION['kullanici_giris'] = true;
            $_SESSION['aktif_kullanici'] = $uye['kullanici_adi'];
            // Normal kullanıcıyı vitrine (index.php) fırlat
            header("Location: index.php");
            exit;
        } else {
            $hata = "Kullanıcı girişi başarısız. Bilgilerinizi kontrol edin.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Soughts | Güvenlik Kapısı</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&family=Playfair+Display:ital,wght@0,600;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #0b1021; color: white; font-family: 'Montserrat', sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .guvenlik-kapsayici { display: flex; gap: 40px; background: #111A3A; padding: 40px; border-radius: 8px; border: 1px solid rgba(212, 175, 55, 0.3); box-shadow: 0 10px 30px rgba(0,0,0,0.5); flex-wrap: wrap; justify-content: center; max-width: 800px; position: relative; }
        .form-kutu { width: 300px; display: flex; flex-direction: column; }
        .dikey-cizgi { width: 1px; background: rgba(212, 175, 55, 0.2); }
        h2 { color: #D4AF37; text-align: center; font-family: 'Playfair Display', serif; margin-top: 0; margin-bottom: 5px; }
        .alt-baslik { text-align: center; color: #A0A5B5; font-size: 13px; margin-bottom: 25px; }
        label { font-size: 13px; color: #D4AF37; margin-bottom: 5px; font-weight: 600; }
        input { width: 100%; padding: 12px; margin-bottom: 20px; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1); color: white; border-radius: 4px; box-sizing: border-box; font-family: 'Montserrat', sans-serif; outline: none; transition: 0.3s; }
        input:focus { border-color: #D4AF37; background: rgba(255,255,255,0.07); }
        button { width: 100%; padding: 14px; font-weight: bold; font-family: 'Montserrat', sans-serif; border-radius: 4px; cursor: pointer; transition: 0.3s; margin-top: auto; }
        .btn-giris { background: #D4AF37; color: #0b1021; border: none; }
        .btn-giris:hover { background: #b5952f; }
        .btn-kayit { background: transparent; color: #D4AF37; border: 1px solid #D4AF37; }
        .btn-kayit:hover { background: rgba(212, 175, 55, 0.1); }
        .hata-mesaji, .basari-mesaji { position: absolute; top: -50px; left: 50%; transform: translateX(-50%); padding: 10px 20px; border-radius: 4px; font-size: 13px; white-space: nowrap; }
        .hata-mesaji { background: rgba(231, 76, 60, 0.1); color: #e74c3c; border: 1px solid rgba(231, 76, 60, 0.3); }
        .basari-mesaji { background: rgba(46, 204, 113, 0.1); color: #2ecc71; border: 1px solid rgba(46, 204, 113, 0.3); }
        .btn-link { background: none; border: none; color: #A0A5B5; text-decoration: underline; font-size: 12px; cursor: pointer; margin-top: 15px; text-align: center; width: 100%; padding: 0; }
        .btn-link:hover { color: #D4AF37; }
    </style>
</head>
<body>

    <div class="guvenlik-kapsayici">
        
        <?php if(isset($_GET['kayit']) && $_GET['kayit'] == 'basarili'): ?>
            <div class="basari-mesaji"><i class="fa-solid fa-circle-check"></i> VIP Kaydınız oluşturuldu! Lütfen sağ taraftan giriş yapın.</div>
        <?php endif; ?>

        <?php if($hata != ""): ?>
            <div class="hata-mesaji"><i class="fa-solid fa-triangle-exclamation"></i> <?php echo $hata; ?></div>
        <?php endif; ?>

        <div class="form-kutu">
            <h2>Soughts VIP</h2>
            <p class="alt-baslik">Yönetim Paneline Giriş Yapın</p>
            
            <form action="login.php" method="POST"> 
                <input type="hidden" name="giris_tipi" value="admin">
                
                <label>Kullanıcı Adı</label>
                <input type="text" name="kullanici_adi" required placeholder="Sistem yöneticisi">
                
                <label>Şifre</label>
                <input type="password" name="sifre" required placeholder="••••••••">
                
                <button type="submit" class="btn-giris">Yönetici Olarak Giriş Yap</button>
            </form>
        </div>

        <div class="dikey-cizgi"></div>

        <div class="form-kutu" id="kayitFormu">
            <h2>Yeni Koleksiyoner</h2>
            <p class="alt-baslik">Soughts Ailesine Katılın</p>
            
            <form action="kayit_kaydet.php" method="POST">
                <label>Kullanıcı Adı</label>
                <input type="text" name="yeni_kullanici" required placeholder="Örn: ibrahim_bey">
                
                <label>E-posta</label>
                <input type="email" name="yeni_eposta" required placeholder="E-posta adresiniz">
                
                <label>Şifre</label>
                <input type="password" name="yeni_sifre" required placeholder="Güçlü bir şifre">
                
                <button type="submit" class="btn-kayit">VIP Kaydımı Oluştur</button>
            </form>
            
            <button class="btn-link" onclick="formDegistir('giris')">Zaten üye misiniz? Giriş Yapın.</button>
        </div>

        <div class="form-kutu" id="girisFormu" style="display: none;">
            <h2>Koleksiyoner Girişi</h2>
            <p class="alt-baslik">Vitrini Keşfetmeye Devam Edin</p>
            
            <form action="login.php" method="POST">
                <input type="hidden" name="giris_tipi" value="kullanici">
                
                <label>Kullanıcı Adı</label>
                <input type="text" name="kullanici_adi" required placeholder="Kayıtlı kullanıcı adınız">
                
                <label>Şifre</label>
                <input type="password" name="sifre" required placeholder="••••••••">
                
                <button type="submit" class="btn-kayit" style="background: #D4AF37; color: #0b1021;">Koleksiyoner Girişi Yap</button>
            </form>
            
            <button class="btn-link" onclick="formDegistir('kayit')">Hesabınız yok mu? Yeni Kayıt Oluşturun.</button>
        </div>

    </div>

    <script>
        function formDegistir(hedef) {
            if (hedef === 'giris') {
                document.getElementById('kayitFormu').style.display = 'none';
                document.getElementById('girisFormu').style.display = 'flex';
            } else {
                document.getElementById('girisFormu').style.display = 'none';
                document.getElementById('kayitFormu').style.display = 'flex';
            }
        }
        
        // Eğer kullanıcı kayıttan başarılı döndüyse direkt giriş formunu aç
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            if(urlParams.get('kayit') === 'basarili') {
                formDegistir('giris');
            }
        }
    </script>

</body>
</html>