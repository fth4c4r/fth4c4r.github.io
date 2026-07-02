<?php
// Ensure $t is defined to avoid warnings
$t = $t ?? [];
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang ?? 'tr') ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= htmlspecialchars($t['hero_desc'] ?? 'Portfolio') ?>">
    <title><?= htmlspecialchars($t['site_title'] ?? 'Portfolio') ?></title>

    <!-- Preconnect for performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500&display=swap">

    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <a href="#main-content" class="skip-link"><?= ($lang ?? 'tr') === 'tr' ? 'İçeriğe geç' : 'Skip to content' ?></a>

    <header class="site-header" id="site-header">
        <div class="container">
            <nav class="nav" aria-label="Ana navigasyon">
                <div class="logo">
                    <a href="index.php" aria-label="<?= htmlspecialchars($t['site_title'] ?? 'Portfolio') ?> — Anasayfa">
                        <span><?= htmlspecialchars($t['hero_name'] ?? 'Portfolio') ?></span>
                    </a>
                </div>

                <button
                    class="nav-toggle"
                    aria-label="Menüyü aç/kapat"
                    aria-expanded="false"
                    aria-controls="nav-links"
                >
                    <span></span>
                    <span></span>
                    <span></span>
                </button>

                <ul class="nav-links" id="nav-links" role="list">
                    <li><a href="index.php#about"    data-section="about"><?= htmlspecialchars($t['nav_about'] ?? 'Hakkımda') ?></a></li>
                    <li><a href="index.php#skills"   data-section="skills"><?= htmlspecialchars($t['nav_skills'] ?? 'Yetenekler') ?></a></li>
                    <li><a href="index.php#projects" data-section="projects"><?= htmlspecialchars($t['nav_projects'] ?? 'Projeler') ?></a></li>
                    <li><a href="index.php#contact"  data-section="contact"><?= htmlspecialchars($t['nav_contact'] ?? 'İletişim') ?></a></li>
                    <li class="lang-switch" aria-label="Dil seçimi">
                        <a href="?lang=tr" class="<?= (($lang ?? 'tr') == 'tr') ? 'active' : '' ?>" lang="tr" hreflang="tr">TR</a>
                        <a href="?lang=en" class="<?= (($lang ?? 'tr') == 'en') ? 'active' : '' ?>" lang="en" hreflang="en">EN</a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <main id="main-content">