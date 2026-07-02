<?php
require 'auth.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'save') {
        $id = $_POST['id'] ?? null;
        $name = $_POST['name'] ?? '';
        $level = $_POST['level'] ?? 0;

        if ($id) {
            $stmt = $mysqli->prepare("UPDATE skills SET name=?, level=? WHERE id=?");
            $stmt->bind_param("sii", $name, $level, $id);
            $stmt->execute();
            $message = 'Yetenek güncellendi.';
        } else {
            $stmt = $mysqli->prepare("INSERT INTO skills (name, level) VALUES (?, ?)");
            $stmt->bind_param("si", $name, $level);
            $stmt->execute();
            $message = 'Yetenek eklendi.';
        }
    } elseif ($_POST['action'] === 'delete') {
        $id = $_POST['id'] ?? null;
        if ($id) {
            $stmt = $mysqli->prepare("DELETE FROM skills WHERE id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $message = 'Yetenek silindi.';
        }
    }
}

$skills = [];
$result = $mysqli->query("SELECT * FROM skills ORDER BY level DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $skills[] = $row;
    }
}

$edit_skill = null;
if (isset($_GET['edit'])) {
    $stmt = $mysqli->prepare("SELECT * FROM skills WHERE id=?");
    $stmt->bind_param("i", $_GET['edit']);
    $stmt->execute();
    $edit_skill = $stmt->get_result()->fetch_assoc();
}

$activeNav = 'skills';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yetenekler — Yönetim Paneli</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="admin-wrapper">
        <?php require 'sidebar.php'; ?>

        <div class="admin-content">
            <div class="admin-topbar">
                <span class="admin-topbar-title">Yetenekler</span>
                <div class="admin-topbar-actions">
                    <?php if ($edit_skill): ?>
                        <a href="skills.php" class="btn btn-ghost btn-sm">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                            Geri Dön
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="admin-page">
                <div class="admin-page-header">
                    <h1 class="admin-page-title"><?= $edit_skill ? 'Yetenek Düzenle' : 'Yetenek Yönetimi' ?></h1>
                    <p class="admin-page-desc">Portfolyonuzda görünecek yetenekleri ve seviyeleri yönetin.</p>
                </div>

                <?php if ($message): ?>
                    <div class="alert alert-success" role="alert">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                        <?= htmlspecialchars($message) ?>
                    </div>
                <?php endif; ?>

                <!-- Form -->
                <div class="form-card">
                    <h2 class="form-card-title"><?= $edit_skill ? 'Yetenek Düzenle' : 'Yeni Yetenek Ekle' ?></h2>
                    <form method="post" novalidate>
                        <input type="hidden" name="action" value="save">
                        <?php if ($edit_skill): ?>
                            <input type="hidden" name="id" value="<?= $edit_skill['id'] ?>">
                        <?php endif; ?>

                        <div class="form-row" style="margin-bottom:16px;">
                            <div class="form-group">
                                <label for="skill-name">Yetenek Adı</label>
                                <input
                                    type="text"
                                    name="name"
                                    id="skill-name"
                                    value="<?= htmlspecialchars($edit_skill['name'] ?? '') ?>"
                                    placeholder="Ör: Flutter, React, Python..."
                                    required
                                >
                            </div>
                            <div class="form-group">
                                <label for="skill-level">Seviye <small style="color:var(--subtle);font-weight:400;">(1–100)</small></label>
                                <input
                                    type="number"
                                    name="level"
                                    id="skill-level"
                                    min="1"
                                    max="100"
                                    value="<?= htmlspecialchars($edit_skill['level'] ?? '') ?>"
                                    placeholder="85"
                                    required
                                >
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary btn-sm" style="width:auto;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                                <?= $edit_skill ? 'Güncelle' : 'Kaydet' ?>
                            </button>
                            <?php if ($edit_skill): ?>
                                <a href="skills.php" class="btn btn-ghost btn-sm">İptal</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>

                <!-- Table -->
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Yetenek Adı</th>
                                <th>Seviye</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($skills as $s): ?>
                                <tr>
                                    <td><?= htmlspecialchars($s['name']) ?></td>
                                    <td>
                                        <div class="level-badge">
                                            <div class="level-bar" title="<?= htmlspecialchars($s['level']) ?>%">
                                                <div class="level-fill" style="width:<?= (int)$s['level'] ?>%"></div>
                                            </div>
                                            <?= htmlspecialchars($s['level']) ?>%
                                        </div>
                                    </td>
                                    <td class="actions">
                                        <a href="?edit=<?= $s['id'] ?>" class="btn btn-outline btn-sm">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                            Düzenle
                                        </a>
                                        <form method="post" onsubmit="return confirm('Bu yeteneği silmek istediğinize emin misiniz?')" style="display:inline;">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?= $s['id'] ?>">
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                                                Sil
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($skills)): ?>
                                <tr>
                                    <td colspan="3">
                                        <div class="empty-state">
                                            <div class="empty-state-icon">⚡</div>
                                            <p>Henüz yetenek eklenmemiş.</p>
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