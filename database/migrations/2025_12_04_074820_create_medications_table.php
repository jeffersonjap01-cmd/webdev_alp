<?php

namespace Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medications', function (Blueprint $table) {
            $table->id();

            $table->foreignId('medical_record_id')->nullable()->constrained()->onDelete('cascade');

            $table->string('medicine_name');
            $table->string('dosage')->nullable();       // contoh: 1 tablet
            $table->string('frequency')->nullable();    // contoh: 2x sehari
            $table->string('duration')->nullable();     // contoh: 7 hari

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medications');
    }
};
