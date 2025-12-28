# 🔧 IMPLEMENTASI SHIFT TIME PROTECTION - GUIDE LENGKAP

## ✅ Yang Sudah Diimplementasi

### 1. Middleware Baru
📁 `app/Http/Middleware/CheckShiftTime.php`

**Functionality**:
- Parse shift time dari database (format HH:MM atau HH:MM:SS)
- Validasi current time vs shift time
- Tambahkan toleransi ±30 menit
- Handle midnight crossing shifts
- Generate alert messages

**Logic Flow**:
```
User akses /admin atau /dapur
  ↓
Middleware execute
  ↓
Check: User punya shift? → Tidak → Continue (Allowed)
  ↓
Check: Shift active? → Tidak → Continue (Allowed)
  ↓
Parse: Start time & End time
  ↓
Hitung: Tolerance = ±30 minutes
  ↓
Compare: Current time vs (start-30 ... end+30)
  ↓
❌ Outside tolerance:
   └─ Flash error message
   └─ Redirect to home
   └─ Status: BLOCKED
  ↓
⏰ Between start-30 dan start (early access):
   └─ Flash warning message
   └─ Continue to destination
   └─ Status: ALLOWED
  ↓
✅ Between start dan end:
   └─ No alert
   └─ Continue to destination
   └─ Status: ALLOWED
  ↓
⏰ Between end dan end+30 (grace period):
   └─ Flash warning message
   └─ Continue to destination
   └─ Status: ALLOWED
```

### 2. Routes Protection
📁 `routes/web.php`

**Updated Routes**:
```php
// Kitchen Routes
Route::middleware(['auth.custom', 'role:kitchen', 'check.shift.time'])->group(...)

// Admin Routes
Route::middleware(['auth.custom', 'role:admin', 'check.shift.time'])->group(...)
```

**Middleware Stack**:
1. `auth.custom` → Check user login
2. `role:kitchen|admin` → Check role
3. `check.shift.time` → Check shift time ⭐ NEW

### 3. Middleware Registration
📁 `bootstrap/app.php`

```php
->withMiddleware(function (Middleware $middleware): void {
    $middleware->alias([
        'role' => \App\Http\Middleware\RoleMiddleware::class,
        'auth.custom' => \App\Http\Middleware\EnsureUserIsAuthenticated::class,
        'check.shift.time' => \App\Http\Middleware\CheckShiftTime::class, // ⭐ NEW
    ]);
})
```

### 4. Alert System Enhancement
📁 `resources/views/layouts/admin.blade.php`
📁 `resources/views/layouts/app.blade.php`

**Alert Components Added**:

#### Error Alert (Red)
```blade
@if(session('error'))
    <div class="alert alert-error">
        <i class="ri-alert-fill"></i>
        <p>{{ session('error') }}</p>
        <button @click="close">×</button>
    </div>
@endif
```
**Auto-dismiss**: 5 detik
**Manual close**: Yes
**Color**: Red (#ef4444)

#### Warning Alert (Amber)
```blade
@if(session('warning'))
    <div class="alert alert-warning">
        <i class="ri-alert-line"></i>
        <p>{{ session('warning') }}</p>
        <button @click="close">×</button>
    </div>
@endif
```
**Auto-dismiss**: 4 detik
**Manual close**: Yes
**Color**: Amber (#f59e0b)

#### Success Alert (Green)
```blade
@if(session('success'))
    <div class="alert alert-success">
        <i class="ri-checkbox-circle-fill"></i>
        <p>{{ session('success') }}</p>
        <button @click="close">×</button>
    </div>
@endif
```
**Auto-dismiss**: 3 detik
**Manual close**: Yes
**Color**: Green (#10b981)

---

## 🎯 USE CASES

### Case 1: Regular Admin (9 AM - 5 PM)
```
Shift: Morning (09:00 - 17:00)
Tolerance: 08:30 - 17:30

Time: 10:30 → ✅ ALLOWED (no alert)
Time: 08:50 → ⏰ WARNING (early access) + ALLOWED
Time: 17:10 → ⏰ WARNING (grace period) + ALLOWED
Time: 18:00 → ❌ BLOCKED (outside tolerance) + REDIRECT HOME
```

### Case 2: Night Kitchen Staff (10 PM - 6 AM)
```
Shift: Night (22:00 - 06:00)
Tolerance: 21:30 - 06:30

Time: 02:30 → ✅ ALLOWED (no alert)
Time: 21:50 → ⏰ WARNING (early access) + ALLOWED
Time: 06:15 → ⏰ WARNING (grace period) + ALLOWED
Time: 07:00 → ❌ BLOCKED (outside tolerance) + REDIRECT HOME
Time: 10:00 → ❌ BLOCKED (outside tolerance) + REDIRECT HOME
```

### Case 3: User Without Shift
```
shift_id: NULL
Result: ✅ ALWAYS ALLOWED (no validation)
```

### Case 4: Inactive Shift
```
Shift: Shift A (is_active: false)
Result: ✅ ALWAYS ALLOWED (no validation)
```

---

## 📊 Shift Time Handling

### Normal Shift (tidak melintasi tengah malam)
```
Start: 09:00
End:   17:00

Comparison:
End (17:00) > Start (09:00) ✓
└─ No adjustment needed
```

### Midnight Crossing Shift
```
Start: 22:00
End:   06:00

Comparison:
End (06:00) < Start (22:00) ✓
└─ This is a night shift
└─ Adjust: If now < 22:00, subtract 1 day from start
└─ Adjust: If now >= 22:00, add 1 day to end
```

**Example Calculation**:
```
Shift: 22:00 - 06:00
Current: 2024-12-28 01:30

Start: 2024-12-27 22:00 (yesterday, because now < tomorrow's 06:00)
End:   2024-12-28 06:00

Result: 01:30 is between 22:00 yesterday and 06:00 today ✓
```

---

## 🔄 Session Flash Messages

### Error Message Format
```php
session()->flash('error', "Anda tidak dalam jam kerja. Shift Anda: {$shiftName} ({$startTimeFormatted} - {$endTimeFormatted} WIB)");
```

**Example Output**:
```
Anda tidak dalam jam kerja. Shift Anda: Morning (09:00 - 17:00 WIB)
```

### Warning Message Format (Before Start)
```php
session()->flash('warning', "⏰ Shift Anda belum dimulai. Mulai dalam {$minutesUntil} menit.");
```

**Example Output**:
```
⏰ Shift Anda belum dimulai. Mulai dalam 15 menit.
```

### Warning Message Format (After End)
```php
session()->flash('warning', "⏰ Shift Anda sudah berakhir {$minutesAfter} menit lalu. Segera selesaikan pekerjaan.");
```

**Example Output**:
```
⏰ Shift Anda sudah berakhir 20 menit lalu. Segera selesaikan pekerjaan.
```

---

## 🧩 Database Relationship

```
User
├─ id
├─ name
├─ email
├─ password
├─ role (admin|kitchen)
├─ shift_id ← Foreign Key
└─ timestamps

Shift
├─ id
├─ name
├─ start_time (TIME format: HH:MM:SS)
├─ end_time (TIME format: HH:MM:SS)
├─ is_active (boolean)
└─ timestamps
```

**Example Data**:
```sql
-- Shift Table
INSERT INTO shifts (name, start_time, end_time, is_active)
VALUES 
  ('Morning', '09:00:00', '17:00:00', true),
  ('Evening', '17:00:00', '22:00:00', true),
  ('Night', '22:00:00', '06:00:00', true);

-- User Table
INSERT INTO users (name, email, password, role, shift_id)
VALUES 
  ('Admin User', 'admin@billiard.com', '...', 'admin', 1),
  ('Kitchen Staff', 'kitchen@billiard.com', '...', 'kitchen', 2),
  ('Night Staff', 'night@billiard.com', '...', 'kitchen', 3);
```

---

## ✨ Features Summary

| Feature | Status | Detail |
|---------|--------|--------|
| Shift Time Validation | ✅ Done | Check current time vs shift time |
| Tolerance Window | ✅ Done | ±30 minutes |
| Error Alert | ✅ Done | Red alert, auto-dismiss 5s |
| Warning Alert | ✅ Done | Amber alert, auto-dismiss 4s |
| Success Alert | ✅ Done | Green alert, auto-dismiss 3s |
| Access Blocking | ✅ Done | Redirect to home on error |
| Midnight Shift Support | ✅ Done | Handle 22:00-06:00 format |
| No Shift User | ✅ Done | Allow access (no validation) |
| Inactive Shift | ✅ Done | Allow access (no validation) |

---

## 🚀 How to Test

### Test 1: Simulate Outside Shift Hours
```
1. Login as admin/kitchen user
2. Set system time OUTSIDE shift hours
3. Try access /admin or /dapur
4. Expected: ❌ Error alert + redirect home
```

### Test 2: Simulate Early Access
```
1. Login as admin/kitchen user
2. Set system time 20 minutes BEFORE shift start
3. Try access /admin or /dapur
4. Expected: ⏰ Warning alert + allowed access
```

### Test 3: Simulate Grace Period
```
1. Login as admin/kitchen user
2. Set system time 20 minutes AFTER shift end
3. Try access /admin or /dapur
4. Expected: ⏰ Warning alert + allowed access
```

### Test 4: Normal Hours
```
1. Login as admin/kitchen user
2. Set system time DURING shift hours
3. Try access /admin or /dapur
4. Expected: ✅ No alert + normal access
```

---

## 📝 Code Files Modified

| File | Change | Type |
|------|--------|------|
| `app/Http/Middleware/CheckShiftTime.php` | Created | NEW |
| `bootstrap/app.php` | Added middleware alias | MODIFIED |
| `routes/web.php` | Added middleware to routes | MODIFIED |
| `resources/views/layouts/admin.blade.php` | Added alert system | MODIFIED |
| `resources/views/layouts/app.blade.php` | Added alert system | MODIFIED |

---

## 🔒 Security Notes

✅ **Protected Resources**:
- `/admin/*` routes
- `/dapur` (kitchen)
- `/reports` (reports)

✅ **Validation Chain**:
1. Authentication check (`auth.custom`)
2. Role check (`role:admin|kitchen`)
3. Shift time check (`check.shift.time`)

✅ **Exception Cases**:
- User without shift → No validation
- Shift inactive → No validation
- Public routes → No validation

---

## 📞 Support

Jika ada error atau tidak bekerja:

1. **Check middleware registered**: 
   ```bash
   grep -n "check.shift.time" bootstrap/app.php
   ```

2. **Clear cache**:
   ```bash
   php artisan optimize:clear
   ```

3. **Check route**:
   ```bash
   php artisan route:list | grep admin
   ```

4. **Check middleware order**:
   ```bash
   # Middleware harus dalam urutan:
   # 1. auth.custom
   # 2. role:admin|kitchen
   # 3. check.shift.time
   ```

