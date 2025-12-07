<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Owner;
use App\Models\User;

class OwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get owner users
        $owner1 = User::where('email', 'owner@vetcare.com')->first();
        $owner2 = User::where('email', 'owner2@vetcare.com')->first();
        $owner3 = User::where('email', 'owner3@vetcare.com')->first();

        if ($owner1) {
            Owner::create([
                'user_id' => $owner1->id,
                'name' => 'Budi Santoso',
                'email' => 'owner@vetcare.com',
                'phone' => '081234567892',
                'address' => 'Jl. Sudirman No. 123, Jakarta',
                'registered_date' => now(),
            ]);
        }

        if ($owner2) {
            Owner::create([
                'user_id' => $owner2->id,
                'name' => 'Siti Sarah',
                'email' => 'owner2@vetcare.com',
                'phone' => '081234567893',
                'address' => 'Jl. Thamrin No. 456, Jakarta',
                'registered_date' => now(),
            ]);
        }

        if ($owner3) {
            Owner::create([
                'user_id' => $owner3->id,
                'name' => 'Ahmad Wijaya',
                'email' => 'owner3@vetcare.com',
                'phone' => '081234567894',
                'address' => 'Jl. Gatot Subroto No. 789, Jakarta',
                'registered_date' => now(),
            ]);
        }

        // Get test user owner
        $testUser = User::where('email', 'mk@gmail.com')->first();

        if ($testUser) {
            Owner::create([
                'user_id' => $testUser->id,
                'name' => 'Test User',
                'email' => 'mk@gmail.com',
                'phone' => '081234567895',
                'address' => 'Jl. Test No. 999, Jakarta',
                'registered_date' => now(),
            ]);
        }
    }
}
