# Setup Email untuk Local Development

## Opsi 1: Mailtrap (Paling Mudah untuk Local)

Mailtrap adalah service khusus untuk testing email di local development. Email tidak benar-benar dikirim, tapi bisa dilihat di dashboard Mailtrap.

### Langkah Setup:
1. Daftar gratis di: https://mailtrap.io/
2. Buat inbox baru
3. Copy credentials (Username & Password)
4. Update `.env`:

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

5. Clear cache: `php artisan config:clear`
6. Test kirim email, lalu cek di dashboard Mailtrap

**Keuntungan**: 
- Tidak perlu setup Gmail App Password
- Email bisa dilihat langsung di dashboard
- Cocok untuk development

---

## Opsi 2: Gmail SMTP (Bisa di Local)

Gmail SMTP bisa digunakan di local, tapi perlu setup App Password.

### Setup:
1. Aktifkan 2-Step Verification di Gmail
2. Buat App Password: https://myaccount.google.com/apppasswords
3. Update `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-16-char-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="Billiard Class"
```

4. Clear cache: `php artisan config:clear`

**Catatan**: 
- Email akan benar-benar dikirim ke Gmail
- Cek folder Spam jika tidak masuk inbox
- Beberapa ISP memblokir port 587

---

## Opsi 3: Log Driver (Hanya untuk Testing)

Email tidak benar-benar dikirim, hanya disimpan di log file.

### Setup:
```env
MAIL_MAILER=log
```

Email akan tersimpan di: `storage/logs/laravel.log`

**Keuntungan**: 
- Tidak perlu setup apapun
- Cocok untuk testing tanpa benar-benar kirim email

**Kekurangan**: 
- Tidak bisa test attachment
- Tidak bisa lihat format email

---

## Opsi 4: MailHog (Local SMTP Server)

MailHog adalah local SMTP server untuk testing.

### Setup:
1. Download MailHog: https://github.com/mailhog/MailHog/releases
2. Jalankan MailHog
3. Update `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
```

4. Buka http://127.0.0.1:8025 untuk melihat email

---

## Rekomendasi untuk Local Development:

**Gunakan Mailtrap** karena:
- ✅ Paling mudah setup
- ✅ Tidak perlu Gmail App Password
- ✅ Bisa lihat email dengan attachment
- ✅ Cocok untuk development
- ✅ Gratis untuk testing

Setelah aplikasi di-deploy ke hosting, baru ganti ke Gmail SMTP atau service email production lainnya.

