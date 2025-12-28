# ⚡ QUICK START - SHIFT TIME PROTECTION

## ✅ Apa yang Sudah Diimplementasi?

Sistem shift time protection dengan 3 komponen utama:

1. **✨ Middleware** - Validasi shift time
2. **🔐 Routes Protection** - Middleware applied ke admin & kitchen
3. **📢 Alert System** - Error/Warning/Success alerts

---

## 📌 Key Features

| Feature | Behavior |
|---------|----------|
| **Akses Dalam Jam Kerja** | ✅ Allowed, no alert |
| **30 min Sebelum Shift** | ⏰ Allowed, warning alert |
| **30 min Sesudah Shift** | ⏰ Allowed, warning alert |
| **Di Luar Tolerance** | ❌ Blocked, error alert, redirect home |
| **User Tanpa Shift** | ✅ Always allowed (no check) |
| **Shift Inactive** | ✅ Always allowed (no check) |

---

## 🧪 Quick Test

### Test 1: Akses Normal (Jam Kerja)
```
1. Login: admin.morning@test.com / password
2. Time: 10:30 AM (dalam shift 09:00-17:00)
3. Navigate: /admin/dashboard
4. Result: ✅ Access OK, no alert
```

### Test 2: Akses Awal (Early Grace)
```
1. Login: admin.morning@test.com / password
2. Time: 08:50 AM (30 menit sebelum shift)
3. Navigate: /admin/dashboard
4. Result: ⏰ Warning alert + access OK
```

### Test 3: Akses Akhir (Late Grace)
```
1. Login: kitchen.evening@test.com / password
2. Time: 22:10 PM (10 menit setelah shift 17:00-22:00)
3. Navigate: /dapur
4. Result: ⏰ Warning alert + access OK
```

### Test 4: Akses Blocked
```
1. Login: admin.morning@test.com / password
2. Time: 18:00 PM (45 menit setelah shift)
3. Navigate: /admin/dashboard
4. Result: ❌ Error alert + redirect to home
```

---

## 🔍 Implementation Details

### Files Modified

```
✅ NEW:  app/Http/Middleware/CheckShiftTime.php
✏️ EDIT: bootstrap/app.php
✏️ EDIT: routes/web.php
✏️ EDIT: resources/views/layouts/admin.blade.php
✏️ EDIT: resources/views/layouts/app.blade.php
```

### Middleware Logic

```
User Request
  ↓
Check: Authenticated? YES
  ↓
Check: Correct Role? YES
  ↓
Check: Has Shift? NO → Allow
  ↓
Check: Shift Active? NO → Allow
  ↓
Parse: Start time, End time
  ↓
Calculate: Tolerance ±30 min
  ↓
Compare: Current time in range?
  ├─ YES: Allow
  ├─ EARLY (start-30): Warn but Allow
  ├─ LATE (end+30): Warn but Allow
  └─ NO: Block + Error Alert + Redirect
```

### Alert Messages

**ERROR** (Red):
```
Anda tidak dalam jam kerja. Shift Anda: Morning (09:00 - 17:00 WIB)
```

**WARNING EARLY** (Amber):
```
⏰ Shift Anda belum dimulai. Mulai dalam 15 menit.
```

**WARNING LATE** (Amber):
```
⏰ Shift Anda sudah berakhir 10 menit lalu. Segera selesaikan pekerjaan.
```

---

## 🚀 How to Use

### For Admin Users
- Login sebelum shift time
- Akses dashboard seperti biasa
- Akan dapat warning jika early/late
- Akan diblok jika completely outside shift

### For Kitchen Staff
- Login untuk mulai shift
- Akses /dapur untuk lihat pesanan
- Akan dapat warning jika early/late
- Akan diblok jika outside shift hours

### For Flexible Users (No Shift)
- Bisa login kapan saja
- Bisa akses admin/kitchen kapan saja
- No alerts akan ditampilkan

---

## 📊 Example Timeline

Shift: 09:00 - 17:00 (Tolerance: 08:30 - 17:30)

```
08:00    ❌ BLOCKED (outside tolerance)
│        └─ Error: "Anda tidak dalam jam kerja"
│
08:30    ⏰ WARNING (tolerance start)
│        └─ Warning: "Mulai dalam 30 menit"
│
09:00    ✅ ALLOWED (shift start)
│        └─ No alert
│
10:30    ✅ ALLOWED (normal hours)
│        └─ No alert
│
17:00    ✅ ALLOWED (shift end)
│        └─ No alert
│
17:30    ⏰ WARNING (tolerance end)
│        └─ Warning: "Sudah berakhir 30 menit lalu"
│
18:00    ❌ BLOCKED (outside tolerance)
         └─ Error: "Anda tidak dalam jam kerja"
```

---

## 🎨 Alert Visual

### Admin Layout (Top of Content)
```
┌─────────────────────────────────────────┐
│ ❌ Anda tidak dalam jam kerja...      [×]│
├─────────────────────────────────────────┤
│     Dashboard Content Here...           │
└─────────────────────────────────────────┘
```

### Kitchen/Public Layout (Below Header)
```
┌─────────────────────────────────────────┐
│     Header (Logo, Nav, etc)             │
├─────────────────────────────────────────┤
│ ⏰ Shift Anda belum dimulai...        [×]│
├─────────────────────────────────────────┤
│     Page Content Here...                │
└─────────────────────────────────────────┘
```

---

## 🔧 Configuration

### Tolerance Duration
Located in: `app/Http/Middleware/CheckShiftTime.php`

```php
$toleranceStart = $startTime->copy()->subMinutes(30);  // ← Change here
$toleranceEnd = $endTime->copy()->addMinutes(30);      // ← Change here
```

To change tolerance to 15 minutes:
```php
$toleranceStart = $startTime->copy()->subMinutes(15);
$toleranceEnd = $endTime->copy()->addMinutes(15);
```

### Auto-Dismiss Duration
Located in: `resources/views/layouts/admin.blade.php` & `app.blade.php`

```blade
<!-- Error: Auto-dismiss after 5000ms -->
x-init="setTimeout(() => show = false, 5000)"

<!-- Warning: Auto-dismiss after 4000ms -->
x-init="setTimeout(() => show = false, 4000)"

<!-- Success: Auto-dismiss after 3000ms -->
x-init="setTimeout(() => show = false, 3000)"
```

---

## 🚨 Troubleshooting

### Alert Not Showing?
```bash
# Clear cache
php artisan optimize:clear

# Check middleware is registered
grep "check.shift.time" bootstrap/app.php
```

### Always Blocked?
```bash
# Check shift data
mysql> SELECT * FROM shifts;

# Check user's shift_id
mysql> SELECT id, name, shift_id FROM users;

# Check current time
laravel> dd(now());
```

### Midnight Shift Issues?
```
Shift: 22:00 - 06:00 should work
If not, check: end_time (06:00) < start_time (22:00)
This condition must trigger the midnight handling
```

---

## 📚 Documentation Files

| File | Purpose |
|------|---------|
| `SHIFT_TIME_PROTECTION.md` | Full detailed documentation |
| `IMPLEMENTATION_GUIDE.md` | Technical implementation details |
| `TESTING_GUIDE.md` | Complete testing procedures |
| `QUICK_START.md` | This file - quick overview |

---

## ✨ Next Steps

1. **Clear Cache**
   ```bash
   php artisan optimize:clear
   ```

2. **Test Basic Flow**
   - Login as shift user
   - Try access /admin or /dapur
   - Check alert displays properly

3. **Test Edge Cases**
   - Early access (30 min before)
   - Late access (30 min after)
   - Outside tolerance
   - No shift user

4. **Monitor in Production**
   - Check logs for blocked access
   - Get user feedback
   - Adjust tolerance if needed

---

## 📞 Support

Issues? Check:
1. Middleware registered in `bootstrap/app.php`
2. Routes have middleware in `routes/web.php`
3. Shifts exist and is_active = 1
4. User has shift_id assigned
5. System time is correct

---

## 🎉 Done!

Your shift time protection system is ready to use.

**Test it now and enjoy!** 🚀

