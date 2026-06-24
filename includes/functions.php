<?php
/**
 * Soughts Premium Marketplace — Güvenlik ve Yardımcı Fonksiyonlar
 * 
 * CSRF koruması, giriş temizleme (XSS), oturum yönetimi,
 * yetkilendirme kontrolleri ve yardımcı araçlar.
 */

// Oturum başlat (eğer henüz başlamamışsa)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ============================================================
//  PROJE KÖK DİZİNİ
// ============================================================
define('PROJE_KOK', dirname(__DIR__) . DIRECTORY_SEPARATOR);

// ============================================================
//  CSRF KORUMASI
// ============================================================

function csrf_token_olustur(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_token_input(): string
{
    $token = csrf_token_olustur();
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
}

function csrf_dogrula(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return;
    }

    $token = $_POST['csrf_token'] ?? '';

    if (empty($token) || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(403);
        die("
        <div style='
            background: linear-gradient(145deg, #111A3A, #0A1128);
            border: 1px solid rgba(231, 76, 60, 0.3);
            color: white; padding: 40px; text-align: center;
            font-family: sans-serif; max-width: 500px;
            margin: 100px auto; border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.6);
        '>
            <h2 style='color: #e74c3c;'>Güvenlik İhlali</h2>
            <p style='color: #A0A5B5;'>Geçersiz veya süresi dolmuş güvenlik anahtarı.<br>Lütfen sayfayı yenileyip tekrar deneyin.</p>
            <a href='javascript:history.back()' style='color: #D4AF37; text-decoration: underline;'>Geri Dön</a>
        </div>
        ");
    }

    // Token kullanıldıktan sonra yenile
    unset($_SESSION['csrf_token']);
}

// ============================================================
//  GİRDİ TEMİZLEME (XSS Koruması)
// ============================================================

function temizle(string $veri): string
{
    return htmlspecialchars(strip_tags(trim($veri)), ENT_QUOTES, 'UTF-8');
}

function temizle_int($veri): int
{
    return (int) filter_var($veri, FILTER_SANITIZE_NUMBER_INT);
}

function temizle_email(string $veri): string
{
    $email = filter_var(trim($veri), FILTER_SANITIZE_EMAIL);
    return filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : '';
}

// ============================================================
//  OTURUM ve YETKİLENDİRME
//  Hem yeni (rol tabanlı) hem eski (admin_giris/kullanici_giris)
//  session değişkenlerini destekler.
// ============================================================

/**
 * Herhangi bir kullanıcı giriş yapmış mı?
 */
function giris_yapildi_mi(): bool
{
    // Yeni yapı
    if (isset($_SESSION['kullanici_id']) && $_SESSION['kullanici_id'] > 0) {
        return true;
    }
    // Eski yapı — geriye dönük uyumluluk
    if (!empty($_SESSION['admin_giris']) || !empty($_SESSION['kullanici_giris'])) {
        return true;
    }
    return false;
}

/**
 * Aktif kullanıcı admin mi?
 */
function admin_mi(): bool
{
    // Yeni yapı
    if (isset($_SESSION['kullanici_id']) && ($_SESSION['rol'] ?? '') === 'admin') {
        return true;
    }
    // Eski yapı
    if (!empty($_SESSION['admin_giris']) && $_SESSION['admin_giris'] === true) {
        return true;
    }
    return false;
}

/**
 * Aktif kullanıcı normal kullanıcı mı?
 */
function kullanici_mi(): bool
{
    // Yeni yapı
    if (isset($_SESSION['kullanici_id']) && ($_SESSION['rol'] ?? '') === 'kullanici') {
        return true;
    }
    // Eski yapı
    if (!empty($_SESSION['kullanici_giris']) && $_SESSION['kullanici_giris'] === true) {
        return true;
    }
    return false;
}

/**
 * Admin değilse giriş sayfasına yönlendir.
 */
function admin_gerekli(): void
{
    if (!admin_mi()) {
        yonlendir(site_url('auth/login.php'));
    }
}

/**
 * Giriş yapılmamışsa giriş sayfasına yönlendir.
 */
function giris_gerekli(): void
{
    if (!giris_yapildi_mi()) {
        yonlendir(site_url('auth/login.php'));
    }
}

function aktif_kullanici_id(): ?int
{
    return $_SESSION['kullanici_id'] ?? null;
}

function aktif_kullanici_adi(): string
{
    // Yeni yapıdan oku, yoksa eskiden oku
    return $_SESSION['kullanici_adi'] ?? $_SESSION['aktif_kullanici'] ?? $_SESSION['aktif_admin'] ?? '';
}

function aktif_kullanici_eposta(): string
{
    return $_SESSION['eposta'] ?? '';
}

// ============================================================
//  URL & YÖNLENDİRME
// ============================================================

function site_url(string $yol = ''): string
{
    $kok = '/Sought/';
    return $kok . ltrim($yol, '/');
}

function yonlendir(string $url): void
{
    header("Location: {$url}");
    exit;
}

function basari_sayfasi(string $baslik, string $mesaj, string $url, int $sure = 3): void
{
    $guvenli_baslik = htmlspecialchars($baslik, ENT_QUOTES, 'UTF-8');
    $guvenli_mesaj  = htmlspecialchars($mesaj, ENT_QUOTES, 'UTF-8');
    $guvenli_url    = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');

    header("Refresh: {$sure}; url={$guvenli_url}");

    echo "<!DOCTYPE html>
    <html lang='tr'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Soughts | İşlem Başarılı</title>
        <link href='https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&family=Playfair+Display:wght@600&display=swap' rel='stylesheet'>
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css'>
    </head>
    <body style='
        background-color: #0A1128;
        display: flex; justify-content: center; align-items: center;
        height: 100vh; margin: 0; font-family: Montserrat, sans-serif;
    '>
        <div style='
            background: linear-gradient(145deg, #111A3A, #0A1128);
            border: 1px solid rgba(46, 204, 113, 0.3);
            padding: 50px; border-radius: 16px; text-align: center;
            color: white; box-shadow: 0 20px 60px rgba(0,0,0,0.6);
            max-width: 480px; width: 90%;
            animation: fadeIn 0.6s ease-out;
        '>
            <div style='
                width: 64px; height: 64px; margin: 0 auto 20px;
                background: rgba(46, 204, 113, 0.12); border-radius: 50%;
                display: flex; align-items: center; justify-content: center;
                border: 1px solid rgba(46, 204, 113, 0.2);
            '>
                <i class='fa-solid fa-check' style='color: #2ecc71; font-size: 26px;'></i>
            </div>
            <h2 style='color: #D4AF37; font-family: Playfair Display, serif; margin-bottom: 15px;'>{$guvenli_baslik}</h2>
            <p style='color: #A0A5B5; line-height: 1.8;'>{$guvenli_mesaj}</p>
            <p style='font-size: 12px; margin-top: 25px; color: rgba(212, 175, 55, 0.7);'>
                <i class='fa-solid fa-spinner fa-spin'></i> {$sure} saniye içinde yönlendiriliyorsunuz...
            </p>
        </div>
        <style>
            @keyframes fadeIn { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
        </style>
    </body>
    </html>";
    exit;
}

// ============================================================
//  DOSYA YÜKLEME GÜVENLİĞİ
// ============================================================

function guvenli_yukle(array $dosya, string $hedef_dizin = 'uploads/'): string|false
{
    $izinli_tipler = [
        'image/jpeg' => ['jpg', 'jpeg'],
        'image/png'  => ['png'],
        'image/webp' => ['webp'],
    ];

    $maks_boyut = 5 * 1024 * 1024; // 5 MB

    if ($dosya['error'] !== UPLOAD_ERR_OK) {
        return false;
    }

    if ($dosya['size'] > $maks_boyut) {
        return false;
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $gercek_mime = $finfo->file($dosya['tmp_name']);

    if (!array_key_exists($gercek_mime, $izinli_tipler)) {
        return false;
    }

    $uzanti = strtolower(pathinfo($dosya['name'], PATHINFO_EXTENSION));
    if (!in_array($uzanti, $izinli_tipler[$gercek_mime])) {
        $uzanti = $izinli_tipler[$gercek_mime][0];
    }

    $yeni_isim = 'vip_' . uniqid() . '.' . $uzanti;
    $hedef_yol = rtrim($hedef_dizin, '/') . '/' . $yeni_isim;
    $tam_yol   = PROJE_KOK . $hedef_yol;

    $hedef_dir = dirname($tam_yol);
    if (!is_dir($hedef_dir)) {
        mkdir($hedef_dir, 0755, true);
    }

    if (move_uploaded_file($dosya['tmp_name'], $tam_yol)) {
        return $hedef_yol;
    }

    return false;
}
