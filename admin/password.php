<?php
require 'auth.php';

$message = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'] ?? '';
    $new_password     = $_POST['new_password']     ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // --- Validasyon ---
    if (!$current_password || !$new_password || !$confirm_password) {
        $error = 'Lütfen tüm alanları doldurun.';
    } elseif ($new_password !== $confirm_password) {
        $error = 'Yeni şifreler eşleşmiyor.';
    } elseif (strlen($new_password) < 8) {
        $error = 'Yeni şifre en az 8 karakter olmalıdır.';
    } else {
        // Mevcut kullanıcıyı veritabanından çek
        $username = $_SESSION['admin_username'];
        $stmt = $mysqli->prepare("SELECT password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if (!$user) {
            $error = 'Kullanıcı bulunamadı.';
        } elseif (!password_verify($current_password, $user['password'])) {
            $error = 'Mevcut şifre hatalı.';
        } else {
            // Yeni şifreyi hashle ve güncelle
            $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $mysqli->prepare("UPDATE users SET password = ? WHERE username = ?");
            $stmt->bind_param("ss", $new_hash, $username);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $message = 'Şifreniz başarıyla güncellendi.';
            } else {
                $error = 'Şifre güncellenirken bir hata oluştu.';
            }
        }
    }
}

$activeNav = 'password';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Şifre Değiştir — Yönetim Paneli</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="admin-wrapper">
        <?php require 'sidebar.php'; ?>

        <div class="admin-content">
            <div class="admin-topbar">
                <span class="admin-topbar-title">Şifre Değiştir</span>
            </div>

            <div class="admin-page">
                <div class="admin-page-header">
                    <h1 class="admin-page-title">Şifre Değiştir</h1>
                    <p class="admin-page-desc">Güvenliğiniz için şifrenizi düzenli olarak güncelleyin.</p>
                </div>

                <?php if ($message): ?>
                    <div class="alert alert-success" role="alert">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                        <?= htmlspecialchars($message) ?>
                    </div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="alert alert-error" role="alert">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <div style="display:grid;grid-template-columns:1fr 320px;gap:24px;align-items:start;">

                    <!-- Şifre Formu -->
                    <div class="form-card">
                        <h2 class="form-card-title">Yeni Şifre Belirle</h2>
                        <form method="post" novalidate id="password-form">

                            <div class="form-group" style="margin-bottom:16px;">
                                <label for="current_password">Mevcut Şifre</label>
                                <div class="input-wrapper">
                                    <input
                                        type="password"
                                        name="current_password"
                                        id="current_password"
                                        placeholder="Mevcut şifrenizi girin"
                                        autocomplete="current-password"
                                        required
                                    >
                                    <button type="button" class="toggle-eye" aria-label="Şifreyi göster/gizle" data-target="current_password">
                                        <svg class="eye-icon eye-closed" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                                        <svg class="eye-icon eye-open" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </button>
                                </div>
                            </div>

                            <div class="form-group" style="margin-bottom:16px;">
                                <label for="new_password">Yeni Şifre</label>
                                <div class="input-wrapper">
                                    <input
                                        type="password"
                                        name="new_password"
                                        id="new_password"
                                        placeholder="En az 8 karakter"
                                        autocomplete="new-password"
                                        required
                                        minlength="8"
                                    >
                                    <button type="button" class="toggle-eye" aria-label="Şifreyi göster/gizle" data-target="new_password">
                                        <svg class="eye-icon eye-closed" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                                        <svg class="eye-icon eye-open" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </button>
                                </div>
                                <!-- Şifre Güç Göstergesi -->
                                <div class="strength-meter" id="strength-meter" aria-live="polite">
                                    <div class="strength-bar">
                                        <div class="strength-fill" id="strength-fill"></div>
                                    </div>
                                    <span class="strength-label" id="strength-label"></span>
                                </div>
                            </div>

                            <div class="form-group" style="margin-bottom:0;">
                                <label for="confirm_password">Yeni Şifre (Tekrar)</label>
                                <div class="input-wrapper">
                                    <input
                                        type="password"
                                        name="confirm_password"
                                        id="confirm_password"
                                        placeholder="Şifreyi tekrar girin"
                                        autocomplete="new-password"
                                        required
                                    >
                                    <button type="button" class="toggle-eye" aria-label="Şifreyi göster/gizle" data-target="confirm_password">
                                        <svg class="eye-icon eye-closed" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                                        <svg class="eye-icon eye-open" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </button>
                                </div>
                                <div class="match-indicator" id="match-indicator" aria-live="polite"></div>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary btn-sm" style="width:auto;" id="submit-btn">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                    Şifreyi Güncelle
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Güvenlik İpuçları -->
                    <div class="form-card" style="background:var(--surface-2);">
                        <h2 class="form-card-title">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="color:var(--primary);display:inline;margin-right:6px;"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            Güvenlik İpuçları
                        </h2>
                        <ul class="security-tips">
                            <li class="tip-item">
                                <span class="tip-icon">✓</span>
                                <span>En az <strong>8 karakter</strong> kullanın</span>
                            </li>
                            <li class="tip-item">
                                <span class="tip-icon">✓</span>
                                <span>Büyük ve küçük <strong>harf karışımı</strong> tercih edin</span>
                            </li>
                            <li class="tip-item">
                                <span class="tip-icon">✓</span>
                                <span><strong>Sayı ve sembol</strong> ekleyin (ör: @, #, !)</span>
                            </li>
                            <li class="tip-item">
                                <span class="tip-icon">✓</span>
                                <span>Başka sitelerde <strong>kullanmadığınız</strong> bir şifre seçin</span>
                            </li>
                            <li class="tip-item tip-warn">
                                <span class="tip-icon">✗</span>
                                <span>Kişisel bilgilerinizi (doğum tarihi vb.) kullanmayın</span>
                            </li>
                        </ul>

                        <div class="current-user-info">
                            <div class="tip-icon-lg" aria-hidden="true">👤</div>
                            <div>
                                <div style="font-size:.78rem;color:var(--muted);margin-bottom:2px;">Oturum açan kullanıcı</div>
                                <div style="font-weight:700;color:var(--text);font-size:.95rem;"><?= htmlspecialchars($_SESSION['admin_username']) ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Input wrapper (eye toggle için) */
        .input-wrapper {
            position: relative;
        }
        .input-wrapper input {
            padding-right: 44px !important;
        }
        .toggle-eye {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--muted);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 4px;
            border-radius: 4px;
            transition: color .2s;
        }
        .toggle-eye:hover { color: var(--text); }

        /* Şifre Güç Göstergesi */
        .strength-meter {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 8px;
            min-height: 20px;
        }
        .strength-bar {
            flex: 1;
            height: 4px;
            background: var(--border-2);
            border-radius: 100px;
            overflow: hidden;
        }
        .strength-fill {
            height: 100%;
            width: 0;
            border-radius: 100px;
            transition: width .4s ease, background .4s ease;
        }
        .strength-label {
            font-size: .75rem;
            font-weight: 600;
            white-space: nowrap;
            min-width: 60px;
            text-align: right;
        }

        /* Eşleşme göstergesi */
        .match-indicator {
            font-size: .78rem;
            font-weight: 600;
            margin-top: 7px;
            min-height: 18px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* Güvenlik ipuçları */
        .security-tips {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 24px;
        }
        .tip-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: .88rem;
            color: var(--text-2);
            line-height: 1.5;
        }
        .tip-icon {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: rgba(34,197,94,.15);
            color: #22c55e;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .7rem;
            font-weight: 800;
            flex-shrink: 0;
            margin-top: 2px;
        }
        .tip-warn .tip-icon {
            background: rgba(239,68,68,.12);
            color: #ef4444;
        }
        .tip-item strong { color: var(--text); }

        .current-user-info {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            background: var(--surface);
            border: 1px solid var(--border-2);
            border-radius: var(--radius);
        }
        .tip-icon-lg { font-size: 1.4rem; }

        @media (max-width: 768px) {
            [style*="grid-template-columns:1fr 320px"] {
                grid-template-columns: 1fr !important;
            }
        }
    </style>

    <script>
    // Göster/Gizle toggle
    document.querySelectorAll('.toggle-eye').forEach(btn => {
        btn.addEventListener('click', () => {
            const input = document.getElementById(btn.dataset.target);
            const isText = input.type === 'text';
            input.type = isText ? 'password' : 'text';
            btn.querySelector('.eye-closed').style.display = isText ? '' : 'none';
            btn.querySelector('.eye-open').style.display  = isText ? 'none' : '';
            btn.setAttribute('aria-label', isText ? 'Şifreyi göster/gizle' : 'Şifreyi gizle');
        });
    });

    // Şifre güç hesaplama
    const newPassInput = document.getElementById('new_password');
    const strengthFill  = document.getElementById('strength-fill');
    const strengthLabel = document.getElementById('strength-label');

    function calcStrength(pw) {
        let score = 0;
        if (pw.length >= 8)  score++;
        if (pw.length >= 12) score++;
        if (/[A-Z]/.test(pw)) score++;
        if (/[0-9]/.test(pw)) score++;
        if (/[^A-Za-z0-9]/.test(pw)) score++;
        return score;
    }

    const strengthConfig = [
        { label: '',          color: 'transparent', pct: 0   },
        { label: 'Çok Zayıf', color: '#ef4444',     pct: 20  },
        { label: 'Zayıf',     color: '#f97316',     pct: 40  },
        { label: 'Orta',      color: '#eab308',     pct: 60  },
        { label: 'Güçlü',     color: '#22c55e',     pct: 80  },
        { label: 'Çok Güçlü', color: '#4f9cf9',     pct: 100 },
    ];

    newPassInput.addEventListener('input', () => {
        const score = newPassInput.value ? calcStrength(newPassInput.value) : 0;
        const cfg = strengthConfig[score] || strengthConfig[0];
        strengthFill.style.width      = cfg.pct + '%';
        strengthFill.style.background = cfg.color;
        strengthLabel.textContent     = cfg.label;
        strengthLabel.style.color     = cfg.color;
        checkMatch();
    });

    // Şifre eşleşme kontrolü
    const confirmInput    = document.getElementById('confirm_password');
    const matchIndicator  = document.getElementById('match-indicator');

    function checkMatch() {
        const a = newPassInput.value;
        const b = confirmInput.value;
        if (!b) { matchIndicator.innerHTML = ''; return; }
        if (a === b) {
            matchIndicator.innerHTML = '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg> <span style="color:#22c55e">Şifreler eşleşiyor</span>';
        } else {
            matchIndicator.innerHTML = '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg> <span style="color:#ef4444">Şifreler eşleşmiyor</span>';
        }
    }
    confirmInput.addEventListener('input', checkMatch);
    </script>
</body>
</html>
