<?php
require 'auth.php';

$message = '';

// Fetch settings
$settings = [];
$result = $mysqli->query("SELECT * FROM site_settings ORDER BY setting_key");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $settings[] = $row;
    }
}

// Update settings
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['settings'])) {
    foreach ($_POST['settings'] as $key => $values) {
        $val_tr = $values['tr'] ?? '';
        $val_en = $values['en'] ?? '';
        $stmt = $mysqli->prepare("UPDATE site_settings SET val_tr=?, val_en=? WHERE setting_key=?");
        $stmt->bind_param("sss", $val_tr, $val_en, $key);
        $stmt->execute();
    }
    $message = 'Tüm ayarlar güncellendi.';
}

$activeNav = 'settings';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Ayarları — Yönetim Paneli</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="admin-wrapper">
        <?php require 'sidebar.php'; ?>

        <div class="admin-content">
            <div class="admin-topbar">
                <span class="admin-topbar-title">Site Ayarları</span>
            </div>

            <div class="admin-page">
                <div class="admin-page-header">
                    <h1 class="admin-page-title">Site Ayarları</h1>
                    <p class="admin-page-desc">Sitenizde görünen metin ve içerikleri iki dilde düzenleyin.</p>
                </div>

                <?php if ($message): ?>
                    <div class="alert alert-success" role="alert">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                        <?= htmlspecialchars($message) ?>
                    </div>
                <?php endif; ?>

                <form method="post">
                    <div class="settings-grid">
                        <?php foreach ($settings as $s): ?>
                            <div class="setting-item">
                                <div class="setting-item-key"><?= htmlspecialchars($s['setting_key']) ?></div>
                                <div class="setting-inputs">
                                    <div>
                                        <div class="setting-lang-label">🇹🇷 Türkçe</div>
                                        <input
                                            type="text"
                                            name="settings[<?= $s['setting_key'] ?>][tr]"
                                            value="<?= htmlspecialchars($s['val_tr'] ?? '') ?>"
                                            placeholder="Türkçe değer..."
                                            aria-label="<?= htmlspecialchars($s['setting_key']) ?> Türkçe"
                                        >
                                    </div>
                                    <div>
                                        <div class="setting-lang-label">🇬🇧 English</div>
                                        <input
                                            type="text"
                                            name="settings[<?= $s['setting_key'] ?>][en]"
                                            value="<?= htmlspecialchars($s['val_en'] ?? '') ?>"
                                            placeholder="English value..."
                                            aria-label="<?= htmlspecialchars($s['setting_key']) ?> English"
                                        >
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="save-bar">
                        <span style="font-size:.85rem;color:var(--muted);"><?= count($settings) ?> ayar</span>
                        <button type="submit" class="btn btn-primary btn-sm" style="width:auto;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                            Tüm Ayarları Kaydet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>