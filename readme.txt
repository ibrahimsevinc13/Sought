======================================================================
              SOUGHTS PREMIUM MARKETPLACE - KULLANIM KILAVUZU
======================================================================

1. PROJENİN AMACI
Soughts; nadide koleksiyon parçalarının, lüks aksesuarların ve özel tasarım 
ürünlerin güvenli, minimalist ve premium bir arayüzle sergilendiği, tam 
fonksiyonel bir dinamik pazar yeri simülasyonudur.

2. SİSTEM GEREKSİNİMLERİ & KURULUM
- PHP 7.4 veya üzeri (XAMPP / WampServer)
- MySQL / MariaDB
- Kurulum için klasör içerisindeki "sought_db.sql" dosyasını phpMyAdmin 
  üzerinden içe aktarmanız (Import) ve "baglan.php" dosyasındaki veritabanı 
  bağlantı ayarlarını kontrol etmeniz yeterlidir.

3. ADRES VE ERİŞİM BİLGİLERİ
- Kullanıcı / Ziyaretçi Vitrini: localhost/odev_proje/satici_vitrini.php
- Yönetim Paneli (Envanter): localhost/odev_proje/ilan_listesi.php
- Yeni Ürün Ekleme Sayfası: localhost/odev_proje/ilan_ekle.php

4. YÖNETİCİ KİMLİK BİLGİLERİ (ADMIN CREDENTIALS)
- Yönetici Kullanıcı Adı: [Buraya Giriş Kullanıcı Adınızı Yazın]
- Yönetici Şifresi: [Buraya Giriş Şifrenizi Yazın]

5. SİSTEMİN ÇALIŞMA MANTIĞI
- Üretim Bandı: Satıcı, "ilan_ekle.php" formunu doldurarak resim seçer. 
  Sistem, "move_uploaded_file" fonksiyonu ve "uniqid()" kriptolama yapısıyla 
  görseli güvenli bir şekilde "images/" klasörüne taşır ve SQL INSERT INTO 
  ile veritabanına mühürler.
- Güvenlik Duvarı: Form girdileri HTML required validasyonuna tabidir. Ayrıca 
  dosya yükleme motorunda sadece .jpg, .jpeg ve .png uzantılarına izin veren 
  bir backend kalkanı mevcuttur. Sahte dosya girişleri otomatik engellenir.
- CRUD Döngüsü: "ilan_listesi.php" üzerinden tüm envanter anlık olarak 
  izlenebilir, "sil.php" ve "duzenle.php" motorları sayesinde veritabanı 
  üzerinde tam yetkiyle güncelleme/silme işlemleri yapılabilir.