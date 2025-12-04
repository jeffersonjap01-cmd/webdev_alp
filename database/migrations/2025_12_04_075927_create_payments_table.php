<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // Relasi ke medical record
            $table->foreignId('medical_record_id')->constrained()->onDelete('cascade');

            // Nominal (diisi admin)
            $table->decimal('consultation_fee', 12, 2)->default(0);
            $table->decimal('medication_fee', 12, 2)->default(0);
            $table->decimal('other_fee', 12, 2)->default(0);

            // Total final
            $table->decimal('total_amount', 12, 2);

            // Status
            $table->enum('status', [
                'unpaid',
                'paid',
                'cancelled'
            ])->default('unpaid');

            // Optional: upload bukti
            $table->string('proof_image')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
