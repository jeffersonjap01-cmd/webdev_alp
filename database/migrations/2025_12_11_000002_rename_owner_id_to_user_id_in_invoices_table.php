<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // Drop the old foreign key constraint first if it exists
            try {
                $table->dropForeign(['owner_id']);
            } catch (\Exception $e) {
                // FK might not exist, continue
            }
            
            // Rename the column from owner_id to user_id
            $table->renameColumn('owner_id', 'user_id');
            
            // Add the new foreign key constraint for user_id
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // Drop the new foreign key constraint first
            $table->dropForeign(['user_id']);
            
            // Rename the column back to owner_id
            $table->renameColumn('user_id', 'owner_id');
        });
    }
};
