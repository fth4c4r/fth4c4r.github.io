<?php
require 'auth.php';

$message = '';

// --- Actions ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'save') {
            $id = $_POST['id'] ?? null;
            $title_tr = $_POST['title_tr'] ?? '';
            $title_en = $_POST['title_en'] ?? '';
            $desc_tr = $_POST['desc_tr'] ?? '';
            $desc_en = $_POST['desc_en'] ?? '';
            $link = $_POST['link'] ?? '';
            $tags = $_POST['tags'] ?? '';

            if ($id) {
                $stmt = $mysqli->prepare("UPDATE projects SET title_tr=?, title_en=?, desc_tr=?, desc_en=?, link=?, tags=? WHERE id=?");
                $stmt->bind_param("ssssssi", $title_tr, $title_en, $desc_tr, $desc_en, $link, $tags, $id);
                $stmt->execute();
                $message = 'Proje güncellendi.';
            } else {
                $stmt = $mysqli->prepare("INSERT INTO projects (title_tr, title_en, desc_tr, desc_en, link, tags) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssss", $title_tr, $title_en, $desc_tr, $desc_en, $link, $tags);
                $stmt->execute();
                $message = 'Proje eklendi.';
            }
        } elseif ($_POST['action'] === 'delete') {
            $id = $_POST['id'] ?? null;
            if ($id) {
                $stmt = $mysqli->prepare("DELETE FROM projects WHERE id=?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $message = 'Proje silindi.';
            }
        }
    }
}

// --- Fetch Data ---
$projects = [];
$result = $mysqli->query("SELECT * FROM projects ORDER BY created_at DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $projects[] = $row;
    }
}

$edit_project = null;
if (isset($_GET['edit'])) {
    $stmt = $mysqli->prepare("SELECT * FROM projects WHERE id=?");
    $stmt->bind_param("i", $_GET['edit']);
    $stmt->execute();
    $edit_project = $stmt->get_result()->fetch_assoc();
}

$activeNav = 'projects';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projeler — Yönetim Paneli</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="admin-wrapper">
        <?php require 'sidebar.php'; ?>

        <div class="admin-content">
            <div class="admin-topbar">
                <span class="admin-topbar-title">Projeler</span>
                <div class="admin-topbar-actions">
                    <?php if ($edit_project): ?>
                        <a href="projects.php" class="btn btn-ghost btn-sm">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                            Geri Dön
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="admin-page">
                <div class="admin-page-header">
                    <h1 class="admin-page-title"><?= $edit_project ? 'Projeyi Düzenle' : 'Proje Yönetimi' ?></h1>
                    <p class="admin-page-desc"><?= $edit_project ? 'Proje bilgilerini güncelleyin.' : 'Tüm projelerinizi buradan yönetin.' ?></p>
                </div>

                <?php if ($message): ?>
                    <div class="alert alert-success" role="alert">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                        <?= htmlspecialchars($message) ?>
                    </div>
                <?php endif; ?>

                <!-- Add / Edit Form -->
                <div class="form-card">
                    <h2 class="form-card-title"><?= $edit_project ? 'Projeyi Düzenle' : 'Yeni Proje Ekle' ?></h2>
                    <form method="post" novalidate>
                        <input type="hidden" name="action" value="save">
                        <?php if ($edit_project): ?>
                            <input type="hidden" name="id" value="<?= $edit_project['id'] ?>">
                        <?php endif; ?>

                        <div class="form-row" style="margin-bottom:16px;">
                            <div class="form-group">
                                <label for="title-tr">Başlık (TR)</label>
                                <input type="text" name="title_tr" id="title-tr" value="<?= htmlspecialchars($edit_project['title_tr'] ?? '') ?>" placeholder="Proje başlığı" required>
                            </div>
                            <div class="form-group">
                                <label for="title-en">Title (EN)</label>
                                <input type="text" name="title_en" id="title-en" value="<?= htmlspecialchars($edit_project['title_en'] ?? '') ?>" placeholder="Project title" required>
                            </div>
                        </div>

                        <div class="form-row" style="margin-bottom:16px;">
                            <div class="form-group">
                                <label for="desc-tr">Açıklama (TR)</label>
                                <textarea name="desc_tr" id="desc-tr" placeholder="Proje açıklaması..."><?= htmlspecialchars($edit_project['desc_tr'] ?? '') ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="desc-en">Description (EN)</label>
                                <textarea name="desc_en" id="desc-en" placeholder="Project description..."><?= htmlspecialchars($edit_project['desc_en'] ?? '') ?></textarea>
                            </div>
                        </div>

                        <div class="form-row" style="margin-bottom:16px;">
                            <div class="form-group">
                                <label for="proj-link">Proje Linki</label>
                                <input type="url" name="link" id="proj-link" value="<?= htmlspecialchars($edit_project['link'] ?? '') ?>" placeholder="https://github.com/...">
                            </div>
                            <div class="form-group">
                                <label for="proj-tags">Etiketler <small style="color:var(--subtle);font-weight:400;">(virgülle ayırın)</small></label>
                                <input type="text" name="tags" id="proj-tags" value="<?= htmlspecialchars($edit_project['tags'] ?? '') ?>" placeholder="Flutter, Firebase, Dart">
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary btn-sm" style="width:auto;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                                <?= $edit_project ? 'Güncelle' : 'Kaydet' ?>
                            </button>
                            <?php if ($edit_project): ?>
                                <a href="projects.php" class="btn btn-ghost btn-sm">İptal</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>

                <!-- Table -->
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Başlık (TR)</th>
                                <th>Title (EN)</th>
                                <th>Link</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($projects as $p): ?>
                                <tr>
                                    <td><?= htmlspecialchars($p['title_tr']) ?></td>
                                    <td><?= htmlspecialchars($p['title_en']) ?></td>
                                    <td>
                                        <?php if ($p['link']): ?>
                                            <a href="<?= htmlspecialchars($p['link']) ?>" target="_blank" rel="noopener" style="color:var(--primary);display:inline-flex;align-items:center;gap:4px;font-size:.82rem;">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                                                Görüntüle
                                            </a>
                                        <?php else: ?>
                                            <span style="color:var(--subtle);font-size:.82rem;">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="actions">
                                        <a href="?edit=<?= $p['id'] ?>" class="btn btn-outline btn-sm">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                            Düzenle
                                        </a>
                                        <form method="post" onsubmit="return confirm('Bu projeyi silmek istediğinize emin misiniz?')" style="display:inline;">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                                                Sil
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($projects)): ?>
                                <tr>
                                    <td colspan="4">
                                        <div class="empty-state">
                                            <div class="empty-state-icon">📂</div>
                                            <p>Henüz proje eklenmemiş.</p>
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