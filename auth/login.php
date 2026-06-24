<?php
/**
 * Soughts Premium Marketplace — Giriş & Kayıt Sayfası
 * 
 * Single-page application: Admin Login | User Register/Login
 * Supports hardcoded admin (admin/12345) + DB-based user auth.
 * Sets both new and legacy session variables for full compatibility.
 */

require_once __DIR__ . '/../config/baglan.php';
require_once __DIR__ . '/../includes/functions.php';

// Zaten giriş yapmışsa yönlendir — döngü koruyucusu
if (admin_mi()) {
    yonlendir(site_url('admin/index.php'));
}
if (kullanici_mi()) {
    yonlendir(site_url('index.php'));
}

$hata = '';

// URL'den gelen hata mesajlarını yakala
if (isset($_GET['hata'])) {
    switch ($_GET['hata']) {
        case 'bos':      $hata = 'Lütfen tüm alanları doldurun.'; break;
        case 'eposta':   $hata = 'Geçerli bir e-posta adresi girin.'; break;
        case 'sifre':    $hata = 'Şifre en az 6 karakter olmalıdır.'; break;
        case 'kullanici':$hata = 'Kullanıcı adı 3-50 karakter arasında olmalıdır.'; break;
        case 'mevcut':   $hata = 'Bu kullanıcı adı veya e-posta zaten kayıtlı.'; break;
        case 'sistem':   $hata = 'Bir sistem hatası oluştu. Lütfen tekrar deneyin.'; break;
    }
}

// ============================================================
//  GİRİŞ MOTORU
// ============================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['giris_tipi'])) {
    csrf_dogrula();

    $tip       = $_POST['giris_tipi'];
    $kullanici = trim($_POST['kullanici_adi'] ?? '');
    $sifre_raw = $_POST['sifre'] ?? '';

    if (empty($kullanici) || empty($sifre_raw)) {
        $hata = 'Tüm alanları doldurun.';
    } else {

        // ============================================================
        //  1. YÖNETİCİ GİRİŞİ
        // ============================================================
        if ($tip === 'admin') {

            // ÖNCELİK 1: Sabit kodlanmış kurucu admin (admin / 12345)
            if ($kullanici === 'admin' && $sifre_raw === '12345') {
                session_regenerate_id(true);

                // YENİ yapı
                $_SESSION['kullanici_id']  = 1;
                $_SESSION['kullanici_adi'] = 'admin';
                $_SESSION['eposta']        = 'admin@soughts.com';
                $_SESSION['ad_soyad']      = 'İbrahim Sevinç';
                $_SESSION['rol']           = 'admin';

                // ESKİ yapı — geriye dönük uyumluluk
                $_SESSION['admin_giris']   = true;
                $_SESSION['aktif_admin']   = 'İbrahim Sevinç';

                yonlendir(site_url('admin/index.php'));
            }

            // ÖNCELİK 2: Veritabanından admin doğrulama (Bcrypt)
            $sorgu = $db->prepare("SELECT id, kullanici_adi, eposta, sifre, ad_soyad, rol, durum FROM kullanicilar WHERE kullanici_adi = ? AND rol = 'admin' LIMIT 1");
            $sorgu->execute([$kullanici]);
            $uye = $sorgu->fetch();

            if ($uye && password_verify($sifre_raw, $uye['sifre'])) {
                if ($uye['durum'] !== 'aktif') {
                    $hata = 'Hesabınız askıya alınmıştır.';
                } else {
                    session_regenerate_id(true);

                    $_SESSION['kullanici_id']  = $uye['id'];
                    $_SESSION['kullanici_adi'] = $uye['kullanici_adi'];
                    $_SESSION['eposta']        = $uye['eposta'];
                    $_SESSION['ad_soyad']      = $uye['ad_soyad'] ?? $uye['kullanici_adi'];
                    $_SESSION['rol']           = 'admin';
                    $_SESSION['admin_giris']   = true;
                    $_SESSION['aktif_admin']   = $uye['ad_soyad'] ?? $uye['kullanici_adi'];

                    $db->prepare("UPDATE kullanicilar SET son_giris = NOW() WHERE id = ?")->execute([$uye['id']]);
                    yonlendir(site_url('admin/index.php'));
                }
            } else {
                $hata = 'Hatalı giriş! Yönetici kimliğiniz doğrulanamadı.';
            }
        }

        // ============================================================
        //  2. KOLEKSİYONER (KULLANICI) GİRİŞİ
        // ============================================================
        elseif ($tip === 'kullanici') {
            $sorgu = $db->prepare("SELECT id, kullanici_adi, eposta, sifre, ad_soyad, rol, durum FROM kullanicilar WHERE kullanici_adi = ? AND rol = 'kullanici' LIMIT 1");
            $sorgu->execute([$kullanici]);
            $uye = $sorgu->fetch();

            if ($uye && password_verify($sifre_raw, $uye['sifre'])) {
                if ($uye['durum'] !== 'aktif') {
                    $hata = 'Hesabınız askıya alınmıştır. Destek ile iletişime geçin.';
                } else {
                    session_regenerate_id(true);

                    // YENİ yapı
                    $_SESSION['kullanici_id']    = $uye['id'];
                    $_SESSION['kullanici_adi']   = $uye['kullanici_adi'];
                    $_SESSION['eposta']          = $uye['eposta'];
                    $_SESSION['ad_soyad']        = $uye['ad_soyad'] ?? $uye['kullanici_adi'];
                    $_SESSION['rol']             = 'kullanici';

                    // ESKİ yapı — geriye dönük uyumluluk
                    $_SESSION['kullanici_giris'] = true;
                    $_SESSION['aktif_kullanici'] = $uye['kullanici_adi'];

                    $db->prepare("UPDATE kullanicilar SET son_giris = NOW() WHERE id = ?")->execute([$uye['id']]);
                    yonlendir(site_url('index.php'));
                }
            } else {
                $hata = 'Kullanıcı girişi başarısız. Bilgilerinizi kontrol edin.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soughts | Güvenlik Kapısı</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= site_url('assets/css/style.css') ?>">
</head>
<body class="login-sayfa">

    <div class="guvenlik-kapsayici">

        <?php if (isset($_GET['kayit']) && $_GET['kayit'] === 'basarili'): ?>
            <div class="basari-mesaji login-alert">
                <i class="fa-solid fa-circle-check"></i> VIP Kaydınız oluşturuldu! Lütfen giriş yapın.
            </div>
        <?php endif; ?>

        <?php if ($hata): ?>
            <div class="hata-mesaji login-alert">
                <i class="fa-solid fa-triangle-exclamation"></i> <?= htmlspecialchars($hata) ?>
            </div>
        <?php endif; ?>

        <!-- ===== SOL PANEL: YÖNETİCİ GİRİŞİ ===== -->
        <div class="login-form-kutu">
            <h2>Soughts VIP</h2>
            <p class="login-alt-baslik">Yönetim Paneline Giriş Yapın</p>

            <form action="" method="POST">
                <?= csrf_token_input() ?>
                <input type="hidden" name="giris_tipi" value="admin">

                <label>Kullanıcı Adı</label>
                <input type="text" name="kullanici_adi" required placeholder="Sistem yöneticisi" autocomplete="username">

                <label>Şifre</label>
                <input type="password" name="sifre" required placeholder="••••••••" autocomplete="current-password">

                <button type="submit" class="btn-giris">
                    <i class="fa-solid fa-shield-halved"></i> Yönetici Olarak Giriş Yap
                </button>
            </form>
        </div>

        <!-- Dikey Ayırıcı -->
        <div class="dikey-cizgi"></div>

        <!-- ===== SAĞ PANEL: KAYIT FORMU (Varsayılan) ===== -->
        <div class="login-form-kutu" id="kayitFormu">
            <h2>Yeni Koleksiyoner</h2>
            <p class="login-alt-baslik">Soughts Ailesine Katılın</p>

            <form action="<?= site_url('auth/kayit_kaydet.php') ?>" method="POST">
                <?= csrf_token_input() ?>

                <label>Kullanıcı Adı</label>
                <input type="text" name="yeni_kullanici" required placeholder="Örn: ibrahim_bey" autocomplete="username">

                <label>E-posta</label>
                <input type="email" name="yeni_eposta" required placeholder="E-posta adresiniz" autocomplete="email">

                <label>Şifre</label>
                <input type="password" name="yeni_sifre" required placeholder="En az 6 karakter" minlength="6" autocomplete="new-password">

                <button type="submit" class="btn-kayit">
                    <i class="fa-solid fa-user-plus"></i> VIP Kaydımı Oluştur
                </button>
            </form>

            <button class="btn-link" onclick="formDegistir('giris')">Zaten üye misiniz? Giriş Yapın.</button>
        </div>

        <!-- ===== SAĞ PANEL: GİRİŞ FORMU (JS ile açılır) ===== -->
        <div class="login-form-kutu" id="girisFormu" style="display: none;">
            <h2>Koleksiyoner Girişi</h2>
            <p class="login-alt-baslik">Vitrini Keşfetmeye Devam Edin</p>

            <form action="" method="POST">
                <?= csrf_token_input() ?>
                <input type="hidden" name="giris_tipi" value="kullanici">

                <label>Kullanıcı Adı</label>
                <input type="text" name="kullanici_adi" required placeholder="Kayıtlı kullanıcı adınız" autocomplete="username">

                <label>Şifre</label>
                <input type="password" name="sifre" required placeholder="••••••••" autocomplete="current-password">

                <button type="submit" class="btn-giris">
                    <i class="fa-solid fa-right-to-bracket"></i> Koleksiyoner Girişi Yap
                </button>
            </form>

            <button class="btn-link" onclick="formDegistir('kayit')">Hesabınız yok mu? Yeni Kayıt Oluşturun.</button>
        </div>

    </div>

    <script src="<?= site_url('assets/js/app.js') ?>"></script>
</body>
</html>
