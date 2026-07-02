<?php
// Admin sidebar — tüm admin sayfalarında include edilir
// $activeNav değişkeni: 'dashboard' | 'projects' | 'skills' | 'settings'
$activeNav = $activeNav ?? 'dashboard';
?>
<aside class="admin-sidebar" id="admin-sidebar" aria-label="Yönetim navigasyonu">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <div class="sidebar-logo-icon" aria-hidden="true">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
            </div>
            <div>
                <div class="sidebar-logo-name">Admin Panel</div>
                <div class="sidebar-logo-sub"><?= htmlspecialchars($_SESSION['admin_username'] ?? 'admin') ?></div>
            </div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="sidebar-section-label">Yönetim</div>

        <a href="index.php" class="sidebar-link <?= $activeNav === 'dashboard' ? 'active' : '' ?>" aria-current="<?= $activeNav === 'dashboard' ? 'page' : 'false' ?>">
            <span class="sidebar-link-icon" aria-hidden="true">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
            </span>
            Dashboard
        </a>

        <a href="projects.php" class="sidebar-link <?= $activeNav === 'projects' ? 'active' : '' ?>" aria-current="<?= $activeNav === 'projects' ? 'page' : 'false' ?>">
            <span class="sidebar-link-icon" aria-hidden="true">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
            </span>
            Projeler
        </a>

        <a href="skills.php" class="sidebar-link <?= $activeNav === 'skills' ? 'active' : '' ?>" aria-current="<?= $activeNav === 'skills' ? 'page' : 'false' ?>">
            <span class="sidebar-link-icon" aria-hidden="true">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            </span>
            Yetenekler
        </a>

        <a href="certificates.php" class="sidebar-link <?= $activeNav === 'certificates' ? 'active' : '' ?>" aria-current="<?= $activeNav === 'certificates' ? 'page' : 'false' ?>">
            <span class="sidebar-link-icon" aria-hidden="true">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 15l-3 3 3 3"/><path d="M9 21h9a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2z"/><path d="M9 15h6"/><path d="M9 11h6"/></svg>
            </span>
            Sertifikalar
        </a>

        <a href="mebbook/index.php" class="sidebar-link <?= $activeNav === 'mebbook' ? 'active' : '' ?>" aria-current="<?= $activeNav === 'mebbook' ? 'page' : 'false' ?>">
            <span class="sidebar-link-icon" aria-hidden="true">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
            </span>
            MEBbook
        </a>

        <div class="sidebar-section-label">Yapılandırma</div>

        <a href="settings.php" class="sidebar-link <?= $activeNav === 'settings' ? 'active' : '' ?>" aria-current="<?= $activeNav === 'settings' ? 'page' : 'false' ?>">
            <span class="sidebar-link-icon" aria-hidden="true">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
            </span>
            Site Ayarları
        </a>

        <div class="sidebar-section-label">Güvenlik</div>

        <a href="password.php" class="sidebar-link <?= $activeNav === 'password' ? 'active' : '' ?>" aria-current="<?= $activeNav === 'password' ? 'page' : 'false' ?>">
            <span class="sidebar-link-icon" aria-hidden="true">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            </span>
            Şifre Değiştir
        </a>

        <div class="sidebar-section-label">Site</div>

        <a href="../index.php" class="sidebar-link" target="_blank" rel="noopener">
            <span class="sidebar-link-icon" aria-hidden="true">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
            </span>
            Siteyi Görüntüle
        </a>
    </nav>

    <div class="sidebar-footer">
        <a href="logout.php" class="sidebar-link">
            <span class="sidebar-link-icon" aria-hidden="true">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            </span>
            Çıkış Yap
        </a>
    </div>
</aside>
