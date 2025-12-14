<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medications', function (Blueprint $table) {
            $table->foreignId('prescription_id')->nullable()->constrained('prescriptions')->nullOnDelete()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('medications', function (Blueprint $table) {
            $table->dropConstrainedForeignId('prescription_id');
        });
    }
};
