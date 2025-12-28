# 📅 SISTEM SHIFT TIME PROTECTION

## 📝 Deskripsi
Middleware yang melindungi akses admin dan kitchen operator hanya pada jam kerja mereka dengan toleransi **30 menit sebelum dan sesudah** jam shift.

---

## ✨ Fitur Utama

### 1. **Shift Time Validation**
- ✅ Validasi otomatis saat user akses admin/dapur
- ✅ Toleransi 30 menit SEBELUM jam mulai (early access)
- ✅ Toleransi 30 menit SESUDAH jam selesai (grace period)
- ✅ Support shift yang melintasi tengah malam (misal: 22:00 - 06:00)

### 2. **Alert Messages**
| Kondisi | Alert Type | Pesan |
|---------|-----------|-------|
| **Di luar jam kerja** | ❌ ERROR | "Anda tidak dalam jam kerja. Shift Anda: [Nama Shift] (HH:MM - HH:MM WIB)" |
| **Sebelum mulai (30 menit)** | ⏰ WARNING | "Shift Anda belum dimulai. Mulai dalam X menit." |
| **Sesudah selesai (30 menit)** | ⏰ WARNING | "Shift Anda sudah berakhir X menit lalu. Segera selesaikan pekerjaan." |

### 3. **Access Control**
- **ERROR**: Redirect ke halaman home dengan status error
- **WARNING**: Izinkan akses tapi tampilkan peringatan
- User tanpa shift assignment: Akses penuh (tidak ada batasan)
- Shift tidak aktif: Akses penuh (tidak ada batasan)

---

## 🔧 Technical Implementation

### Middleware File
**Location**: `app/Http/Middleware/CheckShiftTime.php`

**Key Methods**:
```php
handle()          // Main validation logic
parseTime()       // Parse time string to Carbon instance
```

### Registration
**File**: `bootstrap/app.php`

```php
'check.shift.time' => \App\Http\Middleware\CheckShiftTime::class,
```

### Routes Protected
**File**: `routes/web.php`

```php
// Kitchen Routes
Route::middleware(['auth.custom', 'role:kitchen', 'check.shift.time'])

// Admin Routes
Route::middleware(['auth.custom', 'role:admin', 'check.shift.time'])
```

---

## 📊 Example Scenarios

### Scenario 1: Normal Working Hours
```
Shift: 09:00 - 17:00 WIB
Tolerance: 08:30 - 17:30 WIB

Current Time: 10:30
✅ Status: ALLOWED (dalam jam kerja)
📨 Alert: None
```

### Scenario 2: Early Access (Grace Period Before)
```
Shift: 09:00 - 17:00 WIB
Tolerance: 08:30 - 17:30 WIB

Current Time: 08:50
⏰ Status: ALLOWED (early access)
📨 Alert: "Shift Anda belum dimulai. Mulai dalam 10 menit."
```

### Scenario 3: Late Access (Grace Period After)
```
Shift: 09:00 - 17:00 WIB
Tolerance: 08:30 - 17:30 WIB

Current Time: 17:15
⏰ Status: ALLOWED (grace period)
📨 Alert: "Shift Anda sudah berakhir 15 menit lalu. Segera selesaikan pekerjaan."
```

### Scenario 4: Outside Working Hours
```
Shift: 09:00 - 17:00 WIB
Tolerance: 08:30 - 17:30 WIB

Current Time: 18:00
❌ Status: BLOCKED (outside tolerance)
📨 Alert: "Anda tidak dalam jam kerja. Shift Anda: Morning (09:00 - 17:00 WIB)"
🔄 Redirect: / (home page)
```

### Scenario 5: Night Shift (Crosses Midnight)
```
Shift: 22:00 - 06:00 WIB
Tolerance: 21:30 - 06:30 WIB

Current Time: 01:00 AM
✅ Status: ALLOWED (dalam jam kerja)
📨 Alert: None
```

---

## 🎨 UI Alert Design

### Error Alert (Red)
```
🔴 | Anda tidak dalam jam kerja. Shift Anda: Morning (09:00 - 17:00 WIB)
    | [Close Button]
```

### Warning Alert (Amber)
```
⏰ | Shift Anda belum dimulai. Mulai dalam 10 menit.
   | [Close Button]
```

### Success Alert (Green)
```
✅ | Perubahan berhasil disimpan!
   | [Close Button]
```

**Features**:
- Auto-dismiss setelah beberapa detik
- Manual close button
- Smooth fade-in animation
- Responsive design

---

## 🔍 Alert Display Locations

### 1. Admin Dashboard
**File**: `resources/views/layouts/admin.blade.php`
- Tampil di bagian atas content area
- Alert type: Error, Warning, Success

### 2. Public Pages / Kitchen View
**File**: `resources/views/layouts/app.blade.php`
- Tampil di bawah header
- Alert type: Error, Warning, Success

---

## 🛡️ Security Considerations

✅ **Protected Routes**:
- `/admin/*` - Admin dashboard & management
- `/dapur` - Kitchen work area
- `/reports` - Kitchen reports

✅ **Double Authentication**:
1. `auth.custom` - Check user login
2. `check.shift.time` - Check shift time
3. `role:admin|kitchen` - Check user role

✅ **User Without Shift**:
- User yang tidak punya shift assignment: **Akses penuh tanpa batasan**
- User dengan shift inactive: **Akses penuh tanpa batasan**

---

## ⏱️ Time Parsing

Middleware mendukung berbagai format waktu:

```php
// Format yang didukung:
"09:00"      // HH:MM
"09:00:00"   // HH:MM:SS
Carbon instance // Dari database cast

// Parsing otomatis ke:
09:00:00     // HH:MM:SS (normalized)
```

---

## 🔄 Redirect Behavior

### Error Alert → Redirect
```
Request: GET /admin/dashboard (Outside shift time)
↓
Middleware Check: ❌ BLOCKED
↓
Session Flash: error message
↓
Redirect: / (home page)
↓
Home page menampilkan error alert
```

### Warning Alert → Continue
```
Request: GET /admin/dashboard (Early/late access)
↓
Middleware Check: ⏰ WARNING
↓
Session Flash: warning message
↓
Continue: Proceed ke destination
↓
Destination page menampilkan warning alert
```

---

## 🧪 Testing

### Test Case 1: Admin dengan Shift, Akses Normal
```
Setup: Admin user dengan shift 09:00-17:00
Time: 10:30
Expected: ✅ Allowed, no alert
```

### Test Case 2: Admin dengan Shift, Akses di Luar Jam
```
Setup: Admin user dengan shift 09:00-17:00
Time: 20:00
Expected: ❌ Blocked, error alert, redirect to home
```

### Test Case 3: Admin tanpa Shift
```
Setup: Admin user tanpa shift_id
Expected: ✅ Always allowed
```

### Test Case 4: Kitchen dengan Shift, Akses Awal
```
Setup: Kitchen user dengan shift 14:00-22:00
Time: 13:50
Expected: ✅ Allowed, warning alert
```

---

## 📌 Notes

- Sistem menggunakan waktu server (Carbon::now())
- Validasi dilakukan pada SETIAP request ke protected routes
- Alert tersimpan di session dan auto-dismiss
- Tidak perlu setup special config - otomatis berjalan setelah middleware register

---

## 🚀 Future Enhancements

- [ ] Audit log: Track akses di luar jam
- [ ] Dashboard: Lihat riwayat akses per user
- [ ] Permission: Admin bisa extend shift time untuk emergency
- [ ] Notification: Reminder email 30 menit sebelum shift
- [ ] Export: Report akses per shift per user

