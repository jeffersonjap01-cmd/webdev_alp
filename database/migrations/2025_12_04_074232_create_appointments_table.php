<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('pet_id')->constrained()->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained()->onDelete('cascade');

            // Booking info
            $table->dateTime('appointment_time');

            // Status
            $table->enum('status', [
                'pending',      // baru booking
                'approved',     // doctor active
                'cancelled',    // doctor inactive or customer cancel
                'completed'     // selesai, siap buat medical record
            ])->default('pending');

            $table->string('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
