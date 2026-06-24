<?php
// GÖREV 3.3: DÜKKANI KİLİTLE (Oturumu sonlandır)
session_start();      // Önce mevcut oturumu bul
session_destroy();    // Sonra o oturumu tamamen yok et (Bileti yırt)

// Kişiyi tekrar giriş ekranına veya ana sayfaya yönlendir
header("Location: login.php");
exit;
?>