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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('pet_id')->constrained()->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained()->onDelete('cascade');

            // Booking info
            $table->dateTime('appointment_time');

            // Status (expanded to include all application states)
            $table->enum('status', [
                'pending',      // newly booked
                'accepted',     // doctor accepted
                'declined',     // doctor declined
                'in_progress',  // doctor started
                'scheduled',    // system/confirmed schedule
                'confirmed',    // confirmed
                'completed',    // finished
                'cancelled'     // cancelled by user or system
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
