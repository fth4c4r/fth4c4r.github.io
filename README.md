# Portföy Sitesi

Flutter geliştiricisi için iki dilli (Türkçe / İngilizce) kişisel portföy sitesi. Saf PHP ile yazıldı, harici çerçeve (framework) gerektirmez.

## Özellikler
- 🌍 TR / EN dil değiştirme (çerez ile hatırlanır)
- 📱 Responsive (mobil uyumlu) modern tasarım
- 🧩 Bölümler: Hakkımda, Yetenekler, Projeler, İletişim
- ✉️ Çalışan iletişim formu iskeleti (doğrulama dahil)

## Klasör Yapısı
```
portfolio/
├── index.php            # Ana sayfa
├── includes/
│   ├── init.php         # Dil seçimi + içerik verisi (yetenekler, projeler)
│   ├── header.php       # Üst kısım + navigasyon
│   └── footer.php       # Alt kısım
├── lang/
│   ├── tr.php           # Türkçe metinler
│   └── en.php           # İngilizce metinler
└── assets/
    ├── css/style.css
    ├── js/main.js
    └── img/             # Görseller (foto, proje ekran görüntüleri)
```

## Çalıştırma
Yerel makinede PHP'nin yerleşik sunucusuyla:

```bash
cd /Users/fatihacar/Desktop/portfolio
php -S localhost:8000
```

Ardından tarayıcıda `http://localhost:8000` adresini aç.

## Kişiselleştirme
İşe başlamak için düzenlemen gereken yerler:

1. **Metinler** → `lang/tr.php` ve `lang/en.php` (isim, ünvan, hakkımda yazısı).
2. **Yetenekler ve projeler** → `includes/init.php` içindeki `$skills` ve `$projects` dizileri.
3. **Sosyal linkler** → `includes/footer.php` (GitHub, LinkedIn, e-posta).
4. **Renkler** → `assets/css/style.css` en üstteki `:root` değişkenleri.
5. **Profil/proje görselleri** → `assets/img/` klasörüne ekle.

## İletişim formu hakkında
`index.php` içindeki form şu an mesajı doğruluyor ama göndermiyor.
Gerçekten e-posta göndermek için ilgili `mail()` satırını etkinleştir veya
bir veritabanına/servise bağla (yorum satırında örnek var).
