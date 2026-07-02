<?php
require __DIR__ . '/../includes/init.php';

// Already logged in? Redirect.
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username && $password) {
        $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $user['username'];
            header('Location: index.php');
            exit;
        } else {
            $error = 'Kullanıcı adı veya şifre hatalı.';
        }
    } else {
        $error = 'Lütfen kullanıcı adı ve şifre girin.';
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yönetici Girişi — Admin Panel</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body class="login-body">
    <div class="login-box">
        <div class="login-logo">
            <div class="login-logo-icon" aria-hidden="true">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            </div>
            <div>
                <div class="login-logo-text">Admin Panel</div>
                <div class="login-logo-sub">Portfolio Yönetimi</div>
            </div>
        </div>

        <h1>Hoş Geldiniz</h1>
        <p>Devam etmek için giriş yapın.</p>

        <?php if ($error): ?>
            <div class="alert alert-error" role="alert">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="post" novalidate>
            <div class="form-group">
                <label for="login-username">Kullanıcı Adı</label>
                <input
                    type="text"
                    name="username"
                    id="login-username"
                    placeholder="admin"
                    autocomplete="username"
                    required
                    autofocus
                >
            </div>
            <div class="form-group" style="margin-bottom: 24px;">
                <label for="login-password">Şifre</label>
                <input
                    type="password"
                    name="password"
                    id="login-password"
                    placeholder="••••••••"
                    autocomplete="current-password"
                    required
                >
            </div>
            <button type="submit" class="btn btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                Giriş Yap
            </button>
        </form>
    </div>
</body>
</html>
<?php exit; ?>