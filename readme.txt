======================================================================
              SOUGHTS PREMIUM MARKETPLACE - KULLANIM KILAVUZU
======================================================================

1. PROJENİN AMACI
Soughts; nadide koleksiyon parçalarının, antika eserlerin ve yüksek 
değere sahip ikinci el lüks ürünlerin el değiştirdiği, kapalı devre 
ve yüksek güvenlikli bir pazar yeri (marketplace) platformudur. 

Standart alışveriş platformlarından farklı olarak Soughts, seçkin 
koleksiyonerlere iki yönlü bir hizmet sunar: Kullanıcılar sahip 
oldukları değerli antika ve koleksiyon parçalarını güvenle satışa 
sunabilir veya eksikliğini hissettikleri, uzun süredir aradıkları 
özel bir parça için sisteme "Arama (Wanted)" talebi bırakabilirler. 
Tüm bu işlemler, lüks, minimalist ve premium bir arayüz üzerinden 
Soughts güvencesiyle yürütülür.

2. SİSTEM GEREKSİNİMLERİ & KURULUM
- PHP 7.4 veya üzeri (XAMPP / WampServer önerilir)
- MySQL / MariaDB
- Kurulum: Klasör içerisindeki "sought_db.sql" dosyasını phpMyAdmin 
  üzerinden içe aktarmanız (Import) ve "baglan.php" dosyasındaki 
  veritabanı bağlantı ayarlarını kendi sunucunuza göre yapılandırmanız 
  yeterlidir.

3. ADRES VE ERİŞİM BİLGİLERİ
- Ziyaretçi ve Koleksiyoner Vitrini: localhost/Sought/satici_vitrini.php
- Yönetim Paneli (Envanter Merkezi): localhost/Sought/ilan_listesi.php
- Yeni Eser/Koleksiyon Ekleme: localhost/Sought/ilan_ekle.php

4. YÖNETİCİ KİMLİK BİLGİLERİ (ADMIN CREDENTIALS)
- Yönetici Kullanıcı Adı: [Buraya Giriş Kullanıcı Adınızı Yazın]
- Yönetici Şifresi: [Buraya Giriş Şifrenizi Yazın]

5. SİSTEMİN ÇALIŞMA MANTIĞI VE MİMARİSİ
- Üretim Bandı: Satıcı, "ilan_ekle.php" formunu doldurarak eserine ait 
  görseli seçer. Sistem, "move_uploaded_file" fonksiyonu ve "uniqid()" 
  kriptolama yapısıyla bu görseli benzersizleştirerek güvenli bir şekilde 
  "images/" klasörüne taşır ve SQL INSERT komutu ile veritabanına mühürler.
- Güvenlik Duvarı: Form girdileri HTML "required" validasyonuna tabidir. 
  Ayrıca dosya yükleme motorunda sadece .jpg, .jpeg ve .png uzantılarına 
  izin veren sıkı bir backend kalkanı mevcuttur. Sahte veya zararlı dosya 
  girişleri sistem tarafından otomatik olarak engellenir.
- Dinamik Veri Yönetimi (CRUD): "ilan_listesi.php" üzerinden tüm koleksiyon 
  envanteri anlık olarak izlenebilir. "sil.php" ve "duzenle.php" motorları 
  sayesinde veritabanı üzerinde tam yetkiyle pürüzsüz güncelleme ve 
  kaldırma işlemleri gerçekleştirilir.
