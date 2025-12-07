<?php

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