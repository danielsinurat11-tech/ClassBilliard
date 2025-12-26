# Setup Email untuk Fitur Kirim Laporan Excel

## Konfigurasi Email di .env

Tambahkan konfigurasi berikut di file `.env` Anda:

### Untuk Gmail SMTP:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Catatan Penting untuk Gmail:
1. **App Password**: Gmail tidak mengizinkan password biasa untuk aplikasi pihak ketiga. Anda perlu membuat "App Password":
   - Buka: https://myaccount.google.com/apppasswords
   - Pilih "Mail" dan "Other (Custom name)"
   - Masukkan nama aplikasi (contoh: "Billiard Laravel")
   - Copy password yang dihasilkan dan gunakan sebagai `MAIL_PASSWORD`

2. **2-Step Verification**: Pastikan 2-Step Verification sudah aktif di akun Gmail Anda

### Alternatif: Mailtrap (untuk testing)
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@billiard-class.com
MAIL_FROM_NAME="Billiard Class"
```

## Setelah Setup:
1. Clear config cache: `php artisan config:clear`
2. Test dengan mengirim email dari halaman laporan

