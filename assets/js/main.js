/**
 * Portfolio — main.js
 * İş mantığı değiştirilmedi, animasyon ve etkileşim katmanı zenginleştirildi.
 */

/* ================================================================
   1. HEADER: Scroll efekti
   ================================================================ */
const header = document.getElementById('site-header');
if (header) {
    const onScroll = () => {
        header.classList.toggle('scrolled', window.scrollY > 20);
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll(); // İlk yüklemede kontrol
}

/* ================================================================
   2. MOBİL MENÜ: Hamburger aç/kapat + X animasyonu
   ================================================================ */
const toggle = document.querySelector('.nav-toggle');
const navLinks = document.querySelector('.nav-links');

if (toggle && navLinks) {
    toggle.addEventListener('click', () => {
        const open = navLinks.classList.toggle('open');
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });

    // Link'e tıklayınca menüyü kapat
    navLinks.querySelectorAll('a').forEach((a) => {
        a.addEventListener('click', () => {
            navLinks.classList.remove('open');
            toggle.setAttribute('aria-expanded', 'false');
        });
    });

    // Escape ile kapat
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && navLinks.classList.contains('open')) {
            navLinks.classList.remove('open');
            toggle.setAttribute('aria-expanded', 'false');
            toggle.focus();
        }
    });

    // Dışarı tıklayınca kapat
    document.addEventListener('click', (e) => {
        if (
            navLinks.classList.contains('open') &&
            !toggle.contains(e.target) &&
            !navLinks.contains(e.target)
        ) {
            navLinks.classList.remove('open');
            toggle.setAttribute('aria-expanded', 'false');
        }
    });
}

/* ================================================================
   3. REVEAL: Scroll-triggered fade-in animasyonları
   ================================================================ */
const revealEls = document.querySelectorAll('.reveal');

if (revealEls.length && window.IntersectionObserver) {
    const revealObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    revealObserver.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.12, rootMargin: '0px 0px -40px 0px' }
    );
    revealEls.forEach((el) => revealObserver.observe(el));
} else {
    // IntersectionObserver desteklenmiyor veya element yok — hepsini göster
    revealEls.forEach((el) => el.classList.add('visible'));
}

/* ================================================================
   4. SKILL BARS: Scroll ile doldur (orijinal mantık korundu)
   ================================================================ */
const fills = document.querySelectorAll('.skill-fill');
if (fills.length && window.IntersectionObserver) {
    const skillObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    const el = entry.target;
                    const targetWidth = el.style.width;
                    el.style.width = '0';
                    // İki frame bekle, sonra genişliği uygula
                    requestAnimationFrame(() => {
                        requestAnimationFrame(() => {
                            el.style.width = targetWidth;
                        });
                    });
                    skillObserver.unobserve(el);
                }
            });
        },
        { threshold: 0.3 }
    );
    fills.forEach((f) => skillObserver.observe(f));
}

/* ================================================================
   5. AKTIF BÖLÜM: Navigasyon linkleri vurgulama
   ================================================================ */
const sections = document.querySelectorAll('section[id]');
const navAnchors = document.querySelectorAll('.nav-links a[data-section]');

if (sections.length && navAnchors.length && window.IntersectionObserver) {
    const sectionObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    const id = entry.target.getAttribute('id');
                    navAnchors.forEach((link) => {
                        if (link.getAttribute('data-section') === id) {
                            link.setAttribute('aria-current', 'page');
                        } else {
                            link.removeAttribute('aria-current');
                        }
                    });
                }
            });
        },
        { rootMargin: '-35% 0px -55% 0px', threshold: 0 }
    );
    sections.forEach((s) => sectionObserver.observe(s));
}