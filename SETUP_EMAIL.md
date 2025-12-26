# Setup Email - Troubleshooting

## 1. Tambahkan Konfigurasi Email di .env

Buka file `.env` di root project dan tambahkan konfigurasi berikut:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="Billiard Class"
```

## 2. Setup Gmail App Password

### Langkah-langkah:
1. Buka: https://myaccount.google.com/security
2. Aktifkan **2-Step Verification** jika belum aktif
3. Setelah aktif, buka: https://myaccount.google.com/apppasswords
4. Pilih:
   - **Select app**: Mail
   - **Select device**: Other (Custom name)
   - Masukkan nama: "Billiard Laravel"
5. Klik **Generate**
6. **Copy password yang dihasilkan** (16 karakter tanpa spasi)
7. Paste sebagai `MAIL_PASSWORD` di `.env`

## 3. Clear Cache

Setelah mengubah `.env`, jalankan:

```bash
php artisan config:clear
php artisan cache:clear
```

## 4. Test Email

Setelah setup, coba kirim email dari halaman laporan. Jika masih error, cek:

1. **Log Laravel**: `storage/logs/laravel.log`
2. **Error di browser**: Buka Developer Tools > Console
3. **Cek folder Spam** di Gmail

## 5. Alternatif: Mailtrap (untuk Testing)

Jika Gmail masih bermasalah, gunakan Mailtrap untuk testing:

1. Daftar di: https://mailtrap.io/
2. Dapatkan credentials dari inbox
3. Update `.env`:

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

## Troubleshooting

### Error: "Connection could not be established"
- Pastikan `MAIL_HOST` benar
- Pastikan `MAIL_PORT` benar (587 untuk Gmail)
- Pastikan firewall tidak memblokir port 587

### Error: "Authentication failed"
- Pastikan menggunakan **App Password**, bukan password Gmail biasa
- Pastikan 2-Step Verification sudah aktif
- Pastikan `MAIL_USERNAME` adalah email lengkap (dengan @gmail.com)

### Email masuk ke Spam
- Cek folder Spam di Gmail
- Email mungkin memerlukan beberapa saat untuk sampai

### Error: "Could not instantiate mailer"
- Pastikan semua konfigurasi di `.env` sudah benar
- Jalankan `php artisan config:clear`

