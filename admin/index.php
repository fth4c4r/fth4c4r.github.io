<?php
require 'auth.php';

$result = $mysqli->query("SELECT COUNT(*) as total FROM projects");
$project_count = $result->fetch_row()[0];

$result = $mysqli->query("SELECT COUNT(*) as total FROM skills");
$skill_count = $result->fetch_row()[0];

$activeNav = 'dashboard';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Yönetim Paneli</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="admin-wrapper">
        <?php require 'sidebar.php'; ?>

        <div class="admin-content">
            <div class="admin-topbar">
                <span class="admin-topbar-title">Dashboard</span>
                <div class="admin-topbar-actions">
                    <a href="../index.php" class="btn btn-outline btn-sm" target="_blank" rel="noopener">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                        Siteyi Gör
                    </a>
                </div>
            </div>

            <div class="admin-page">
                <div class="admin-page-header">
                    <h1 class="admin-page-title">Hoş Geldiniz, <?= htmlspecialchars($_SESSION['admin_username']) ?> 👋</h1>
                    <p class="admin-page-desc">Portfolyo içeriklerinizi buradan yönetebilirsiniz.</p>
                </div>

                <!-- Stats -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-card-icon blue" aria-hidden="true">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                        </div>
                        <div class="stat-card-number"><?= $project_count ?></div>
                        <div class="stat-card-label">Toplam Proje</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-card-icon purple" aria-hidden="true">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                        </div>
                        <div class="stat-card-number"><?= $skill_count ?></div>
                        <div class="stat-card-label">Toplam Yetenek</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-card-icon green" aria-hidden="true">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        </div>
                        <div class="stat-card-number">2</div>
                        <div class="stat-card-label">Dil Desteği</div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="quick-actions">
                    <div class="quick-actions-title">Hızlı İşlemler</div>
                    <div class="quick-actions-grid">
                        <a href="projects.php" class="btn btn-outline btn-sm">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Yeni Proje
                        </a>
                        <a href="skills.php" class="btn btn-outline btn-sm">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Yeni Yetenek
                        </a>
                        <a href="settings.php" class="btn btn-outline btn-sm">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                            Site Ayarları
                        </a>
                    </div>
                </div>

                <!-- Welcome -->
                <div class="welcome-card">
                    <h2>Portfolyonuzu Yönetin</h2>
                    <p>Sol menüden projelerinizi, yeteneklerinizi ve site içeriklerinizi düzenleyebilirsiniz. Değişiklikler anında sitede görünür.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>