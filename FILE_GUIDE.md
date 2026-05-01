# 📚 Complete Project File Guide

## Study Guide - Where Everything Is

This guide shows you exactly where each component is located and what to study.

---

## 📖 Documentation Files (Read These First!)

```
START_HERE.md           ← 👈 BEGIN HERE! Quick overview
SETUP.md                ← Installation & troubleshooting
EXAM_GUIDE.md           ← Complete study material
QUICK_REFERENCE.md      ← Code snippets cheat sheet
FILE_GUIDE.md           ← This file (you are here)
```

**Study Order:**
1. START_HERE.md (10 min)
2. SETUP.md (Setup project)
3. EXAM_GUIDE.md (Deep learning)
4. QUICK_REFERENCE.md (Code patterns)

---

## 🗄️ Database Files

### Migrations (Define database tables)
```
database/migrations/
├── 2026_05_01_080017_create_patients_table.php
│   └── Columns: first_name, last_name, email, phone, date_of_birth, gender, address, medical_history, emergency_contact
│
├── 2026_05_01_080018_create_doctors_table.php
│   └── Columns: first_name, last_name, email, phone, specialization, consultation_fee, license_number, bio, status
│
├── 2026_05_01_080018_create_appointments_table.php
│   └── Columns: patient_id (FK), doctor_id (FK), appointment_date_time, status, notes, reason_for_visit, consultation_fee
│   └── KEY: Foreign keys link to patients and doctors
│
└── 2026_05_01_080019_create_transactions_table.php
    └── Columns: appointment_id (FK), patient_id (FK), amount, payment_method, payment_status, remaining_balance, receipt_number
    └── KEY: Tracks payments and balances
```

**What to Study:**
- How foreign keys create relationships
- Why status field is important
- How consultation_fee is stored

---

## 🧩 Models (Business Logic)

### `app/Models/Patient.php`
```php
Relationships:
  ├── appointments() → HasMany → Appointment
  └── transactions() → HasMany → Transaction

Attributes: first_name, last_name, email, phone, date_of_birth, gender, address, medical_history, emergency_contact

Methods:
  └── getFullNameAttribute() → Returns "first_name last_name"
```

**Study:** 
- HasMany relationships
- Fillable attributes

---

### `app/Models/Doctor.php`
```php
Relationships:
  └── appointments() → HasMany → Appointment

Attributes: first_name, last_name, email, phone, specialization, consultation_fee, license_number, bio, status

Methods:
  └── getFullNameAttribute() → Returns "first_name last_name"
  └── getAvailableSlots($date) → Returns available time slots
```

**Study:**
- Decimal casting for money
- Custom methods

---

### `app/Models/Appointment.php` ⭐ MOST IMPORTANT
```php
Relationships:
  ├── patient() → BelongsTo → Patient
  ├── doctor() → BelongsTo → Doctor
  └── transaction() → HasOne → Transaction

Attributes: patient_id, doctor_id, appointment_date_time, status, notes, reason_for_visit, consultation_fee

Methods:
  ├── canReschedule() → Checks if pending or confirmed
  └── canBeCancelled() → Checks if pending or confirmed
```

**Study:**
- BelongsTo relationships
- Status workflow logic
- Constraints on operations

---

### `app/Models/Transaction.php`
```php
Relationships:
  ├── appointment() → BelongsTo → Appointment
  └── patient() → BelongsTo → Patient

Attributes: appointment_id, patient_id, amount, payment_method, payment_status, description, remaining_balance, receipt_number

Methods:
  └── generateReceiptNumber() → Creates "RCP-YYYYMMDD-XXXXX" format
```

**Study:**
- How receipts are generated
- Decimal casting for money

---

## 🎮 Controllers (Request Handling)

### `app/Http/Controllers/PatientController.php`
- CRUD operations for patients
- **Key Methods:**
  - `store()` - Validates and creates patient
  - `update()` - Updates patient with unique email exception

**Study:** 
- Basic CRUD pattern
- Form validation with unique rules

---

### `app/Http/Controllers/DoctorController.php`
- CRUD operations for doctors
- **Key Methods:**
  - `store()` - Validates and creates doctor
  - `show()` - Shows doctor with appointments

**Study:**
- Relationships in views
- Displaying related data

---

### `app/Http/Controllers/AppointmentController.php` ⭐⭐⭐ MOST IMPORTANT
- **Key Methods:**
  - `store()` - ⭐ CONFLICT PREVENTION LOGIC HERE
    - Checks for overlapping appointments
    - Uses `whereBetween()` for ±30 minute check
  - `update()` - Reschedules with conflict checking
  - `cancel()` - Only allowed for pending/confirmed
  - `confirm()` - Marks as confirmed

**Study:**
- Conflict prevention algorithm
- Status constraints
- DateTime handling with Carbon

---

### `app/Http/Controllers/TransactionController.php`
- Payment recording and reporting
- **Key Methods:**
  - `store()` - Records payment with balance calculation
  - `refund()` - Reverses payment
  - `report()` - Generates revenue statistics

**Study:**
- Payment status logic
- Revenue calculations
- Grouping and sum queries

---

### `app/Http/Controllers/DashboardController.php`
- Dashboard statistics
- **Key Methods:**
  - `index()` - Shows today's stats and upcoming appointments

**Study:**
- Aggregation queries
- Dashboard data collection

---

## 🎨 Views (User Interface)

### Master Layout
```
resources/views/layouts/app.blade.php
├── Sidebar navigation
├── Alert messages (success/error)
├── Bootstrap styling
└── @yield('content') for page content
```

**Study:**
- How @extends() works
- How @section() works
- Alert display pattern

---

### Dashboard
```
resources/views/dashboard.blade.php
├── Statistics cards (today's appointments, revenue, etc.)
└── Tables showing recent and upcoming appointments
```

**Study:**
- Data display patterns
- Number formatting

---

### Patient Views
```
resources/views/patients/
├── index.blade.php      → List all patients with actions
├── create.blade.php     → Form to add new patient
├── edit.blade.php       → Form to edit patient
└── show.blade.php       → Patient details + appointments
```

**Study:**
- Form patterns with old() for validation feedback
- @error() directive
- Loops and conditionals
- Delete forms with CSRF

---

### Doctor Views
```
resources/views/doctors/
├── index.blade.php      → List all doctors with fees and status
├── create.blade.php     → Form to add new doctor
├── edit.blade.php       → Form to edit doctor
└── show.blade.php       → Doctor details + appointments
```

**Study:**
- Same patterns as patients
- Displaying money with number_format()

---

### Appointment Views ⭐ IMPORTANT FOR EXAM
```
resources/views/appointments/
├── index.blade.php      → List with status badges and action buttons
├── create.blade.php     → Form to schedule appointment
│   └── Shows patients and doctors dropdowns
├── edit.blade.php       → Form to reschedule
└── show.blade.php       → Appointment details + payment info
```

**Study:**
- Conditional buttons based on status
- Dropdown select fields
- DateTime input field

---

### Transaction Views
```
resources/views/transactions/
├── index.blade.php      → List all payments with status
├── create.blade.php     → Form to record payment
│   └── Dropdown of completed appointments
├── show.blade.php       → Payment details + receipt
└── report.blade.php     → Revenue by doctor
```

**Study:**
- Payment status badges
- Table grouping in reports
- Receipt display

---

## 🛣️ Routes

```
routes/web.php
├── GET  /                          → dashboard
├── GET  /patients                  → patients.index
├── POST /patients                  → patients.store
├── GET  /patients/{id}             → patients.show
├── PUT  /patients/{id}             → patients.update
├── DELETE /patients/{id}           → patients.destroy
├── (same pattern for doctors)
├── GET  /appointments              → appointments.index
├── POST /appointments              → appointments.store
├── PATCH /appointments/{id}/confirm → appointments.confirm
├── PATCH /appointments/{id}/cancel → appointments.cancel
├── GET  /transactions              → transactions.index
├── PATCH /transactions/{id}/refund → transactions.refund
└── GET  /transactions-report       → transactions.report
```

**Study:**
- RESTful routing conventions
- Custom routes for actions (confirm, cancel, refund)
- Resource routes shorthand

---

## ⚙️ Configuration Files

```
.env                    ← Database and app configuration (EDIT THIS!)
composer.json           ← PHP dependencies
config/                 ← App configuration files (usually don't edit)
```

**What to Study:**
- How `.env` stores secrets
- DB_CONNECTION setting

---

## 📊 Key Study Paths

### Path 1: Understand Database
1. Read migrations in `database/migrations/`
2. Study model relationships
3. Understand foreign keys

### Path 2: Understand Business Logic
1. Study `AppointmentController::store()` - Conflict prevention
2. Study `TransactionController::store()` - Payment calculation
3. Study appointment status constraints

### Path 3: Understand Views
1. Study form patterns (create.blade.php)
2. Study data display (index.blade.php)
3. Study conditional rendering

### Path 4: Full Application Flow
1. User adds patient → PatientController::store()
2. User schedules appointment → AppointmentController::store() (with conflict check)
3. User records payment → TransactionController::store() (with balance calc)
4. View in dashboard → DashboardController::index()

---

## 💾 File Size Summary

| Type | Count | Size |
|------|-------|------|
| Models | 4 | ~200 lines each |
| Controllers | 5 | ~300-400 lines each |
| Migrations | 4 | ~40 lines each |
| Views | 15+ | ~150 lines each |
| Routes | 1 | ~25 lines |
| Docs | 5 | ~1000 lines each |

---

## 🎯 Quick File Lookup

### "How do I validate forms?"
→ See any `store()` or `update()` method in `app/Http/Controllers/`

### "How do I prevent conflicts?"
→ See `AppointmentController::store()` method

### "How do I display related data?"
→ See any `show()` view file

### "How do I calculate remaining balance?"
→ See `TransactionController::store()` method

### "How do I handle status changes?"
→ See `Appointment model` methods

### "How do I display forms?"
→ See any `create.blade.php` file

### "How do I display lists?"
→ See any `index.blade.php` file

---

## 📝 Study Checklist

- [ ] Read all 5 documentation files
- [ ] Run the project successfully
- [ ] Understand all 4 models and relationships
- [ ] Study conflict prevention logic in AppointmentController
- [ ] Understand payment balance calculation
- [ ] Study form validation patterns
- [ ] Understand status workflow for appointments
- [ ] Can trace a complete request flow (user action → controller → database → view)
- [ ] Can explain what each controller method does
- [ ] Can write basic validation rules

---

**Once you've studied this structure, you'll understand the entire application! 🎓**

Good luck with your exam! 🚀
