<?php 
// Olası oturum kayıplarını önlemek için güvenlik kemeri
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'baglan.php'; 

// Site ayarlarını veritabanından çek (Sadece 1. satırı al)
$ayarSorgu = $db->prepare("SELECT * FROM site_ayarlari WHERE id = 1");
$ayarSorgu->execute();
$ayar = $ayarSorgu->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($sayfa_basligi) ? $sayfa_basligi : 'Soughts'; ?> | Özel Koleksiyonların Güvenli Limanı</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,600&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">

    <style>
      /* LÜKS PROFİL DROPDOWN TASARIMI (Header'a Özel) */
        .profil-dropdown {
            position: relative;
            display: inline-block;
            margin-left: 15px;
        }
        
        /* İŞTE SİHİRLİ KÖPRÜ: Fare aşağı inerken boşluğa düşmesin diye */
        .profil-dropdown::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            height: 15px; /* Menü ile buton arasındaki boşluk kadar bir köprü */
            background: transparent;
            z-index: 9998;
        }

        .profil-btn {
            background: transparent;
            color: #D4AF37;
            border: 1px solid #D4AF37;
            padding: 8px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: 0.3s;
        }
        .profil-btn:hover {
            background: rgba(212, 175, 55, 0.1);
        }
        .dropdown-icerik {
            display: none;
            position: absolute;
            right: 0;
            top: 100%; /* Butonun tam altına hizalar */
            background-color: #111A3A;
            min-width: 180px;
            box-shadow: 0px 10px 30px rgba(0,0,0,0.6);
            border: 1px solid rgba(212, 175, 55, 0.2);
            border-radius: 8px;
            z-index: 9999;
            margin-top: 10px; /* Tasarım boşluğu kalır ama köprüden dolayı kapanmaz */
            overflow: hidden;
        }
        .profil-dropdown:hover .dropdown-icerik {
            display: block;
        }
        .kullanici-bilgi {
            padding: 15px;
            color: #A0A5B5;
            font-size: 11px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            line-height: 1.6;
            text-align: left;
        }
        .kullanici-bilgi b {
            color: white;
            font-size: 14px;
            display: block;
            margin-top: 3px;
        }
        .dropdown-icerik a {
            color: #e74c3c;
            padding: 12px 15px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            transition: background 0.3s;
        }
        .dropdown-icerik a:hover {
            background-color: rgba(231, 76, 60, 0.1);
        }
    </style>
</head>
<body>

    <header class="topbar">
        <div class="logo">
            <img src="images/logo.png" alt="Sought Logo" class="site-logo">
            Soughts<span class="logo-ext"></span>
        </div>
        
        <nav class="ana-menu">
            <a href="talepler.php"><i class="fa-solid fa-hand-holding-heart"></i> Alıcı Talepleri</a>
            <a href="satici_vitrini.php" class="nav-link"><i class="fa-solid fa-store"></i> Satıcı Vitrini</a>
            <a href="#" onclick="iletisimAc(); return false;"><i class="fa-solid fa-envelope"></i> İletişim</a>
            <a href="hakkimizda.php"><i class="fa-solid fa-info-circle"></i>Hakkımızda</a>
        </nav>
        
        <div class="topbar-search">
            <input type="text" placeholder="Aradığınız nadir parça veya referans numarası...">
            <button class="topbar-search-btn"><i class="fa-solid fa-magnifying-glass"></i></button>
        </div>

        <div class="user-actions">
            <span class="username"></span>
            <div class="notification-wrapper">
                <i class="fa-regular fa-bell notification-icon"></i>
                <span class="badge"></span>
            </div>
            
            <?php if(isset($_SESSION['kullanici_giris']) && $_SESSION['kullanici_giris'] === true): ?>
                
                <div class="profil-dropdown">
                    <button class="profil-btn">
                        <i class="fa-solid fa-user-astronaut"></i> <?php echo htmlspecialchars($_SESSION['aktif_kullanici']); ?>
                    </button>
                    <div class="dropdown-icerik">
                        <div class="kullanici-bilgi">
                            Onaylı Koleksiyoner
                            <b><?php echo htmlspecialchars($_SESSION['aktif_kullanici']); ?></b>
                        </div>
                        <a href="cikis.php"><i class="fa-solid fa-power-off"></i> Güvenli Çıkış Yap</a>
                    </div>
                </div>

            <?php elseif(isset($_SESSION['admin_giris']) && $_SESSION['admin_giris'] === true): ?>
                
                <div class="profil-dropdown">
                    <button class="profil-btn" style="border-color: #e74c3c; color: #e74c3c;">
                        <i class="fa-solid fa-shield-halved"></i> <?php echo htmlspecialchars($_SESSION['aktif_admin']); ?>
                    </button>
                    <div class="dropdown-icerik">
                        <div class="kullanici-bilgi">
                            Sistem Kurucusu
                            <b style="color: #e74c3c;"><?php echo htmlspecialchars($_SESSION['aktif_admin']); ?></b>
                        </div>
                        <a href="admin.php" style="color: #D4AF37;"><i class="fa-solid fa-gauge"></i> Yönetim Paneli</a>
                        <a href="cikis.php"><i class="fa-solid fa-power-off"></i> Çıkış Yap</a>
                    </div>
                </div>

            <?php else: ?>
                
                <a href="login.php" class="btn-signup" style="text-decoration: none; display: inline-block; text-align: center; margin-left: 15px;">KAYIT OL</a>
                
            <?php endif; ?>
            </div> </header>