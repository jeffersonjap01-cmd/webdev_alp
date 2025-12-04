<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            // relasi ke users
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // profile customer
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();   
            $table->string('address')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
