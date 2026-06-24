<?php
/**
 * Soughts Premium Marketplace — Güvenli Çıkış (Logout)
 * 
 * Clears ALL session vars (new + legacy), cookie, and redirects.
 */

require_once __DIR__ . '/../includes/functions.php';

// 1. Tüm oturum verilerini temizle
$_SESSION = [];
session_unset();

// 2. Oturum cookie'sini sil
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}

// 3. Oturumu yok et
session_destroy();

// 4. Ana sayfaya yönlendir
header("Location: " . site_url('index.php'));
exit;
