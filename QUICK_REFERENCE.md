# Laravel Clinic System - Quick Reference Cheat Sheet

## 🚀 Quick Start Commands

```bash
# Setup
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
php artisan serve

# Visit: http://localhost:8000
```

---

## 📋 Routes Quick Reference

```
Dashboard:    GET  /                        dashboard
Patients:     GET  /patients                patients.index
              POST /patients                patients.store
              GET  /patients/{id}           patients.show
              PUT  /patients/{id}           patients.update
              DELETE /patients/{id}         patients.destroy

Doctors:      Similar RESTful pattern      doctors.*
Appointments: Similar RESTful pattern      appointments.*
              PATCH /appointments/{id}/confirm
              PATCH /appointments/{id}/cancel

Transactions: Similar RESTful pattern      transactions.*
              PATCH /transactions/{id}/refund
              GET  /transactions-report    transactions.report
```

---

## 🔑 Most Important Code Snippets

### Validation Template
```php
$validated = $request->validate([
    'field_name' => 'required|string|max:100',
    'email' => 'required|email|unique:table_name',
    'amount' => 'required|numeric|min:0.01',
    'status' => 'required|in:pending,confirmed,completed',
    'date' => 'required|date|after:now',
]);
```

### Conflict Prevention (The KEY Logic)
```php
use Carbon\Carbon;

$appointmentDateTime = Carbon::parse($request->appointment_date_time);

$exists = Appointment::where('doctor_id', $doctor_id)
    ->where('status', '!=', 'cancelled')
    ->whereBetween('appointment_date_time', [
        $appointmentDateTime->copy()->subMinutes(30),
        $appointmentDateTime->copy()->addMinutes(30)
    ])
    ->exists();

if ($exists) {
    return back()->with('error', 'Time slot not available');
}
```

### Create with Auto-Calculated Fields
```php
$appointment = Appointment::create([
    'patient_id' => $validated['patient_id'],
    'doctor_id' => $validated['doctor_id'],
    'appointment_date_time' => $validated['appointment_date_time'],
    'reason_for_visit' => $validated['reason_for_visit'],
    'consultation_fee' => Doctor::find($validated['doctor_id'])->consultation_fee,
    'status' => 'pending',
]);
```

### Payment with Remaining Balance
```php
$consultation_fee = $appointment->consultation_fee;
$payment_amount = $request->amount;
$remaining_balance = max(0, $consultation_fee - $payment_amount);
$payment_status = $remaining_balance > 0 ? 'partial' : 'completed';

Transaction::create([
    'appointment_id' => $appointment->id,
    'patient_id' => $appointment->patient_id,
    'amount' => $payment_amount,
    'payment_method' => $request->payment_method,
    'payment_status' => $payment_status,
    'remaining_balance' => $remaining_balance,
    'receipt_number' => Transaction::generateReceiptNumber(),
]);
```

---

## 📊 Database Relationships

```
Patient ──┐
          ├──> Appointment <──┐ Doctor
                    │         
                    └──> Transaction
                    
Patient ──────> Transaction
```

**Eloquent Syntax:**
```php
$patient->appointments();        // HasMany
$patient->transactions();        // HasMany
$appointment->patient();         // BelongsTo
$appointment->doctor();          // BelongsTo
$appointment->transaction();     // HasOne
$transaction->patient();         // BelongsTo
$transaction->appointment();     // BelongsTo
```

---

## 📝 Blade View Quick Guide

```blade
<!-- Extends layout -->
@extends('layouts.app')

<!-- Section title -->
@section('title', 'Page Title')

<!-- Content section -->
@section('content')
    <!-- Loops -->
    @foreach ($items as $item)
        {{ $item->name }}
    @endforeach

    <!-- Conditionals -->
    @if ($appointment->status == 'pending')
        <span>Pending</span>
    @else
        <span>Not Pending</span>
    @endif

    <!-- Forms with CSRF & Old Values -->
    <form method="POST" action="{{ route('patients.store') }}">
        @csrf
        <input type="text" name="first_name" value="{{ old('first_name') }}" required>
        @error('first_name')
            <span class="error">{{ $message }}</span>
        @enderror
        <button type="submit">Save</button>
    </form>

    <!-- Display Authenticated User -->
    @auth
        {{ Auth::user()->name }}
    @endauth
@endsection
```

---

## 🎯 Appointment Status Flow

```
┌──────────┐
│ PENDING  │ (Just scheduled)
└────┬─────┘
     │
     ├─→ CONFIRMED (Admin confirms)
     │      │
     │      └─→ COMPLETED (After appointment)
     │
     └─→ CANCELLED (User cancels)

Rules:
- Can reschedule from: PENDING, CONFIRMED
- Can cancel from: PENDING, CONFIRMED
- Can only complete: CONFIRMED appointments
```

---

## 💰 Payment Status Reference

```
PENDING  → Awaiting payment
PARTIAL  → Partially paid (balance remains)
COMPLETED → Fully paid
REFUNDED → Payment reversed
```

**Formula:**
```
remaining_balance = max(0, consultation_fee - payment_amount)

If remaining_balance > 0:
    status = 'partial'
Else:
    status = 'completed'
```

---

## 🔍 Common Queries

```php
// Get all pending appointments
$pending = Appointment::where('status', 'pending')->get();

// Get today's appointments
$today = Appointment::whereDate('appointment_date_time', today())->get();

// Get completed transactions
$completed = Transaction::where('payment_status', 'completed')->sum('amount');

// Get revenue by doctor
$revenue = Transaction::join('appointments', 'transactions.appointment_id', '=', 'appointments.id')
    ->join('doctors', 'appointments.doctor_id', '=', 'doctors.id')
    ->selectRaw('doctors.name, SUM(transactions.amount) as total')
    ->groupBy('doctors.id')
    ->get();

// Find patients with medical history
$patients = Patient::whereNotNull('medical_history')->get();
```

---

## ✅ Form Validation Common Rules

```php
// Strings
'field' => 'required|string|max:100'

// Emails
'email' => 'required|email|unique:table_name'

// Numbers
'amount' => 'required|numeric|min:0.01|max:999999.99'

// Dates
'date' => 'required|date|after:now'
'date' => 'required|date|before:2025-12-31'

// Selections
'status' => 'required|in:pending,confirmed,completed'

// Relationships
'patient_id' => 'required|exists:patients,id'
'doctor_id' => 'required|exists:doctors,id'

// Unique with exclusion
'email' => 'required|email|unique:doctors,email,' . $doctor->id

// Combinations
'first_name' => 'required|string|max:100'
'last_name' => 'required|string|max:100'
```

---

## 🔧 Controller Pattern Template

```php
<?php

namespace App\Http\Controllers;

use App\Models\YourModel;
use Illuminate\Http\Request;

class YourModelController extends Controller
{
    // List all
    public function index()
    {
        $items = YourModel::all();
        return view('yourmodels.index', compact('items'));
    }

    // Create form
    public function create()
    {
        return view('yourmodels.create');
    }

    // Store to DB
    public function store(Request $request)
    {
        $validated = $request->validate([/* rules */]);
        YourModel::create($validated);
        return redirect()->route('yourmodels.index')->with('success', 'Created!');
    }

    // Show one
    public function show(YourModel $item)
    {
        return view('yourmodels.show', compact('item'));
    }

    // Edit form
    public function edit(YourModel $item)
    {
        return view('yourmodels.edit', compact('item'));
    }

    // Update in DB
    public function update(Request $request, YourModel $item)
    {
        $validated = $request->validate([/* rules */]);
        $item->update($validated);
        return redirect()->route('yourmodels.index')->with('success', 'Updated!');
    }

    // Delete
    public function destroy(YourModel $item)
    {
        $item->delete();
        return redirect()->route('yourmodels.index')->with('success', 'Deleted!');
    }
}
```

---

## 📦 Model Relationships Template

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Parent extends Model
{
    protected $fillable = ['field1', 'field2'];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'datetime',
    ];

    // One-to-Many
    public function children(): HasMany
    {
        return $this->hasMany(Child::class);
    }
}

class Child extends Model
{
    protected $fillable = ['parent_id', 'field1'];

    // Many-to-One
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Parent::class);
    }
}
```

---

## 🧪 Testing One Request Flow

### Example: Schedule Appointment
1. **Route**: `POST /appointments`
2. **Controller**: `AppointmentController@store()`
3. **Validation**: Check required fields and conflicts
4. **Conflict Check**: `whereBetween()` for ±30 minutes
5. **Save**: Create appointment with status='pending'
6. **Response**: Redirect with success message

---

## 💡 Debug Commands

```php
// In controller
dd($request->all());           // See all input
dd($validated);                // See validated data
dd($appointment);              // See model data

// In Blade
@dd($appointment)

// Check database directly
php artisan tinker
>>> Appointment::all()
>>> Appointment::where('status', 'pending')->first()
```

---

## ⚠️ Common Exam Mistakes to Avoid

1. ❌ Forgetting `@csrf` in forms
2. ❌ Using `unique` without table name: `'email' => 'required|email|unique'`
3. ❌ Not checking conflicts before creating appointments
4. ❌ Not calculating remaining balance for partial payments
5. ❌ Using `DateTime` instead of `Carbon`
6. ❌ Forgetting to add relationships in models
7. ❌ Not using `old()` in form fields
8. ❌ Missing route parameters: `route('appointments.edit', $id)`
9. ❌ Not validating before creating records
10. ❌ Forgetting to return views or redirects from controller

---

## ✨ Quick Wins for Your Exam

- Use scaffolding: Most views follow the same pattern
- Copy-paste controller template and modify
- Remember: Validation → Business Logic → Create → Redirect
- Test your conflict prevention THOROUGHLY
- Make sure all calculations are correct (especially balance)
- Test with different status values
- Check that relationships work in views

---

**Remember: Practice makes perfect! Keep this sheet nearby during preparation.** 📚
