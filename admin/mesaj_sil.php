<?php
/**
 * Soughts Premium Marketplace — Admin: Mesaj Silme İşlemi
 * 
 * Security: admin auth, POST-only, CSRF
 */

require_once __DIR__ . '/../config/baglan.php';
require_once __DIR__ . '/../includes/functions.php';

admin_gerekli();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    yonlendir(site_url('admin/index.php'));
}

csrf_dogrula();

$id = temizle_int($_POST['id'] ?? 0);

if ($id > 0) {
    try {
        $sorgu = $db->prepare("DELETE FROM mesajlar WHERE id = ?");
        $sorgu->execute([$id]);
    } catch (PDOException $e) {
        error_log('Soughts Mesaj Silme Hatası: ' . $e->getMessage());
    }
}

yonlendir(site_url('admin/index.php'));
