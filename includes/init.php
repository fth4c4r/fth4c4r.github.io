<?php
// Hata raporlama
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// --- Veritabanı Ayarları ---
$db_host = 'localhost';
$db_name = 'httpdvtd_portfoy';
$db_user = 'httpdvtd_portfoy_admin'; // Kullanıcı adınız
$db_pass = 'Facar7985..';     // Şifreniz

// MySQLi ile bağlan (PDO yoksa çalışır)
$mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_name);

$db_error = false;
if ($mysqli->connect_errno) {
    $db_error = "Veritabanı Bağlantı Hatası: " . $mysqli->connect_error . " (Hata Kodu: " . $mysqli->connect_errno . ")";
}

if (!$db_error) {
    $mysqli->set_charset("utf8mb4");
}

// --- Dil Ayarı ---
$lang = $_GET['lang'] ?? $_SESSION['lang'] ?? 'tr';
if (!in_array($lang, ['tr', 'en'])) $lang = 'tr';
$_SESSION['lang'] = $lang;

// --- Site Ayarlarını Yükle ---
$t = [];
if (!$db_error) {
    $result = $mysqli->query("SELECT setting_key, val_tr, val_en FROM site_settings");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $t[$row['setting_key']] = ($lang === 'tr') ? $row['val_tr'] : $row['val_en'];
        }
    }
}

// --- Yetenekleri Yükle ---
$skills = [];
if (!$db_error) {
    $result = $mysqli->query("SELECT name, level FROM skills ORDER BY level DESC");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $skills[] = $row;
        }
    }
}

// --- Projeleri Yükle ---
$projects = [];
if (!$db_error) {
    $result = $mysqli->query("SELECT * FROM projects ORDER BY created_at DESC");
    if ($result) {
        while ($p = $result->fetch_assoc()) {
            $projects[] = [
                'title' => ['tr' => $p['title_tr'], 'en' => $p['title_en']],
                'desc'  => ['tr' => $p['desc_tr'], 'en' => $p['desc_en']],
                'link'  => $p['link'],
                'tags'  => array_filter(explode(',', $p['tags']))
            ];
        }
    }
}

// --- Sertifikaları Yükle ---
$certificates = [];
if (!$db_error) {
    $result = $mysqli->query("SELECT * FROM certificates ORDER BY issue_date DESC, created_at DESC");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $certificates[] = $row;
        }
    }
}

// --- CSRF Token ---
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrfToken = $_SESSION['csrf_token'];

// --- Dil Değiştirme Linki ---
$lang_code = ($lang === 'tr') ? 'tr' : 'en';
$lang_url = ($lang === 'tr') ? 'en' : 'tr';