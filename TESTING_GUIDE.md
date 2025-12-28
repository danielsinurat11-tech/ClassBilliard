# 🧪 TESTING GUIDE - SHIFT TIME PROTECTION

## 🎯 Objectives
Test shift time protection system untuk memastikan semuanya bekerja sesuai requirement:
- ✅ Alert ditampilkan saat bukan jam kerja
- ✅ Akses diblok saat di luar jam shift
- ✅ Toleransi 30 menit sebelum dan sesudah shift diterapkan
- ✅ Warning ditampilkan saat early/late access
- ✅ User tanpa shift bisa akses kapan saja

---

## 📋 Pre-requisites

### 1. Database Setup
Pastikan sudah ada shift data:

```sql
-- Check shifts
SELECT id, name, start_time, end_time, is_active FROM shifts;

-- Example:
-- id | name    | start_time | end_time | is_active
-- 1  | Morning | 09:00:00   | 17:00:00 | 1
-- 2  | Evening | 17:00:00   | 22:00:00 | 1
-- 3  | Night   | 22:00:00   | 06:00:00 | 1
```

### 2. User Setup
Create test users dengan berbagai konfigurasi:

```sql
-- Admin with shift
INSERT INTO users (name, email, password, role, shift_id, created_at, updated_at)
VALUES ('Admin Morning', 'admin.morning@test.com', bcrypt('password'), 'admin', 1, NOW(), NOW());

-- Kitchen with shift
INSERT INTO users (name, email, password, role, shift_id, created_at, updated_at)
VALUES ('Kitchen Evening', 'kitchen.evening@test.com', bcrypt('password'), 'kitchen', 2, NOW(), NOW());

-- User without shift
INSERT INTO users (name, email, password, role, shift_id, created_at, updated_at)
VALUES ('Admin Flexible', 'admin.flex@test.com', bcrypt('password'), 'admin', NULL, NOW(), NOW());
```

### 3. Credentials
| Role | Email | Password | Shift | Shift Time |
|------|-------|----------|-------|------------|
| Admin | admin.morning@test.com | password | Morning (1) | 09:00-17:00 |
| Kitchen | kitchen.evening@test.com | password | Evening (2) | 17:00-22:00 |
| Admin | admin.flex@test.com | password | None | Always |

---

## 🔍 Test Cases

### TEST 1: Normal Working Hours
**Objective**: User dapat akses saat di dalam jam kerja

**Setup**:
- User: Admin Morning (shift: 09:00-17:00)
- System Time: 10:30 AM
- Route: `/admin/dashboard`

**Steps**:
1. Login sebagai `admin.morning@test.com`
2. Navigate to `/admin/dashboard`

**Expected Result**:
```
✅ Access Granted
📨 No alert message
🎯 Dashboard loads normally
```

**Verification**:
- [ ] Dashboard visible
- [ ] No error/warning alert
- [ ] Sidebar functional
- [ ] Can navigate to other admin pages

---

### TEST 2: Early Access (Before Shift - Within Tolerance)
**Objective**: User dapat akses dengan warning saat 30 menit sebelum shift

**Setup**:
- User: Admin Morning (shift: 09:00-17:00)
- System Time: 08:50 AM (10 min sebelum shift)
- Route: `/admin/dashboard`

**Steps**:
1. Login sebagai `admin.morning@test.com`
2. Navigate to `/admin/dashboard`

**Expected Result**:
```
⏰ WARNING ALERT:
"Shift Anda belum dimulai. Mulai dalam 10 menit."

✅ Access Granted (despite warning)
🎯 Dashboard loads with amber alert
⏱️ Alert auto-dismiss setelah 4 detik
```

**Verification**:
- [ ] Amber/yellow alert visible
- [ ] Correct message with minute calculation
- [ ] Dashboard accessible
- [ ] Alert has close button
- [ ] Alert auto-dismisses

---

### TEST 3: Grace Period (After Shift - Within Tolerance)
**Objective**: User dapat akses dengan warning saat 30 menit setelah shift selesai

**Setup**:
- User: Kitchen Evening (shift: 17:00-22:00)
- System Time: 22:25 PM (25 min setelah shift)
- Route: `/dapur`

**Steps**:
1. Login sebagai `kitchen.evening@test.com`
2. Navigate to `/dapur` (kitchen)

**Expected Result**:
```
⏰ WARNING ALERT:
"Shift Anda sudah berakhir 25 menit lalu. Segera selesaikan pekerjaan."

✅ Access Granted (despite warning)
🎯 Kitchen page loads with amber alert
⏱️ Alert auto-dismiss setelah 4 detik
```

**Verification**:
- [ ] Amber/yellow alert visible
- [ ] Correct message with minute calculation (25 menit)
- [ ] Kitchen page accessible
- [ ] Alert has close button
- [ ] Alert auto-dismisses

---

### TEST 4: Outside Shift Hours (Blocked Access)
**Objective**: User tidak dapat akses saat completely outside shift + tolerance

**Setup**:
- User: Admin Morning (shift: 09:00-17:00, tolerance: 08:30-17:30)
- System Time: 18:00 PM (outside 17:30 tolerance)
- Route: `/admin/dashboard`

**Steps**:
1. Login sebagai `admin.morning@test.com`
2. Try navigate to `/admin/dashboard`

**Expected Result**:
```
❌ ERROR ALERT on home page:
"Anda tidak dalam jam kerja. Shift Anda: Morning (09:00 - 17:00 WIB)"

🔄 Redirect: `/` (home page)
⏱️ Alert auto-dismiss setelah 5 detik
```

**Verification**:
- [ ] Redirected to home page (/)
- [ ] Red error alert visible
- [ ] Correct shift info in message
- [ ] Dashboard NOT accessible
- [ ] Alert has close button
- [ ] Cannot access other protected routes either

---

### TEST 5: Night Shift (Crosses Midnight)
**Objective**: Night shift yang melintasi tengah malam bekerja dengan benar

**Setup**:
- User: Night Kitchen (shift: 22:00-06:00)
- System Time: 02:30 AM
- Route: `/dapur`

**Steps**:
1. Login sebagai user dengan night shift
2. Navigate to `/dapur` at 02:30 AM

**Expected Result**:
```
✅ Access Granted
📨 No alert message
🎯 Kitchen page loads normally
```

**Verification**:
- [ ] Kitchen page accessible
- [ ] No alert (within shift hours)
- [ ] Works correctly for midnight crossing

---

### TEST 6: Night Shift - Too Early
**Objective**: Night shift user blocked saat pagi (sebelum shift malam)

**Setup**:
- User: Night Kitchen (shift: 22:00-06:00)
- System Time: 08:00 AM (jauh dari shift)
- Route: `/dapur`

**Steps**:
1. Login sebagai user dengan night shift
2. Try navigate to `/dapur` at 08:00 AM

**Expected Result**:
```
❌ ERROR ALERT:
"Anda tidak dalam jam kerja. Shift Anda: Night (22:00 - 06:00 WIB)"

🔄 Redirect: `/`
```

**Verification**:
- [ ] Redirected to home
- [ ] Red error alert
- [ ] Cannot access kitchen page

---

### TEST 7: User Without Shift (Always Allowed)
**Objective**: User tanpa shift assignment dapat akses kapan saja

**Setup**:
- User: Admin Flexible (shift_id: NULL)
- System Time: Doesn't matter (bisa kapan saja)
- Route: `/admin/dashboard`

**Steps**:
1. Login sebagai `admin.flex@test.com`
2. Navigate to `/admin/dashboard` at any time
3. Try again at different times

**Expected Result**:
```
✅ Access Granted (no matter what time)
📨 No alert message
🎯 Dashboard loads normally
```

**Verification**:
- [ ] Always accessible
- [ ] No alerts ever shown
- [ ] Works at 03:00 AM
- [ ] Works at 20:00 PM
- [ ] Works at 10:00 AM

---

### TEST 8: Inactive Shift (Always Allowed)
**Objective**: User dengan inactive shift dapat akses (no validation)

**Setup**:
- Create shift dengan is_active = 0
- User assigned ke inactive shift
- System Time: Outside shift hours
- Route: `/admin/dashboard`

**Steps**:
1. Update shift: `UPDATE shifts SET is_active=0 WHERE id=1;`
2. Login as user dengan shift tersebut
3. Try navigate to admin

**Expected Result**:
```
✅ Access Granted (because shift inactive)
📨 No alert message
🎯 Dashboard loads normally
```

**Verification**:
- [ ] Access allowed despite system time
- [ ] No alerts
- [ ] Can still access all pages

---

### TEST 9: Logout & Redirect Behavior
**Objective**: Session handling bekerja dengan benar

**Setup**:
- User dengan valid shift
- Outside shift hours
- Try to access protected route

**Steps**:
1. Try access `/admin/dashboard` outside shift
2. Get error alert & redirect
3. Close alert
4. Verify error message is gone
5. Refresh page

**Expected Result**:
```
First Load:
❌ Error alert visible

After Manual Close:
✅ Alert dismissed (can be closed anytime)

After Page Refresh:
❌ Alert shown again (session still holds message)

After navigation away:
✅ Alert disappears (session cleared after display)
```

**Verification**:
- [ ] Manual close works
- [ ] Alert persists on refresh
- [ ] Alert clears after redirect

---

### TEST 10: Multiple Alert Types
**Objective**: Sistem alert untuk error/warning/success berfungsi proper

**Setup**:
- Various operations
- Observe alerts

**Steps**:
1. Trigger error (access outside shift)
2. Trigger warning (early access)
3. Trigger success (save user profile)
4. Check all display correctly

**Expected Result**:
```
❌ ERROR: Red, auto-dismiss 5s
⏰ WARNING: Amber, auto-dismiss 4s
✅ SUCCESS: Green, auto-dismiss 3s
```

**Verification**:
- [ ] Color coding correct
- [ ] Auto-dismiss timing different
- [ ] All have close buttons
- [ ] Proper icons displayed

---

## 🛠️ Manual Testing Checklist

### Pre-Test
- [ ] Clear Laravel cache: `php artisan optimize:clear`
- [ ] Database migrations run: `php artisan migrate`
- [ ] Test users created
- [ ] Shifts configured properly

### During Test
- [ ] Test each case in order
- [ ] Document any failures
- [ ] Check browser console for errors
- [ ] Verify database values

### Post-Test
- [ ] All tests passed: ✅
- [ ] No console errors: ✅
- [ ] Database clean: ✅
- [ ] Code committed: ✅

---

## 🔧 Debugging Tips

### If Tests Fail

**Issue**: Alert not showing
```bash
# Check session flash message
php artisan tinker
>>> session('error')
>>> session('warning')
>>> session('success')

# Check middleware execution
# Add in CheckShiftTime.php:
Log::info('Middleware executed', ['user' => $user?->id, 'shift' => $user?->shift_id]);
```

**Issue**: Always blocked even during shift
```bash
# Check shift data
SELECT * FROM shifts WHERE id = 1;

# Check user shift_id
SELECT id, name, shift_id FROM users WHERE id = 1;

# Check current time
php artisan tinker
>>> now()
```

**Issue**: Tolerance not working
```bash
# Check middleware calculation
// In CheckShiftTime.php, add:
\Log::info('Tolerance check', [
    'now' => $now,
    'start' => $startTime,
    'end' => $endTime,
    'tolerance_start' => $toleranceStart,
    'tolerance_end' => $toleranceEnd,
]);
```

**Issue**: Midnight shift not working
```bash
# Check shift order
if ($endTime < $startTime) {
    // This should trigger for night shifts
}

// Verify with:
SELECT * FROM shifts WHERE name = 'Night';
```

---

## 📊 Test Results Template

```markdown
## Test Results - [DATE]

### General Info
- Tester: [Your Name]
- Date: [YYYY-MM-DD]
- System Time: [Current Time]
- Laravel Version: 11.x
- PHP Version: 8.x

### Test Summary
- Total Tests: 10
- Passed: ✅ X
- Failed: ❌ Y
- Skipped: ⊘ Z

### Detailed Results

#### TEST 1: Normal Working Hours
- Status: [PASS/FAIL]
- Notes: [Any observations]

#### TEST 2: Early Access
- Status: [PASS/FAIL]
- Notes: [Any observations]

[... continue for all tests ...]

### Issues Found
1. [Issue description]
   - Steps to reproduce
   - Expected vs Actual
   - Severity: [Critical/High/Medium/Low]

### Recommendations
- [ ] Action item 1
- [ ] Action item 2

### Sign-Off
Tested by: [Name]
Date: [YYYY-MM-DD]
Approved: [Manager/Lead]
```

---

## 🚀 Next Steps

After successful testing:

1. **Deploy to Production**
   ```bash
   git add .
   git commit -m "feat: Add shift time protection system"
   git push origin main
   ```

2. **Monitor Logs**
   ```bash
   tail -f storage/logs/laravel.log
   ```

3. **Get User Feedback**
   - Admin feedback on workflow
   - Kitchen staff feedback on alerts
   - System stability check

4. **Collect Metrics**
   - How often users access outside shift?
   - Which shifts have issues?
   - False positive alerts?

