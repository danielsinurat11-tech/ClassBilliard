# Perbaiki File .env

## Masalah:
Error: "Encountered unexpected whitespace at [pfwu awtc ebfu mloi]"

Ini berarti ada spasi di App Password atau format yang salah di file `.env`.

## Solusi:

### 1. Buka file `.env` di root project

### 2. Cari bagian konfigurasi email (MAIL_*)

### 3. Pastikan format seperti ini (TANPA SPASI di password):

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=kbangetya@gmail.com
MAIL_PASSWORD=pfwuawtcebfumloi
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=kbangetya@gmail.com
MAIL_FROM_NAME="Billiard Class"
```

**PENTING**: 
- `MAIL_PASSWORD` harus **TANPA SPASI**
- Jika App Password Anda adalah "pfwu awtc ebfu mloi", ubah menjadi "pfwuawtcebfumloi" (hilangkan semua spasi)
- Pastikan tidak ada spasi sebelum atau sesudah tanda `=`

### 4. Contoh yang SALAH:
```env
MAIL_PASSWORD=pfwu awtc ebfu mloi  ❌ (ada spasi)
MAIL_PASSWORD = pfwuawtcebfumloi   ❌ (ada spasi sebelum/sesudah =)
```

### 5. Contoh yang BENAR:
```env
MAIL_PASSWORD=pfwuawtcebfumloi     ✅ (tanpa spasi)
```

### 6. Setelah diperbaiki, jalankan:
```bash
php artisan config:clear
```

### 7. Test lagi:
```
http://127.0.0.1:8000/test-email?email=kbangetya@gmail.com
```

## Tips:
- App Password dari Gmail biasanya 16 karakter tanpa spasi
- Jika Anda copy-paste App Password, pastikan tidak ada spasi yang ikut ter-copy
- Gunakan text editor yang bisa menampilkan whitespace untuk memastikan tidak ada spasi tersembunyi

