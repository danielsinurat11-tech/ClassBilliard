# Test Email untuk kbangetya@gmail.com

## Langkah-langkah Test:

### 1. Pastikan Konfigurasi .env Sudah Benar

Buka file `.env` dan pastikan ada konfigurasi berikut:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=kbangetya@gmail.com
MAIL_PASSWORD=your-16-char-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=kbangetya@gmail.com
MAIL_FROM_NAME="Billiard Class"
```

**PENTING**: 
- `MAIL_PASSWORD` harus menggunakan **App Password** dari Gmail
- Bukan password Gmail biasa
- App Password adalah 16 karakter tanpa spasi

### 2. Buat Gmail App Password

1. Buka: https://myaccount.google.com/security
2. Pastikan **2-Step Verification** sudah aktif
3. Buka: https://myaccount.google.com/apppasswords
4. Pilih:
   - **Select app**: Mail
   - **Select device**: Other (Custom name)
   - Masukkan nama: "Billiard Laravel"
5. Klik **Generate**
6. **Copy password yang dihasilkan** (16 karakter tanpa spasi)
7. Paste sebagai `MAIL_PASSWORD` di `.env`

### 3. Clear Cache

Setelah mengubah `.env`, jalankan:

```bash
php artisan config:clear
php artisan cache:clear
```

### 4. Test Email

Buka browser dan akses:

```
http://127.0.0.1:8000/test-email?email=kbangetya@gmail.com
```

Ini akan:
- Menampilkan konfigurasi email saat ini
- Mengirim test email sederhana ke kbangetya@gmail.com
- Menampilkan error jika ada masalah

### 5. Cek Email

Setelah test email berhasil dikirim, cek:

1. **Kotak Masuk** di Gmail
2. **Folder Spam** (sering kali email masuk ke sini)
3. **Folder Promosi**
4. **Tab "All Mail"**

Email mungkin memerlukan beberapa menit untuk sampai.

### 6. Test Kirim Laporan Excel

Setelah test email berhasil, coba kirim laporan Excel:

1. Buka halaman laporan di: http://127.0.0.1:8000/dapur
2. Klik menu "Laporan"
3. Pilih filter (Harian/Bulanan/Tahunan)
4. Klik "Tampilkan"
5. Klik tombol "Export Excel"
6. Masukkan email: **kbangetya@gmail.com**
7. Klik "Kirim Email"

### Troubleshooting

Jika email tidak masuk:

1. **Cek folder Spam** - Email sering masuk ke sini
2. **Cek log error**: `storage/logs/laravel.log`
3. **Pastikan App Password benar** - Harus 16 karakter tanpa spasi
4. **Pastikan 2-Step Verification aktif**
5. **Coba tunggu beberapa menit** - Email mungkin delay

### Alternatif: Gunakan Mailtrap untuk Testing

Jika Gmail masih bermasalah, gunakan Mailtrap:

1. Daftar di: https://mailtrap.io/
2. Buat inbox baru
3. Copy credentials
4. Update `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=tls
```
5. Clear cache dan test lagi

