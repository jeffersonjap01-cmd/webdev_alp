# Database Migrations - VetCare Laravel

## 📁 File Migrations

### 1. Users Table (Authentication)
```php
<?php
// database/migrations/2024_01_01_000000_create_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['admin', 'vet', 'owner'])->default('owner');
            $table->foreignId('reference_id')->nullable()->comment('ID dari owners atau doctors table');
            $table->string('reference_type')->nullable()->comment('Owner atau Doctor');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
```

---

### 2. Owners Table
```php
<?php
// database/migrations/2024_01_01_000001_create_owners_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('owners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->text('address');
            $table->date('registered_date');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('email');
            $table->index('phone');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('owners');
    }
};
```

---

### 3. Pets Table
```php
<?php
// database/migrations/2024_01_01_000002_create_pets_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('species'); // Anjing, Kucing, etc
            $table->string('breed');
            $table->decimal('age', 5, 2); // Support decimal for months
            $table->decimal('weight', 8, 2);
            $table->string('color');
            $table->enum('gender', ['Jantan', 'Betina']);
            $table->string('photo_url')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('owner_id');
            $table->index('species');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
```

---

### 4. Doctors Table
```php
<?php
// database/migrations/2024_01_01_000003_create_doctors_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('specialization'); // Umum, Bedah, Dermatologi
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('photo_url')->nullable();
            $table->text('bio')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('status');
            $table->index('specialization');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
```

---

### 5. Medical Records Table
```php
<?php
// database/migrations/2024_01_01_000004_create_medical_records_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pet_id')->constrained()->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained()->onDelete('restrict');
            $table->date('date');
            $table->text('diagnosis');
            $table->text('treatment');
            $table->text('notes')->nullable();
            $table->decimal('temperature', 5, 2)->nullable();
            $table->integer('heart_rate')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('pet_id');
            $table->index('doctor_id');
            $table->index('date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};
```

---

### 6. Appointments Table
```php
<?php
// database/migrations/2024_01_01_000005_create_appointments_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pet_id')->constrained()->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained()->onDelete('restrict');
            $table->date('date');
            $table->time('time');
            $table->text('reason');
            $table->enum('status', ['scheduled', 'completed', 'cancelled'])->default('scheduled');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('pet_id');
            $table->index('doctor_id');
            $table->index('date');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
```

---

### 7. Invoices Table
```php
<?php
// database/migrations/2024_01_01_000006_create_invoices_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('owner_id')->constrained()->onDelete('restrict');
            $table->foreignId('pet_id')->constrained()->onDelete('restrict');
            $table->date('date');
            $table->decimal('subtotal', 12, 2);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('total', 12, 2);
            $table->enum('status', ['pending', 'paid', 'cancelled'])->default('pending');
            $table->string('payment_method')->nullable(); // gopay, ovo, dana, credit, bank_transfer
            $table->string('payment_reference')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('invoice_number');
            $table->index('owner_id');
            $table->index('status');
            $table->index('date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
```

---

### 8. Invoice Items Table
```php
<?php
// database/migrations/2024_01_01_000007_create_invoice_items_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->string('description');
            $table->integer('quantity')->default(1);
            $table->decimal('price', 12, 2);
            $table->decimal('total', 12, 2);
            $table->timestamps();
            
            $table->index('invoice_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
```

---

### 9. Vaccinations Table
```php
<?php
// database/migrations/2024_01_01_000008_create_vaccinations_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vaccinations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pet_id')->constrained()->onDelete('cascade');
            $table->string('vaccine_name'); // Rabies, Distemper, etc
            $table->date('last_date');
            $table->date('next_date');
            $table->text('notes')->nullable();
            $table->enum('status', ['scheduled', 'completed', 'overdue'])->default('scheduled');
            $table->boolean('reminder_sent')->default(false);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('pet_id');
            $table->index('next_date');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vaccinations');
    }
};
```

---

### 10. Prescriptions Table
```php
<?php
// database/migrations/2024_01_01_000009_create_prescriptions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pet_id')->constrained()->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained()->onDelete('restrict');
            $table->foreignId('medical_record_id')->nullable()->constrained()->onDelete('set null');
            $table->date('date');
            $table->text('diagnosis');
            $table->text('instructions')->nullable();
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('pet_id');
            $table->index('doctor_id');
            $table->index('date');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};
```

---

### 11. Medications Table (Pivot for Prescriptions)
```php
<?php
// database/migrations/2024_01_01_000010_create_medications_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prescription_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('dosage'); // "10mg"
            $table->string('frequency'); // "2x sehari"
            $table->string('duration'); // "7 hari"
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('prescription_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medications');
    }
};
```

---

## 🔄 Running Migrations

```bash
# Run all migrations
php artisan migrate

# Run with seeding
php artisan migrate --seed

# Rollback
php artisan migrate:rollback

# Fresh migration (drop all + migrate)
php artisan migrate:fresh --seed

# Reset and re-run
php artisan migrate:refresh --seed
```

---

## 📊 Database Diagram

```
users
├── id
├── name
├── email
├── role (admin, vet, owner)
└── reference_id (polymorphic to owners/doctors)

owners
├── id
├── user_id (FK → users)
├── name
├── email
├── phone
└── address

pets
├── id
├── owner_id (FK → owners)
├── name
├── species
├── breed
└── age

doctors
├── id
├── user_id (FK → users)
├── name
├── specialization
└── status

medical_records
├── id
├── pet_id (FK → pets)
├── doctor_id (FK → doctors)
├── diagnosis
└── treatment

appointments
├── id
├── pet_id (FK → pets)
├── doctor_id (FK → doctors)
├── date
├── time
└── status

invoices
├── id
├── owner_id (FK → owners)
├── pet_id (FK → pets)
├── total
└── status

invoice_items
├── id
├── invoice_id (FK → invoices)
├── description
├── quantity
└── price

vaccinations
├── id
├── pet_id (FK → pets)
├── vaccine_name
├── last_date
├── next_date
└── status

prescriptions
├── id
├── pet_id (FK → pets)
├── doctor_id (FK → doctors)
├── diagnosis
└── status

medications
├── id
├── prescription_id (FK → prescriptions)
├── name
├── dosage
└── frequency
```

---

## 🎯 Indexes Penting

Semua foreign keys sudah di-index otomatis oleh Laravel.

Additional indexes sudah ditambahkan pada:
- Status columns (untuk filtering)
- Date columns (untuk range queries)
- Email & phone (untuk searching)
- Common lookup fields

---

## 💡 Tips

1. **Soft Deletes**: Semua table menggunakan `softDeletes()` untuk data safety
2. **Timestamps**: Otomatis track `created_at` dan `updated_at`
3. **Foreign Keys**: Gunakan `onDelete('cascade')` atau `onDelete('restrict')` sesuai kebutuhan
4. **Decimal**: Gunakan decimal untuk currency dan measurements
5. **Enum**: Gunakan enum untuk status yang fixed

---

Lanjut ke **MODELS.md** untuk melihat implementasi Model dengan relationships! 🚀
