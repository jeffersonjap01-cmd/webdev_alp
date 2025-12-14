<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();

            // References
            $table->foreignId('doctor_id')->constrained()->onDelete('cascade');
            $table->foreignId('pet_id')->constrained()->onDelete('cascade');
            $table->foreignId('medical_record_id')->nullable()->constrained('medical_records')->nullOnDelete();

            // Prescription fields
            $table->date('date')->nullable();
            $table->text('diagnosis')->nullable();
            $table->text('instructions')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};
