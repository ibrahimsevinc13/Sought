<?php
/**
 * Soughts Premium Marketplace — Global Header (Glassmorphism Topbar)
 * 
 * Dynamic states: Guest | User (Koleksiyoner) | Admin (Kurucu)
 * Features: Invisible Hover Bridge, Profile Dropdown, Search Bar
 */

require_once __DIR__ . '/../config/baglan.php';
require_once __DIR__ . '/functions.php';

// Site ayarlarını çek
$ayarSorgu = $db->prepare("SELECT * FROM site_ayarlari WHERE id = 1");
$ayarSorgu->execute();
$ayar = $ayarSorgu->fetch();

$sayfa_basligi = $sayfa_basligi ?? 'Soughts';
$meta_desc = $ayar['meta_description'] ?? 'Nadide koleksiyonların güvenli limanı.';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= htmlspecialchars($meta_desc) ?>">
    <title><?= htmlspecialchars($sayfa_basligi) ?> | <?= htmlspecialchars($ayar['site_adi'] ?? 'Soughts') ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= site_url('assets/css/style.css') ?>?v=<?= time() ?>">
</head>
<body>

    <header class="topbar" id="topbar">
        <!-- Logo — uses local images/logo.png -->
        <a href="<?= site_url('index.php') ?>" class="logo">
            <img src="<?= site_url('images/logo.png') ?>" alt="Soughts Logo" class="site-logo">
            Soughts<span class="logo-ext"></span>
        </a>

        <!-- Navigation -->
        <nav class="ana-menu">
            <a href="<?= site_url('talepler.php') ?>"><i class="fa-solid fa-hand-holding-heart"></i> <span>Alıcı Talepleri</span></a>
            <a href="<?= site_url('satici_vitrini.php') ?>"><i class="fa-solid fa-store"></i> <span>Satıcı Vitrini</span></a>
            <a href="#" onclick="iletisimAc(); return false;"><i class="fa-solid fa-envelope"></i> <span>İletişim</span></a>
            <a href="<?= site_url('hakkimizda.php') ?>"><i class="fa-solid fa-info-circle"></i> <span>Hakkımızda</span></a>
        </nav>

        <!-- Search -->
        <div class="topbar-search">
            <input type="text" placeholder="Aradığınız nadir parça veya referans numarası...">
            <button class="topbar-search-btn" type="button"><i class="fa-solid fa-magnifying-glass"></i></button>
        </div>

        <!-- User Actions -->
        <div class="user-actions">
            <div class="notification-wrapper">
                <i class="fa-regular fa-bell notification-icon"></i>
                <span class="badge"></span>
            </div>

            <?php if (admin_mi()): ?>
                <!-- ADMIN DROPDOWN -->
                <div class="profil-dropdown">
                    <button class="profil-btn profil-btn-admin">
                        <i class="fa-solid fa-shield-halved"></i> <?= htmlspecialchars(aktif_kullanici_adi()) ?>
                    </button>
                    <div class="dropdown-icerik">
                        <div class="kullanici-bilgi">
                            Sistem Kurucusu
                            <b class="admin-name"><?= htmlspecialchars(aktif_kullanici_adi()) ?></b>
                        </div>
                        <a href="<?= site_url('admin/index.php') ?>" class="dropdown-link-gold">
                            <i class="fa-solid fa-gauge"></i> Yönetim Paneli
                        </a>
                        <a href="<?= site_url('auth/cikis.php') ?>" class="dropdown-link-danger">
                            <i class="fa-solid fa-power-off"></i> Çıkış Yap
                        </a>
                    </div>
                </div>

            <?php elseif (kullanici_mi()): ?>
                <!-- USER DROPDOWN -->
                <div class="profil-dropdown">
                    <button class="profil-btn">
                        <i class="fa-solid fa-user-astronaut"></i> <?= htmlspecialchars(aktif_kullanici_adi()) ?>
                    </button>
                    <div class="dropdown-icerik">
                        <div class="kullanici-bilgi">
                            Onaylı Koleksiyoner
                            <b><?= htmlspecialchars(aktif_kullanici_adi()) ?></b>
                        </div>
                        <a href="<?= site_url('auth/cikis.php') ?>" class="dropdown-link-danger">
                            <i class="fa-solid fa-power-off"></i> Güvenli Çıkış Yap
                        </a>
                    </div>
                </div>

            <?php else: ?>
                <!-- GUEST -->
                <a href="<?= site_url('auth/login.php') ?>" class="btn-signup">KAYIT OL / GİRİŞ</a>

            <?php endif; ?>
        </div>
    </header>
