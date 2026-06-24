-- ============================================================
--  SOUGHTS PREMIUM MARKETPLACE — DATABASE SCHEMA v2.0
--  Engine: InnoDB | Charset: utf8mb4 | Collation: utf8mb4_unicode_ci
--  Author: SeviCode Enterprise Refactor
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- -----------------------------------------------------------
--  1. KULLANICILAR (Users) — Auth & Profile
-- -----------------------------------------------------------
DROP TABLE IF EXISTS `kullanicilar`;
CREATE TABLE `kullanicilar` (
    `id`                INT UNSIGNED        NOT NULL AUTO_INCREMENT,
    `kullanici_adi`     VARCHAR(50)         NOT NULL,
    `eposta`            VARCHAR(100)        NOT NULL,
    `sifre`             VARCHAR(255)        NOT NULL COMMENT 'Bcrypt hash via password_hash()',
    `ad_soyad`          VARCHAR(100)        DEFAULT NULL,
    `rol`               ENUM('kullanici','admin') NOT NULL DEFAULT 'kullanici',
    `durum`             ENUM('aktif','pasif','yasakli') NOT NULL DEFAULT 'aktif',
    `olusturma_tarihi`  TIMESTAMP           NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `son_giris`         TIMESTAMP           NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_kullanici_adi` (`kullanici_adi`),
    UNIQUE KEY `uk_eposta` (`eposta`),
    KEY `idx_rol` (`rol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
--  2. İLANLAR (Listings / Collection Inventory)
-- -----------------------------------------------------------
DROP TABLE IF EXISTS `ilanlar`;
CREATE TABLE `ilanlar` (
    `id`                INT UNSIGNED        NOT NULL AUTO_INCREMENT,
    `kullanici_id`      INT UNSIGNED        DEFAULT NULL COMMENT 'FK → kullanicilar.id',
    `ilan_adi`          VARCHAR(200)        NOT NULL,
    `aciklama`          TEXT                DEFAULT NULL,
    `fiyat`             DECIMAL(12,2)       NOT NULL DEFAULT 0.00,
    `kategori`          VARCHAR(50)         DEFAULT NULL,
    `resim_yolu`        VARCHAR(500)        DEFAULT NULL,
    `durum`             ENUM('aktif','satildi','pasif') NOT NULL DEFAULT 'aktif',
    `olusturma_tarihi`  TIMESTAMP           NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `guncelleme_tarihi` TIMESTAMP           NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_kullanici` (`kullanici_id`),
    KEY `idx_kategori` (`kategori`),
    KEY `idx_durum` (`durum`),
    CONSTRAINT `fk_ilan_kullanici` FOREIGN KEY (`kullanici_id`)
        REFERENCES `kullanicilar` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
--  3. MESAJLAR (Messages / Contact Form Submissions)
-- -----------------------------------------------------------
DROP TABLE IF EXISTS `mesajlar`;
CREATE TABLE `mesajlar` (
    `id`                INT UNSIGNED        NOT NULL AUTO_INCREMENT,
    `kullanici_id`      INT UNSIGNED        DEFAULT NULL COMMENT 'FK → kullanicilar.id (NULL if guest)',
    `ad_soyad`          VARCHAR(100)        NOT NULL,
    `eposta`            VARCHAR(100)        NOT NULL,
    `konu`              VARCHAR(200)        DEFAULT NULL,
    `mesaj`             TEXT                NOT NULL,
    `tip`               ENUM('iletisim','talep','sikayet') NOT NULL DEFAULT 'iletisim',
    `okundu`            TINYINT(1)          NOT NULL DEFAULT 0,
    `tarih`             TIMESTAMP           NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_tip` (`tip`),
    KEY `idx_okundu` (`okundu`),
    KEY `idx_kullanici` (`kullanici_id`),
    CONSTRAINT `fk_mesaj_kullanici` FOREIGN KEY (`kullanici_id`)
        REFERENCES `kullanicilar` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
--  4. TALEPLER (VIP Buyer Requests / Escrow Requests)
-- -----------------------------------------------------------
DROP TABLE IF EXISTS `talepler`;
CREATE TABLE `talepler` (
    `id`                INT UNSIGNED        NOT NULL AUTO_INCREMENT,
    `kullanici_id`      INT UNSIGNED        DEFAULT NULL,
    `ad_soyad`          VARCHAR(100)        NOT NULL,
    `eposta`            VARCHAR(100)        NOT NULL,
    `baslik`            VARCHAR(200)        NOT NULL COMMENT 'What they are looking for',
    `fiyat`             DECIMAL(12,2)       DEFAULT NULL COMMENT 'Max budget',
    `durum`             ENUM('beklemede','eslesti','tamamlandi','iptal') NOT NULL DEFAULT 'beklemede',
    `tarih`             TIMESTAMP           NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_durum` (`durum`),
    KEY `idx_kullanici` (`kullanici_id`),
    CONSTRAINT `fk_talep_kullanici` FOREIGN KEY (`kullanici_id`)
        REFERENCES `kullanicilar` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
--  5. SITE_AYARLARI (Site Settings — single row config)
-- -----------------------------------------------------------
DROP TABLE IF EXISTS `site_ayarlari`;
CREATE TABLE `site_ayarlari` (
    `id`                INT UNSIGNED        NOT NULL AUTO_INCREMENT,
    `site_adi`          VARCHAR(100)        NOT NULL DEFAULT 'Soughts',
    `telefon`           VARCHAR(30)         DEFAULT NULL,
    `eposta`            VARCHAR(100)        DEFAULT NULL,
    `footer_telif`      VARCHAR(255)        DEFAULT '© 2026 Soughts. Tüm hakları saklıdır.',
    `meta_description`  VARCHAR(300)        DEFAULT 'Nadide koleksiyonların güvenli limanı.',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  SEED DATA
-- ============================================================

-- Default site settings
INSERT INTO `site_ayarlari` (`id`, `site_adi`, `telefon`, `eposta`, `footer_telif`, `meta_description`)
VALUES (1, 'Soughts', '+90 555 000 00 00', 'info@soughts.com',
        '© 2026 Soughts Premium Marketplace. Tüm hakları saklıdır.',
        'Nadide koleksiyonların ve özel parçaların güvenli VIP pazarı.');

-- Admin seed account
-- Password: Admin.123! (Bcrypt hash generated with password_hash())
-- IMPORTANT: Change this password after first login!
INSERT INTO `kullanicilar` (`kullanici_adi`, `eposta`, `sifre`, `ad_soyad`, `rol`, `durum`)
VALUES ('admin', 'admin@soughts.com',
        '$2y$10$P4e69DpdSSggopDofa87w.Rg8EFVnwdmfYc3pv/wwMSiaGPwsdJQ2',
        'İbrahim Sevinç', 'admin', 'aktif');

SET FOREIGN_KEY_CHECKS = 1;
