# Database Seeders - VetCare Laravel

## 📁 Seeder Files

### 1. Database Seeder (Main)
```php
<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            OwnerSeeder::class,
            DoctorSeeder::class,
            PetSeeder::class,
            AppointmentSeeder::class,
            MedicalRecordSeeder::class,
            InvoiceSeeder::class,
            VaccinationSeeder::class,
            PrescriptionSeeder::class,
        ]);
    }
}
```

---

### 2. User Seeder
```php
<?php
// database/seeders/UserSeeder.php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin User
        User::create([
            'name' => 'Admin VetCare',
            'email' => 'admin@vetcare.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Vet Users (will be linked to doctors)
        User::create([
            'name' => 'drh. Ahmad Wijaya',
            'email' => 'ahmad.wijaya@vetcare.com',
            'password' => Hash::make('password'),
            'role' => 'vet',
        ]);

        User::create([
            'name' => 'drh. Diana Putri',
            'email' => 'diana.putri@vetcare.com',
            'password' => Hash::make('password'),
            'role' => 'vet',
        ]);

        // Owner Users (will be linked to owners)
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@email.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
        ]);

        User::create([
            'name' => 'Siti Rahayu',
            'email' => 'siti@email.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
        ]);
    }
}
```

---

### 3. Owner Seeder
```php
<?php
// database/seeders/OwnerSeeder.php

namespace Database\Seeders;

use App\Models\Owner;
use App\Models\User;
use Illuminate\Database\Seeder;

class OwnerSeeder extends Seeder
{
    public function run(): void
    {
        $budiUser = User::where('email', 'budi@email.com')->first();
        $sitiUser = User::where('email', 'siti@email.com')->first();

        $budi = Owner::create([
            'user_id' => $budiUser->id,
            'name' => 'Budi Santoso',
            'email' => 'budi@email.com',
            'phone' => '081234567890',
            'address' => 'Jl. Merdeka No. 123, Jakarta Pusat, DKI Jakarta 10110',
            'registered_date' => '2024-01-15',
        ]);

        $siti = Owner::create([
            'user_id' => $sitiUser->id,
            'name' => 'Siti Rahayu',
            'email' => 'siti@email.com',
            'phone' => '082345678901',
            'address' => 'Jl. Sudirman No. 456, Bandung, Jawa Barat 40123',
            'registered_date' => '2024-02-20',
        ]);

        // Update user reference
        $budiUser->update(['reference_id' => $budi->id, 'reference_type' => 'Owner']);
        $sitiUser->update(['reference_id' => $siti->id, 'reference_type' => 'Owner']);

        // Additional owners
        Owner::create([
            'name' => 'Andi Wijaya',
            'email' => 'andi.wijaya@email.com',
            'phone' => '083456789012',
            'address' => 'Jl. Gatot Subroto No. 789, Surabaya, Jawa Timur 60275',
            'registered_date' => '2024-03-10',
        ]);

        Owner::create([
            'name' => 'Dewi Lestari',
            'email' => 'dewi.lestari@email.com',
            'phone' => '084567890123',
            'address' => 'Jl. Ahmad Yani No. 321, Yogyakarta, DIY 55161',
            'registered_date' => '2024-04-05',
        ]);
    }
}
```

---

### 4. Doctor Seeder
```php
<?php
// database/seeders/DoctorSeeder.php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        $ahmadUser = User::where('email', 'ahmad.wijaya@vetcare.com')->first();
        $dianaUser = User::where('email', 'diana.putri@vetcare.com')->first();

        $ahmad = Doctor::create([
            'user_id' => $ahmadUser->id,
            'name' => 'drh. Ahmad Wijaya',
            'specialization' => 'Umum',
            'status' => 'active',
            'email' => 'ahmad.wijaya@vetcare.com',
            'phone' => '081234567890',
            'photo_url' => 'https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?w=400',
            'bio' => 'Dokter hewan dengan pengalaman 10 tahun dalam perawatan hewan peliharaan.',
        ]);

        $diana = Doctor::create([
            'user_id' => $dianaUser->id,
            'name' => 'drh. Diana Putri',
            'specialization' => 'Bedah',
            'status' => 'active',
            'email' => 'diana.putri@vetcare.com',
            'phone' => '082345678901',
            'photo_url' => 'https://images.unsplash.com/photo-1594824476967-48c8b964273f?w=400',
            'bio' => 'Spesialis bedah hewan dengan sertifikasi internasional.',
        ]);

        // Update user reference
        $ahmadUser->update(['reference_id' => $ahmad->id, 'reference_type' => 'Doctor']);
        $dianaUser->update(['reference_id' => $diana->id, 'reference_type' => 'Doctor']);

        // Additional doctors
        Doctor::create([
            'name' => 'drh. Budi Santoso',
            'specialization' => 'Dermatologi',
            'status' => 'inactive',
            'email' => 'budi.santoso@vetcare.com',
            'phone' => '083456789012',
            'photo_url' => 'https://images.unsplash.com/photo-1622253692010-333f2da6031d?w=400',
            'bio' => 'Ahli dalam penyakit kulit dan alergi hewan.',
        ]);

        Doctor::create([
            'name' => 'drh. Citra Dewi',
            'specialization' => 'Gigi',
            'status' => 'active',
            'email' => 'citra.dewi@vetcare.com',
            'phone' => '084567890123',
            'photo_url' => 'https://images.unsplash.com/photo-1559839734-2b71ea197ec2?w=400',
            'bio' => 'Spesialis kesehatan gigi dan mulut hewan.',
        ]);
    }
}
```

---

### 5. Pet Seeder
```php
<?php
// database/seeders/PetSeeder.php

namespace Database\Seeders;

use App\Models\Pet;
use App\Models\Owner;
use Illuminate\Database\Seeder;

class PetSeeder extends Seeder
{
    public function run(): void
    {
        $budi = Owner::where('email', 'budi@email.com')->first();
        $siti = Owner::where('email', 'siti@email.com')->first();
        $andi = Owner::where('email', 'andi.wijaya@email.com')->first();
        $dewi = Owner::where('email', 'dewi.lestari@email.com')->first();

        // Budi's pets
        Pet::create([
            'owner_id' => $budi->id,
            'name' => 'Max',
            'species' => 'Anjing',
            'breed' => 'Golden Retriever',
            'age' => 3,
            'weight' => 28.5,
            'color' => 'Emas',
            'gender' => 'Jantan',
            'photo_url' => 'https://images.unsplash.com/photo-1633722715463-d30f4f325e24?w=400',
        ]);

        Pet::create([
            'owner_id' => $budi->id,
            'name' => 'Bella',
            'species' => 'Kucing',
            'breed' => 'Domestic Shorthair',
            'age' => 2,
            'weight' => 3.8,
            'color' => 'Hitam Putih',
            'gender' => 'Betina',
            'photo_url' => 'https://images.unsplash.com/photo-1574158622682-e40e69881006?w=400',
        ]);

        // Siti's pets
        Pet::create([
            'owner_id' => $siti->id,
            'name' => 'Luna',
            'species' => 'Kucing',
            'breed' => 'Persian',
            'age' => 2,
            'weight' => 4.5,
            'color' => 'Putih',
            'gender' => 'Betina',
            'photo_url' => 'https://images.unsplash.com/photo-1595433707802-6b2626ef1c91?w=400',
        ]);

        // Andi's pets
        Pet::create([
            'owner_id' => $andi->id,
            'name' => 'Rocky',
            'species' => 'Anjing',
            'breed' => 'German Shepherd',
            'age' => 4,
            'weight' => 32.0,
            'color' => 'Coklat Hitam',
            'gender' => 'Jantan',
            'photo_url' => 'https://images.unsplash.com/photo-1568572933382-74d440642117?w=400',
        ]);

        // Dewi's pets
        Pet::create([
            'owner_id' => $dewi->id,
            'name' => 'Mochi',
            'species' => 'Kelinci',
            'breed' => 'Holland Lop',
            'age' => 1,
            'weight' => 1.5,
            'color' => 'Putih Abu',
            'gender' => 'Betina',
            'photo_url' => 'https://images.unsplash.com/photo-1585110396000-c9ffd4e4b308?w=400',
        ]);

        Pet::create([
            'owner_id' => $dewi->id,
            'name' => 'Kiwi',
            'species' => 'Burung',
            'breed' => 'Lovebird',
            'age' => 0.5,
            'weight' => 0.05,
            'color' => 'Hijau',
            'gender' => 'Jantan',
        ]);
    }
}
```

---

### 6. Appointment Seeder
```php
<?php
// database/seeders/AppointmentSeeder.php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Pet;
use App\Models\Doctor;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        $max = Pet::where('name', 'Max')->first();
        $luna = Pet::where('name', 'Luna')->first();
        $rocky = Pet::where('name', 'Rocky')->first();

        $ahmad = Doctor::where('name', 'drh. Ahmad Wijaya')->first();
        $diana = Doctor::where('name', 'drh. Diana Putri')->first();

        // Past appointments (completed)
        Appointment::create([
            'pet_id' => $max->id,
            'doctor_id' => $ahmad->id,
            'date' => now()->subDays(7),
            'time' => '09:00',
            'reason' => 'Pemeriksaan rutin dan vaksinasi',
            'status' => 'completed',
            'notes' => 'Hewan dalam kondisi sehat.',
        ]);

        Appointment::create([
            'pet_id' => $luna->id,
            'doctor_id' => $ahmad->id,
            'date' => now()->subDays(5),
            'time' => '10:30',
            'reason' => 'Perawatan gigi',
            'status' => 'completed',
        ]);

        // Today's appointments
        Appointment::create([
            'pet_id' => $rocky->id,
            'doctor_id' => $diana->id,
            'date' => now(),
            'time' => '14:00',
            'reason' => 'Konsultasi masalah kulit',
            'status' => 'scheduled',
        ]);

        // Upcoming appointments
        Appointment::create([
            'pet_id' => $max->id,
            'doctor_id' => $ahmad->id,
            'date' => now()->addDays(3),
            'time' => '11:00',
            'reason' => 'Follow-up vaksinasi',
            'status' => 'scheduled',
        ]);

        Appointment::create([
            'pet_id' => $luna->id,
            'doctor_id' => $diana->id,
            'date' => now()->addDays(5),
            'time' => '15:30',
            'reason' => 'Sterilisasi',
            'status' => 'scheduled',
        ]);

        // Cancelled appointment
        Appointment::create([
            'pet_id' => $rocky->id,
            'doctor_id' => $ahmad->id,
            'date' => now()->subDays(2),
            'time' => '09:30',
            'reason' => 'Check-up',
            'status' => 'cancelled',
            'notes' => 'Dibatalkan oleh pemilik.',
        ]);
    }
}
```

---

### 7. Invoice Seeder
```php
<?php
// database/seeders/InvoiceSeeder.php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\Owner;
use App\Models\Pet;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $budi = Owner::where('email', 'budi@email.com')->first();
        $siti = Owner::where('email', 'siti@email.com')->first();
        $max = Pet::where('name', 'Max')->first();
        $luna = Pet::where('name', 'Luna')->first();

        // Paid invoice
        $invoice1 = Invoice::create([
            'invoice_number' => 'INV-20241201-0001',
            'owner_id' => $budi->id,
            'pet_id' => $max->id,
            'date' => now()->subDays(7),
            'subtotal' => 450000,
            'tax' => 45000,
            'discount' => 0,
            'total' => 495000,
            'status' => 'paid',
            'payment_method' => 'gopay',
            'payment_reference' => 'GP-' . now()->timestamp,
            'paid_at' => now()->subDays(7),
        ]);

        $invoice1->items()->createMany([
            ['description' => 'Pemeriksaan Umum', 'quantity' => 1, 'price' => 150000, 'total' => 150000],
            ['description' => 'Vaksin Rabies', 'quantity' => 1, 'price' => 200000, 'total' => 200000],
            ['description' => 'Vitamin', 'quantity' => 2, 'price' => 50000, 'total' => 100000],
        ]);

        // Paid invoice 2
        $invoice2 = Invoice::create([
            'invoice_number' => 'INV-20241205-0002',
            'owner_id' => $siti->id,
            'pet_id' => $luna->id,
            'date' => now()->subDays(5),
            'subtotal' => 350000,
            'tax' => 35000,
            'discount' => 50000,
            'total' => 335000,
            'status' => 'paid',
            'payment_method' => 'bank_transfer',
            'payment_reference' => 'TRF-20241205-001',
            'paid_at' => now()->subDays(5),
        ]);

        $invoice2->items()->createMany([
            ['description' => 'Perawatan Gigi', 'quantity' => 1, 'price' => 250000, 'total' => 250000],
            ['description' => 'Obat Kumur Hewan', 'quantity' => 1, 'price' => 100000, 'total' => 100000],
        ]);

        // Pending invoice
        $invoice3 = Invoice::create([
            'invoice_number' => 'INV-20241206-0003',
            'owner_id' => $budi->id,
            'pet_id' => $max->id,
            'date' => now(),
            'subtotal' => 300000,
            'tax' => 30000,
            'discount' => 0,
            'total' => 330000,
            'status' => 'pending',
        ]);

        $invoice3->items()->createMany([
            ['description' => 'Konsultasi', 'quantity' => 1, 'price' => 150000, 'total' => 150000],
            ['description' => 'Obat Kulit', 'quantity' => 1, 'price' => 150000, 'total' => 150000],
        ]);
    }
}
```

---

### 8. Vaccination Seeder
```php
<?php
// database/seeders/VaccinationSeeder.php

namespace Database\Seeders;

use App\Models\Vaccination;
use App\Models\Pet;
use Illuminate\Database\Seeder;

class VaccinationSeeder extends Seeder
{
    public function run(): void
    {
        $max = Pet::where('name', 'Max')->first();
        $luna = Pet::where('name', 'Luna')->first();
        $rocky = Pet::where('name', 'Rocky')->first();

        // Completed vaccination
        Vaccination::create([
            'pet_id' => $max->id,
            'vaccine_name' => 'Rabies',
            'last_date' => now()->subDays(7),
            'next_date' => now()->addYear(),
            'status' => 'completed',
            'notes' => 'Vaksinasi berhasil, tidak ada reaksi alergi.',
        ]);

        // Upcoming vaccination (within 30 days)
        Vaccination::create([
            'pet_id' => $luna->id,
            'vaccine_name' => 'Distemper',
            'last_date' => now()->subMonths(6),
            'next_date' => now()->addDays(15),
            'status' => 'scheduled',
            'reminder_sent' => false,
        ]);

        Vaccination::create([
            'pet_id' => $rocky->id,
            'vaccine_name' => 'Parvovirus',
            'last_date' => now()->subMonths(5),
            'next_date' => now()->addDays(25),
            'status' => 'scheduled',
            'reminder_sent' => false,
        ]);

        // Overdue vaccination
        Vaccination::create([
            'pet_id' => $luna->id,
            'vaccine_name' => 'Rabies',
            'last_date' => now()->subYear()->subDays(10),
            'next_date' => now()->subDays(10),
            'status' => 'overdue',
            'reminder_sent' => true,
            'notes' => 'Segera hubungi klinik untuk jadwal ulang.',
        ]);
    }
}
```

---

## 🎯 Running Seeders

```bash
# Run all seeders
php artisan db:seed

# Run specific seeder
php artisan db:seed --class=UserSeeder

# Fresh migration with seeding
php artisan migrate:fresh --seed

# Refresh and seed
php artisan migrate:refresh --seed
```

---

## 🏭 Using Factories (Alternative to Seeders)

### Create Factory
```bash
php artisan make:factory PetFactory --model=Pet
```

### Example Factory
```php
<?php
// database/factories/PetFactory.php

namespace Database\Factories;

use App\Models\Owner;
use Illuminate\Database\Eloquent\Factories\Factory;

class PetFactory extends Factory
{
    public function definition(): array
    {
        return [
            'owner_id' => Owner::factory(),
            'name' => $this->faker->firstName(),
            'species' => $this->faker->randomElement(['Anjing', 'Kucing', 'Kelinci', 'Burung']),
            'breed' => $this->faker->word(),
            'age' => $this->faker->numberBetween(1, 15),
            'weight' => $this->faker->randomFloat(2, 0.5, 50),
            'color' => $this->faker->colorName(),
            'gender' => $this->faker->randomElement(['Jantan', 'Betina']),
        ];
    }
}
```

### Using Factory in Seeder
```php
public function run(): void
{
    // Create 10 owners each with 2 pets
    Owner::factory()
        ->count(10)
        ->hasPets(2)
        ->create();
}
```

---

## 📊 Verification

Setelah seeding, verify data dengan:

```bash
php artisan tinker
```

```php
// Check counts
User::count();
Owner::count();
Pet::count();
Doctor::count();
Appointment::count();

// Test relationships
$owner = Owner::first();
$owner->pets;

$pet = Pet::first();
$pet->owner;
$pet->medicalRecords;

// Test scopes
Appointment::today()->count();
Doctor::active()->count();
Invoice::pending()->count();
```

---

## 🔑 Default Login Credentials

After seeding, you can login with:

**Admin:**
- Email: `admin@vetcare.com`
- Password: `password`

**Dokter:**
- Email: `ahmad.wijaya@vetcare.com`
- Password: `password`

**Pemilik:**
- Email: `budi@email.com`
- Password: `password`

---

Selesai! Anda sekarang memiliki sample data lengkap untuk testing. 🎉
