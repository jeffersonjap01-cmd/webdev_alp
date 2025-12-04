<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // LOGIN INFO
            $table->string('email')->unique();
            $table->string('password');

            // ROLE
            $table->enum('role', ['admin', 'customer', 'doctor'])->default('customer');

            // Untuk fitur remember me (optional)
            $table->rememberToken();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
