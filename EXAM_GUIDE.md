# Clinic Management System - Complete Exam Guide

## 📚 Study Guide for Laravel Exam

This is a complete, production-ready clinic management system built with Laravel. Use this project to understand and master all essential Laravel concepts for your exam.

---

## ⚡ Quick Start

```bash
cd clinic_system

# Install dependencies
composer install

# Setup environment
cp .env.example .env
php artisan key:generate

# Create database
touch database/database.sqlite

# Run migrations
php artisan migrate

# Start server
php artisan serve
```

Then visit: **http://localhost:8000**

---

## 📊 Features Implemented

### 1. **Patient Management**
- CRUD operations for patients
- Store medical history, contact info
- View appointment history per patient
- Validation for all inputs

### 2. **Doctor Management**
- Add/edit/view doctors
- Specialization and consultation fees
- Track availability status
- View schedules

### 3. **Appointment Scheduling** ⭐ (Critical for Exam)
```php
// KEY CONCEPT: Conflict Prevention
// Prevents double-booking for the same doctor
// Uses Carbon datetime comparison
```
- Schedule with specific date/time
- **PREVENTS OVERLAPPING APPOINTMENTS** ← Most Important
- Appointment statuses: Pending → Confirmed → Completed/Cancelled
- Reschedule with conflict checking
- Cancel restrictions based on status

### 4. **Billing & Payments**
- Record full/partial payments
- Auto-generate receipt numbers
- Track remaining balances
- Process refunds
- Generate revenue reports by doctor

### 5. **Admin Dashboard**
- Real-time statistics
- Today's revenue tracking
- Recent & upcoming appointments
- Centralized navigation

---

## 🔑 Key Exam Concepts to Master

### 1. **Model Relationships**
```php
// Patient.php
public function appointments(): HasMany {
    return $this->hasMany(Appointment::class);
}

// Appointment.php
public function patient(): BelongsTo {
    return $this->belongsTo(Patient::class);
}

// Doctor.php
public function appointments(): HasMany {
    return $this->hasMany(Appointment::class);
}
```

### 2. **Form Validation** (MUST Know)
```php
$validated = $request->validate([
    'first_name' => 'required|string|max:100',
    'email' => 'required|email|unique:patients',
    'phone' => 'required|string|max:15',
    'date_of_birth' => 'required|date',
    'gender' => 'required|in:Male,Female,Other',
]);

Patient::create($validated);
```

### 3. **Conflict Prevention Logic** ⭐ CRITICAL ⭐
```php
// IN: AppointmentController::store()
$appointmentDateTime = Carbon::parse($validated['appointment_date_time']);

// Check if doctor has appointments within ±30 minutes
$exists = Appointment::where('doctor_id', $validated['doctor_id'])
    ->where('status', '!=', 'cancelled')
    ->whereBetween('appointment_date_time', [
        $appointmentDateTime->copy()->subMinutes(30),
        $appointmentDateTime->copy()->addMinutes(30)
    ])
    ->exists();

if ($exists) {
    return back()->with('error', 'Doctor is not available at this time.');
}
```

### 4. **Status Management**
```php
// Appointments can only be rescheduled if:
public function canReschedule() {
    return in_array($this->status, ['pending', 'confirmed']);
}

// Can only be cancelled if:
public function canBeCancelled() {
    return in_array($this->status, ['pending', 'confirmed']);
}
```

### 5. **Payment Tracking**
```php
// Calculate remaining balance
$remainingBalance = max(0, $consultationFee - $payment);

// Status depends on balance
$paymentStatus = $remainingBalance > 0 ? 'partial' : 'completed';
```

---

## 🗂️ Project Structure

```
clinic_system/
├── app/Models/
│   ├── Patient.php          ← Patient model with relationships
│   ├── Doctor.php           ← Doctor model
│   ├── Appointment.php      ← Appointment with conflict logic
│   └── Transaction.php      ← Payment tracking
│
├── app/Http/Controllers/
│   ├── PatientController.php
│   ├── DoctorController.php
│   ├── AppointmentController.php  ← Study this carefully!
│   ├── TransactionController.php
│   └── DashboardController.php
│
├── database/migrations/
│   ├── create_patients_table.php
│   ├── create_doctors_table.php
│   ├── create_appointments_table.php
│   └── create_transactions_table.php
│
├── resources/views/
│   ├── layouts/app.blade.php      ← Master layout
│   ├── dashboard.blade.php
│   ├── patients/                  ← Patient views (index, create, edit, show)
│   ├── doctors/                   ← Doctor views
│   ├── appointments/              ← Appointment views
│   └── transactions/              ← Billing views
│
└── routes/web.php                 ← All routes defined here
```

---

## 🗄️ Database Schema

### PATIENTS
```
id | first_name | last_name | email | phone | date_of_birth | gender | address | medical_history | emergency_contact | timestamps
```

### DOCTORS
```
id | first_name | last_name | email | phone | specialization | consultation_fee | license_number | bio | status | timestamps
```

### APPOINTMENTS
```
id | patient_id | doctor_id | appointment_date_time | status | notes | reason_for_visit | consultation_fee | timestamps
```

### TRANSACTIONS
```
id | appointment_id | patient_id | amount | payment_method | payment_status | description | remaining_balance | receipt_number | timestamps
```

---

## 📝 Important File References

### For Appointment Logic Study
- [AppointmentController.php](app/Http/Controllers/AppointmentController.php) - See `store()` and `update()` methods
- [Appointment Model](app/Models/Appointment.php) - See `canReschedule()` and `canBeCancelled()` methods

### For Payment Logic Study
- [TransactionController.php](app/Http/Controllers/TransactionController.php) - See `store()` and `report()` methods
- [Transaction Model](app/Models/Transaction.php) - See `generateReceiptNumber()` method

### For Validation Study
- [PatientController.php](app/Http/Controllers/PatientController.php) - See `store()` method
- [DoctorController.php](app/Http/Controllers/DoctorController.php) - See `store()` method

---

## 🎓 Exam Questions You Should Be Able to Answer

1. **What tables are in the database and how do they relate?**
   - Answer: 4 tables with HasMany and BelongsTo relationships

2. **How does the system prevent double-booking?**
   - Answer: Check for appointments within ±30 minutes using whereBetween()

3. **What are the appointment statuses and how do they flow?**
   - Answer: pending → confirmed/cancelled → completed

4. **Which appointments can be rescheduled?**
   - Answer: Only pending and confirmed appointments

5. **How is remaining balance calculated for payments?**
   - Answer: `max(0, consultationFee - paymentAmount)`

6. **What validation rules are required?**
   - Answer: Required fields, unique emails, numeric fees, valid dates

7. **How are receipts generated?**
   - Answer: `RCP-YYYYMMDD-XXXXX` format using transaction model method

8. **What is the dashboard showing?**
   - Answer: Today's stats (appointments, patients, doctors, revenue) + recent/upcoming appointments

---

## 🔧 Common Coding Patterns in This Project

### Pattern 1: CRUD Operations
```php
// All controllers follow this pattern
public function index() { /* List all */ }
public function create() { /* Show form */ }
public function store() { /* Save to DB + Validate */ }
public function show() { /* View details */ }
public function edit() { /* Show edit form */ }
public function update() { /* Update DB */ }
public function destroy() { /* Delete */ }
```

### Pattern 2: Form Validation
```php
$validated = $request->validate([...]);
Model::create($validated);
return redirect()->route(...)->with('success', '...');
```

### Pattern 3: Business Logic in Controller
```php
// Check conflict before creating
if ($conflictExists) {
    return back()->with('error', '...');
}

// Create with additional data
$data['status'] = 'pending';
$data['consultation_fee'] = Doctor::find(...)->consultation_fee;
Model::create($data);
```

### Pattern 4: Blade View with Form
```blade
@extends('layouts.app')
@section('title', 'Page Title')
@section('content')
    <form method="POST" action="{{ route(...) }}">
        @csrf
        <input type="text" name="field" value="{{ old('field') }}" required>
        @error('field')
            <span>{{ $message }}</span>
        @enderror
        <button type="submit">Save</button>
    </form>
@endsection
```

---

## 📋 Validation Rules Reference

| Model | Field | Rules |
|-------|-------|-------|
| Patient | first_name | required, string, max:100 |
| Patient | email | required, email, unique:patients |
| Patient | phone | required, string, max:15 |
| Patient | date_of_birth | required, date |
| Patient | gender | required, in:Male,Female,Other |
| Doctor | specialization | required, string, max:100 |
| Doctor | consultation_fee | required, numeric, min:0 |
| Doctor | license_number | required, string, unique:doctors |
| Appointment | appointment_date_time | required, date_time, after:now |
| Appointment | doctor_id | required, exists:doctors,id |
| Appointment | patient_id | required, exists:patients,id |
| Transaction | amount | required, numeric, min:0.01 |
| Transaction | payment_method | required, in:cash,card,online |

---

## 🚀 Tips for Passing Your Exam

1. **Understand the Conflict Prevention Logic**
   - This is the most important part of the project
   - Practice writing this query yourself

2. **Know Your Migrations**
   - Study the table structure
   - Understand relationships (foreign keys)

3. **Master Form Validation**
   - Know all common validation rules
   - Understand unique validation with parameters

4. **Study Blade Templating**
   - Old values in forms: `value="{{ old('field') }}"`
   - Error display: `@error()` and `@enderror`
   - Loops: `@foreach` and `@endforeach`

5. **Practice Redirects & Messaging**
   - `redirect()->route()` syntax
   - Flash messages: `->with('success', '...')`

6. **Payment Logic**
   - How partial payments are handled
   - How remaining balance is calculated
   - Receipt number generation

---

## 📚 Key Laravel Concepts Tested

- ✅ Models & Relationships
- ✅ Migrations
- ✅ Controllers (Resource Controllers)
- ✅ Routing (RESTful Routes)
- ✅ Form Validation
- ✅ Views & Blade Templating
- ✅ Database Queries (Eloquent ORM)
- ✅ DateTime Handling (Carbon)
- ✅ Flash Messages

---

## 🐛 Debugging Tips

```php
// In controllers, use dd() to debug
dd($validated);  // Dump and die
dd($appointment); // See object data

// Or use dump() to continue execution
dump($data);
```

```blade
<!-- In views, use @dd() in Blade -->
@dd($appointments)

<!-- Or display data -->
{{ $appointment->appointment_date_time->format('M d, Y') }}
```

---

## 💡 How to Study This Project

1. **Day 1**: Read through all models and understand relationships
2. **Day 2**: Study the appointment conflict prevention logic
3. **Day 3**: Practice writing validation rules
4. **Day 4**: Trace a complete flow (add patient → schedule appointment → record payment)
5. **Day 5**: Try to modify/add a new feature

---

## 🎯 Final Exam Checklist

- [ ] Understand all 4 models and their relationships
- [ ] Know how conflict prevention works
- [ ] Can write basic CRUD operations
- [ ] Understand validation rules
- [ ] Know how to create and update records
- [ ] Can read and write Blade templates
- [ ] Know routing conventions
- [ ] Understand status workflow for appointments
- [ ] Know how payments are tracked
- [ ] Can generate reports (queries)

---

**Good luck with your exam! Remember: understand the concepts, don't just memorize the code! 🚀**

For questions: Study the controllers and models - they're well-commented.
