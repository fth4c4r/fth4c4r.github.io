-- ===================================================
-- Portfolyo Veritabanı Şeması
-- Hostunuzda (phpMyAdmin veya komut satırı) çalıştırın
-- ===================================================

CREATE DATABASE IF NOT EXISTS portfolio_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE portfolio_db;

-- Admin Kullanıcıları
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Site Ayarları (Hero, Hakkımda, İletişim vb. tüm metinler)
CREATE TABLE IF NOT EXISTS site_settings (
    setting_key VARCHAR(50) PRIMARY KEY,
    val_tr TEXT,
    val_en TEXT
);

-- Yetenekler
CREATE TABLE IF NOT EXISTS skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    level INT NOT NULL
);

-- Projeler
CREATE TABLE IF NOT EXISTS projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title_tr VARCHAR(255) NOT NULL,
    title_en VARCHAR(255) NOT NULL,
    desc_tr TEXT,
    desc_en TEXT,
    link VARCHAR(255),
    tags VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ===================================================
-- Varsayılan Veriler
-- ===================================================

-- Admin kullanıcısı (şifre: admin123)
-- !!! Giriş yaptıktan sonra şifrenizi değiştirin !!!
INSERT IGNORE INTO users (username, password) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.ogs7S6S6S6S6S6S6');

-- Varsayılan site ayarları
INSERT IGNORE INTO site_settings (setting_key, val_tr, val_en) VALUES 
('site_title', 'Adınız Soyadınız — Flutter Geliştirici', 'Your Name — Flutter Developer'),
('nav_about', 'Hakkımda', 'About'),
('nav_skills', 'Yetenekler', 'Skills'),
('nav_projects', 'Projeler', 'Projects'),
('nav_contact', 'İletişim', 'Contact'),
('hero_greeting', 'Merhaba, ben', 'Hi, I am'),
('hero_name', 'Adınız Soyadınız', 'Your Name'),
('hero_role', 'Bilgisayar Mühendisi & Flutter Geliştirici', 'Computer Engineer & Flutter Developer'),
('hero_desc', 'iOS ve Android için performanslı, şık ve kullanıcı dostu mobil uygulamalar geliştiriyorum.', 'I build fast, elegant and user-friendly mobile apps for iOS and Android.'),
('hero_cta', 'Projelerimi Gör', 'View My Work'),
('hero_contact', 'İletişime Geç', 'Get in Touch'),
('about_title', 'Hakkımda', 'About Me'),
('about_text', 'Bilgisayar mühendisiyim ve mobil uygulama geliştirmeye tutkuyla bağlıyım. Flutter ile iOS ve Android\'de native hissiyatlı uygulamalar geliştiriyorum. Clean architecture, akıcı animasyonlar ve harika kullanıcı deneyimi benim için önemlidir.', 'I am a computer engineer passionate about mobile app development. With Flutter, I build apps that feel native on both iOS and Android from a single codebase. I care about clean architecture, smooth animations and great user experience.'),
('skills_title', 'Yeteneklerim', 'My Skills'),
('projects_title', 'Projelerim', 'My Projects'),
('project_view', 'İncele', 'View'),
('contact_title', 'İletişim', 'Contact'),
('contact_text', 'Yeni projeler ve iş birlikleri için benimle iletişime geçebilirsiniz.', 'Feel free to reach out for new projects and collaborations.'),
('contact_name', 'Adınız', 'Your Name'),
('contact_email', 'E-posta', 'Email'),
('contact_message', 'Mesajınız', 'Your Message'),
('contact_send', 'Gönder', 'Send'),
('footer_rights', 'Tüm hakları saklıdır.', 'All rights reserved.');