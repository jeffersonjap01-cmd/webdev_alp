# Panduan Konversi VetCare ke Laravel

## 📋 Overview
Dokumen ini berisi panduan lengkap untuk mengkonversi aplikasi VetCare dari React ke Laravel dengan Herd.

## 🚀 Setup Awal

### 1. Install Laravel dengan Herd
```bash
# Pastikan Herd sudah terinstall
cd ~/Herd

# Buat project Laravel baru
composer create-project laravel/laravel vetcare-backend
cd vetcare-backend

# Install dependencies tambahan
composer require laravel/sanctum
composer require laravel/breeze --dev
php artisan breeze:install api
```

### 2. Konfigurasi Database (.env)
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=vetcare
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Setup Authentication
```bash
php artisan migrate
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

---

## 📊 Database Schema

### Tabel yang Diperlukan:
1. **users** - Authentication untuk 3 role (admin, vet, owner)
2. **owners** - Data pemilik hewan
3. **pets** - Data hewan peliharaan
4. **doctors** - Data dokter hewan
5. **medical_records** - Rekam medis
6. **appointments** - Booking/janji temu
7. **invoices** - Tagihan
8. **invoice_items** - Detail item tagihan
9. **vaccinations** - Jadwal vaksinasi
10. **prescriptions** - Resep obat
11. **medications** - Detail obat dalam resep

---

## 📁 Struktur File Laravel

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── OwnerController.php
│   │   ├── PetController.php
│   │   ├── DoctorController.php
│   │   ├── MedicalRecordController.php
│   │   ├── AppointmentController.php
│   │   ├── InvoiceController.php
│   │   ├── VaccinationController.php
│   │   ├── PrescriptionController.php
│   │   ├── ReportController.php
│   │   └── DashboardController.php
│   ├── Middleware/
│   │   └── CheckRole.php
│   └── Requests/
│       ├── StoreOwnerRequest.php
│       ├── StorePetRequest.php
│       └── ...
├── Models/
│   ├── User.php
│   ├── Owner.php
│   ├── Pet.php
│   ├── Doctor.php
│   ├── MedicalRecord.php
│   ├── Appointment.php
│   ├── Invoice.php
│   ├── InvoiceItem.php
│   ├── Vaccination.php
│   ├── Prescription.php
│   └── Medication.php
└── Enums/
    ├── UserRole.php
    ├── AppointmentStatus.php
    ├── InvoiceStatus.php
    └── DoctorStatus.php

database/
├── migrations/
│   ├── 2024_01_01_000000_create_users_table.php
│   ├── 2024_01_01_000001_create_owners_table.php
│   ├── 2024_01_01_000002_create_pets_table.php
│   ├── 2024_01_01_000003_create_doctors_table.php
│   ├── 2024_01_01_000004_create_medical_records_table.php
│   ├── 2024_01_01_000005_create_appointments_table.php
│   ├── 2024_01_01_000006_create_invoices_table.php
│   ├── 2024_01_01_000007_create_invoice_items_table.php
│   ├── 2024_01_01_000008_create_vaccinations_table.php
│   ├── 2024_01_01_000009_create_prescriptions_table.php
│   └── 2024_01_01_000010_create_medications_table.php
└── seeders/
    ├── DatabaseSeeder.php
    ├── UserSeeder.php
    ├── OwnerSeeder.php
    ├── PetSeeder.php
    └── DoctorSeeder.php

routes/
└── api.php
```

---

## 🔐 Authentication & Authorization

### Roles:
- `admin` - Full access (Receptionist/Admin klinik)
- `vet` - Dokter hewan (akses rekam medis, resep, vaksinasi)
- `owner` - Pemilik hewan (booking, riwayat, pembayaran)

### Middleware untuk Role:
```php
// app/Http/Middleware/CheckRole.php
public function handle($request, Closure $next, ...$roles)
{
    if (!in_array($request->user()->role, $roles)) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }
    return $next($request);
}
```

---

## 🛣️ API Routes Structure

### Authentication Routes
```
POST   /api/register
POST   /api/login
POST   /api/logout
GET    /api/user
```

### Owner Routes (Admin only)
```
GET    /api/owners
POST   /api/owners
GET    /api/owners/{id}
PUT    /api/owners/{id}
DELETE /api/owners/{id}
```

### Pet Routes (Admin, Owner)
```
GET    /api/pets
POST   /api/pets
GET    /api/pets/{id}
PUT    /api/pets/{id}
DELETE /api/pets/{id}
```

### Doctor Routes (Admin only)
```
GET    /api/doctors
POST   /api/doctors
GET    /api/doctors/{id}
PUT    /api/doctors/{id}
DELETE /api/doctors/{id}
PATCH  /api/doctors/{id}/toggle-status
```

### Medical Record Routes (Admin, Vet)
```
GET    /api/medical-records
POST   /api/medical-records
GET    /api/medical-records/{id}
GET    /api/pets/{petId}/medical-records
```

### Appointment Routes (Admin, Owner)
```
GET    /api/appointments
POST   /api/appointments
GET    /api/appointments/{id}
PUT    /api/appointments/{id}
PATCH  /api/appointments/{id}/status
```

### Invoice & Payment Routes (Admin, Owner)
```
GET    /api/invoices
POST   /api/invoices
GET    /api/invoices/{id}
POST   /api/invoices/{id}/pay
```

### Vaccination Routes (All roles)
```
GET    /api/vaccinations
POST   /api/vaccinations
GET    /api/vaccinations/{id}
PATCH  /api/vaccinations/{id}/complete
GET    /api/vaccinations/upcoming
```

### Prescription Routes (Admin, Vet)
```
GET    /api/prescriptions
POST   /api/prescriptions
GET    /api/prescriptions/{id}
PATCH  /api/prescriptions/{id}/status
```

### Dashboard & Reports (Role-based)
```
GET    /api/dashboard
GET    /api/reports/revenue
GET    /api/reports/appointments
GET    /api/reports/patients
```

---

## 💾 Payment Gateway Integration

### Midtrans (Recommended untuk Indonesia)
```bash
composer require midtrans/midtrans-php
```

### Xendit (Alternative)
```bash
composer require xendit/xendit-php
```

### Implementation:
- Simpan konfigurasi di `.env`
- Buat service class untuk payment gateway
- Webhook untuk update payment status

---

## 📧 Notifications

### Email Reminders untuk Vaksinasi:
```bash
php artisan make:notification VaccinationReminder
```

### Queue Setup:
```bash
# .env
QUEUE_CONNECTION=database

php artisan queue:table
php artisan migrate
php artisan queue:work
```

---

## 🔄 Migration dari React localStorage

### Step 1: Export Data dari Browser
Tambahkan fitur export di React app untuk download semua data localStorage sebagai JSON.

### Step 2: Import ke Laravel
Buat command artisan untuk import data:
```bash
php artisan make:command ImportLegacyData
```

---

## 📱 Frontend Integration

### Opsi 1: Tetap pakai React (SPA)
- Deploy React app terpisah
- Panggil Laravel API dengan axios/fetch
- Handle authentication dengan Sanctum tokens

### Opsi 2: Laravel + Blade + Alpine.js
- Traditional Laravel views
- Alpine.js untuk interaktivity
- Tailwind CSS (sama seperti React version)

### Opsi 3: Laravel + Inertia.js + React
- Best of both worlds
- React components dengan Laravel routing
- No API needed

---

## 🧪 Testing

```bash
# Buat tests
php artisan make:test OwnerTest
php artisan make:test PetTest
php artisan make:test AppointmentTest

# Run tests
php artisan test
```

---

## 📦 Deployment dengan Herd

### Development:
```bash
# Herd akan otomatis serve di:
http://vetcare-backend.test
```

### Production:
1. Setup server dengan Laravel Forge
2. Deploy via Git
3. Setup queue worker
4. Setup scheduled tasks untuk reminder vaksinasi

---

## 🔧 Optimization

### Cache:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Database Indexes:
Tambahkan index pada foreign keys dan kolom yang sering di-query.

### Eager Loading:
Gunakan `with()` untuk prevent N+1 queries.

---

## 📝 Next Steps

1. ✅ Buat migrations (lihat MIGRATIONS.md)
2. ✅ Buat models dengan relationships (lihat MODELS.md)
3. ✅ Buat controllers (lihat CONTROLLERS.md)
4. ✅ Setup routes (lihat ROUTES.md)
5. ✅ Implementasi authentication
6. ✅ Buat seeders untuk sample data
7. ✅ Testing API endpoints
8. ✅ Integrasi payment gateway
9. ✅ Setup notifications & reminders

---

## 📚 Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Laravel Sanctum](https://laravel.com/docs/sanctum)
- [Herd Documentation](https://herd.laravel.com)
- [Midtrans Documentation](https://docs.midtrans.com)

---

**File terkait:**
- `MIGRATIONS.md` - Detail semua migration files
- `MODELS.md` - Model definitions & relationships
- `CONTROLLERS.md` - Controller implementations
- `ROUTES.md` - Complete API routes
- `SEEDERS.md` - Sample data seeders
