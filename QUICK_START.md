# 🚀 Quick Start Guide - VetCare Laravel

## Ringkasan Setup Laravel dengan Herd

### 1️⃣ Prerequisites
- ✅ Laravel Herd terinstall
- ✅ PHP 8.2+
- ✅ Composer
- ✅ MySQL/PostgreSQL

---

### 2️⃣ Create New Laravel Project

```bash
# Navigate to Herd directory
cd ~/Herd

# Create project
composer create-project laravel/laravel vetcare-backend

# Navigate to project
cd vetcare-backend

# Install Sanctum for API authentication
composer require laravel/sanctum

# Publish Sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

---

### 3️⃣ Configure Database

Edit `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=vetcare
DB_USERNAME=root
DB_PASSWORD=
```

Create database:
```bash
mysql -u root -e "CREATE DATABASE vetcare;"
```

---

### 4️⃣ Setup Migrations

Copy all migration files dari `MIGRATIONS.md` ke folder `database/migrations/`

Atau buat dengan artisan:
```bash
php artisan make:migration create_owners_table
php artisan make:migration create_pets_table
php artisan make:migration create_doctors_table
php artisan make:migration create_medical_records_table
php artisan make:migration create_appointments_table
php artisan make:migration create_invoices_table
php artisan make:migration create_invoice_items_table
php artisan make:migration create_vaccinations_table
php artisan make:migration create_prescriptions_table
php artisan make:migration create_medications_table
```

Run migrations:
```bash
php artisan migrate
```

---

### 5️⃣ Create Models

```bash
php artisan make:model Owner
php artisan make:model Pet
php artisan make:model Doctor
php artisan make:model MedicalRecord
php artisan make:model Appointment
php artisan make:model Invoice
php artisan make:model InvoiceItem
php artisan make:model Vaccination
php artisan make:model Prescription
php artisan make:model Medication
```

Copy code dari `MODELS.md` ke masing-masing model file.

---

### 6️⃣ Create Controllers

```bash
php artisan make:controller AuthController
php artisan make:controller OwnerController --api
php artisan make:controller PetController --api
php artisan make:controller DoctorController --api
php artisan make:controller MedicalRecordController --api
php artisan make:controller AppointmentController --api
php artisan make:controller InvoiceController --api
php artisan make:controller VaccinationController --api
php artisan make:controller PrescriptionController --api
php artisan make:controller DashboardController
php artisan make:controller ReportController
```

Copy code dari `CONTROLLERS.md` ke masing-masing controller.

---

### 7️⃣ Setup Routes

Copy routes dari `ROUTES.md` ke `routes/api.php`

---

### 8️⃣ Create Middleware

```bash
php artisan make:middleware CheckRole
```

Copy code middleware dari `ROUTES.md`.

Register middleware di `bootstrap/app.php` (Laravel 11):
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => \App\Http\Middleware\CheckRole::class,
    ]);
})
```

---

### 9️⃣ Create Seeders

```bash
php artisan make:seeder UserSeeder
php artisan make:seeder OwnerSeeder
php artisan make:seeder DoctorSeeder
php artisan make:seeder PetSeeder
php artisan make:seeder AppointmentSeeder
php artisan make:seeder InvoiceSeeder
php artisan make:seeder VaccinationSeeder
```

Copy code dari `SEEDERS.md`.

Update `database/seeders/DatabaseSeeder.php`:
```php
public function run(): void
{
    $this->call([
        UserSeeder::class,
        OwnerSeeder::class,
        DoctorSeeder::class,
        PetSeeder::class,
        AppointmentSeeder::class,
        InvoiceSeeder::class,
        VaccinationSeeder::class,
    ]);
}
```

---

### 🔟 Run Seeders

```bash
php artisan migrate:fresh --seed
```

---

### 1️⃣1️⃣ Test API

Herd akan otomatis serve di: `http://vetcare-backend.test`

Test dengan Postman/Insomnia:

#### Login
```http
POST http://vetcare-backend.test/api/login
Content-Type: application/json

{
  "email": "admin@vetcare.com",
  "password": "password"
}
```

Response:
```json
{
  "user": {
    "id": 1,
    "name": "Admin VetCare",
    "email": "admin@vetcare.com",
    "role": "admin"
  },
  "token": "1|xxxxxxxxxxxxxx"
}
```

#### Get Dashboard
```http
GET http://vetcare-backend.test/api/dashboard
Authorization: Bearer 1|xxxxxxxxxxxxxx
```

---

## 📁 Struktur Project Final

```
vetcare-backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── OwnerController.php
│   │   │   ├── PetController.php
│   │   │   ├── DoctorController.php
│   │   │   ├── MedicalRecordController.php
│   │   │   ├── AppointmentController.php
│   │   │   ├── InvoiceController.php
│   │   │   ├── VaccinationController.php
│   │   │   ├── PrescriptionController.php
│   │   │   ├── DashboardController.php
│   │   │   └── ReportController.php
│   │   └── Middleware/
│   │       └── CheckRole.php
│   └── Models/
│       ├── User.php
│       ├── Owner.php
│       ├── Pet.php
│       ├── Doctor.php
│       ├── MedicalRecord.php
│       ├── Appointment.php
│       ├── Invoice.php
│       ├── InvoiceItem.php
│       ├── Vaccination.php
│       ├── Prescription.php
│       └── Medication.php
├── database/
│   ├── migrations/
│   │   ├── 2024_01_01_000000_create_users_table.php
│   │   ├── 2024_01_01_000001_create_owners_table.php
│   │   └── ... (10 more migrations)
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── UserSeeder.php
│       ├── OwnerSeeder.php
│       └── ... (6 more seeders)
├── routes/
│   └── api.php
└── .env
```

---

## ✅ Testing Checklist

### Authentication
- [ ] Register user
- [ ] Login
- [ ] Get user profile
- [ ] Logout

### Owners (Admin)
- [ ] List owners
- [ ] Create owner
- [ ] View owner
- [ ] Update owner
- [ ] Delete owner

### Pets
- [ ] List pets
- [ ] Create pet
- [ ] View pet details
- [ ] Update pet
- [ ] Delete pet

### Doctors
- [ ] List doctors
- [ ] View doctor
- [ ] Toggle doctor status (Admin)

### Appointments
- [ ] Create appointment
- [ ] List appointments
- [ ] Update appointment status
- [ ] Today's appointments

### Invoices
- [ ] Create invoice
- [ ] List invoices
- [ ] Process payment
- [ ] View invoice details

### Vaccinations
- [ ] List vaccinations
- [ ] Create vaccination schedule
- [ ] Get upcoming vaccinations
- [ ] Mark as completed

### Dashboard
- [ ] Get admin stats
- [ ] Get vet stats
- [ ] Get owner stats

---

## 🔧 Common Issues & Solutions

### Issue: Token mismatch
**Solution:** Make sure Sanctum is configured in `config/sanctum.php`:
```php
'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', 'localhost,localhost:3000')),
```

### Issue: CORS errors
**Solution:** Update `config/cors.php`:
```php
'paths' => ['api/*'],
'allowed_origins' => ['*'],
'allowed_methods' => ['*'],
```

### Issue: Database connection error
**Solution:** Check `.env` database credentials and ensure MySQL is running.

---

## 🚀 Next Steps

### 1. Frontend Integration
Gunakan React app yang sudah dibuat sebelumnya, ubah:
- Replace `localStorage` calls dengan API calls
- Implement Axios/Fetch for HTTP requests
- Store token in localStorage/cookies
- Add loading states
- Handle errors

### 2. Payment Gateway Integration
Install Midtrans:
```bash
composer require midtrans/midtrans-php
```

Configure in `.env`:
```env
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_IS_PRODUCTION=false
```

### 3. Email Notifications
Setup queue for vaccination reminders:
```bash
php artisan make:notification VaccinationReminder
php artisan queue:table
php artisan migrate
```

Update `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
```

### 4. Scheduled Tasks
Add to `routes/console.php`:
```php
use Illuminate\Support\Facades\Schedule;

Schedule::command('vaccinations:send-reminders')->daily();
```

Create command:
```bash
php artisan make:command SendVaccinationReminders
```

---

## 📚 Documentation Files

1. **LARAVEL_CONVERSION_GUIDE.md** - Overview & setup
2. **MIGRATIONS.md** - All database migrations
3. **MODELS.md** - Models & relationships
4. **CONTROLLERS.md** - Controller implementations
5. **ROUTES.md** - API routes & endpoints
6. **SEEDERS.md** - Sample data seeders
7. **QUICK_START.md** - This file

---

## 🎉 Selesai!

Aplikasi Laravel backend sudah siap digunakan dengan:
- ✅ 11 database tables
- ✅ 11 models with relationships
- ✅ 11 controllers
- ✅ 50+ API endpoints
- ✅ Role-based authentication
- ✅ Sample data for testing

**Default URL:** `http://vetcare-backend.test`

**Login Credentials:**
- Admin: `admin@vetcare.com` / `password`
- Vet: `ahmad.wijaya@vetcare.com` / `password`
- Owner: `budi@email.com` / `password`

Happy coding! 🚀
