<?php
require __DIR__ . '/includes/init.php';

// --- İletişim formu gönderimi ---
$formStatus = $_SESSION['form_status'] ?? null;
unset($_SESSION['form_status']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Honeypot kontrolü: doldurulmuşsa bottur
    if (!empty($_POST['website'])) {
        header('Location: /', true, 302);
        exit;
    }

    // CSRF kontrolü
    $submittedToken = $_POST['_csrf_token'] ?? '';
    if (!hash_equals($csrfToken, $submittedToken)) {
        $_SESSION['form_status'] = 'error';
        header('Location: /#contact', true, 303);
        exit;
    }

    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name && filter_var($email, FILTER_VALIDATE_EMAIL) && $message) {
        // Gerçek projede burada mail() ile e-posta gönderilir ya da veritabanına kaydedilir.
        // Örn: mail('siz@ornek.com', 'Yeni mesaj', $message, "From: $email");
        $_SESSION['form_status'] = 'success';
    } else {
        $_SESSION['form_status'] = 'error';
    }

    // PRG: sayfayı yeniden yönlendir (tekrar POST önleme)
    header("Location: /#contact", true, 303);
    exit;
}

$currentSection = $_GET['section'] ?? '';

require __DIR__ . '/includes/header.php';
?>

<?php if (!empty($db_error)): ?>
<div style="background:rgba(239,68,68,.15);color:#f87171;border:1px solid rgba(239,68,68,.3);border-radius:10px;padding:16px 20px;margin:24px auto;max-width:900px;font-family:monospace;font-size:.88rem;">
    ⚠️ <strong>Veritabanı Bağlantı Hatası:</strong> <?= htmlspecialchars($db_error) ?>
</div>
<?php endif; ?>

<!-- ============ HERO ============ -->
<section class="hero" aria-labelledby="hero-heading">
    <div class="container hero-inner">

        <div class="hero-badge reveal">
            <span class="hero-badge-dot" aria-hidden="true"></span>
            <?= $lang === 'tr' ? 'Yeni projelere açık' : 'Available for work' ?>
        </div>

        <p class="hero-greeting reveal reveal-delay-1"><?= htmlspecialchars($t['hero_greeting']) ?></p>
        <h1 class="hero-name reveal reveal-delay-2" id="hero-heading"><?= htmlspecialchars($t['hero_name']) ?></h1>
        <h2 class="hero-role reveal reveal-delay-3"><?= htmlspecialchars($t['hero_role']) ?></h2>
        <p class="hero-desc reveal reveal-delay-4"><?= htmlspecialchars($t['hero_desc']) ?></p>

        <div class="hero-actions reveal reveal-delay-4">
            <a href="#projects" class="btn btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
                <?= htmlspecialchars($t['hero_cta']) ?>
            </a>
            <a href="#contact" class="btn btn-outline">
                <?= htmlspecialchars($t['hero_contact']) ?>
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
            </a>
        </div>
    </div>
</section>

<!-- ============ HAKKIMDA ============ -->
<section class="section" id="about" aria-labelledby="about-heading">
    <div class="container">
        <div class="about-layout">
            <div>
                <span class="section-label reveal"><?= $lang === 'tr' ? 'Hakkımda' : 'About me' ?></span>
                <h2 class="section-title reveal" id="about-heading"><?= htmlspecialchars($t['about_title']) ?></h2>
                <p class="about-text reveal reveal-delay-1"><?= htmlspecialchars($t['about_text']) ?></p>

                <div class="about-stats">
                    <div class="stat-card reveal reveal-delay-1">
                        <div class="stat-number">3+</div>
                        <div class="stat-label"><?= $lang === 'tr' ? 'Yıl Deneyim' : 'Years Exp.' ?></div>
                    </div>
                    <div class="stat-card reveal reveal-delay-2">
                        <div class="stat-number"><?= count($projects) ?>+</div>
                        <div class="stat-label"><?= $lang === 'tr' ? 'Tamamlanan Proje' : 'Projects Done' ?></div>
                    </div>
                    <div class="stat-card reveal reveal-delay-3">
                        <div class="stat-number"><?= count($skills) ?>+</div>
                        <div class="stat-label"><?= $lang === 'tr' ? 'Teknoloji' : 'Technologies' ?></div>
                    </div>
                </div>
            </div>

            <div class="about-card reveal reveal-delay-2">
                <div class="about-card-title"><?= $lang === 'tr' ? 'Hızlı Bilgi' : 'Quick Info' ?></div>
                <ul class="about-card-list" role="list">
                    <li class="about-card-item">
                        <span class="about-card-icon" aria-hidden="true">📍</span>
                        <span><?= $lang === 'tr' ? 'Türkiye' : 'Turkey' ?></span>
                    </li>
                    <li class="about-card-item">
                        <span class="about-card-icon" aria-hidden="true">💼</span>
                        <span><?= $lang === 'tr' ? 'Freelance & Tam Zamanlı' : 'Freelance & Full-time' ?></span>
                    </li>
                    <li class="about-card-item">
                        <span class="about-card-icon" aria-hidden="true">🎓</span>
                        <span><?= $lang === 'tr' ? 'Bilgisayar Mühendisliği' : 'Computer Engineering' ?></span>
                    </li>
                    <li class="about-card-item">
                        <span class="about-card-icon" aria-hidden="true">🚀</span>
                        <span><?= $lang === 'tr' ? 'Flutter · Dart · Firebase' : 'Flutter · Dart · Firebase' ?></span>
                    </li>
                    <li class="about-card-item">
                        <span class="about-card-icon" aria-hidden="true">✅</span>
                        <span style="color: #22c55e; font-weight: 600;"><?= $lang === 'tr' ? 'Müsait' : 'Available' ?></span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- ============ YETENEKLER ============ -->
<section class="section section-alt" id="skills" aria-labelledby="skills-heading">
    <div class="container">
        <span class="section-label reveal"><?= $lang === 'tr' ? 'Uzmanlık Alanları' : 'Expertise' ?></span>
        <h2 class="section-title reveal" id="skills-heading"><?= htmlspecialchars($t['skills_title']) ?></h2>
        <p class="section-subtitle reveal reveal-delay-1">
            <?= $lang === 'tr'
                ? 'Mobil uygulama geliştirme sürecinde kullandığım teknolojiler ve uzmanlık seviyelerim.'
                : 'Technologies I use in mobile app development and my proficiency levels.' ?>
        </p>

        <div class="skills-layout">
            <?php foreach ($skills as $i => $skill): ?>
                <div class="skill-item reveal" style="transition-delay: <?= min($i * 0.07, 0.4) ?>s">
                    <div class="skill-head">
                        <span class="skill-name"><?= htmlspecialchars($skill['name']) ?></span>
                        <span class="skill-pct"><?= (int)$skill['level'] ?>%</span>
                    </div>
                    <div class="skill-bar" role="progressbar" aria-valuenow="<?= (int)$skill['level'] ?>" aria-valuemin="0" aria-valuemax="100" aria-label="<?= htmlspecialchars($skill['name']) ?> seviyesi">
                        <div class="skill-fill" style="width: <?= (int)$skill['level'] ?>%"></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============ PROJELER ============ -->
<section class="section" id="projects" aria-labelledby="projects-heading">
    <div class="container">
        <span class="section-label reveal"><?= $lang === 'tr' ? 'Öne Çıkan Çalışmalar' : 'Featured Work' ?></span>
        <h2 class="section-title reveal" id="projects-heading"><?= htmlspecialchars($t['projects_title']) ?></h2>
        <p class="section-subtitle reveal reveal-delay-1">
            <?= $lang === 'tr'
                ? 'Geliştirdiğim ürünler ve açık kaynak katkılarım.'
                : 'Products I built and my open source contributions.' ?>
        </p>

        <div class="projects-grid">
            <?php foreach ($projects as $i => $p): ?>
                <article class="project-card reveal" style="transition-delay: <?= min($i * 0.1, 0.4) ?>s">
                    <div class="project-card-accent" aria-hidden="true"></div>
                    <div class="project-card-body">
                        <div class="project-card-number"><?= str_pad($i + 1, 2, '0', STR_PAD_LEFT) ?></div>
                        <h3><?= htmlspecialchars($p['title'][$lang]) ?></h3>
                        <p><?= htmlspecialchars($p['desc'][$lang]) ?></p>
                        <div class="tags" aria-label="Etiketler">
                            <?php foreach ($p['tags'] as $tag): ?>
                                <span class="tag"><?= htmlspecialchars($tag) ?></span>
                            <?php endforeach; ?>
                        </div>
                        <a href="<?= htmlspecialchars($p['link']) ?>" class="project-link" target="_blank" rel="noopener noreferrer" aria-label="<?= htmlspecialchars($p['title'][$lang]) ?> projesini görüntüle">
                            <?= htmlspecialchars($t['project_view']) ?>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 17L17 7"/><path d="M7 7h10v10"/></svg>
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============ SERTİFİKALAR ============ -->
<section class="section section-alt" id="certificates" aria-labelledby="certs-heading">
    <div class="container">
        <span class="section-label reveal"><?= $lang === 'tr' ? 'Başarılar' : 'Achievements' ?></span>
        <h2 class="section-title reveal" id="certs-heading"><?= htmlspecialchars($t['certs_title'] ?? ($lang === 'tr' ? 'Sertifikalar ve Eğitimler' : 'Certificates & Training')) ?></h2>
        <p class="section-subtitle reveal reveal-delay-1">
            <?= htmlspecialchars($t['certs_subtitle'] ?? ($lang === 'tr' ? 'Kariyerim boyunca aldığım belgeler ve tamamladığım eğitimler.' : 'Certificates and training I\'ve completed throughout my career.')) ?>
        </p>

        <div class="projects-grid">
            <?php foreach ($certificates as $i => $c): ?>
                <article class="project-card reveal" style="transition-delay: <?= min($i * 0.1, 0.4) ?>s">
                    <div class="project-card-accent" aria-hidden="true" style="background: var(--primary-light);"></div>
                    <div class="project-card-body">
                        <div class="project-card-number"><?= str_pad($i + 1, 2, '0', STR_PAD_LEFT) ?></div>
                        <h3><?= htmlspecialchars($lang === 'tr' ? $c['title_tr'] : $c['title_en']) ?></h3>
                        <p style="font-size: 0.9rem; color: var(--subtle); margin-bottom: 12px;">
                            <strong><?= $lang === 'tr' ? 'Kurum: ' : 'Issuer: ' ?></strong><?= htmlspecialchars($c['issuer']) ?>
                            <?php if ($c['issue_date']): ?>
                                <span style="margin-left: 8px;">• <?= htmlspecialchars($c['issue_date']) ?></span>
                            <?php endif; ?>
                        </p>
                        <?php if ($c['link']): ?>
                            <a href="<?= htmlspecialchars($c['link']) ?>" class="project-link" target="_blank" rel="noopener noreferrer">
                                <?= $lang === 'tr' ? 'Belgeyi Gör' : 'View Certificate' ?>
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 17L17 7"/><path d="M7 7h10v10"/></svg>
                            </a>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
            <?php if (empty($certificates)): ?>
                <div class="empty-state" style="grid-column: 1 / -1; text-align: center; padding: 40px; color: var(--subtle);">
                    <p><?= $lang === 'tr' ? 'Henüz eklenmiş bir sertifika bulunmuyor.' : 'No certificates added yet.' ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ============ İLETİŞİM ============ -->
<section class="section section-alt" id="contact" aria-labelledby="contact-heading">
    <div class="container">
        <div class="contact-layout">
            <!-- Sol: Bilgi -->
            <div class="contact-info">
                <span class="section-label reveal"><?= $lang === 'tr' ? 'İletişim' : 'Contact' ?></span>
                <h2 class="section-title reveal" id="contact-heading"><?= htmlspecialchars($t['contact_title']) ?></h2>
                <p class="contact-text reveal reveal-delay-1"><?= htmlspecialchars($t['contact_text']) ?></p>

                <div class="contact-social reveal reveal-delay-2">
                    <a href="#" class="contact-social-link" target="_blank" rel="noopener noreferrer" aria-label="GitHub profilini ziyaret et">
                        <span class="contact-social-icon" aria-hidden="true">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.374 0 0 5.373 0 12c0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23A11.509 11.509 0 0112 5.803c1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576C20.566 21.797 24 17.3 24 12c0-6.627-5.373-12-12-12z"/></svg>
                        </span>
                        GitHub
                    </a>
                    <a href="#" class="contact-social-link" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn profilini ziyaret et">
                        <span class="contact-social-icon" aria-hidden="true">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                        </span>
                        LinkedIn
                    </a>
                    <a href="mailto:#" class="contact-social-link" aria-label="E-posta gönder">
                        <span class="contact-social-icon" aria-hidden="true">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                        </span>
                        E-mail
                    </a>
                </div>
            </div>

            <!-- Sağ: Form kartı -->
            <div class="contact-form-card reveal reveal-delay-1">
                <?php if ($formStatus === 'success'): ?>
                    <div class="alert alert-success" role="alert" aria-live="polite">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                        <?= $lang === 'tr' ? 'Mesajınız alındı, teşekkürler!' : 'Your message has been received, thank you!' ?>
                    </div>
                <?php elseif ($formStatus === 'error'): ?>
                    <div class="alert alert-error" role="alert" aria-live="polite">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                        <?= $lang === 'tr' ? 'Lütfen tüm alanları doğru doldurun.' : 'Please fill all fields correctly.' ?>
                    </div>
                <?php endif; ?>

                <form class="contact-form" method="post" action="/#contact" novalidate>
                    <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                    <div style="position:absolute;left:-9999px" aria-hidden="true">
                        <input type="text" name="website" tabindex="-1" autocomplete="off">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="contact-name"><?= htmlspecialchars($t['contact_name']) ?></label>
                            <input
                                type="text"
                                name="name"
                                id="contact-name"
                                placeholder="<?= $lang === 'tr' ? 'Adınız Soyadınız' : 'Full Name' ?>"
                                autocomplete="name"
                                required
                            >
                        </div>
                        <div class="form-group">
                            <label for="contact-email"><?= htmlspecialchars($t['contact_email']) ?></label>
                            <input
                                type="email"
                                name="email"
                                id="contact-email"
                                placeholder="<?= $lang === 'tr' ? 'ornek@email.com' : 'you@email.com' ?>"
                                autocomplete="email"
                                required
                            >
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="contact-message"><?= htmlspecialchars($t['contact_message']) ?></label>
                        <textarea
                            name="message"
                            id="contact-message"
                            rows="5"
                            placeholder="<?= $lang === 'tr' ? 'Projeniz hakkında birkaç cümle yazın...' : 'Tell me about your project...' ?>"
                            required
                        ></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                        <?= htmlspecialchars($t['contact_send']) ?>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
