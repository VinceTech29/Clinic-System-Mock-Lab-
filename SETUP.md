# Setup & Installation Guide

## Prerequisites

- PHP 8.0 or higher
- Composer (latest)
- SQLite or MySQL
- A text editor (VS Code recommended)

---

## Step 1: Navigate to Project

```bash
cd "c:\Users\user1\swinetrack\Lab Exam Prac\clinic_system"
```

---

## Step 2: Install Dependencies

```bash
composer install
```

This will download all Laravel packages and dependencies.

---

## Step 3: Create Environment File

```bash
copy .env.example .env
```

---

## Step 4: Generate Application Key

```bash
php artisan key:generate
```

---

## Step 5: Configure Database

### Option A: Use SQLite (Recommended for Exam)

1. Create database file:
```bash
touch database/database.sqlite
```

2. Edit `.env` file:
```
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

### Option B: Use MySQL

Edit `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=clinic_system
DB_USERNAME=root
DB_PASSWORD=
```

Then create database in MySQL first:
```sql
CREATE DATABASE clinic_system;
```

---

## Step 6: Run Migrations

```bash
php artisan migrate
```

This creates all database tables from migrations.

---

## Step 7: Start Development Server

```bash
php artisan serve
```

---

## Step 8: Access Application

Open browser and visit:
```
http://localhost:8000
```

---

## 🎉 You're Ready!

The application is now running! Start exploring:

1. **Create Patients** → Click "Patients" in sidebar
2. **Create Doctors** → Click "Doctors" in sidebar  
3. **Schedule Appointments** → Click "Appointments" → "Schedule Appointment"
4. **Record Payments** → Click "Billing" → "Record Payment"
5. **View Reports** → Click "Reports"

---

## Troubleshooting

### Error: "No application encryption key has been specified"
**Solution:**
```bash
php artisan key:generate
```

### Error: "SQLSTATE[HY000]: General error: 1 cannot open"
**Solution:**
Make sure database file exists:
```bash
touch database/database.sqlite
chmod 666 database/database.sqlite
```

### Error: "Target class does not exist"
**Solution:**
Clear cache:
```bash
php artisan route:clear
php artisan config:clear
```

### Error: "Class 'PDO' not found"
**Solution:**
Make sure PHP PDO extension is installed (usually installed by default)

### Migration Fails
**Solution:**
Check `.env` database configuration, then:
```bash
php artisan migrate:reset
php artisan migrate
```

---

## Common Commands

```bash
# Start server
php artisan serve

# Run migrations
php artisan migrate

# Reset database (DELETES DATA!)
php artisan migrate:reset

# Fresh migrations
php artisan migrate:fresh

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Debug in artisan tinker
php artisan tinker
> Appointment::all()
> Patient::find(1)

# Make new model
php artisan make:model ModelName -m

# Make new controller
php artisan make:controller ControllerName

# Make migration
php artisan make:migration create_table_name_table
```

---

## Project Structure Quick Guide

```
clinic_system/
├── app/
│   ├── Http/Controllers/          ← Business logic here
│   └── Models/                    ← Database models here
├── database/
│   ├── migrations/                ← Table definitions here
│   └── database.sqlite            ← Your database file
├── resources/
│   └── views/                     ← HTML templates here
├── routes/
│   └── web.php                    ← All routes here
├── .env                           ← Configuration file
├── composer.json                  ← Dependencies
└── README.md                      ← Documentation
```

---

## Key Files to Study

1. **Models** (Understand relationships):
   - `app/Models/Patient.php`
   - `app/Models/Doctor.php`
   - `app/Models/Appointment.php`
   - `app/Models/Transaction.php`

2. **Controllers** (Understand business logic):
   - `app/Http/Controllers/AppointmentController.php` ← Most important!
   - `app/Http/Controllers/TransactionController.php`

3. **Migrations** (Understand database):
   - `database/migrations/create_patients_table.php`
   - `database/migrations/create_appointments_table.php`

4. **Routes**:
   - `routes/web.php` ← See all application routes here

5. **Views** (Understand Blade):
   - `resources/views/appointments/create.blade.php` ← Study forms
   - `resources/views/dashboard.blade.php` ← Study data display

---

## First Time Setup Checklist

- [ ] Installed Composer
- [ ] Ran `composer install`
- [ ] Created `.env` file
- [ ] Ran `php artisan key:generate`
- [ ] Created `database/database.sqlite`
- [ ] Updated `.env` with DB settings
- [ ] Ran `php artisan migrate`
- [ ] Started server with `php artisan serve`
- [ ] Accessed `http://localhost:8000`
- [ ] Explored all menu items

---

## Fresh Start (If Something Breaks)

```bash
# Delete and recreate database
rm database/database.sqlite
touch database/database.sqlite

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Migrate fresh
php artisan migrate

# Restart server
php artisan serve
```

---

## Ready to Code?

Now that you have the project running:

1. Read `EXAM_GUIDE.md` - Study the key concepts
2. Read `QUICK_REFERENCE.md` - Quick code snippets
3. Explore controllers and models
4. Practice adding new features
5. Test your understanding

---

**Need Help?**
- Check Laravel docs: https://laravel.com/docs
- Review code comments in controllers
- Use `php artisan tinker` to test queries
- Check `.env` if database isn't connecting

**Good luck! 🚀**
