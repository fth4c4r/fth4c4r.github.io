<?php
require 'auth.php';

// --- Auto-setup Settings ---
$settings = [
    'certs_title' => ['tr' => 'Sertifikalar ve Eğitimler', 'en' => 'Certificates & Training'],
    'certs_subtitle' => [
        'tr' => 'Kariyerim boyunca aldığım belgeler ve tamamladığım eğitimler.',
        'en' => 'Certificates and training I\'ve completed throughout my career.'
    ],
];

foreach ($settings as $key => $vals) {
    $stmt = $mysqli->prepare("INSERT INTO site_settings (setting_key, val_tr, val_en) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE val_tr=VALUES(val_tr), val_en=VALUES(val_en)");
    $stmt->bind_param("sss", $key, $vals['tr'], $vals['en']);
    $stmt->execute();
}

$message = '';

// --- Actions ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'save') {
            $id = $_POST['id'] ?? null;
            $title_tr = $_POST['title_tr'] ?? '';
            $title_en = $_POST['title_en'] ?? '';
            $issuer = $_POST['issuer'] ?? '';
            $issue_date = $_POST['issue_date'] ?? null;
            $link = $_POST['link'] ?? '';

            if ($id) {
                $stmt = $mysqli->prepare("UPDATE certificates SET title_tr=?, title_en=?, issuer=?, issue_date=?, link=? WHERE id=?");
                $stmt->bind_param("sssssi", $title_tr, $title_en, $issuer, $issue_date, $link, $id);
                $stmt->execute();
                $message = 'Sertifika güncellendi.';
            } else {
                $stmt = $mysqli->prepare("INSERT INTO certificates (title_tr, title_en, issuer, issue_date, link) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $title_tr, $title_en, $issuer, $issue_date, $link);
                $stmt->execute();
                $message = 'Sertifika eklendi.';
            }
        } elseif ($_POST['action'] === 'delete') {
            $id = $_POST['id'] ?? null;
            if ($id) {
                $stmt = $mysqli->prepare("DELETE FROM certificates WHERE id=?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $message = 'Sertifika silindi.';
            }
        }
    }
}

// --- Fetch Data ---
$certificates = [];
$result = $mysqli->query("SELECT * FROM certificates ORDER BY issue_date DESC, created_at DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $certificates[] = $row;
    }
}

$edit_cert = null;
if (isset($_GET['edit'])) {
    $stmt = $mysqli->prepare("SELECT * FROM certificates WHERE id=?");
    $stmt->bind_param("i", $_GET['edit']);
    $stmt->execute();
    $edit_cert = $stmt->get_result()->fetch_assoc();
}

$activeNav = 'certificates';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikalar — Yönetim Paneli</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="admin-wrapper">
        <?php require 'sidebar.php'; ?>

        <div class="admin-content">
            <div class="admin-topbar">
                <span class="admin-topbar-title">Sertifikalar</span>
                <div class="admin-topbar-actions">
                    <?php if ($edit_cert): ?>
                        <a href="certificates.php" class="btn btn-ghost btn-sm">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                            Geri Dön
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="admin-page">
                <div class="admin-page-header">
                    <h1 class="admin-page-title"><?= $edit_cert ? 'Sertifikayı Düzenle' : 'Sertifika Yönetimi' ?></h1>
                    <p class="admin-page-desc"><?= $edit_cert ? 'Sertifika bilgilerini güncelleyin.' : 'Aldığınız belgeleri ve katıldığınız eğitimleri buradan yönetin.' ?></p>
                </div>

                <?php if ($message): ?>
                    <div class="alert alert-success" role="alert">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                        <?= htmlspecialchars($message) ?>
                    </div>
                <?php endif; ?>

                <!-- Add / Edit Form -->
                <div class="form-card">
                    <h2 class="form-card-title"><?= $edit_cert ? 'Sertifikayı Düzenle' : 'Yeni Sertifika Ekle' ?></h2>
                    <form method="post" novalidate>
                        <input type="hidden" name="action" value="save">
                        <?php if ($edit_cert): ?>
                            <input type="hidden" name="id" value="<?= $edit_cert['id'] ?>">
                        <?php endif; ?>

                        <div class="form-row" style="margin-bottom:16px;">
                            <div class="form-group">
                                <label for="title-tr">Sertifika Adı (TR)</label>
                                <input type="text" name="title_tr" id="title-tr" value="<?= htmlspecialchars($edit_cert['title_tr'] ?? '') ?>" placeholder="Örn: Google Cloud Professional" required>
                            </div>
                            <div class="form-group">
                                <label for="title-en">Certificate Title (EN)</label>
                                <input type="text" name="title_en" id="title-en" value="<?= htmlspecialchars($edit_cert['title_en'] ?? '') ?>" placeholder="e.g. Google Cloud Professional" required>
                            </div>
                        </div>

                        <div class="form-row" style="margin-bottom:16px;">
                            <div class="form-group">
                                <label for="issuer">Veren Kurum</label>
                                <input type="text" name="issuer" id="issuer" value="<?= htmlspecialchars($edit_cert['issuer'] ?? '') ?>" placeholder="Örn: Google, Coursera, Udemy">
                            </div>
                            <div class="form-group">
                                <label for="issue-date">Alınma Tarihi</label>
                                <input type="date" name="issue_date" id="issue-date" value="<?= htmlspecialchars($edit_cert['issue_date'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="form-row" style="margin-bottom:16px;">
                            <div class="form-group">
                                <label for="cert-link">Sertifika Linki / Belge URL</label>
                                <input type="url" name="link" id="cert-link" value="<?= htmlspecialchars($edit_cert['link'] ?? '') ?>" placeholder="https://credential.net/...">
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary btn-sm" style="width:auto;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                                <?= $edit_cert ? 'Güncelle' : 'Kaydet' ?>
                            </button>
                            <?php if ($edit_cert): ?>
                                <a href="certificates.php" class="btn btn-ghost btn-sm">İptal</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>

                <!-- Table -->
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Sertifika (TR)</th>
                                <th>Title (EN)</th>
                                <th>Kurum</th>
                                <th>Tarih</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($certificates as $c): ?>
                                <tr>
                                    <td><?= htmlspecialchars($c['title_tr']) ?></td>
                                    <td><?= htmlspecialchars($c['title_en']) ?></td>
                                    <td><?= htmlspecialchars($c['issuer']) ?></td>
                                    <td><?= htmlspecialchars($c['issue_date']) ?></td>
                                    <td class="actions">
                                        <a href="?edit=<?= $c['id'] ?>" class="btn btn-outline btn-sm">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                            Düzenle
                                        </a>
                                        <form method="post" onsubmit="return confirm('Bu sertifikayı silmek istediğinize emin misiniz?')" style="display:inline;">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?= $c['id'] ?>">
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                                                Sil
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($certificates)): ?>
                                <tr>
                                    <td colspan="5">
                                        <div class="empty-state">
                                            <div class="empty-state-icon">📜</div>
                                            <p>Henüz sertifika eklenmemiş.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>