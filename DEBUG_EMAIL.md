# Debug Email Tidak Masuk ke Gmail

## Langkah-langkah Debugging:

### 1. Cek Folder Spam
**PENTING**: Email mungkin masuk ke folder **Spam** atau **Promosi** di Gmail. Silakan cek:
- Folder Spam
- Folder Promosi
- Tab "All Mail"

### 2. Test Email Sederhana
Buka browser dan akses:
```
http://127.0.0.1:8000/test-email?email=your-email@gmail.com
```
Ganti `your-email@gmail.com` dengan email Gmail Anda.

Ini akan mengirim test email sederhana tanpa attachment untuk memastikan konfigurasi email sudah benar.

### 3. Cek Log Laravel
Buka file `storage/logs/laravel.log` dan cari error terkait email. Cari kata kunci:
- "Email Error"
- "Mail"
- "SMTP"
- "Connection"

### 4. Verifikasi Konfigurasi .env
Pastikan di file `.env` sudah ada konfigurasi berikut:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password-16-karakter
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="Billiard Class"
```

**PENTING**: 
- `MAIL_PASSWORD` harus menggunakan **App Password** dari Gmail, bukan password Gmail biasa
- App Password adalah 16 karakter tanpa spasi
- Pastikan 2-Step Verification sudah aktif di Gmail

### 5. Clear Cache Setelah Mengubah .env
```bash
php artisan config:clear
php artisan cache:clear
```

### 6. Cek Error di Browser
1. Buka Developer Tools (F12)
2. Tab Console
3. Coba kirim email lagi
4. Lihat apakah ada error di console

### 7. Kemungkinan Masalah Lain:

#### Email Masuk ke Spam
- Gmail sering memindahkan email dari aplikasi ke spam
- Cek folder Spam secara manual
- Mark email sebagai "Not Spam" jika ditemukan

#### Gmail Memblokir Email
- Gmail mungkin memblokir email dari aplikasi lokal
- Coba gunakan email Gmail yang berbeda untuk testing
- Atau gunakan Mailtrap untuk testing (lihat SETUP_EMAIL.md)

#### Port 587 Diblokir
- Pastikan firewall tidak memblokir port 587
- Coba gunakan port 465 dengan SSL:
```env
MAIL_PORT=465
MAIL_ENCRYPTION=ssl
```

### 8. Alternatif: Gunakan Mailtrap untuk Testing
Jika Gmail masih bermasalah, gunakan Mailtrap:
1. Daftar di https://mailtrap.io/
2. Dapatkan credentials
3. Update `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=tls
```

### 9. Test dengan Route Test
Setelah setup, test dengan:
```
http://127.0.0.1:8000/test-email?email=your-email@gmail.com
```

Jika berhasil, akan muncul pesan sukses dan email akan masuk ke inbox Mailtrap (bukan Gmail).

