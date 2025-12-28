# 📊 SHIFT TIME PROTECTION - VISUAL DIAGRAMS & REFERENCE

## 🎬 Request Flow Diagram

```
                    USER REQUEST
                         ↓
              GET /admin/dashboard
                    (or /dapur)
                         ↓
        ┌─────────────────────────────────┐
        │    Middleware Stack Execution    │
        └─────────────────────────────────┘
                         ↓
        ┌──────────────────────────────────┐
        │  1. EnsureUserIsAuthenticated    │
        │     (auth.custom)                │
        └──────────────────────────────────┘
              ↓              ↓
           ✅ OK         ❌ NOT AUTH
            │              │
            ↓              ↓
         Continue    Redirect /login
            │
            ↓
        ┌──────────────────────────────────┐
        │  2. RoleMiddleware               │
        │     (role:admin|kitchen)         │
        └──────────────────────────────────┘
              ↓              ↓
           ✅ OK         ❌ WRONG ROLE
            │              │
            ↓              ↓
         Continue    Redirect / + Error
            │
            ↓
        ┌──────────────────────────────────┐
        │  3. CheckShiftTime ⭐ NEW        │
        │     (check.shift.time)           │
        └──────────────────────────────────┘
              ↓                          ↓
           ✅ OK              ⏰ WARNING or ❌ ERROR
            │                          │
            ↓                          ↓
         Continue              Flash Message
            │                          │
            ↓                          ↓
      Destination              Redirect / + Alert
      Page Loaded
```

---

## ⏰ Shift Time Windows Visualization

### Normal Shift (09:00 - 17:00)
```
Timeline:
────────────────────────────────────────────────────────────
08:00  08:30  09:00  10:00  12:00  14:00  17:00  17:30  18:00
       │      │                                   │      │
       ↓      ↓                                   ↓      ↓
     START   GRACE                              GRACE   END
   TOLERANCE  EARLY                             LATE   TOLERANCE
   BEGINS     ACCESS                            GRACE  ENDS
             ALLOWED                           ALLOWED

Color Legend:
🔴 = Blocked (No Access)
🟡 = Warning (Allowed with Alert)
🟢 = Normal (Allowed, No Alert)

08:00-08:30  🔴 Blocked
08:30-09:00  🟡 Warning: "Mulai dalam X menit"
09:00-17:00  🟢 Normal
17:00-17:30  🟡 Warning: "Sudah berakhir X menit"
17:30-18:00+ 🔴 Blocked
```

### Night Shift (22:00 - 06:00)
```
Previous Day                    Current Day
─────────────────────────────────────────────────────────────
20:00  21:30  22:00  02:00  04:00  06:00  06:30  08:00
                │                   │      │
                ↓                   ↓      ↓
              START                GRACE   END
            TOLERANCE              LATE   TOLERANCE
            BEGINS               GRACE    ENDS
                                ALLOWED

21:30-22:00  🟡 Warning
22:00-06:00  🟢 Normal (spans midnight)
06:00-06:30  🟡 Warning
Other times  🔴 Blocked
```

---

## 🔄 Shift Transition Timeline

### Morning Shift (09:00-17:00) to Evening Shift (17:00-22:00)

```
Morning Shift End → Grace Period → Evening Shift Begin

17:00  17:30      18:00        17:00      17:30      18:00      22:00
 │      │          │            │          │          │          │
 │    Grace       Blocked      Evening    Grace       Can       Evening
 │     End                      Shift     Begins      Start     Shift
 │                              Begin     (17:00)     Work      Starts

User Morning:
17:00-17:30  🟡 Can still finish morning shift (grace)

User Evening:
17:00-17:30  🟡 Can start early (grace before 17:00)
17:30+       🟢 Normal operation
```

---

## 📍 Middleware Execution Chain

```
REQUEST → KERNEL → BEFORE MIDDLEWARE → ROUTE → AFTER MIDDLEWARE → RESPONSE
                        ↓
                   ┌────────────────────────────────────┐
                   │  Route Middleware Groups Applied   │
                   └────────────────────────────────────┘
                        ↓                   ↓
              Kitchen Routes          Admin Routes
              [auth.custom]           [auth.custom]
              [role:kitchen]          [role:admin]
              [check.shift.time]  ← [check.shift.time]
```

---

## 🧩 Database Schema References

### Shifts Table
```
┌─────────────────────────────────┐
│         SHIFTS TABLE            │
├─────────────────────────────────┤
│ id (PK)        │ 1, 2, 3...     │
├─────────────────────────────────┤
│ name           │ Morning, Evening, Night
├─────────────────────────────────┤
│ start_time     │ 09:00:00, 17:00:00, 22:00:00
├─────────────────────────────────┤
│ end_time       │ 17:00:00, 22:00:00, 06:00:00
├─────────────────────────────────┤
│ is_active      │ 1 (true) or 0 (false)
├─────────────────────────────────┤
│ created_at     │ timestamp
├─────────────────────────────────┤
│ updated_at     │ timestamp
└─────────────────────────────────┘
```

### Users Table (Relevant Fields)
```
┌─────────────────────────────────┐
│         USERS TABLE             │
├─────────────────────────────────┤
│ id (PK)        │ 1, 2, 3...     │
├─────────────────────────────────┤
│ name           │ User name
├─────────────────────────────────┤
│ email          │ user@example.com
├─────────────────────────────────┤
│ password       │ hashed password
├─────────────────────────────────┤
│ role           │ admin or kitchen
├─────────────────────────────────┤
│ shift_id (FK)  │ references shifts.id or NULL
├─────────────────────────────────┤
│ created_at     │ timestamp
├─────────────────────────────────┤
│ updated_at     │ timestamp
└─────────────────────────────────┘

Relationship:
User.shift_id → Shift.id
```

---

## 🎨 Alert Component Structure

### Error Alert (Red)
```
┌─────────────────────────────────────────────────────────┐
│ ❌ │ Anda tidak dalam jam kerja. Shift...           [×] │
└─────────────────────────────────────────────────────────┘
     └─ Icon │ Message Content │ Close Button
             
Properties:
- Background: Red/Error color (#ef4444)
- Icon: ri-alert-fill
- Auto-dismiss: 5000ms (5 seconds)
- Manual close: Available
- Priority: HIGHEST
```

### Warning Alert (Amber)
```
┌─────────────────────────────────────────────────────────┐
│ ⏰ │ Shift Anda belum dimulai. Mulai dalam...        [×] │
└─────────────────────────────────────────────────────────┘
     └─ Icon │ Message Content │ Close Button
             
Properties:
- Background: Amber/Warning color (#f59e0b)
- Icon: ri-alert-line
- Auto-dismiss: 4000ms (4 seconds)
- Manual close: Available
- Priority: MEDIUM
```

### Success Alert (Green)
```
┌─────────────────────────────────────────────────────────┐
│ ✅ │ Data berhasil diperbarui!                      [×] │
└─────────────────────────────────────────────────────────┘
     └─ Icon │ Message Content │ Close Button
             
Properties:
- Background: Green/Success color (#10b981)
- Icon: ri-checkbox-circle-fill
- Auto-dismiss: 3000ms (3 seconds)
- Manual close: Available
- Priority: LOW
```

---

## 🔀 Decision Tree

```
USER ATTEMPTS ACCESS TO /admin or /dapur
            │
            ├─── Is user authenticated?
            │    ├─ NO → Redirect /login
            │    └─ YES ↓
            │
            ├─── Does user have correct role?
            │    ├─ NO → Redirect / (error: wrong role)
            │    └─ YES ↓
            │
            ├─── Does user have shift_id assigned?
            │    ├─ NO → ALLOW ✅ (no time check)
            │    └─ YES ↓
            │
            ├─── Is user's shift active?
            │    ├─ NO → ALLOW ✅ (no time check)
            │    └─ YES ↓
            │
            ├─── Is current time within shift ± 30 min?
            │    │
            │    ├─ BEFORE shift (> 30 min):
            │    │  └─ BLOCK ❌
            │    │     Flash: error message
            │    │     Action: redirect / + alert
            │    │
            │    ├─ BEFORE shift (0-30 min):
            │    │  └─ ALLOW ⏰
            │    │     Flash: warning (early access)
            │    │     Action: continue + alert
            │    │
            │    ├─ DURING shift:
            │    │  └─ ALLOW ✅
            │    │     Flash: (no message)
            │    │     Action: continue normally
            │    │
            │    ├─ AFTER shift (0-30 min):
            │    │  └─ ALLOW ⏰
            │    │     Flash: warning (late access)
            │    │     Action: continue + alert
            │    │
            │    └─ AFTER shift (> 30 min):
            │       └─ BLOCK ❌
            │          Flash: error message
            │          Action: redirect / + alert
            │
            └─ DESTINATION PAGE LOADS
```

---

## 📈 State Diagram

```
                ┌─────────────────┐
                │  User Requests  │
                │  /admin or      │
                │  /dapur         │
                └────────┬────────┘
                         │
                         ↓
            ┌────────────────────────┐
            │  Middleware Chain      │
            │  Checks Auth & Role    │
            └────────────┬───────────┘
                         │
              ┌──────────┴──────────┐
              ↓                     ↓
         ✅ OK               ❌ FAILED
              │                    │
              ↓                    ↓
        ┌────────────┐      Redirect
        │ Check Shift│      with Error
        │ Time       │
        └─────┬──────┘
              │
    ┌─────────┼─────────┐
    ↓         ↓         ↓
   ✅        ⏰        ❌
   OK      WARNING    BLOCKED
   │         │         │
   ↓         ↓         ↓
 ALLOW     ALLOW    REDIRECT
 NO ALERT + WARN    + ERROR
           ALERT    ALERT

STATE TRANSITIONS:
✅ → ALLOW (immediate access)
⏰ → ALLOW (with warning message)
❌ → REDIRECT (with error message)
```

---

## 🎯 Message Template Map

```
┌─────────────────────────────────────────────────────────────┐
│  Message Type       Template                Color            │
├─────────────────────────────────────────────────────────────┤
│
│  BLOCKED            "Anda tidak dalam jam kerja.             │
│  (ERROR)            Shift Anda: {name}                       │
│                     ({start} - {end} WIB)"                   │
│                                                    🔴 RED    │
│
│  EARLY ACCESS       "⏰ Shift Anda belum dimulai.             │
│  (WARNING)          Mulai dalam {minutes} menit."            │
│                                                    🟡 AMBER  │
│
│  LATE ACCESS        "⏰ Shift Anda sudah berakhir             │
│  (WARNING)          {minutes} menit lalu.                    │
│                     Segera selesaikan pekerjaan."            │
│                                                    🟡 AMBER  │
│
│  NORMAL             (No message)                   🟢 GREEN  │
│  (SUCCESS)                                                   │
│
└─────────────────────────────────────────────────────────────┘
```

---

## 🔗 File Dependency Map

```
routes/web.php
  │
  ├─→ applies middleware: 'check.shift.time'
  │   │
  │   └─→ references in bootstrap/app.php
  │
  └─→ protected routes
      ├─ /admin/*
      └─ /dapur


bootstrap/app.php
  │
  └─→ registers middleware alias
      └─→ \App\Http\Middleware\CheckShiftTime


app/Http/Middleware/CheckShiftTime.php
  │
  ├─→ uses User model
  ├─→ uses Shift model
  ├─→ uses Carbon for time
  └─→ returns session flash messages


resources/views/layouts/admin.blade.php
  │
  └─→ displays alert components
      ├─ @if(session('error'))
      ├─ @if(session('warning'))
      └─ @if(session('success'))


resources/views/layouts/app.blade.php
  │
  └─→ displays alert components (same structure)
      ├─ @if(session('error'))
      ├─ @if(session('warning'))
      └─ @if(session('success'))
```

---

## 📱 Responsive Design Breakpoints

```
Alert System Responsive:

Mobile (< 640px):
┌──────────────────┐
│ ❌ Anda tidak    │
│    dalam jam     │
│    kerja...  [×] │
└──────────────────┘

Tablet (640px - 1024px):
┌────────────────────────────────┐
│ ❌ Anda tidak dalam jam kerja.. [×]│
└────────────────────────────────┘

Desktop (> 1024px):
┌─────────────────────────────────────────────────┐
│ ❌ Anda tidak dalam jam kerja. Shift: [Info]  [×]│
└─────────────────────────────────────────────────┘

All versions:
- Readable text
- Clickable close button
- Auto-dismiss functional
- Proper spacing & padding
```

---

## ⚙️ Configuration Reference

```
CONFIGURABLE ITEMS:

1. Tolerance Duration (Minutes)
   Location: app/Http/Middleware/CheckShiftTime.php
   Line: 45-46
   Default: 30 minutes
   
2. Error Alert Duration (Milliseconds)
   Location: resources/views/layouts/admin.blade.php
   Line: (error alert)
   Default: 5000ms
   
3. Warning Alert Duration (Milliseconds)
   Location: resources/views/layouts/admin.blade.php
   Line: (warning alert)
   Default: 4000ms
   
4. Success Alert Duration (Milliseconds)
   Location: resources/views/layouts/admin.blade.php
   Line: (success alert)
   Default: 3000ms
```

---

## 📊 Color Scheme

```
Alert Colors:
├─ ERROR (❌ Red):      #ef4444 with #10b981 accent
├─ WARNING (⏰ Amber):  #f59e0b with #f97316 accent
└─ SUCCESS (✅ Green):  #10b981 with #059669 accent

Dark Mode Support:
├─ ERROR:   bg-red-500/10, border-red-500/30, text-red-400
├─ WARNING: bg-amber-500/10, border-amber-500/30, text-amber-400
└─ SUCCESS: bg-emerald-500/10, border-emerald-500/30, text-emerald-400

Icons (Remix Icon):
├─ ERROR:   ri-alert-fill
├─ WARNING: ri-alert-line
├─ SUCCESS: ri-checkbox-circle-fill
└─ CLOSE:   ri-close-line
```

---

## 🔄 Session Data Flow

```
[Request] → [Middleware Check]
                    ↓
            ┌───────┴────────┐
            ↓                ↓
         ALLOWED         BLOCKED/WARNING
            │                │
            ↓                ↓
      [No Session]    [Flash Message]
            │                │
            ↓                ↓
      [Next Page]     [Session Storage]
                             │
                             ↓
                       [Redirect/Continue]
                             │
                             ↓
                       [View Renders]
                             │
                             ↓
                       [Check Session]
                             │
                             ↓
                       [Display Alert]
                             │
                             ↓
                       [Auto-dismiss or
                        Manual close]
```

---

**This visual reference guide complements the detailed documentation.**  
**For implementation details, see IMPLEMENTATION_GUIDE.md**  
**For testing procedures, see TESTING_GUIDE.md**

