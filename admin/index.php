<?php
/**
 * Soughts Premium Marketplace — Admin Dashboard
 * 
 * VIP Requests table + Collection Inventory (CRUD) + Stats Cards
 * Security: admin_gerekli() auth gate, CSRF on delete forms
 */

$sayfa_basligi = 'Yönetim Merkezi';
require_once __DIR__ . '/../includes/header.php';

// Admin koruma duvarı
admin_gerekli();

// ============================================================
//  VERİ ÇEKME
// ============================================================

// İstatistikler
$stat_talepler  = $db->query("SELECT COUNT(*) FROM talepler")->fetchColumn();
$stat_ilanlar   = $db->query("SELECT COUNT(*) FROM ilanlar")->fetchColumn();
$stat_mesajlar  = $db->query("SELECT COUNT(*) FROM mesajlar WHERE okundu = 0")->fetchColumn();
$stat_uyeler    = $db->query("SELECT COUNT(*) FROM kullanicilar WHERE rol = 'kullanici'")->fetchColumn();

// Talepler (VIP buyer requests)
$sorgu_talep = $db->prepare("SELECT * FROM talepler ORDER BY id DESC");
$sorgu_talep->execute();
$talepler = $sorgu_talep->fetchAll();

// Mesajlar (Contact messages)
$sorgu_mesaj = $db->prepare("SELECT * FROM mesajlar ORDER BY id DESC");
$sorgu_mesaj->execute();
$mesajlar = $sorgu_mesaj->fetchAll();

// İlanlar (Product listings)
$sorgu_ilan = $db->prepare("SELECT * FROM ilanlar ORDER BY id DESC");
$sorgu_ilan->execute();
$ilanlar = $sorgu_ilan->fetchAll();
?>

<div class="admin-sayfa">
    <!-- Hoşgeldin Başlığı -->
    <div class="admin-hosgeldin">
        <h1>Soughts Kontrol Merkezi</h1>
        <p>Hoş geldin, VIP Yönetici: <strong style="color: var(--text-primary);"><?= htmlspecialchars(aktif_kullanici_adi()) ?></strong></p>
    </div>

    <!-- ===== İSTATİSTİK KARTLARI ===== -->
    <div class="admin-stats">
        <div class="stat-kart">
            <div class="stat-ikon gold"><i class="fa-solid fa-hand-holding-heart"></i></div>
            <div>
                <div class="stat-deger"><?= $stat_talepler ?></div>
                <div class="stat-etiket">VIP Talepler</div>
            </div>
        </div>
        <div class="stat-kart">
            <div class="stat-ikon success"><i class="fa-solid fa-boxes-stacked"></i></div>
            <div>
                <div class="stat-deger"><?= $stat_ilanlar ?></div>
                <div class="stat-etiket">Koleksiyon Ürünü</div>
            </div>
        </div>
        <div class="stat-kart">
            <div class="stat-ikon danger"><i class="fa-solid fa-envelope"></i></div>
            <div>
                <div class="stat-deger"><?= $stat_mesajlar ?></div>
                <div class="stat-etiket">Okunmamış Mesaj</div>
            </div>
        </div>
        <div class="stat-kart">
            <div class="stat-ikon gold"><i class="fa-solid fa-users"></i></div>
            <div>
                <div class="stat-deger"><?= $stat_uyeler ?></div>
                <div class="stat-etiket">Kayıtlı Üye</div>
            </div>
        </div>
    </div>

    <!-- ===== VIP TALEPLER TABLOSU ===== -->
    <div class="bolum-baslik">
        <h2><i class="fa-solid fa-hand-holding-heart"></i> VIP Alıcı Talepleri</h2>
    </div>

    <div class="kutu">
        <?php if (count($talepler) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Tarih</th>
                        <th>Ad Soyad</th>
                        <th>E-posta</th>
                        <th>Aranan Eser</th>
                        <th>Bütçe</th>
                        <th>Durum</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($talepler as $talep): ?>
                    <tr>
                        <td style="color: var(--text-secondary); font-size: 13px;">
                            <?= date('d.m.Y H:i', strtotime($talep['tarih'])) ?>
                        </td>
                        <td style="font-weight: 600;"><?= htmlspecialchars($talep['ad_soyad']) ?></td>
                        <td style="color: var(--text-secondary);"><?= htmlspecialchars($talep['eposta']) ?></td>
                        <td style="font-style: italic;"><?= htmlspecialchars($talep['baslik']) ?></td>
                        <td style="color: var(--gold-primary); font-weight: 600;">
                            <?= $talep['fiyat'] ? number_format($talep['fiyat'], 0, ',', '.') . ' ₺' : '-' ?>
                        </td>
                        <td>
                            <span style="color: var(--color-warning); font-size: 12px; text-transform: uppercase; letter-spacing: 1px;">
                                <?= htmlspecialchars($talep['durum']) ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="bos-mesaj">Henüz alıcı talebi bulunmuyor.</p>
        <?php endif; ?>
    </div>

    <!-- ===== GELEN KUTUSU (İLETİŞİM MESAJLARI) ===== -->
    <div class="bolum-baslik">
        <h2><i class="fa-solid fa-envelope-open-text"></i> Gelen Kutusu (Mesajlar)</h2>
    </div>

    <div class="kutu">
        <?php if (count($mesajlar) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Tarih</th>
                        <th>Ad Soyad</th>
                        <th>E-posta</th>
                        <th>Mesaj</th>
                        <th style="text-align: center;">İşlem</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($mesajlar as $mesaj): ?>
                    <tr>
                        <td style="color: var(--text-secondary); font-size: 13px;">
                            <?= date('d.m.Y H:i', strtotime($mesaj['tarih'])) ?>
                        </td>
                        <td style="font-weight: 600;"><?= htmlspecialchars($mesaj['ad_soyad']) ?></td>
                        <td style="color: var(--text-secondary);"><?= htmlspecialchars($mesaj['eposta']) ?></td>
                        <td style="font-style: italic; max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            "<?= htmlspecialchars($mesaj['mesaj']) ?>"
                        </td>
                        <td style="text-align: center;">
                            <form action="<?= site_url('admin/mesaj_sil.php') ?>" method="POST" style="display: inline;"
                                  onsubmit="return confirm('Bu mesajı silmek istediğinize emin misiniz?');">
                                <?= csrf_token_input() ?>
                                <input type="hidden" name="id" value="<?= $mesaj['id'] ?>">
                                <button type="submit" class="btn-tehlike">
                                    <i class="fa-solid fa-trash"></i> Sil
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="bos-mesaj">Sistemde henüz hiç mesaj bulunmuyor.</p>
        <?php endif; ?>
    </div>

    <!-- ===== KOLEKSİYON ENVANTERİ ===== -->
    <div class="bolum-baslik">
        <h2><i class="fa-solid fa-boxes-stacked"></i> Koleksiyon Envanteri</h2>
        <a href="<?= site_url('admin/ilan_ekle.php') ?>" class="btn-yeni">
            <i class="fa-solid fa-plus"></i> Yeni İlan Ekle
        </a>
    </div>

    <div class="kutu">
        <?php if (count($ilanlar) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Görsel</th>
                        <th>ID</th>
                        <th>Ürün Adı</th>
                        <th>Fiyat</th>
                        <th>Durum</th>
                        <th style="text-align: center;">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ilanlar as $ilan): ?>
                    <tr>
                        <td>
                            <img src="<?= site_url(htmlspecialchars($ilan['resim_yolu'] ?? '')) ?>"
                                 class="gorsel-mini" alt="Ürün" loading="lazy">
                        </td>
                        <td style="color: var(--text-secondary);">#<?= $ilan['id'] ?></td>
                        <td style="font-weight: 600;"><?= htmlspecialchars($ilan['ilan_adi']) ?></td>
                        <td style="color: var(--gold-primary); font-weight: 600;">
                            <?= number_format($ilan['fiyat'], 0, ',', '.') ?> ₺
                        </td>
                        <td>
                            <span style="color: var(--color-success); font-size: 12px; text-transform: uppercase;">
                                <?= htmlspecialchars($ilan['durum']) ?>
                            </span>
                        </td>
                        <td style="text-align: center;">
                            <a href="<?= site_url('admin/duzenle.php?id=' . $ilan['id']) ?>" class="btn-duzenle">
                                <i class="fa-solid fa-pen"></i> Düzenle
                            </a>
                            <form action="<?= site_url('admin/sil.php') ?>" method="POST" style="display: inline;"
                                  onsubmit="return confirm('Bu ürünü vitrinden kalıcı olarak silmek istediğinize emin misiniz?');">
                                <?= csrf_token_input() ?>
                                <input type="hidden" name="id" value="<?= $ilan['id'] ?>">
                                <button type="submit" class="btn-tehlike">
                                    <i class="fa-solid fa-trash"></i> Sil
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="bos-mesaj">Vitrinde henüz hiç ürün bulunmuyor. Yeni ilan ekleyerek başlayın.</p>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
