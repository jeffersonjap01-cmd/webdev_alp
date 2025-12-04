<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pets', function (Blueprint $table) {
            $table->id();

            // Relasi ke customers
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');

            $table->string('name');
            $table->string('species');      // jenis hewan (dog, cat, rabbit)
            $table->string('breed')->nullable(); // ras hewan
            $table->integer('age')->nullable();  // usia
            $table->string('gender')->nullable(); // male / female
            $table->string('color')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
