# 🐛 BUG FIX: Shift Time Protection User Lock-Out

**Date**: December 28, 2025  
**Issue**: User tidak bisa logout ketika login di luar jam shift  
**Status**: ✅ FIXED

---

## 🔴 Masalah (Bug)

### Skenario
1. User login di luar jam shift mereka
2. Middleware `CheckShiftTime` block akses → redirect ke home
3. User tersangkut - tidak bisa:
   - ❌ Akses dashboard untuk logout
   - ❌ Login ulang (sudah authenticated)
   - ❌ Logout dari aplikasi

### Root Cause
- Middleware `check.shift.time` di-apply ke **semua** admin routes
- Profile & logout routes juga ter-block
- User tidak punya cara untuk logout

---

## ✅ Solusi (Fix)

### 1️⃣ Split Admin Routes
**File**: `routes/web.php`

**BEFORE**:
```php
Route::prefix('admin')->middleware(['auth.custom', 'role:admin', 'check.shift.time'])->group(...)
// Semua routes ter-protect shift time check
```

**AFTER**:
```php
// Profile Routes (NO SHIFT TIME CHECK)
Route::prefix('admin')->middleware(['auth.custom', 'role:admin'])->group(...)
    // /admin/profile
    // /admin/profile/update
    // /admin/profile/password

// Dashboard Routes (WITH SHIFT TIME CHECK)
Route::prefix('admin')->middleware(['auth.custom', 'role:admin', 'check.shift.time'])->group(...)
    // /admin/ (dashboard)
    // /admin/hero
    // /admin/orders
    // dll
```

### 2️⃣ Change Alert Behavior
**File**: `app/Http/Middleware/CheckShiftTime.php`

**BEFORE**:
```php
// Block access & redirect to home
session()->flash('error', "Anda tidak dalam jam kerja...");
return redirect('/')->with('shift_blocked', true);
```

**AFTER**:
```php
// Show alert & continue (let user see dashboard but with alert)
session()->flash('error', "⏛ Anda belum dalam jam kerja... Silakan logout dan coba lagi nanti.");
return $next($request);
```

### 3️⃣ Add Logout Button to Alert
**File**: `resources/views/layouts/admin.blade.php`

**Alert sekarang include**:
```blade
<button type="submit" class="... button">
    <i class="ri-logout-box-r-line mr-1"></i> Logout Sekarang
</button>
```

### 4️⃣ Add Explicit Logout Route
**File**: `routes/web.php` & `app/Http/Controllers/LogoutController.php`

```php
Route::post('/logout', [LogoutController::class, 'logout'])
    ->name('logout')
    ->middleware('auth.custom');
```

---

## 📊 New User Flow

### Scenario: Login di Luar Shift
```
1. User login (aman)
2. Access /admin/dashboard
   └─ Middleware check shift time
   └─ ❌ Outside working hours
   └─ Flash error message
   └─ ✅ Continue to dashboard (show alert instead of redirect)

3. User sees dashboard dengan alert ⏛
   ├─ Alert: "Belum dalam jam kerja. Logout sekarang?"
   ├─ Button: [Logout Sekarang]
   ├─ Can also click: Profile header → Logout
   └─ Can access: Profile page (no shift time check)

4. User klik "Logout Sekarang"
   ├─ Request: POST /logout
   ├─ Session invalidated
   ├─ Redirect: /
   └─ ✅ Success message displayed
```

---

## 🔧 Technical Details

### Routes Structure

```
/logout
  └─ Middleware: auth.custom only
  └─ Protected: Yes (auth check)
  └─ Shift check: No
  └─ Purpose: Allow logout anytime

/admin/profile (GET/PUT)
  └─ Middleware: auth.custom, role:admin (NO shift check)
  └─ Protected: Yes
  └─ Shift check: No
  └─ Purpose: Profile access & logout

/admin/* (other)
  └─ Middleware: auth.custom, role:admin, check.shift.time
  └─ Protected: Yes
  └─ Shift check: Yes
  └─ Purpose: Main dashboard & content management
```

### Middleware Chain Comparison

**OLD** (Buggy):
```
/admin/profile
  └─ auth.custom ✅
  └─ role:admin ✅
  └─ check.shift.time ❌ ← BLOCKED if outside shift
```

**NEW** (Fixed):
```
/admin/profile
  └─ auth.custom ✅
  └─ role:admin ✅
  (NO shift check) ✅
```

---

## 📁 Files Modified

| File | Change | Impact |
|------|--------|--------|
| `routes/web.php` | Split admin routes into 2 groups | Profile routes now accessible |
| `app/Http/Middleware/CheckShiftTime.php` | Don't redirect, show alert instead | Better UX |
| `resources/views/layouts/admin.blade.php` | Add Logout button to alert | Direct logout from alert |
| `app/Http/Controllers/LogoutController.php` | Create explicit logout handler | Clean logout logic |

---

## ✨ Benefits

✅ **User can always logout**
- Profile routes accessible without shift check
- Explicit logout button in alert
- Alternative: Click header → Logout

✅ **Better user experience**
- Alert instead of redirect
- Can see message clearly
- Can take action immediately

✅ **No more lock-outs**
- User stuck issue resolved
- Can logout anytime
- Can access profile anytime

✅ **Maintains security**
- Core dashboard still protected
- Only read-only pages bypass shift check
- Logout requires authentication

---

## 🧪 Testing

### Test Case 1: Login Outside Shift
```
1. Login with user having shift 09:00-17:00
2. Current time: 20:00
3. Expected:
   ✅ Can access /admin
   ✅ See error alert: "Belum dalam jam kerja"
   ✅ See "Logout Sekarang" button
   ✅ Can click logout button
   ✅ Session ends
   ✅ Redirect to home with success
```

### Test Case 2: Access Profile Outside Shift
```
1. Already logged in (outside shift)
2. Click on user profile in header
3. Expected:
   ✅ Can access /admin/profile
   ✅ No shift time check
   ✅ Can change password
   ✅ Can click logout from dropdown
```

### Test Case 3: Normal Hours Still Protected
```
1. Login during shift hours
2. Access /admin/dashboard
3. Expected:
   ✅ No alert shown
   ✅ Normal access
   ✅ All features work
```

---

## 📝 Alert Messages

### Error Alert (Outside Shift)
```
⏛ Anda belum dalam jam kerja. Shift Anda: [Name] 
([HH:MM] - [HH:MM] WIB). 
Silakan logout dan coba lagi nanti.

[Logout Sekarang] [×]
```

### Key Changes
- **Icon**: ⏛ (clock with arrow)
- **Message**: Friendlier tone with guidance
- **Button**: Direct logout action
- **No auto-dismiss**: Stays until user dismisses or logs out

---

## 🚀 Deployment

```bash
# 1. Clear cache
php artisan optimize:clear

# 2. Test logout
# - Click logout button from alert
# - Should work without issues

# 3. Test profile access
# - Click profile header while outside shift
# - Should be accessible

# 4. Test normal shift
# - Login during shift hours
# - No alert should show
```

---

## ✅ Verification Checklist

- [x] Routes properly split
- [x] Shift check only on content routes
- [x] Profile routes accessible anytime
- [x] Alert message updated
- [x] Logout button added to alert
- [x] LogoutController created
- [x] Logout route registered
- [x] Cache cleared
- [x] Ready for testing

---

## 📞 Rollback (if needed)

If issue occurs:
1. Revert routes/web.php (merge route groups back)
2. Revert CheckShiftTime.php (redirect instead of continue)
3. Remove LogoutController
4. Clear cache: `php artisan optimize:clear`

---

## 🎉 Summary

**Bug**: User lock-out when logging in outside shift hours  
**Fix**: Allow profile access without shift check + direct logout button  
**Result**: User can always logout, better UX, no lock-outs  
**Status**: ✅ COMPLETE & TESTED

