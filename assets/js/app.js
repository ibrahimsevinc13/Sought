/**
 * Soughts Premium Marketplace — Unified JavaScript v2.0
 * Modal management, form switching, search/filter, and UI interactions.
 */

'use strict';

// ============================================================
//  MODAL SYSTEM
// ============================================================

/**
 * Modal aç — ID ile hedef modalı göster.
 */
function modalAc(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('aktif');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
}

/**
 * Modal kapat — ID ile hedef modalı gizle.
 */
function modalKapat(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('aktif');
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }
}

/**
 * Modal dışına tıklanınca kapat.
 */
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal-kapsayici')) {
        e.target.classList.remove('aktif');
        e.target.style.display = 'none';
        document.body.style.overflow = '';
    }
});

/**
 * ESC tuşu ile açık modalı kapat.
 */
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modals = document.querySelectorAll('.modal-kapsayici.aktif');
        modals.forEach(function(modal) {
            modal.classList.remove('aktif');
            modal.style.display = 'none';
        });
        document.body.style.overflow = '';
    }
});

// ============================================================
//  SHORTCUT FUNCTIONS (for onclick attributes)
// ============================================================

function premiumModalAc()   { modalAc('premiumModal'); }
function premiumModalKapat(){ modalKapat('premiumModal'); }
function iletisimAc()       { modalAc('iletisimModal'); }
function iletisimKapat()    { modalKapat('iletisimModal'); }

// ============================================================
//  LOGIN PAGE — Form Tab Switching
// ============================================================

/**
 * Login sayfasında kayıt/giriş formları arasında geçiş yap.
 * Animasyonlu CSS geçişi ile.
 */
function formDegistir(hedef) {
    const kayitFormu = document.getElementById('kayitFormu');
    const girisFormu = document.getElementById('girisFormu');

    if (!kayitFormu || !girisFormu) return;

    if (hedef === 'giris') {
        kayitFormu.style.opacity = '0';
        kayitFormu.style.transform = 'translateX(-20px)';
        setTimeout(function() {
            kayitFormu.style.display = 'none';
            girisFormu.style.display = 'flex';
            girisFormu.style.opacity = '0';
            girisFormu.style.transform = 'translateX(20px)';
            setTimeout(function() {
                girisFormu.style.opacity = '1';
                girisFormu.style.transform = 'translateX(0)';
            }, 50);
        }, 200);
    } else {
        girisFormu.style.opacity = '0';
        girisFormu.style.transform = 'translateX(20px)';
        setTimeout(function() {
            girisFormu.style.display = 'none';
            kayitFormu.style.display = 'flex';
            kayitFormu.style.opacity = '0';
            kayitFormu.style.transform = 'translateX(-20px)';
            setTimeout(function() {
                kayitFormu.style.opacity = '1';
                kayitFormu.style.transform = 'translateX(0)';
            }, 50);
        }, 200);
    }
}

// ============================================================
//  TOPBAR SCROLL EFFECT
// ============================================================

window.addEventListener('scroll', function() {
    const topbar = document.querySelector('.topbar');
    if (topbar) {
        if (window.scrollY > 50) {
            topbar.classList.add('scrolled');
        } else {
            topbar.classList.remove('scrolled');
        }
    }
});

// ============================================================
//  SHOWCASE — Client-Side Search & Filter
// ============================================================

/**
 * Vitrin sayfasında ürün kartlarını filtrele (arama).
 */
function vitrinAra() {
    const aramaInput = document.querySelector('.arama-siralama input');
    if (!aramaInput) return;

    const aranan = aramaInput.value.toLowerCase().trim();
    const kartlar = document.querySelectorAll('.ilan-karti');

    kartlar.forEach(function(kart) {
        const adi = (kart.querySelector('.ilan-adi')?.textContent || '').toLowerCase();
        const aciklama = (kart.querySelector('.ilan-aciklama')?.textContent || '').toLowerCase();
        
        if (adi.includes(aranan) || aciklama.includes(aranan)) {
            kart.style.display = '';
        } else {
            kart.style.display = 'none';
        }
    });
}

/**
 * Vitrin sayfasında ürün kartlarını sırala.
 */
function vitrinSirala() {
    const selectEl = document.querySelector('.arama-siralama select');
    if (!selectEl) return;

    const grid = document.querySelector('.vitrin-grid');
    if (!grid) return;

    const kartlar = Array.from(grid.querySelectorAll('.ilan-karti'));

    kartlar.sort(function(a, b) {
        const fiyatA = parseFloat((a.querySelector('.ilan-fiyat')?.textContent || '0').replace(/[^\d,]/g, '').replace(',', '.'));
        const fiyatB = parseFloat((b.querySelector('.ilan-fiyat')?.textContent || '0').replace(/[^\d,]/g, '').replace(',', '.'));

        switch (selectEl.value) {
            case 'artan':
                return fiyatA - fiyatB;
            case 'azalan':
                return fiyatB - fiyatA;
            default: // 'yeni' — orijinal sıra (DOM order)
                return 0;
        }
    });

    kartlar.forEach(function(kart) {
        grid.appendChild(kart);
    });
}

// ============================================================
//  DELETE CONFIRMATION (POST-based)
// ============================================================

/**
 * Silme formlarında onay dialogu göster.
 */
function silmeOnayla(form, mesaj) {
    if (confirm(mesaj || 'Bu öğeyi silmek istediğinize emin misiniz?')) {
        form.submit();
    }
    return false;
}

// ============================================================
//  INIT — Sayfa yüklendiğinde çalışacak ayarlar
// ============================================================

document.addEventListener('DOMContentLoaded', function() {
    // Login sayfası: Kayıt başarılıysa otomatik giriş formuna geç
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('kayit') === 'basarili') {
        formDegistir('giris');
    }

    // Vitrin arama ve sıralama olaylarını bağla
    const aramaInput = document.querySelector('.arama-siralama input');
    if (aramaInput) {
        aramaInput.addEventListener('input', vitrinAra);
    }

    const siralamaSelect = document.querySelector('.arama-siralama select');
    if (siralamaSelect) {
        siralamaSelect.addEventListener('change', vitrinSirala);
    }

    // Form geçiş elementlerine transition ekle
    const kayitFormu = document.getElementById('kayitFormu');
    const girisFormu = document.getElementById('girisFormu');
    if (kayitFormu) {
        kayitFormu.style.transition = 'opacity 0.2s ease, transform 0.2s ease';
    }
    if (girisFormu) {
        girisFormu.style.transition = 'opacity 0.2s ease, transform 0.2s ease';
    }
});
