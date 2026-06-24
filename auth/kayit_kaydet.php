<?php
/**
 * Soughts Premium Marketplace — Kayıt İşlemi
 * 
 * Security: CSRF validation, Bcrypt hashing, duplicate check,
 * input sanitization, server-side validation.
 */

require_once __DIR__ . '/../config/baglan.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    yonlendir(site_url('auth/login.php'));
}

// CSRF doğrulama
csrf_dogrula();

// Girdileri temizle
$kullanici = temizle($_POST['yeni_kullanici'] ?? '');
$eposta    = temizle_email($_POST['yeni_eposta'] ?? '');
$sifre     = $_POST['yeni_sifre'] ?? ''; // Hash öncesi temizlenmez

// ============================================================
//  DOĞRULAMA KONTROLLERI
// ============================================================

// Boş alan kontrolü
if (empty($kullanici) || empty($eposta) || empty($sifre)) {
    yonlendir(site_url('auth/login.php?hata=bos'));
}

// E-posta format kontrolü
if (empty($eposta)) {
    yonlendir(site_url('auth/login.php?hata=eposta'));
}

// Şifre uzunluk kontrolü
if (mb_strlen($sifre) < 6) {
    yonlendir(site_url('auth/login.php?hata=sifre'));
}

// Kullanıcı adı uzunluk kontrolü (3-50 karakter)
if (mb_strlen($kullanici) < 3 || mb_strlen($kullanici) > 50) {
    yonlendir(site_url('auth/login.php?hata=kullanici'));
}

// ============================================================
//  ÇAKIŞMA KONTROLÜ (Duplicate Check)
// ============================================================
try {
    $kontrol = $db->prepare("SELECT id FROM kullanicilar WHERE kullanici_adi = ? OR eposta = ? LIMIT 1");
    $kontrol->execute([$kullanici, $eposta]);

    if ($kontrol->fetch()) {
        // Bu kullanıcı adı veya e-posta zaten kayıtlı
        yonlendir(site_url('auth/login.php?hata=mevcut'));
    }

    // ============================================================
    //  KAYIT İŞLEMİ — Bcrypt ile şifre hash'leme
    // ============================================================
    $hash = password_hash($sifre, PASSWORD_BCRYPT);

    $sorgu = $db->prepare(
        "INSERT INTO kullanicilar (kullanici_adi, eposta, sifre, ad_soyad, rol, durum) 
         VALUES (?, ?, ?, ?, 'kullanici', 'aktif')"
    );
    $basari = $sorgu->execute([$kullanici, $eposta, $hash, $kullanici]);

    if ($basari) {
        yonlendir(site_url('auth/login.php?kayit=basarili'));
    } else {
        yonlendir(site_url('auth/login.php?hata=sistem'));
    }

} catch (PDOException $e) {
    error_log('Soughts Kayıt Hatası: ' . $e->getMessage());
    yonlendir(site_url('auth/login.php?hata=sistem'));
}
